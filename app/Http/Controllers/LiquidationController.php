<?php

namespace App\Http\Controllers;

use App\Models\{User,PersonInf,Lead,Role,Company,AccessCompany,Contract,ContractFee,LiquidationModel, Liquidation};
use DataTables,Auth,Redirect,Response,Config,DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use PDF,File,Mail;
use DateTime;
use Illuminate\Http\Request;
use App\Mail\sendLiquidation as sendLiquidation;

class LiquidationController extends Controller
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
        // dd($request);
        if (Auth::check()) {
            $user = Auth::user();
            $companies = User::find($user->id)->companies;
            $positive_value = (!empty($_GET["positive_value"])) ? ($_GET["positive_value"]) : 0;
            $negative_value = (!empty($_GET["negative_value"])) ? ($_GET["negative_value"]) : 0;
            $dt_reception_filter = (!empty($_GET["dt_reception_filter"])) ? ($_GET["dt_reception_filter"]) : Carbon::now()->format('Y-m');
            $agent_list_filter = (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : "7"; //Elige a sin asignar
            $agentName = User::find($agent_list_filter)->name;
            $liq_range = $dt_reception_filter."-01";
            $liq_range_date = new DateTime($liq_range);
            $dt_ini_filter = $liq_range_date->format('Y-m-d');
            $dt_last_filter = $liq_range_date->format('Y-m-t');
            
            if ($request->ajax()) {
                //Obtiene las ventas reales del comercial
                
                            
                if(User::find($agent_list_filter)->hasRole('comercial')){
                    $agentTotalSales = Contract::from('contracts as ct')
                            ->leftJoin('users as us','ct.agent_id','=','us.id')
                            ->where('ct.contract_status_id','=',3)
                            ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                            ->where('ct.agent_id','=',$agent_list_filter)
                            ->count();
                }else{
                    $agentTotalSales = Contract::from('contracts as ct')
                            ->leftJoin('users as us','ct.agent_id','=','us.id')
                            ->where('ct.contract_status_id','=',3)
                            ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                            ->where('ct.teacher_id','=',$agent_list_filter)
                            ->count();
                }
                //Obtiene las ventas reales del comercial en euros
                $agentTotalSalesMoneyAdvance = Contract::from('contracts as ct')
                            ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                            ->where('ct.contract_status_id','=',3)
                            ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                            ->where('ct.agent_id','=',$agent_list_filter)
                            ->select(DB::raw('coalesce(ct.pp_initial_payment,0)AS advance'))
                            ->groupBy('ct.id','advance')
                            ->get();
                $agentTotalSalesMoney = Contract::from('contracts as ct')
                            ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                            ->where('ct.contract_status_id','=',3)
                            ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                            ->where('ct.agent_id','=',$agent_list_filter)
                            ->sum('cf.fee_value');
                $agentTotalSalesMoney = $agentTotalSalesMoneyAdvance->sum('advance') + $agentTotalSalesMoney;
                            // ->sum('cf.fee_value');
                //Obtiene los Leads asignados
                $agentTotalLeads = Lead::from('leads as le')
                            ->where('le.agent_id','=',$agent_list_filter)
                            ->whereBetween('le.dt_assignment',[$dt_ini_filter,$dt_last_filter])
                            ->count();
                //Conversión leads
                $agentConversion =  ($agentTotalLeads < 1) ? 0.0:round((((float)$agentTotalSales / (float)$agentTotalLeads) * 100),3);
                //Obtiene el modelo de liquidación del comercial
                $agentLiquidationModel = LiquidationModel::from('liquidation_models as lm')
                            ->leftJoin('people_inf as pi','pi.liquidation_model_id','=','lm.id')
                            ->leftJoin('users as us','us.person_id','=','pi.id')
                            ->where('us.id','=',$agent_list_filter)
                            ->select('lm.*')
                            ->get()->first();
                
                //Bonificaciones
                
                // $positive_value = Liquidation::from('liquidations as li')
                //             ->leftJoin('people_inf as pi','pi.id','=','li.agent_id')
                //             ->leftJoin('users as us','us.person_id','=','pi.id')
                //             ->where('us.id','=',$agent_list_filter)
                //             ->select('li.positive_value')
                //             ->get()->first();

                //Egresiones
                // $negative_value = Liquidation::from('liquidations as li')
                //             ->leftJoin('people_inf as pi','pi.id','=','li.agent_id')
                //             ->leftJoin('users as us','us.person_id','=','pi.id')
                //             ->where('us.id','=',$agent_list_filter)
                //             ->select('li.negative_value')
                //             ->get()->first();
               
                //Dependiendo si es delegado o no saca la información de delegación
                $agentPersonId = User::find($agent_list_filter)->person_id;
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
                            ->where('ct.agent_id','=',$agent_list_filter)
                            ->count();
                //Matriculas con bonificación
                $salesBonus = Contract::from('contracts as ct')
                            ->leftJoin('users as us','ct.agent_id','=','us.id')
                            ->where('ct.contract_status_id','=',3)
                            ->where('ct.enroll','=',1)
                            ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                            ->where('ct.agent_id','=',$agent_list_filter)
                            ->count();
                //Matriculas de Profesores
                $teacherCount = Contract::from('contracts as ct')
                            ->leftJoin('users as us','ct.agent_id','=','us.id')
                            ->where('ct.contract_status_id','=',3)
                            ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                            ->where('ct.teacher_id','=',$agent_list_filter)
                            ->count();
                if($agentLiquidationModel){
                    if(User::find($agent_list_filter)->hasRole('teacher')){
                        $agentLiquidationPerSales = $teacherCount *(float)$agentLiquidationModel->enroll_commission ;
                    }
                    elseif (User::find($agent_list_filter)->hasRole('comercial')) {
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
                $agentGrandTotal = ($agentLiquidationPerSales + $agentDelegationLiquidationPerSales + $positive_value) - $negative_value;

                //Generar Detalle
                
                // if($LiquidationBonification == 0){
                //   $LiquidationBonification = '<input id="bonification_id" name="bonification_id" type="text" class="form-control form-control-sm m-n1 p-n1" pattern="[0-9]{1,}" size="5">' value;
                // // }
                // else{
                //     $LiquidationBonification = '<span><a href="javascript:void(0)" style = "cursor: pointer; " class="editBonification"><i class="fad fa-plus text-green fa-md"></i> <span class="text-sm" style="color:black!important">'+ ($LiquidationBonification)+' </span></a></span>';  
                // }
                // if($LiquidationEgretion == null){
                //     $LiquidationEgretion = '<a href="javascript:void(0)" style = "cursor: pointer; " class="editEgretion"><i class="fad fa-minus-circle text-red fa-md"></i> <span class="text-sm" style="color:black!important">... </span></a>';
                // // }
                // else{
                //     $LiquidationEgretion = '<span><a href="javascript:void(0)" style = "cursor: pointer; " class="editEgretion"><i class="fad fa-minus-circle text-red fa-md"></i> <span class="text-sm" style="color:black!important">'+ ($LiquidationBonification)+' </span></a></span>';  
                // }
                $liquidationAgent = new Collection;
                $liquidationAgent->push(['agent_name'           =>  $agentName, 
                                        'leads'                 =>  $agentTotalLeads, 
                                        'personal_sales_count'  =>  $agentTotalSales,
                                        'personal_sales'        =>  $agentTotalSalesMoney,
                                        'personal_commission'   =>  $agentLiquidationPerSales,
                                        'conversion'            =>  $agentConversion,
                                        'delegation_sales_count'=>  $agentDelegationTotalSales,
                                        'delegation_sales'      =>  $agentDelegationTotalSalesMoney,
                                        'delegation_commission' =>  $agentDelegationLiquidationPerSales,
                                        'grand_total'           =>  $agentGrandTotal,
                                        'agent_list_filter'     =>  $agent_list_filter,
                                        'positive_value'        =>  $positive_value,
                                        'negative_value'        =>  $negative_value,
                                        'dt_ini_filter'         =>  $dt_ini_filter,
                                        'dt_last_filter'        =>  $dt_last_filter

                                    ]);
                // dd(DataTables::of($liquidationAgent)->make(true));
                return DataTables::of($liquidationAgent)
                            ->addColumn('action_button', function($row){ 
                                $isAgent = ($row['agent_list_filter'] == '7') ? '<a class="btn btn-danger bordered disabled btn-xs" href="javascript:void(0)"><i class="fad fa-search"></i> Sin Detalle</a>' : '<button type="submit" class="btn btn-info bordered pill btn-xs" ><i class="fad fa-search"></i> Ver Detalle</button>';
                                
                                $actionButton = '<form method="get" action="/generateliquidation" enctype="multipart/form-data" target="_blank">'.
                                    csrf_field().
                                    '<input type="hidden" name="liq_agent_id" id="liq_agent_id" value ="'.encrypt($row['agent_list_filter']).'">'.
                                    '<input type="hidden" name="liq_dt_ini" id="liq_dt_ini" value = "'.encrypt($row['dt_ini_filter']).'">'.
                                    '<input type="hidden" name="liq_dt_end" id="liq_dt_end" value = "'.encrypt($row['dt_last_filter']).'">'.
                                    '<input type="hidden" name="bonification_id" id="bonification_id" value ="'.encrypt($row['positive_value']).'">'.
                                    '<input type="hidden" name="egretion_id" id="egretion_id" value ="'.encrypt($row['negative_value']).'">'.
                                    '<input type="hidden" name="liq_type" id="liq_type" value = "'.encrypt('view').'">'.
                                    $isAgent.
                                '</form>';
                                return $actionButton;
                            })
                            ->addColumn('positive_html', function($row){
                                return $row ['positive_value'];
                            })
                            ->escapeColumns([])
                            ->addColumn('negative_html', function($row){
                                return $row ['negative_value'];
                            })
                            ->escapeColumns([])
                            ->rawColumns(['action_button','positive_html','negative_html'])
                            ->make(true);
                
                //Se obtienen los datos del filtro inicial
            }
            else{
                $pageTitle ='Liquidaciones - '.$companies->pluck('name')[0];
                return view('financial.index',compact('pageTitle'));
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
     * @param  \App\Models\Liquidation  $liquidation
     * @return \Illuminate\Http\Response
     */
    public function show(Liquidation $liquidation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Liquidation  $liquidation
     * @return \Illuminate\Http\Response
     */
    public function edit(Liquidation $liquidation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Liquidation  $liquidation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Liquidation $liquidation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Liquidation  $liquidation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Liquidation $liquidation)
    {
        //
    }

    /**
     * Generate Liquidation Detail
     *
     * @param  \App\Models\Liquidation  $liquidation
     * @return \Illuminate\Http\Response
     */
    public function generateLiquidation(Request $request)
    {
        // dd(($request->all()));
        $agent_list_filter = decrypt($request->liq_agent_id);
        $positive_value = decrypt($request->bonification_id);
        $negative_value = decrypt($request->egretion_id);
        $agentName = User::find($agent_list_filter)->name;

        $dt_ini_filter = decrypt($request->liq_dt_ini);
        $dt_last_filter = decrypt($request->liq_dt_end);
        $liq_type = decrypt($request->liq_type);
        if(User::find($agent_list_filter)->hasRole('comercial')){
            $agentTotalSales = Contract::from('contracts as ct')
                    ->leftJoin('users as us','ct.agent_id','=','us.id')
                    ->where('ct.contract_status_id','=',3)
                    ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                    ->where('ct.agent_id','=',$agent_list_filter)
                    ->count();
            $agentSalesperGroup = Contract::from('contracts as ct')
                    ->leftJoin('users as us','ct.agent_id','=','us.id')
                    ->leftJoin('courses as crse','ct.course_id','=','crse.id')
                    ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                    ->where('ct.contract_status_id','=',3)
                    ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                    ->where('ct.agent_id','=',$agent_list_filter)
                    ->select('crar.name','crar.id', DB::raw('count(*) as total'))
                    ->groupBy('crar.name','crar.id')
                    ->orderBy('total','DESC')
                    ->get();
        }else{
            $agentTotalSales = Contract::from('contracts as ct')
                    ->leftJoin('users as us','ct.agent_id','=','us.id')
                    ->where('ct.contract_status_id','=',3)
                    ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                    ->where('ct.teacher_id','=',$agent_list_filter)
                    ->count();
            $agentSalesperGroup = Contract::from('contracts as ct')
                    ->leftJoin('users as us','ct.agent_id','=','us.id')
                    ->leftJoin('courses as crse','ct.course_id','=','crse.id')
                    ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                    ->where('ct.contract_status_id','=',3)
                    ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                    ->where('ct.teacher_id','=',$agent_list_filter)
                    ->select('crar.name','crar.id', DB::raw('count(*) as total'))
                    ->groupBy('crar.name','crar.id')
                    ->orderBy('total','DESC')
                    ->get();
        }
        
        //Obtiene las ventas reales del comercial en euros
        $agentTotalSalesMoneyAdvance = Contract::from('contracts as ct')
                        ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                        ->where('ct.contract_status_id','=',3)
                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                        ->where('ct.agent_id','=',$agent_list_filter)
                        ->select(DB::raw('coalesce(ct.pp_initial_payment,0)AS advance'))
                        ->groupBy('ct.id','advance')
                        ->get();
        $agentTotalSalesMoney = Contract::from('contracts as ct')
                        ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                        ->where('ct.contract_status_id','=',3)
                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                        ->where('ct.agent_id','=',$agent_list_filter)
                        ->sum('cf.fee_value');
        $agentTotalSalesMoney = $agentTotalSalesMoneyAdvance->sum('advance') + $agentTotalSalesMoney;
        //Obtiene los Leads asignados
        $agentTotalLeads = Lead::from('leads as le')
                    ->where('le.agent_id','=',$agent_list_filter)
                    ->whereBetween('le.dt_assignment',[$dt_ini_filter,$dt_last_filter])
                    ->count();
        //Conversión leads
        $agentConversion =  ($agentTotalLeads < 1) ? 0.0:round((((float)$agentTotalSales / (float)$agentTotalLeads) * 100),3);
        //Obtiene el modelo de liquidación del comercial
        $agentLiquidationModel = LiquidationModel::from('liquidation_models as lm')
                    ->leftJoin('people_inf as pi','pi.liquidation_model_id','=','lm.id')
                    ->leftJoin('users as us','us.person_id','=','pi.id')
                    ->where('us.id','=',$agent_list_filter)
                    ->select('lm.*')
                    ->get()->first();
        //Dependiendo si es delegado o no saca la información de delegación
        $agentPersonId = User::find($agent_list_filter)->person_id;
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
                    ->where('ct.agent_id','=',$agent_list_filter)
                    ->count();
        //Matriculas con bonificación
        $salesBonus = Contract::from('contracts as ct')
                    ->leftJoin('users as us','ct.agent_id','=','us.id')
                    ->where('ct.contract_status_id','=',3)
                    ->where('ct.enroll','=',1)
                    ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                    ->where('ct.agent_id','=',$agent_list_filter)
                    ->count();
        //Matriculas de Profesores
        $teacherCount = Contract::from('contracts as ct')
                    ->leftJoin('users as us','ct.agent_id','=','us.id')
                    ->where('ct.contract_status_id','=',3)
                    ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                    ->where('ct.teacher_id','=',$agent_list_filter)
                    ->count();
        if($agentLiquidationModel){
            if(User::find($agent_list_filter)->hasRole('teacher')){
                $agentLiquidationPerSales = $teacherCount *(float)$agentLiquidationModel->enroll_commission ;
            }
            elseif (User::find($agent_list_filter)->hasRole('comercial')) {
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
        $agentGrandTotal = ($agentLiquidationPerSales + $agentDelegationLiquidationPerSales + $positive_value) - $negative_value;

        //Periodo en español
        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
        $fechaLiq = Carbon::parse($dt_ini_filter);
        $date = $fechaLiq->locale();
        // dd($fechaLiq);
        $fechaLiq = $fechaLiq->monthName;
        
        // $fechaLiq = $fechaLiq->diffForHumans();// mes en idioma español
        
        //Variables para la vista
        $logo_empresa = 'https://efpsales.com/storage/uploads/contract_logos/LogoContract.png'; 
        $firma_empresa = 'https://efpsales.com/storage/uploads/contract_logos/LogoSignature.png'; 
        //Verifica si la liquidación ya la firmo el comercial
        $liqStatusCheck = Liquidation::from('liquidations as li')
                                ->where('li.agent_id','=',$agent_list_filter)
                                ->where('li.period_liq','=',Carbon::parse($dt_ini_filter)->format('Y-m'))
                                ->where('li.status','=','A')
                                ->select('li.*')
                                ->get()->first();
        $divFirma  = '';
        $disabledSignature  = '';
        $acceptedArea  = '';
        // dd($agent_list_filter);
        if(!is_null($liqStatusCheck) && $liqStatusCheck->signature_agent <> NULL && $liqStatusCheck->status == 'A'){ //Aceptado y Firmado
            $divFirma = '<br><img width="100%" class="img-responsive" src="https://efpsales.com/liq_signatures/'.$liqStatusCheck->agent_id.'/'.$liqStatusCheck->period_liq.'/'.$liqStatusCheck->signature_agent.'"/>'.
                        '<br><span><b>Fecha y Hora Firma: </b>'.Carbon::parse($liqStatusCheck->created_at)->format('Y-m-d H:m').'</span>';
            $disabledSignature = 'none';
            $acceptedArea =  '<div class="row " style="margin: auto;">'.
                                '<div class="col-sm-12" style="background-color:#d8ecf8;color:black;">'.
                                    '<p style="text-align:center; font-size:1.2em;"><b>ESTE DOCUMENTO HA SIDO FIRMADO CORRECTAMENTE</b></p>'.
                                    '<form method="get" action="/generateliquidation" enctype="multipart/form-data" target="_blank">'.
                                        csrf_field().
                                        '<input type="hidden" name="liq_agent_id" id="liq_agent_id" value ="'.encrypt($agent_list_filter).'">'.
                                        '<input type="hidden" name="liq_dt_ini" id="liq_dt_ini" value = "'.encrypt($dt_ini_filter).'">'.
                                        '<input type="hidden" name="liq_dt_end" id="liq_dt_end" value = "'.encrypt($dt_last_filter).'">'.
                                        '<input type="hidden" name="bonification_id" id="bonification_id" value ="'.encrypt($positive_value).'">'.
                                        '<input type="hidden" name="egretion_id" id="egretion_id" value ="'.encrypt($negative_value).'">'.
                                        '<input type="hidden" name="liq_type" id="liq_type" value = "'.encrypt('pdf').'">'.
                                        '<button type="submit" class="btn btn-block bg-lightblue bordered " ><i class="fad fa-file-pdf"></i> Obtener una Copia en PDF</button>'.
                                    '</form>';
                                '</div>'.
                            '</div>';
        }

        $firma_asesor = $divFirma .'<div id="signature-pad" class="signature-pad" style="display:'.$disabledSignature.'">
                                        <div class="m-signature-pad--body">
                                            <canvas style="border: 2px dashed #ccc"></canvas>
                                        </div>
                                        
                                        <div class="m-signature-pad--footer mt-2" align="center">
                                            <div class="description" style="color: #C3C3C3;text-align: center;font-size:0.8em;font-style: italic;">Firme arriba</div>
                                            <div class="btn-group p-1 ml-n2">
                                                <button type="button" class="btn btn-secondary mr-1" data-action="clear"><i class="fad fa-eraser"></i> Limpiar</button>
                                                <button type="button" class="btn bg-lightblue" data-action="save"><i class="fad fa-signature"></i> Firmar</button>
                                            </div>
                                            
                                        </div>
                                    </div>';
        //SACA LO QUE GANA POR VENTA DELEGACION
        //Antes de enviar a la vista se formatea
        // dd($agentLiquidationModel);
        $delegationperSaleEarning = $agentLiquidationModel->enroll_delegation;
        $currencyFormat = new \NumberFormatter( 'de_DE', \NumberFormatter::CURRENCY );
        $agentTotalSalesT                   = $agentTotalSales;
        $agentTotalSalesMoneyT              = $currencyFormat->format($agentTotalSalesMoney);
        $delegationperSaleEarningT          = $currencyFormat->format($delegationperSaleEarning);
        $agentLiquidationPerSalesT          = $currencyFormat->format($agentLiquidationPerSales);
        $agentDelegationLiquidationPerSales = $currencyFormat->format($agentDelegationLiquidationPerSales);
        $positive_valueT                    = $currencyFormat->format($positive_value);
        $negative_valueT                    = $currencyFormat->format($negative_value);
        $agentGrandTotal                    = $currencyFormat->format($agentGrandTotal);
        switch ($liq_type) {
            case 'view':
                return view('financial.viewliquidationdetail',compact('agentSalesperGroup','agent_list_filter','dt_ini_filter','dt_last_filter'
                                                            ,'agentTotalSalesT','agentTotalSalesMoneyT','agentLiquidationPerSalesT'
                                                            ,'agentDelegationTotalSales','delegationperSaleEarning','agentDelegationLiquidationPerSales'
                                                            ,'agentGrandTotal','fechaLiq','logo_empresa','firma_empresa','firma_asesor','acceptedArea','positive_value','negative_value'));
                break;
            case 'pdf':
                $result=array('agentSalesperGroup'=>$agentSalesperGroup,'agent_list_filter'=>$agent_list_filter,'dt_ini_filter'=>$dt_ini_filter,
                                'dt_last_filter' =>$dt_last_filter,'agentTotalSalesT'=>$agentTotalSalesT,'agentTotalSalesMoneyT'=>$agentTotalSalesMoneyT,
                                'agentLiquidationPerSalesT' =>$agentLiquidationPerSalesT,'agentDelegationTotalSalesT'=>$agentDelegationTotalSales,
                                'delegationperSaleEarningT'=>$delegationperSaleEarningT,
                                'agentDelegationLiquidationPerSalesT' =>$agentDelegationLiquidationPerSales,'agentGrandTotal'=>$agentGrandTotal,
                                'fechaLiq'=>$fechaLiq,'logo_empresa'=>$logo_empresa,
                                'positive_valueT'=>$positive_valueT,'negative_valueT'=>$negative_valueT,
                                'firma_empresa'=>$firma_empresa, 'firma_asesor'=>$firma_asesor,'acceptedArea'=>$acceptedArea
                            );
                
                $pdf = PDF::loadView('financial.viewpdfliquidation',$result);
                $pdf->setOption('margin-left',5);
                $pdf->setOption('margin-right',5);
                $pdf->setOption('margin-bottom',0);
                $pdf->setOption('enable-local-file-access', true);
                $pdf->setPaper('LEGAL');
                // dd($pdf);
                return $pdf->inline('liquidacion.pdf');
                break;
            
            default:
                # code...
                break;
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Liquidation  $liquidation
     * @return \Illuminate\Http\Response
     */
    public function sendLiquidation(Request $request)
    {
        $mailData = User::find($request->liq_agent_id)->toArray();
        // $actionButton = '<form method="get" action="'.route('liquidation.generate').'" enctype="multipart/form-data" target="_blank">'.
        //     csrf_field().
        //     '<input type="hidden" name="liq_agent_id" id="liq_agent_id" value ="'.encrypt($request->liq_agent_id).'">'.
        //     '<input type="hidden" name="liq_dt_ini" id="liq_dt_ini" value = "'.encrypt($request->liq_agent_ini).'">'.
        //     '<input type="hidden" name="liq_dt_end" id="liq_dt_end" value = "'.encrypt($request->liq_agent_end).'">'.
        //     '<input type="hidden" name="liq_type" id="liq_type" value = "'.encrypt('view').'">'.
        //     '<button type="submit" style="padding: 10px;background-color:#3c8dbc!important;color:white;border-radius: 10px;"><i class="fad fa-search"></i> Ver Detalle Liquidación</button>'.
        // '</form>';
        $actionButton = '<a class="btn " style="background-color:#3c8dbc!important;color:white;border-radius: 10px;" target="_blank" href="'
                .route('liquidation.generate').'?'.
                '&liq_agent_id='.encrypt($request->liq_agent_id).
                '&liq_dt_ini='.encrypt($request->liq_agent_ini).
                '&liq_dt_end='.encrypt($request->liq_agent_end).
                '&bonification_id='.encrypt($request->positive_value).
                '&egretion_id='.encrypt($request->negative_value).
                '&liq_type='.encrypt('view').
                '">Ver Detalle Liquidación</a>';
        // dd($mailData[0]['payer_id'] <> $mailData[0]['person_id']);
        $mailData['agent_full'] = $mailData['name'] . ' '. $mailData['last_name'];
        $mailData['btn_view_liq'] = $actionButton;
        Mail::to($mailData['email'])
            ->cc('info@grupoefp.com')
            ->bcc('systemskcd@gmail.com')
            ->send(new sendLiquidation($mailData));
        // Activar el siguiente bloque para pruebas
        // Mail::to('systemskcd@gmail.com')
        //     ->send(new sendLiquidation($mailData));
        
        //Una vez envía el correo guarda la liquidación como pendiente, si ya existe entonces no la guarda
        //Verifica que no existe
        $user = Auth::user();
        $companies = User::find($user->id)->companies;
        $liqStatusCheck = Liquidation::from('liquidations as li')
                                ->where('li.agent_id','=',$request->liq_agent_id)
                                ->where('li.period_liq','=',Carbon::parse($request->liq_agent_ini)->format('Y-m'))
                                ->select('li.*')
                                ->get()->first();
        if(is_null($liqStatusCheck)){
            // dd($request->all());
            $dataToUpdate  = [  'company_id' => $companies->pluck('id')[0],
                                'agent_id'   => $request->liq_agent_id,
                                'period_liq' => Carbon::parse($request->liq_agent_ini)->format('Y-m'),
                                'status' => 'P',
                                'positive_value' => $request->positive_value, 'negative_value' =>$request->negative_value
                            ];
            $liquidationAccept = Liquidation::updateOrCreate(
                    ['id' => $request->liq_id],
                    $dataToUpdate
            );  
        }

        return response()->json(['success'=>'Contrato Enviado al Asesor Correctamente']);
    }

    /**
     *     acceptLiquidation

     *
     * @param  \App\Models\Liquidation  $liquidation
     * @return \Illuminate\Http\Response
     */
     public function acceptLiquidation(Request $request)
     {
        // We create a variable to define the name of the file
        // Here it's the date and the extension signature.png
        $filename = date('dmYHis') . "-signature.png";
        // We store the signature file name in DB
        // We decode the image and store it in public folder
        $data_uri = $request->image_data;
        $typeRoleRequest = $request->typeRoleRequest;
        // dd($data_uri);
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);
        // $path = 'signatures/'.$request->enrollment_number.'/'.$typeRoleRequest.'/';
        $path = 'liq_signatures/'.$request->agent_id.'/'.$request->period_liq.'/';
        if(!Storage::exists($path)){
            File::makeDirectory($path, 0777, true, true);
        }
        // $decoded_image->storeAs($path,$decoded_image, ['disk' => 'public']);
        // dd($request->enrollment_number);
        file_put_contents($path.$filename, $decoded_image);
        // Text of the alter to confirm that the data is posted
        // return response()->json(['success'=>'Data is successfully added']);
        // dd($request->contract_id);
        //Obtiene la liquidación de ese periodo
        $liqStatusCheck = Liquidation::from('liquidations as li')
                                ->where('li.company_id','=',$request->company_id)
                                ->where('li.agent_id','=',$request->agent_id)
                                ->where('li.period_liq','=',$request->period_liq)
                                ->where('li.status','=','P')
                                ->select('li.*')
                                ->get()->first();
        // dd($request->all());
        $liqStatusCheck->update(['signature_agent' => $filename, 'status' => 'A']);
        // $dataToUpdate  = [  'company_id' => $request->company_id,
        //                     'agent_id'   => $request->agent_id,
        //                     'signature_agent' => $filename,
        //                     'period_liq' => $request->period_liq,
        //                     'status' => 'A',
        //             ];
        // $liquidationAccept = Liquidation::updateOrCreate(
        //     ['id' => $request->liq_id],
        //     $dataToUpdate
        // );     
         return response()->json(['success'=>'Hoja De Liquidación Firmada Exitosamente.']);
     }

     //Agregar Bonificación de Liquidacion

     public function updateBonification(Request $request)
    { 
        
        $dataToSave['positive_value'] = $request->positive_value;
        
        $updatebonification = Liquidation::updateOrCreate(
            ['id' => $request->positive_id],
            $dataToSave
        );
        //Actualiza última conexión
        Auth::user()->update_lastlogin();
        return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>actualización realizada con éxito</p>']);      
    }

    //Agregar Egresos de Liquidacion

    public function updateEgretion(Request $request)
    { 
        
        $dataToSave['negative_value'] = $request->negative_value;
        
        $updateegretion = Liquidation::updateOrCreate(
            ['id' => $request->egretion_id],
            $dataToSave
        );
        //Actualiza última conexión
        Auth::user()->update_lastlogin();
        return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>actualización realizada con éxito</p>']);      
    }
 
}
