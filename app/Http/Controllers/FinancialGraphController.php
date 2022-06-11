<?php

namespace App\Http\Controllers;

use App\Models\{Contract, User, Role, Company, AccessCompany, ContractFee, Course, PersonInf, Lead, Term, ManagementNote};
use Illuminate\Http\Request;
use DataTables,Auth,Redirect,Response,Config,DB,Validator,DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use PDF,File,Mail;
use Excel;
class FinancialGraphController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companies = User::find($user->id)->companies;
        $companiesName = $companies->pluck('name')[0];
        $pageTitle ='Estadísticas y Comparativas- '.$companies->pluck('name')[0];
        
        $filter_list = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->where('c.contract_status_id','=',3)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();

        $dt_ini = (!empty($request->dt_sells_from)) ? Carbon::parse($request->dt_sells_from)->format('YYYY-MM-DD') : Carbon::now()->subMonths(12);
        $dt_end = (!empty($request->dt_sells_to)) ? Carbon::parse($request->dt_sells_to)->format('YYYY-MM-DD') : Carbon::now();
        $agent_list_filter = (!empty($request->agent_list_filter)) ? ($request->agent_list_filter) : $filter_list->pluck('agent_id')->unique();
        // dd($request->all());
        /**
        * grafica de barra de últimas matrículas
        */

        $rango =[];
        $rangoMes =[];
        $agents =[];
        $rangoMesM =[];
        $rangoM =[];
        $rangoYDT =[];
        $rangoMesYDT =[];

        
        //Guarda en español la fecha
        $period = \Carbon\CarbonPeriod::create($dt_ini, '1 month', $dt_end);
        foreach ($period as $dt) {
            $inner_dt_ini = Carbon::parse($dt)->startOfMonth()->format('Y-m-d H:i:s');
            $inner_dt_end = Carbon::parse($dt)->endOfMonth()->format('Y-m-d H:i:s');
            $contracts = Contract::from('contracts as c')
                    ->leftJoin('leads as le','c.lead_id','=','le.id')
                    ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                    ->where('c.contract_status_id','=',3)
                    ->whereIn('c.company_id',$companies->pluck('id')) //La compañia mia
                    ->select(DB::raw('SUM(coalesce(c.s_payment,0) + coalesce(c.pp_initial_payment,0) + coalesce(c.m_payment,0)
                    + (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0))) AS total'))
                    ->get()->pluck('total');
            // dd($contracts);
            array_push($rango, $dt->format("Y-m-d"));
            $contracts[0] = number_format($contracts[0],2,',','.').' €';
            array_push($rangoMes, ucwords($dt->monthName).' [Total: '.$contracts[0].']');
        }

        $periodM = \Carbon\CarbonPeriod::create($dt_ini, '1 month', $dt_end);
        foreach ($periodM as $dt) {
            $inner_dt_ini = Carbon::parse($dt)->startOfMonth()->format('Y-m-d H:i:s');
            $inner_dt_end = Carbon::parse($dt)->endOfMonth()->format('Y-m-d H:i:s');
            $contracts = Contract::from('contracts as c')
                    ->leftJoin('leads as le','c.lead_id','=','le.id')
                    ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                    ->where('c.contract_status_id','=',3)
                    ->whereIn('c.company_id',$companies->pluck('id')) //La compañia mia
                    ->count();
            // dd($contracts);
            array_push($rangoM, $dt->format("Y-m-d"));
            array_push($rangoMesM, ucwords($dt->monthName).' [Total: '.$contracts.']');
        }
        // dd($rango);
        //Saca todos los agentes del rango
        
        if ($user->hasRole('superadmin')){
            $agents = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
                    ->whereIn('c.agent_id',$agent_list_filter)
                    ->where('c.contract_status_id','=',3)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();
        }
        else{
            $agents = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
                    ->where('c.contract_status_id','=',3)
                    ->where('c.agent_id','=',$user->id)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();
        }

        /**
            Grafico de YDT Year to Date
            se trae el rango de 12 meses y se grafica por año el total de ventas
        */
        $anniActual     = ['year' => Carbon::now()->format('Y'), 'color' => '#54ce7078'];
        $anniAnterior   = ['year' => Carbon::now()->subYear()->format('Y'), 'color' => '#a2cfffb8'];
        $anniDAnterior  = ['year' => Carbon::now()->subYears(2)->format('Y'), 'color' => '#a973e7cf'];
        $collectionYDT  = collect([$anniActual, $anniAnterior, $anniDAnterior]);

        // dd($collectionYDT);
        $dt_ini_ydt= Carbon::parse('2021-01-01')->format('Y-m-d');
        $dt_end_ydt= Carbon::parse('2021-12-01')->format('Y-m-d');
        $period_ydt = \Carbon\CarbonPeriod::create($dt_ini_ydt, '1 month', $dt_end_ydt);
        foreach ($period_ydt as $dt) {
            array_push($rangoYDT, $dt->format("Y-m-d"));
            $inner_dt_ini = Carbon::parse($dt)->startOfMonth()->format('Y-m-d');
            $inner_dt_end = Carbon::parse($dt)->endOfMonth()->format('Y-m-d');
            array_push($rangoMesYDT, ucwords($dt->monthName));
        }
        //Ultimas Mátriculas
        $lastEnroll = Contract::from('contracts as c')
                    ->leftJoin('leads as le','c.lead_id','=','le.id')
                    ->leftJoin('users as us','le.agent_id','=','us.id')
                    ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                    ->leftJoin('courses as crse','c.course_id','=','crse.id')
                    ->where('c.contract_status_id','=',3)
                    ->whereIn('c.company_id',$companies->pluck('id')) //La compañia mia
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color','c.dt_created'
                    ,'pi.name as name','pi.last_name as last_name','crse.name as course_name','c.enrollment_number as enrollment_number'
                    , DB::raw('coalesce(c.s_payment,0) + coalesce(c.pp_initial_payment,0) + coalesce(c.m_payment,0)
                                           + (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0)) AS total')
                    ,DB::raw('CONCAT(pi.name," ",pi.last_name) as full_name')
                    )
                    ->orderBy('c.dt_created','DESC')
                    ->take(7)->get();
        $rangoMesYDT = json_encode($rangoMesYDT,JSON_NUMERIC_CHECK);   

        
        $rangoMes = json_encode($rangoMes,JSON_NUMERIC_CHECK);
        $rangoMesM = json_encode($rangoMesM,JSON_NUMERIC_CHECK);
        $user = json_encode($user,JSON_NUMERIC_CHECK);


        if ($request->ajax()) {
            return response()->json([
                'rango' => $rango,
                'agents' => $agents,
                'rangoMes' => $rangoMes,
                'rangoMesM' => $rangoMesM,
                'rangoM' => $rangoM
            ]);
        }

        //ventas anuales
        foreach($rango as $indexy => $cMonth)
        {
            $inner_dt_ini = Carbon::parse($cMonth)->startOfMonth()->format('Y-m-d');
            $inner_dt_end = Carbon::parse($cMonth)->endOfMonth()->format('Y-m-d');
            $yearSales= Contract::from('contracts as c')
                        ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                        ->where('c.contract_status_id','=',3)
                        ->select(DB::raw('SUM(coalesce(c.s_payment,0)+ coalesce(c.pp_initial_payment,0)+ (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0))) AS total'))
                        ->get()->pluck('total');
        }           


        return view('financialgraph.index',compact('yearSales','companiesName','pageTitle','rango','agents','rangoMes','rangoMesM','rangoM', 'collectionYDT','rangoMesYDT','rangoYDT'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sellsGraph(Request $request)
    {
        $user = Auth::user();
        $companies = User::find($user->id)->companies;
        $pageTitle ='Estadísticas y Comparativas- '.$companies->pluck('name')[0];
        
        $filter_list = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->where('c.contract_status_id','=',3)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();

        $dt_ini = (!empty($request->dt_sells_from)) ? Carbon::parse($request->dt_sells_from) : Carbon::now()->subMonths(12);
        $dt_end = (!empty($request->dt_sells_to)) ? Carbon::parse($request->dt_sells_to) : Carbon::now();
        $agent_list_filter = (!empty($request->agent_list_filter)) ? ($request->agent_list_filter) : $filter_list->pluck('agent_id')->unique();
        // dd($request->dt_sells_from);
        /**
        * grafica de barra de últimas matrículas
        */

        $rango =[];
        $rangoMes =[];
        $agents =[];

        
        //Guarda en español la fecha
        $period = \Carbon\CarbonPeriod::create($dt_ini, '1 month', $dt_end);
        foreach ($period as $dt) {
            array_push($rango, $dt->format("Y-m-d"));
            array_push($rangoMes, ucwords($dt->monthName));
        }
        // dd($rango);
        //Saca todos los agentes del rango
        
        if ($user->hasRole('superadmin')){
            $agents = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
                    ->whereIn('c.agent_id',$agent_list_filter)
                    ->where('c.contract_status_id','=',3)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();
        }
        else{
            $agents = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
                    ->where('c.contract_status_id','=',3)
                    ->where('c.agent_id','=',$user->id)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();
        }
        

        if ($request->ajax()) {
            // dd($request->all());
            $expY = array();
            foreach($agents as $index => $cData)
            {
                $expY[$index]['label'] = $cData->agent_name;
                $expY[$index]['backgroundColor'] = ($cData->agent_color == null) ?'#2c9ac5' : $cData->agent_color;
                $expY[$index]['borderWidth'] = 1;
                $expY[$index]['data'] = array();
                foreach($rango as $indexy => $cMonth)
                {
                    $inner_dt_ini =Carbon::parse($cMonth)->startOfMonth()->format('Y-m-d');
                    $inner_dt_end = Carbon::parse($cMonth)->endOfMonth()->format('Y-m-d');
                    $contracts = Contract::from('contracts as c')
                            ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                            ->where('c.contract_status_id','=',3)
                            ->where('c.agent_id','=',$cData->agent_id)
                            ->select(DB::raw('SUM(coalesce(c.s_payment,0)+ coalesce(c.pp_initial_payment,0)+ (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0))) AS total'))
                            ->get()->pluck('total');
                    $expY[$index]['data'][$indexy] = ($contracts[0] == null) ? '' : $contracts[0];
                }
            }
            // dd($expY);
            return response()->json([
                'expY' => $expY,
                'rango' => $rango,
                'agents' => $agents,
                'rangoMes' => $rangoMes
            ]);
        }

        $rangoMes = json_encode($rangoMes,JSON_NUMERIC_CHECK);
        $user = json_encode($user,JSON_NUMERIC_CHECK);

        // dd($user);
        // dd(response()->json($user));
        
        return view('financialgraph.index',compact('pageTitle','rango','agents','rangoMes'));
    }

    public function contGraph(Request $request)
    {
        $user = Auth::user();
        $companies = User::find($user->id)->companies;
        $pageTitle ='Estadísticas y Comparativas- '.$companies->pluck('name')[0];
        
        $filter_list = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->where('c.contract_status_id','=',3)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();

        $dt_ini = (!empty($request->dt_sells_fromM)) ? Carbon::parse($request->dt_sells_fromM) : Carbon::now()->subMonths(12);
        $dt_end = (!empty($request->dt_sells_toM)) ? Carbon::parse($request->dt_sells_toM) : Carbon::now();
        $agent_list_filter = (!empty($request->agent_list_filter)) ? ($request->agent_list_filter) : $filter_list->pluck('agent_id')->unique();
        // dd($request->dt_sells_from);
        /**
        * grafica de barra de últimas matrículas
        */

        $rangoM =[];
        $rangoMesM =[];
        $agents =[];

        
        //Guarda en español la fecha
        $periodM = \Carbon\CarbonPeriod::create($dt_ini, '1 month', $dt_end);
        foreach ($periodM as $dt) {
            array_push($rangoM, $dt->format("Y-m-d"));
            array_push($rangoMesM, ucwords($dt->monthName));
        }
        // dd($rango);
        //Saca todos los agentes del rango
        
        if ($user->hasRole('superadmin')){
            $agents = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
                    ->whereIn('c.agent_id',$agent_list_filter)
                    ->where('c.contract_status_id','=',3)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();
        }
        else{
            $agents = Contract::from('contracts as c')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->whereBetween('c.dt_created',[$dt_ini->format('Y-m-d'),$dt_end->format('Y-m-d')])
                    ->where('c.contract_status_id','=',3)
                    ->where('c.agent_id','=',$user->id)
                    ->select('us.name as agent_name','us.id as agent_id','us.color as agent_color')
                    ->groupBy('us.id','us.name','us.color')
                    ->get();
        }
        

        if ($request->ajax()) {
            // dd($request->all());
            $exp = array();
            foreach($agents as $index => $cData)
            {
                $exp[$index]['label'] = $cData->agent_name;
                $exp[$index]['backgroundColor'] = ($cData->agent_color == null) ?'#2c9ac5' : $cData->agent_color;
                $exp[$index]['borderWidth'] = 1;
                $exp[$index]['data'] = array();
                foreach($rangoM as $indexy => $cMonth)
                {
                    $inner_dt_ini =Carbon::parse($cMonth)->startOfMonth()->format('Y-m-d');
                    $inner_dt_end = Carbon::parse($cMonth)->endOfMonth()->format('Y-m-d');
                    $contracts = Contract::from('contracts as c')
                            ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                            ->where('c.contract_status_id','=',3)
                            ->where('c.agent_id','=',$cData->agent_id)
                            ->count();
                    $exp[$index]['data'][$indexy] = ($contracts == null) ? '' : $contracts;
                }
            }
            // dd($expY);
            return response()->json([
                'exp' => $exp,
                'rangoM' => $rangoM,
                'agents' => $agents,
                'rangoMesM' => $rangoMesM
            ]);
        }

        $rangoMesM = json_encode($rangoMesM,JSON_NUMERIC_CHECK);
        $user = json_encode($user,JSON_NUMERIC_CHECK);
        
        // dd($user);
        // dd(response()->json($user));
        
        return view('financialgraph.index',compact('pageTitle','rangoM','agents','rangoMesM'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
