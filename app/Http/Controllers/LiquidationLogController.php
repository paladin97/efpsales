<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,LiquidationLog,PersonInf,Lead,Role,Company,AccessCompany,Contract,ContractFee, Liquidation, LiquidationModel};
use DataTables,Auth,Redirect,Response,Config,DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use PDF,File,Mail;
use DateTime;
class LiquidationLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
        $user;
        $companies;
        $roles;
        $liquidationAgents;
        // dd($request->all());
        if (Auth::check()) {

            //Filtro liquidacion

            $liqFilter = Liquidation::from('liquidations as li')
                            ->select('li.*')    
                            ->orderByRaw(DB::Raw("li.created_at  desc"))
                            ->get();

            $user = Auth::user();
            $companies = User::find($user->id)->companies;
            $dt_reception_filter = (!empty($_GET["dt_reception_filter"])) ? ($_GET["dt_reception_filter"]) : $liqFilter->pluck('period_liq')->unique();
            $agent_list_filter = (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : $liqFilter->pluck('agent_id')->unique();; //Elige a sin asignar
            
            if ($request->ajax()) {

                //Primero Obtengo todas los period_liq de la tabla Liquidation
                $periodLiq = Liquidation::from('liquidations as li')
                            ->leftJoin('users as us','li.agent_id','=','us.id')
                            ->leftJoin('people_inf as pi','us.person_id','=','pi.id')
                            ->leftJoin('person_types as pt','pi.person_type_id','=','pt.id')
                            ->whereIn('li.period_liq',$dt_reception_filter)
                            ->whereIn('li.agent_id',$agent_list_filter)
                            ->select('li.*'
                                    , 'pt.name as agent_type'
                                    , DB::raw('CONCAT(us.name, " ", us.last_name) as agent_name'))    
                            ->orderByRaw(DB::Raw("li.created_at  desc"))
                            ->get();
                //Construyo el foreach de cada liquidación y sobre cada una voy creando el collect
                $liquidationAgent = new Collection;
                foreach ($periodLiq as $key => $value) {
                    $liq_range = $value->period_liq."-01";
                    $liq_range_date = new DateTime($liq_range);
                    $dt_ini_filter = $liq_range_date->format('Y-m-d');
                    $dt_last_filter = $liq_range_date->format('Y-m-t');
                    //Obtiene las ventas reales del comercial
                    if(User::find($value->agent_id)->hasRole('comercial')){
                        $agentTotalSales = Contract::from('contracts as ct')
                                ->leftJoin('users as us','ct.agent_id','=','us.id')
                                ->where('ct.contract_status_id','=',3)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->where('ct.agent_id','=',$value->agent_id)
                                ->count();
                    }else{
                        $agentTotalSales = Contract::from('contracts as ct')
                                ->leftJoin('users as us','ct.agent_id','=','us.id')
                                ->where('ct.contract_status_id','=',3)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->where('ct.teacher_id','=',$value->agent_id)
                                ->count();
                    }
                    //Obtiene las ventas reales del comercial en euros
                    $agentTotalSalesMoneyAdvance = Contract::from('contracts as ct')
                                ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                                ->where('ct.contract_status_id','=',3)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->where('ct.agent_id','=',$value->agent_id)
                                ->select(DB::raw('coalesce(ct.pp_initial_payment,0)AS advance'))
                                ->groupBy('ct.id','advance')
                                ->get();
                    $agentTotalSalesMoney = Contract::from('contracts as ct')
                                ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                                ->where('ct.contract_status_id','=',3)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->where('ct.agent_id','=',$value->agent_id)
                                ->sum('cf.fee_value');
                    $agentTotalSalesMoney = $agentTotalSalesMoneyAdvance->sum('advance') + $agentTotalSalesMoney;
                                // ->sum('cf.fee_value');
                    //Obtiene los Leads asignados
                    $agentTotalLeads = Lead::from('leads as le')
                                ->where('le.agent_id','=',$value->agent_id)
                                ->whereBetween('le.dt_assignment',[$dt_ini_filter,$dt_last_filter])
                                ->count();
                    //Conversión leads
                    $agentConversion =  ($agentTotalLeads < 1) ? 0.0:round((((float)$agentTotalSales / (float)$agentTotalLeads) * 100),3);
                    //Obtiene el modelo de liquidación del comercial
                    $agentLiquidationModel = LiquidationModel::from('liquidation_models as lm')
                                ->leftJoin('people_inf as pi','pi.liquidation_model_id','=','lm.id')
                                ->leftJoin('users as us','us.person_id','=','pi.id')
                                ->where('us.id','=',$value->agent_id)
                                ->select('lm.*')
                                ->get()->first();
                    //Dependiendo si es delegado o no saca la información de delegación
                    $agentPersonId = User::find($value->agent_id)->person_id;
                    $agentDelegates = PersonInf::from('people_inf as pi')
                                ->leftJoin('users as us','us.person_id','=','pi.id')
                                ->where('pi.delegate_id','=',$agentPersonId)
                                ->select('us.*')
                                ->get()->pluck('id');
                    //Se obtiene la información de ventas de los comerciales
                    //Total Ventas Delegación
                    $agentDelegationTotalSales = Contract::from('contracts as ct')
                                ->leftJoin('users as us','ct.agent_id','=','us.id')
                                ->where('ct.contract_status_id','=',3)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->whereIn('ct.agent_id',$agentDelegates)
                                ->count();
                    //las ventas reales del comercial en euros de la Delegación
                    $agentDelegationTotalSalesMoney = Contract::from('contracts as ct')
                                ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                                ->leftJoin('users as us','ct.agent_id','=','us.id')
                                ->where('ct.contract_status_id','=',3)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->whereIn('ct.agent_id',$agentDelegates)
                                ->sum('cf.fee_value');
                    //Genera Datatable
                    //De acuerdo al modelo de liquidación se revisa si tiene bonificación
                    //Matriculas sin bonificación
                    $salesNoBonus = Contract::from('contracts as ct')
                                ->leftJoin('users as us','ct.agent_id','=','us.id')
                                ->where('ct.contract_status_id','=',3)
                                ->where('ct.enroll','=',2)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->where('ct.agent_id','=',$value->agent_id)
                                ->count();
                    //Matriculas con bonificación
                    $salesBonus = Contract::from('contracts as ct')
                                ->leftJoin('users as us','ct.agent_id','=','us.id')
                                ->where('ct.contract_status_id','=',3)
                                ->where('ct.enroll','=',1)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->where('ct.agent_id','=',$value->agent_id)
                                ->count();
                    //Matriculas de Profesores
                    $teacherCount = Contract::from('contracts as ct')
                                ->leftJoin('users as us','ct.agent_id','=','us.id')
                                ->where('ct.contract_status_id','=',3)
                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                ->where('ct.teacher_id','=',$value->agent_id)
                                ->count();
                    if($agentLiquidationModel){
                        if(User::find($value->agent_id)->hasRole('teacher')){
                            $agentLiquidationPerSales = $teacherCount *(float)$agentLiquidationModel->enroll_commission ;
                        }
                        elseif (User::find($value->agent_id)->hasRole('comercial')) {
                            $agentLiquidationPerSales = ($salesNoBonus * (float)$agentLiquidationModel->enroll_commission) + 
                                                ($salesBonus * ((float)$agentLiquidationModel->enroll_commission +
                                                                (float)$agentLiquidationModel->enroll_bonus_commission)) ;
                        }
                        else{
                            $agentLiquidationPerSales = 0.0;
                        }
                        
                        
                        $agentDelegationLiquidationPerSales = $agentDelegationTotalSales * (float)$agentLiquidationModel->enroll_delegation;
                    }
                    else{
                        $agentLiquidationPerSales = 0.0;
                        $agentDelegationLiquidationPerSales = 0.0;
                    }
                    
                
                    //Gran total Liquidación
                    $agentGrandTotal = $agentLiquidationPerSales + $agentDelegationLiquidationPerSales;

                    //Generar Detalle
                    

                    
                    $liquidationAgent->push(['agent_name'           =>  $value->agent_name, 
                                            'agent_type'            =>  $value->agent_type, 
                                            'period_liq'            =>  $value->period_liq, 
                                            'status'                =>  $value->status, 
                                            'leads'                 =>  $agentTotalLeads, 
                                            'personal_sales_count'  =>  $agentTotalSales,
                                            'personal_sales'        =>  $agentTotalSalesMoney,
                                            'personal_commission'   =>  $agentLiquidationPerSales,
                                            'conversion'            =>  $agentConversion,
                                            'delegation_sales_count'=>  $agentDelegationTotalSales,
                                            'delegation_sales'      =>  $agentDelegationTotalSalesMoney,
                                            'delegation_commission' =>  $agentDelegationLiquidationPerSales,
                                            'grand_total'           =>  ((float)$agentGrandTotal + (float)$value->positive_value) - (float)$value->negative_value ,
                                            'positive_value'        =>  $value->positive_value,
                                            'negative_value'        =>  $value->negative_value,
                                            'agent_list_filter'     =>  $value->agent_id,
                                            'dt_ini_filter'         =>  $dt_ini_filter,
                                            'dt_last_filter'        =>  $dt_last_filter

                                        ]);
                }
                // dd($liquidationAgent);
                // dd(DataTables::of($liquidationAgent)->make(true));
                return DataTables::of($liquidationAgent)
                            ->addColumn('action_button', function($row){ 
                                $btn ='<div class="dropdown show" align="center">
                                <a  data-disabled="true" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fad  fa-fw fa-lg fa-ellipsis-v mr-1 text-lightblue"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                                $liquidation = "'liquidation_id"."_".$row['agent_list_filter'].'_'.$row['dt_ini_filter']."'";
                                $isAgent = ($row['agent_list_filter'] == '7') ? '<a style="cursor:pointer" class="dropdown-item" href="javascript:void(0)"><i class="fad fa-fw fa-sm fa-search mr-1 text-lightblue"></i> Sin Detalle</a>' : '<a  style="cursor:pointer"  onclick="document.getElementById('.$liquidation.').submit();" class="dropdown-item" ><i class="fad fa-fw fa-sm fa-search mr-1 text-lightblue"></i> Ver Detalle</a>';
                                
                                $actionButton = '<form method="get" id="liquidation_id_'.$row['agent_list_filter'].'_'.($row['dt_ini_filter']).'" action="/generateliquidation" enctype="multipart/form-data" target="_blank">'.
                                    csrf_field().
                                    '<input type="hidden" name="liq_agent_id" id="liq_agent_id" value ="'.encrypt($row['agent_list_filter']).'">'.
                                    '<input type="hidden" name="liq_dt_ini" id="liq_dt_ini" value = "'.encrypt($row['dt_ini_filter']).'">'.
                                    '<input type="hidden" name="liq_dt_end" id="liq_dt_end" value = "'.encrypt($row['dt_last_filter']).'">'.
                                    '<input type="hidden" name="bonification_id" id="bonification_id" value ="'.encrypt($row['positive_value']).'">'.
                                    '<input type="hidden" name="egretion_id" id="egretion_id" value ="'.encrypt($row['negative_value']).'">'.
                                    '<input type="hidden" name="liq_type" id="liq_type" value = "'.encrypt('view').'">'.
                                    $isAgent.
                                '</form>';
                            $btn = $btn. $actionButton;
                            if($row['status'] == 'A'){//Solo las aceptadas por el comercial
                                $btn = $btn. '<a class="dropdown-item caseDocuments" data-object="'.encrypt($row['agent_list_filter'].'_'.$row['dt_ini_filter']).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-cloud-upload-alt mr-1 text-lightblue"></i> Gestionar Documentos</a>';
                            }
                            $btn = $btn.'</div></div>';
                            return $btn;
                            })
                            ->rawColumns(['action_button'])
                            ->make(true);
                
                //Se obtienen los datos del filtro inicial
            }
            else{
                $pageTitle ='Histórico Liquidaciones - '.$companies->pluck('name')[0];
                return view('financial.liquidationlog',compact('pageTitle'));
            }
        }
        else{
            return view('auth.login'); 
        }
             
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LiquidationLog  $liquidationLog
     * @return \Illuminate\Http\Response
     */
    public function show(LiquidationLog $liquidationLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LiquidationLog  $liquidationLog
     * @return \Illuminate\Http\Response
     */
    public function edit(LiquidationLog $liquidationLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LiquidationLog  $liquidationLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LiquidationLog $liquidationLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LiquidationLog  $liquidationLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(LiquidationLog $liquidationLog)
    {
        //
    }
}
