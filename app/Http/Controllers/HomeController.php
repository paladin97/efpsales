<?php

namespace App\Http\Controllers;

use App\Models\{User,Lead,Contract,PersonInf,Province,AutonomousCommunity};
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables,Auth,Redirect,Response,Config,DB,Validator,DateTime;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $info = PersonInf::whereId($user->person_id)->get()->first();
        $companies = User::find($user->id)->companies;
        $pageTitle ='Panel Principal - '.$companies->pluck('name')[0];
        /*
        * Estadisticas generales
        */
        $rangeIni = Carbon::now()->startOfMonth(); 
        $rangeEnd = Carbon::now()->endOfMonth(); 
        $dataLead = Lead::from('leads as le')
                        ->where('le.lead_type_id','=',1 ) //asignacion1
                        // ->where('le.prev_agent_id','!=',$user)
                        ->whereBetween('le.dt_reception', [$rangeIni, $rangeEnd])
                        ->select('le.*');
        $dataLeadReacond = Lead::from('leads as le')
                        ->where('le.prev_agent_id','!=',7) //agente diferente de sin asignar
                        // ->where('le.prev_agent_id','!=',$user)
                        ->where('le.lead_type_id','=',2) //asignacion 2
                        ->whereBetween('le.dt_assignment', [$rangeIni, $rangeEnd])
                        ->select('le.*');
        $dataContract = Contract::from('contracts as c')
                        ->where('c.contract_status_id','=',3) //Matriculado
                        ->leftJoin('leads as le', 'c.lead_id', '=', 'le.id')
                        ->where('le.lead_type_id', '=', 1)
                        ->whereBetween('c.dt_created', [$rangeIni, $rangeEnd])
                        ->select('c.*');
        $dataContractReacond = Contract::from('contracts as c')
                        ->where('c.contract_status_id','=',3) //Matriculado
                        ->leftJoin('leads as le', 'c.lead_id', '=', 'le.id')
                        ->where('le.lead_type_id', '=', 2)
                        ->whereBetween('c.dt_created', [$rangeIni, $rangeEnd])
                        ->select('c.*');
                        

        if($user->hasRole('comercial')){
            $dataLead->where('le.agent_id','=',$user->id);
            $dataLeadReacond->where('le.agent_id','=',$user->id);
            $dataContract->where('c.agent_id','=',$user->id);
            $dataContractReacond->where('c.agent_id','=',$user->id);
               
        }
        else if($user->hasRole('admin')){

            $dataLead->leftJoin('users as us', 'le.agent_id', '=','us.id')
                     ->leftJoin('people_inf as pi', 'pi.id', '=','us.person_id')
                     ->whereIn('pi.company_id',$companies->pluck('id'));

            $dataLeadReacond->leftJoin('users as us', 'le.agent_id', '=','us.id')
                            ->leftJoin('people_inf as pi', 'pi.id', '=','us.person_id')
                            ->whereIn('pi.company_id',$companies->pluck('id'));

            $dataContract->leftJoin('users as us', 'le.agent_id', '=','us.id')
                         ->leftJoin('people_inf as pi', 'pi.id', '=','us.person_id')
                         ->whereIn('pi.company_id',$companies->pluck('id'));

            $dataContractReacond->leftJoin('users as us', 'le.agent_id', '=','us.id')
                                ->leftJoin('people_inf as pi', 'pi.id', '=','us.person_id')
                                ->whereIn('pi.company_id',$companies->pluck('id'));

        }
            // dd($dataLead->count());
            $countLeadReacond = $dataLeadReacond->count();
            $countLead      = $dataLead->count();
            $countContract  = $dataContract->count(); 
            $countContractReacond  = $dataContractReacond->count(); 
        
        //Si viene cero leads el resultado de conversión será 0
        $percent = ($countLead < 1) ? 0.0: round(((float)$countContract  / (float)$countLead) * 100, 2);
        $percentReacond = ($countLeadReacond < 1) ? 0.0: round(((float)$countContractReacond  / (float)$countLeadReacond) * 100, 2);
        /*
        * Estadisticas por detalle
        */
        $leadHome = Lead::from('leads as le')
                ->leftJoin('lead_status as lu', 'le.lead_status_id', '=', 'lu.id')
                ->leftJoin('users as u', 'le.agent_id', '=', 'u.id')
                ->leftJoin('courses as crs', 'le.course_id', '=', 'crs.id')
                ->select('le.*','u.name as agent_name','crs.name as curso'
                        ,'lu.name as lead_status','lu.color_class as color_class')
                ->orderBy('le.dt_reception','desc')
                ->take(9)->get();
        if($user->hasRole('comercial')){
            $leadHome->where('le.agent_id','=',$user->id);
        }
        $contractHome = Contract::from('contracts as c')
                ->leftJoin('contract_states as cs', 'c.contract_status_id', '=', 'cs.id')
                ->leftJoin('people_inf as pi', 'c.person_id', '=', 'pi.id')
                ->leftJoin('leads as le', 'c.lead_id', '=', 'le.id')
                ->leftJoin('users as u', 'le.agent_id', '=', 'u.id')
                ->leftJoin('courses as crs', 'c.course_id', '=', 'crs.id')
                ->select('c.*','u.name as agent_name','crs.name as curso'
                        ,'pi.name as student_first_name','pi.last_name as student_last_name'
                        ,'pi.mail as student_email'
                        ,'cs.name as contract_status','cs.color_class as color_class')
                ->orderBy('c.dt_created','desc')
                ->take(9)->get();
        if($user->hasRole('comercial')){
            $contractHome->where('c.agent_id','=',$user->id);
        }

        /**
        * Notas de Gestión Leads
         */
        $notesHome = Lead::from('lead_notes as ln')
                            ->leftJoin('leads as le', 'ln.lead_id', '=', 'le.id')
                            ->leftJoin('lead_status as lu', 'le.lead_status_id', '=', 'lu.id')
                            ->leftJoin('users as u', 'le.agent_id', '=', 'u.id')
                            ->leftJoin('courses as crs', 'le.course_id', '=', 'crs.id')
                            ->select('ln.*','u.name as agent_name','u.last_name as agent_lastname'
                                    ,'crs.name as curso','u.avatar as avatar'
                                    ,'le.student_first_name as student_name','le.student_last_name as student_lastname'
                                    ,'le.student_email as student_email'
                                    ,'lu.name as lead_status','lu.color_class as color_class')
                            ->orderBy('le.created_at','desc')
                            ->take(9)->get();
        if($user->hasRole('comercial')){
            $notesHome->where('ln.user_id','=',$user->id);
        }

        /**
         * grafica de barra de últimas matrículas
         */

        $rango =[];
        $rangoMes =[];
        $agents =[];

        
        //Guarda en español la fecha
        $dt_ini= Carbon::now()->subMonths(5);
        $dt_end= Carbon::now();
        $period = \Carbon\CarbonPeriod::create($dt_ini, '1 month', $dt_end);
        foreach ($period as $dt) {
            $inner_dt_ini = Carbon::parse($dt)->startOfMonth()->format('Y-m-d H:i:s');
            $inner_dt_end = Carbon::parse($dt)->endOfMonth()->format('Y-m-d H:i:s');
            $contracts = Contract::from('contracts as c')
                    ->leftJoin('leads as le','c.lead_id','=','le.id')
                    ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                    ->where('c.contract_status_id','=',3)
                    ->whereIn('c.company_id',$companies->pluck('id')) //La compañia mia
                    ->select(DB::raw('(SELECT SUM(cf.fee_value) FROM contract_fees cf WHERE cf.contract_id = c.id)   AS total'))
                    ->get()->pluck('total');
            // dd($contracts);
            array_push($rango, $dt->format("Y-m-d"));
            // $contracts[0] = number_format($contracts[0],2,',','.').' €';
            array_push($rangoMes, ucwords($dt->monthName));
            // array_push($rangoMes, ucwords($dt->monthName).' [Total: '.$contracts[0].']');
        }
        // dd($rango);
        //Saca todos los agentes del rango
        
        if ($user->hasRole('superadmin')){
            $agents = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
                    ->where('c.contract_status_id','=',3)
                    ->select('us.name as agent_name', 'us.last_name as agent_lastname','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.last_name','us.color')
                    ->get();
        }
        else if($user->hasRole('admin')){ //by Robert

            $agents = Contract::from('contracts as c')
            ->leftJoin('users as us','c.agent_id','=','us.id')
            ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
            ->whereIn('c.company_id',$companies->pluck('id'))
            ->where('c.contract_status_id','=',3)
            ->select('us.name as agent_name', 'us.last_name as agent_lastname','us.id as agent_id','us.color as agent_color')
            ->groupBy('us.id','us.name','us.last_name','us.color')
            ->get();

        }else{
            $agents = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
                    ->where('c.contract_status_id','=',3)
                    ->where('c.agent_id','=',$user->id)
                    ->select('us.name as agent_name','us.last_name as agent_lastname','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.last_name','us.color')
                    ->get();
        }
        
        $rangoMes = json_encode($rangoMes,JSON_NUMERIC_CHECK);
        $user = json_encode($user,JSON_NUMERIC_CHECK);

        // dd($user);
        // dd(response()->json($user));

        /*
        * Sacar matriz resumen por comerciales
        */
        $dt_ini_filter = Carbon::now()->firstOfMonth()->format('Y-m-d H:i:s');
        $dt_last_filter = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        $resultsTable = '<tr>';
        if (Auth::user()->hasRole('superadmin')){
            $agentsTable = Contract::from('users as us')
                    ->leftJoin('user_roles as usro','usro.user_id','=','us.id')
                    ->whereIn('usro.role_id',[3,4]) //comercial es 3
                    ->where('us.status','=',1) //comercial activo
                    // ->where('us.id','<>',7) //Sin asignar no debe salir
                    ->select('us.id as id')
                    ->get()->pluck('id');
        }
        else if (Auth::user()->hasRole('admin')){  //by Robert

            $agentsTable = Contract::from('users as us')
            ->leftJoin('user_roles as usro','usro.user_id','=','us.id')
            ->leftJoin('access_companies as accom','accom.user_id','=','us.id')
            ->whereIn('accom.company_id',$companies->pluck('id'))
            ->whereIn('usro.role_id',[3,4]) //comercial es 3
            ->where('us.status','=',1) //comercial activo
            ->select('us.id as id')
            ->get()->pluck('id');

        }else{
            $agentsTable = Contract::from('users as us')
                    ->leftJoin('user_roles as usro','usro.user_id','=','us.id')
                    ->where('usro.role_id','=',3) //comercial es 3
                    ->where('us.id','=',Auth::user()->id)
                    ->select('us.id as id')
                    ->get()->pluck('id');
        }
        foreach ($agentsTable as $key => $agent_id){
            
            $querySP = DB::statement(DB::raw('CALL AgentInformationSales(?, ?, ?, ?, @out1, @out2, @out3, @out4, @out5, @out6, @out7)'), 
            array($dt_ini_filter,$dt_last_filter,$agent_id,0));
            
            $resultsSP = DB::select('select @out1 as nLeads, @out2 as nVentas, @out3 as conversion, @out4 as liquidacion
                            , @out5 as nLeadshoy, @out6 as asesor, @out7 as ult_conexion'
                        );
            //si viene info de ultima conexión la muestra
            if($resultsSP[0]->ult_conexion != NULL){
                $ultCon = Carbon::Parse($resultsSP[0]->ult_conexion)->diffForHumans();
            }
            else{
                $ultCon = 'Sin información';
            }
            //si es admin entonces enseña la columna
            if(Auth::user()->hasRole('superadmin')){
                $ultconfin = '<td>'.$ultCon.'</td></tr>';
            }
            else{
                $ultconfin = '';
            }
            $resultsTable = $resultsTable.  '<td>'.++$key.'</td>'.
                                            '<td>'.$resultsSP[0]->asesor.'</td>'.
                                            '<td>'.$resultsSP[0]->nLeads.'</td>'.
                                            '<td>'.$resultsSP[0]->nVentas.'</td>'.
                                            '<td>'.(float)($resultsSP[0]->conversion * 100) .'%</td>'.
                                            '<td>'.$resultsSP[0]->liquidacion.'€</td>'.
                                            '<td>'.$resultsSP[0]->nLeadshoy.'</td>'.
                                            $ultconfin;
        }
        // dd($resultsTable);
        //Construye las ventas por provincia
        $provinceAll = Province::all();
        if (Auth::user()->hasRole('superadmin')){
            $autonomousCommunityAll = Contract::from('contracts as c')
                            ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                            ->leftJoin('provinces as prov','pi.province_id','=','prov.id')
                            ->leftJoin('autonomous_communities as acom','prov.acommunity_id','=','acom.id')
                            ->where('c.contract_status_id','=',3) //Matriculado
                            ->select('acom.name','acom.color',DB::raw('COALESCE(COUNT(*), 0) as total'))
                            ->groupBy('acom.name','acom.color')->get();
            // dd($autonomousCommunityAll);
            // dd(json($provinceAll));
            $provinceSells = Contract::from('contracts as c')
                            ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                            ->leftJoin('provinces as prov','pi.province_id','=','prov.id')
                            ->where('c.contract_status_id','=',3) //Matriculado
                            ->select('prov.short_name','prov.color',DB::raw('COALESCE(COUNT(*), 0) as total'))
                            ->groupBy('prov.short_name','prov.color')->get();
                            
        }elseif (Auth::user()->hasRole('admin')){
            $autonomousCommunityAll = Contract::from('contracts as c')
                            ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                            ->leftJoin('provinces as prov','pi.province_id','=','prov.id')
                            ->leftJoin('companies as co','c.company_id','=','co.id')
                            ->leftJoin('autonomous_communities as acom','prov.acommunity_id','=','acom.id')
                            ->whereIn('c.company_id',$companies->pluck('id'))
                            ->where('c.contract_status_id','=',3) //Matriculado
                            ->select('acom.name','acom.color',DB::raw('COALESCE(COUNT(*), 0) as total'))
                            ->groupBy('acom.name','acom.color')->get();
            
            $provinceSells = Contract::from('contracts as c')
                            ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                            ->leftJoin('provinces as prov','pi.province_id','=','prov.id')
                            ->whereIn('c.company_id',$companies->pluck('id')) //admin
                            ->where('c.contract_status_id','=',3) //Matriculado
                            ->select('prov.short_name','prov.color',DB::raw('COALESCE(COUNT(*), 0) as total'))
                            ->groupBy('prov.short_name','prov.color')->get();
        }
        else{ //comercial
            $autonomousCommunityAll = Contract::from('contracts as c')
                            ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                            ->leftJoin('provinces as prov','pi.province_id','=','prov.id')
                            ->leftJoin('autonomous_communities as acom','prov.acommunity_id','=','acom.id')
                            ->where('c.contract_status_id','=',3) //Matriculado
                            ->where('c.agent_id','=',Auth::user()->id)
                            ->select('acom.name','acom.color',DB::raw('COALESCE(COUNT(*), 0) as total'))
                            ->groupBy('acom.name','acom.color')->get();
            // dd($autonomousCommunityAll);
            // dd(json($provinceAll));
            $provinceSells = Contract::from('contracts as c')
                            ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                            ->leftJoin('provinces as prov','pi.province_id','=','prov.id')
                            ->where('c.contract_status_id','=',3) //Matriculado
                            ->where('c.agent_id','=',Auth::user()->id)  //comercial
                            ->select('prov.short_name','prov.color',DB::raw('COALESCE(COUNT(*), 0) as total'))
                            ->groupBy('prov.short_name','prov.color')->get();
        }

        // $provinceSells = json_encode($provinceSells,JSON_NUMERIC_CHECK);
        // dd($provinceSells);
        return view('home.index',compact('autonomousCommunityAll','provinceAll','provinceSells','resultsTable','countContractReacond','percentReacond','pageTitle','countLeadReacond','countLead','countContract','percent','leadHome','contractHome','notesHome','rango','agents','rangoMes','info'));
    }


}


