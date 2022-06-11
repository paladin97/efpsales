<?php

namespace App\Http\Controllers;

use App\Models\{Spreadsheet, Province,Contract, User, Role, Company, AccessCompany, ContractFee, Course, PersonInf, Lead, Term, ManagementNote};
use Illuminate\Http\Request;
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use PDF,File,Mail,DateTime;
use Excel;
use Ixudra\Curl\Facades\Curl;
use App\Mail\sendContract as sendContract;
use App\Mail\openContract as openContract;
use App\Mail\acceptContract as acceptContract;

class SpreadsheetController extends Controller
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
        $spreadsheet;
        if (Auth::check()) {
            $user = Auth::user();
            $companies = User::find($user->id)->companies;
            $urlface = $companies[0]->url_facebook;
            $filter_list = Contract::from ('contracts as cf')
                        ->leftJoin('leads as le','cf.lead_id','le.id')
                        ->leftJoin('courses as crse', 'cf.course_id', '=','crse.id')
                        ->leftJoin('course_areas as crsearea', 'crse.area_id', '=','crsearea.id')
                        ->leftJoin('people_inf as pi','cf.person_id','pi.id')
                        ->leftJoin('provinces as prov','pi.province_id','prov.id')
                        ->leftJoin('autonomous_communities as aucom','prov.acommunity_id','aucom.id')
                        ->where('cf.contract_status_id','=', 3)
                        ->select('cf.*','le.agent_id as agent_id','pi.province_id as province_id','aucom.id as aucom_id',
                                'crsearea.id as area_id'
                            )
                        ->orderByRaw(DB::Raw("cf.created_at desc"))
                        ->get();

            $agent_list_filter = (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : $filter_list->pluck('agent_id')->unique()->toArray();
            $agent_list_filter_SP = (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : $filter_list->pluck('agent_id')->unique()->toArray();
            $spreadsheet_method_type_list_filter = (!empty($_GET["contract_method_type_list_filter"])) ? ($_GET["contract_method_type_list_filter"]) : $filter_list->pluck('contract_method_type_id')->unique()->toArray();
            $courses_list_filter = (!empty($_GET["course_filter"])) ? ($_GET["course_filter"]) : $filter_list->pluck('course_id')->unique()->toArray();
            $coursesarea_list_filter = (!empty($_GET["course_area_filter"])) ? ($_GET["course_area_filter"]) : $filter_list->pluck('area_id')->unique()->toArray();
            $spreadsheet_province_filter = (!empty($_GET["contract_province_filter"])) ? ($_GET["contract_province_filter"]) : $filter_list->pluck('province_id')->unique()->toArray();
            $spreadsheet_aucom_filter = (!empty($_GET["contract_aucom_filter"])) ? ($_GET["contract_aucom_filter"]) : $filter_list->pluck('aucom_id')->unique()->toArray();
            $year_filter = (!empty($_GET["year_filter"])) ? ($_GET["year_filter"]) : Carbon::now()->format('Y');
            $quarter_filter = (!empty($_GET["quarter_filter"])) ? ((($_GET["quarter_filter"]) < 5 ) ? ($_GET["quarter_filter"]) : [1,2,3,4]) : [1,2,3,4];
            $quarter_filterSP = (!empty($_GET["quarter_filter"])) ? ((($_GET["quarter_filter"]) < 5 ) ? ($_GET["quarter_filter"]) : 5) : 5;
            
            $agent_list_filter = (is_array($agent_list_filter)) ? (implode(", ", $agent_list_filter)) : $agent_list_filter;
            $courses_list_filter = (is_array($courses_list_filter)) ? (implode(", ", $courses_list_filter)) : $courses_list_filter;
            $coursesarea_list_filter = (is_array($coursesarea_list_filter)) ? (implode(", ", $coursesarea_list_filter)) : $coursesarea_list_filter;
            $spreadsheet_province_filter = (is_array($spreadsheet_province_filter)) ? (implode(", ", $spreadsheet_province_filter)) : $spreadsheet_province_filter;
            $spreadsheet_aucom_filter = (is_array($spreadsheet_aucom_filter)) ? (implode(", ", $spreadsheet_aucom_filter)) : $spreadsheet_aucom_filter;
            $spreadsheet_method_type_list_filter = (is_array($spreadsheet_method_type_list_filter)) ? (implode(", ", $spreadsheet_method_type_list_filter)) : $spreadsheet_method_type_list_filter;
            $quarter_filter = (is_array($quarter_filter)) ? (implode(", ", $quarter_filter)) : $quarter_filter;

            // $courses_list_filter = implode(", ", $courses_list_filter);
            // $spreadsheet_province_filter = implode(", ", $spreadsheet_province_filter);
            // $spreadsheet_aucom_filter = implode(", ", $spreadsheet_aucom_filter);
            // $spreadsheet_method_type_list_filter = implode(", ", $spreadsheet_method_type_list_filter);
            // $quarter_filter = implode(", ", $quarter_filter);
            
            // dd($year_filter);

            $spreadsheet = DB::select( DB::raw("
                                        SELECT P.area_name, P.course_name, P.year, P.quarter, SUM(P.n_ventas) as n_ventas , SUM(P.ventas) as ventas
                                        FROM (
                                            SELECT crsearea.name as area_name, crse.name as course_name,
                                                    YEAR(c.dt_created) as year, QUARTER(c.dt_created) as quarter, 
                                                    0 as n_ventas, SUM(cf.fee_value) as ventas
                                            FROM contracts c, contract_fees cf, leads le,people_inf as pi
                                                    ,courses as crse, course_areas as crsearea
                                                    ,provinces as prov, autonomous_communities as aucom
                                            WHERE c.lead_id = le.id
                                            AND c.course_id = crse.id
                                            AND crse.area_id = crsearea.id
                                            AND cf.contract_id = c.id
                                            AND c.person_id = pi.id
                                            AND pi.province_id = prov.id
                                            AND prov.acommunity_id = aucom.id
                                            AND c.contract_type_id = 1
                                            AND c.contract_status_id = 3
                                            AND le.agent_id IN ($agent_list_filter)
                                            AND crse.id IN ($courses_list_filter)
                                            AND crsearea.id IN ($coursesarea_list_filter)
                                            AND prov.id IN ($spreadsheet_province_filter)
                                            AND aucom.id IN ($spreadsheet_aucom_filter)
                                            AND c.contract_method_type_id IN ($spreadsheet_method_type_list_filter)
                                            AND YEAR(c.dt_created) = $year_filter
                                            AND QUARTER(c.dt_created) IN ($quarter_filter)
                                            GROUP BY 1,2,3,4,5
                                            UNION ALL
                                            SELECT crsearea.name as area_name, crse.name as course_name,
                                                   YEAR(c.dt_created) as year, QUARTER(c.dt_created) as quarter,
                                                   COUNT(*) as n_ventas, 0 as ventas
                                            FROM contracts c,  leads le,people_inf as pi
                                                    ,courses as crse, course_areas as crsearea
                                                    ,provinces as prov, autonomous_communities as aucom
                                            WHERE c.lead_id = le.id
                                            AND c.course_id = crse.id
                                            AND crse.area_id = crsearea.id
                                            AND c.person_id = pi.id
                                            AND pi.province_id = prov.id
                                            AND prov.acommunity_id = aucom.id
                                            AND c.contract_type_id = 1
                                            AND c.contract_status_id = 3
                                            AND le.agent_id IN ($agent_list_filter)
                                            AND crse.id IN ($courses_list_filter)
                                            AND crsearea.id IN ($coursesarea_list_filter)
                                            AND prov.id IN ($spreadsheet_province_filter)
                                            AND aucom.id IN ($spreadsheet_aucom_filter)
                                            AND c.contract_method_type_id IN ($spreadsheet_method_type_list_filter)
                                            AND YEAR(c.dt_created) = $year_filter
                                            AND QUARTER(c.dt_created) IN ($quarter_filter)
                                            GROUP BY 1,2,3,4,6
                                        ) P
                                        GROUP BY 1,2,3,4"));
            // dd($results);
            // $spreadsheet = Contract::from('contracts as c')
            //         ->whereIn('c.company_id',$companies->pluck('id'))
            //         ->leftJoin('contract_fees as cf','cf.contract_id','=','cf.id')
            //         ->leftJoin('leads as le','c.lead_id','=','le.id')
            //         ->leftJoin('companies as com','c.company_id','=','com.id')
            //         ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
            //         ->leftJoin('provinces as prov','pi.province_id','prov.id')
            //         ->leftJoin('autonomous_communities as aucom','prov.acommunity_id','aucom.id')
            //         ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
            //         ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
            //         ->leftJoin('course_areas as crsearea', 'crse.area_id', '=','crsearea.id')
            //         ->leftJoin('users as us','c.agent_id','=','us.id')
            //         ->WhereIn('crse.id',$courses_list_filter)
            //         ->WhereIn('le.agent_id',$agent_list_filter)
            //         ->Where('c.contract_type_id','=',1) //Matriculas
            //         ->Where('c.contract_status_id','=', 3) //Matriculado
            //         ->WhereIn('c.contract_method_type_id',$spreadsheet_method_type_list_filter)
            //         ->WhereIn('pi.province_id',$spreadsheet_province_filter)
            //         ->where(DB::raw('YEAR(c.dt_created)'), $year_filter)
            //         ->groupBy('crsearea.name','crse.name','c.dt_created')
            //         ->select('crsearea.name as area_name','crse.name as course_name'
            //                 , DB::raw('YEAR(c.dt_created) as year_filter')
            //                 , DB::raw('QUARTER(c.dt_created) as quarter_filter')
            //                 , DB::raw('SUM(cf.fee_value) as ventas')
            //                 , DB::raw('COUNT(c.enrollment_number) as n_ventas')
            //         );
            // if($quarter_filter< 5){
            //     $spreadsheet->where(DB::raw('QUARTER(c.dt_created)'), $quarter_filter);
            // }
            // dd($filter_list);
            // dd(collect($spreadsheet));
            if ($request->ajax()) {
            return DataTables::of($spreadsheet)
            // return DataTables::queryBuilder($spreadsheet)
                    // ->toJson();
                    ->addColumn('commissions',function($row)use ($quarter_filterSP,$year_filter,$agent_list_filter_SP){
                        // dd($quarter_filterSP);
                        // SE COMENTA PORQUE NO LO PIDIO
                        // switch ($quarter_filterSP) {
                        //     case '5':
                        //         $dt_ini_filter = Carbon::parse($year_filter.'-01-01')->format('Y-m-d');
                        //         $dt_last_filter = Carbon::parse($year_filter.'-12-31')->format('Y-m-d');
                        //         break;
                            
                        //     case '4':
                        //         $dt_ini_filter = Carbon::parse($year_filter.'-10-01')->format('Y-m-d');
                        //         $dt_last_filter = Carbon::parse($year_filter.'-12-01')->endOfMonth()->format('Y-m-d');
                        //         break;
                            
                        //     case '3':
                        //         $dt_ini_filter = Carbon::parse($year_filter.'-07-01')->format('Y-m-d');
                        //         $dt_last_filter = Carbon::parse($year_filter.'-09-01')->endOfMonth()->format('Y-m-d');
                        //         break;
                            
                        //     case '2':
                        //         $dt_ini_filter = Carbon::parse($year_filter.'-04-01')->format('Y-m-d');
                        //         $dt_last_filter = Carbon::parse($year_filter.'-06-01')->endOfMonth()->format('Y-m-d');
                        //         break;
                            
                        //     case '1':
                        //         $dt_ini_filter = Carbon::parse($year_filter.'-01-01')->format('Y-m-d');
                        //         $dt_last_filter = Carbon::parse($year_filter.'-03-01')->endOfMonth()->format('Y-m-d');
                        //         break;
                            
                        //     default:
                        //         $dt_ini_filter = Carbon::parse($year_filter.'-01-01')->format('Y-m-d');
                        //         $dt_last_filter = Carbon::parse($year_filter.'-12-31')->format('Y-m-d');
                        //         break;
                        // }
                        // // dd($dt_ini_filter);
                        // $salesComissions = 0;
                        // foreach ($agent_list_filter_SP as $key => $agent_id) {
            
                        //     $querySP = DB::statement(DB::raw('CALL AgentInformationSales(?, ?, ?, ?, @out1, @out2, @out3, @out4, @out5, @out6, @out7)'), 
                        //     array($dt_ini_filter,$dt_last_filter,$agent_id,0));
                            
                        //     $resultsSP = DB::select('select @out1 as nLeads, @out2 as nVentas, @out3 as conversion, @out4 as liquidacion
                        //                     , @out5 as nLeadshoy, @out6 as asesor, @out7 as ult_conexion'
                        //                 );
                        //     $salesComissions = $salesComissions + $resultsSP[0]->liquidacion;
                        // }
                        // return ($salesComissions);
                        return 0.00;
                    })
                    ->rawColumns(['commissions'])
                    ->make(true);
            }
            $pageTitle ='Hoja Contable - '.$companies->pluck('name')[0];
            // dd($call_reminder_charges);
            return view('admin.spreadsheet.index',compact('pageTitle'));
            // return view('contractcrud');
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
        // dd($request->material_list);
        $rules = [];
        $customMessages = [];
        $rules = [
            'case_first_name' => ['required','string'],
            'case_last_name' => ['required','string'],
            'case_dni' => ['required','string'],
            'case_address' => ['required'],
            'case_town' => ['required','string'],
            'case_provinces_list.0' => ['required'],
            'case_cp' => ['required','regex:/\b\d{5}\b/'],
            'case_email' => ['required','email'],
            'case_mobile' => ['required','regex:/[0-9+]{9,13}/'],
            'case_dt_birth' => ['required','date'],
            'case_countries_list' => ['required'],
            // 'case_gender' => ['required'],
            // 'case_marital_status' => ['required'],
            'case_service_category_list.*' => ['required'],
            'case_service_list.*' => ['required'],
            'procedural_position.*' => ['required'],
            'user_id_list.*' => ['required'],
            'material_list' => ['required'],
            'contract_observations' => ['required'],
            'validity' => ['required'],
            'companies_list.*' => ['required'],
            'contract_payment_type_id' => ['required'],
            'contract_method_type_id' => ['required']
        ];
        if(is_null($request->same_payer_person)){
            $rules['case_first_name_payer'] = ['required'];
            $rules['case_last_name_payer'] = ['required'];
            $rules['case_dni_payer'] = ['required'];
            $rules['case_address_payer'] = ['required'];
            $rules['case_town_payer'] = ['required'];
            $rules['case_cp_payer'] = ['required'];
            $rules['case_provinces_list_payer.0'] = ['required'];
            $rules['case_email_payer'] = ['required'];
            $rules['case_mobile_payer'] = ['required','regex:/[0-9+]{9,13}/'];
            $rules['case_dt_birth_payer'] = ['required'];
            $rules['case_countries_list_payer.0'] = ['required'];
        }
        switch ($request->contract_payment_type_id) {
            case "2":
                $rules['pp_fee_quantity'] = ['required','numeric'];
                $rules['pp_fee_value'] = ['required'];
                $rules['pp_dt_first_payment'] = ['required','date'];
                $rules['pp_dt_final_payment'] = ['required','date'];
                break;
            case "3":
                $rules['m_payment'] = ['required','regex:/^\d+([.,]\d{1,2})?$/'];
                break;
            case "1":
                $rules['s_payment'] = ['required','regex:/^\d+([.,]\d{1,2})?$/'];                
                break;
        }
        switch ($request->contract_method_type_id) {
            case "2":
                $rules['dd_holder_name'] = ['required','string'];
                $rules['dd_holder_dni'] = ['required','string'];
                $rules['dd_iban'] = ['required','string'];
                $rules['dd_bank_name'] = ['required','string'];
                break;
            case "4":
                $rules['cash_payment'] = ['required'];                
                break;
            case "5": //Financiera
                // $rules['paypal_receipt'] = ['required'];                
                break;
            case "1":
                $rules['card_holder_name'] = ['required','string'];                
                $rules['card_number'] = ['required','string'];                
                $rules['expiry_month'] = ['required'];                
                $rules['expiry_year'] = ['required'];                
                $rules['cvv'] = ['required','numeric'];                
                $rules['cc_type'] = ['required'];                
                break;
            case "3":
                $rules['t_payment_concept'] = ['required','string'];                
                $rules['t_iban'] = ['required','string'];                
                break;
        }
        $customMessages = [
            'case_first_name.required' => 'El nombre es obligatorio',
            'case_first_name.string' => 'El nombre debe contener solo texto',
            'case_last_name.required' => 'El apellido es obligatorio',
            'case_last_name.string' => 'El apellido debe contener solo texto',
            'case_dni.required' => 'El DNI/NIE/PASS es obligatorio',
            'case_dni.string' => 'El DNI/NIE/PASS debe cumplir el formato correcto',
            'case_address.required' => 'El domicilio es obligatorio',
            'case_town.required' => 'La ciudad es obligatoria',
            'case_town.string' => 'La ciudad debe contener solo texto',
            'case_provinces_list.0.required' => 'La provincia es obligatoria',
            'case_cp.required' => 'El codigo postal es obligatorio',
            'case_cp.regex' => 'El codigo postal debe tener el formato correcto',
            'case_email.required' => 'El email es obligatorio',
            'case_email.email' => 'El email debe tener el formato correcto',
            'case_mobile.required' => 'El móvil es obligatorio',
            'case_mobile.regex' => 'El formato del télefono es incorrecto, debe usar: +34,0034 ó 678912345',
            'case_dt_birth.required' => 'La fecha de nacimiento es obligatoria',
            'case_dt_birth.date' => 'La fecha debe tener el formato correcto',
            'case_countries_list.required' => 'El país es obligatorio',
            'case_gender.required' => 'El genero es obligatorio',
            'case_marital_status.required' => 'El estado civil es obligatorio',
            'case_service_category_list.0.required' => 'El tipo de asunto es obligatorio',
            'case_service_list.0.required' => 'El tipo de procedimiento es obligatorio',
            'procedural_position.0.required' => 'La posición procesal es obligatorio',
            'user_id_list.0.required' => 'El profesional asignado es obligatorio',
            'material_list.required' => 'El material es obligatorio',
            'contract_payment_type_id.required' => 'El método de pago es obligatorio',
            'contract_method_type_id.required' => 'La forma de pago es obligatorio',
            'contract_observations.required' => 'Las observaciones son obligatorias',
            'contract_observations.string' => 'Las observaciones deben tener solo texto',
            'validity.required' => 'La vigencia es obligatoria',
            'companies_list.0.required' => 'La Empresa es obligatoria',
            'pp_fee_quantity.required' => 'El número de cuotas es obligatorio',
            'pp_fee_quantity.numeric' => 'El número de cuotas debe ser solo numerico',
            'pp_fee_value.required' => 'El valor de la cuota es obligatorio',
            'pp_dt_first_payment.required' => 'La fecha de inicio de la cuota es obligatoria',
            'pp_dt_first_payment.date' => 'La fecha de inicio debe ser una fecha valida',
            'pp_dt_final_payment.required' => 'La fecha de fin de la cuota es obligatoria',
            'pp_dt_final_payment.date' => 'La fecha de fin debe ser una fecha valida',
            'm_payment.required' => 'El pago mensual es obligatorio',
            'm_payment.regex' => 'El pago mensual debe ser en formato moneda',
            's_payment.required' => 'El pago de contado es obligatorio',
            's_payment.regex' => 'El pago de contado debe ser en formato moneda',
            'dd_holder_name.required' => 'El nombre para la domiciliación es obligatorio',
            'dd_holder_name.string' => 'El nombre para la domiciliación debe ser solo texto',
            'dd_holder_dni.required' => 'El dni del nombre para la domiciliación es obligatorio',
            'dd_holder_dni.string' => 'El dni del nombre para la domiciliación debe ser del formato correcto',
            'dd_iban.required' => 'El dni del nombre para la domiciliación es obligatorio',
            'dd_iban.string' => 'El dni del nombre para la domiciliación debe ser solo texto',
            'dd_bank_name.required' => 'El banco del nombre dpara la domiciliación es obligatorio',
            'dd_bank_name.string' => 'El banco del nombre para la domiciliación debe ser solo texto',
            'paypal_receipt.required' => 'El recibo de PayPal es obligatorio',
            'cash_payment.required' => 'El pago en efectivo es obligatorio',
            'cash_payment.string' => 'El pago en efectivo debe ser en formato moneda',
            'card_holder_name.required' => 'El nombre de la tarjeta es obligatorio',
            'card_holder_name.string' => 'El nombre de la tarjeta debe ser solo texto',
            'cc_type.required' => 'El tipo de tarjeta es obligatorio',
            'card_number.required' => 'El numero de la tarjeta es obligatorio',
            'card_number.string' => 'El numero de la tarjeta debe ser solo texto',
            'expiry_month.required' => 'El mes de vencimiento de la tarjeta es obligatorio',
            'expiry_year.required' => 'El año de vencimiento de la tarjeta es obligatorio',
            'cvv.required' => 'El CCV de la tarjeta es obligatorio',
            't_payment_concept.required' => 'El concepto de transferencia es obligatorio',
            't_payment_concept.string' => 'El concepto de transferencia debe ser solo texto',
            't_iban.required' => 'El IBAN de la transferencia es obligatorio',
            't_iban.string' => 'El IBAN de la transferencia debe ser del formato correcto',
            'case_first_name_payer.required' => 'El nombre del pagador es obligatorio',
            'case_last_name_payer.required' => 'El apellido del pagador es obligatorio',
            'case_dni_payer.required' => 'El documento del pagador es obligatorio',
            'case_address_payer.required' => 'El domicilio del pagador es obligatorio',
            'case_town_payer.required' => 'La ciudad del pagador es obligatoria',
            'case_cp_payer.required' => 'El código postal del pagador es obligatorio',
            'case_provinces_list_payer.0.required' => 'La provincia del pagador es obligatoria',
            'case_email_payer.required' => 'El email del pagador es obligatorio',
            'case_mobile_payer.required' => 'El móvil del pagador es obligatorio',
            'case_mobile_payer.regex' => 'El formato del télefono es incorrecto, debe usar: +34,0034 ó 678912345',
            'case_dt_birth_payer.required' => 'El cumpleaños del pagador es obligatorio',
            'case_countries_list_payer.0.required' => 'El País del pagador es obligatorio',
        ];        
        
        $validator = Validator::make($request->all(),$rules,$customMessages);
        
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{
            //Verificamos el tipo de contrato
            $enrollment_number = Contract::all()->max('enrollment_number');
            // dd(DB::getQueryLog()); // Show results of log
            // dd($enrollment_number); // Show results of log
            
            // dd($request->all());
            $spreadsheet = $request->case_lead_id;
            if (is_null($enrollment_number)){
                $enrollment_number = 'EFP00001';
            }else {
                ++$enrollment_number;
            }
            $spreadsheet_id = null; //Se deja el contract_id null para que cuando sea nuevo sea null y para cuando sea editar sea el mismo
            if($request->contract_enrollment_number){
                $enrollment_number = $request->contract_enrollment_number;
                //Logica para verificar si ya existe un contrato matriculado para agregar el count +1, Ej: EFP00121_2
                $countEnrollment = Contract::where('enrollment_number','=',$enrollment_number)
                                            ->where('contract_status_id','=',3)
                                            ->get()->count();
                // dd($countEnrollment);
                $spreadsheet_id = $request->contract_id;
                if($countEnrollment >= 1){
                    $enrollment_number = $enrollment_number.'_'.$countEnrollment;
                    $spreadsheet_id = null;
                }
            }
            // dd($enrollment_number);
            // dd($enrollment_number); // Show results of log
            //Crear al alumno, verifica por dni, si es el mismo actualiza la info
            // dd($request);
            // dd($request->case_service_list[0]);
            // dd($request->all());
            $newPerson = PersonInf::updateOrCreate(
                ['dni' => $request->case_dni],
                ['name' => $request->case_first_name,'last_name' => $request->case_last_name,
                'gender' => $request->case_gender,'dt_birth' => $request->case_dt_birth,
                'country_id' =>  $request->case_countries_list[0],
                // 'marital_status' => $request->case_marital_status,
                'studies' => $request->case_studies,'profession' => $request->case_profession,
                'mail' => $request->case_email,'mobile' => $request->case_mobile,
                'phone' => $request->case_phone,
                'address' => $request->case_address,'town' => $request->case_town,
                'province_id' => $request->case_provinces_list[0],
                'postal_code' => $request->case_cp, 'person_type_id' => 1]
            );
            $idNewPerson = $newPerson->id;
            $idNewPayer = $idNewPerson;
            //Lógica para que si es el mismo pagador entonces guarde los mimos datos
            if(is_null($request->same_payer_person)){
                // dd($request);
                $newPayer = PersonInf::updateOrCreate(
                    ['dni' => $request->case_dni_payer],
                    ['name' => $request->case_first_name_payer,'last_name' => $request->case_last_name_payer,
                    'gender' => $request->case_gender_payer,'dt_birth' => $request->case_dt_birth_payer,
                    'country_id' =>  $request->case_countries_list_payer[0],
                    // 'marital_status' => $request->case_marital_status_payer,'has_work' => $request->has_work_t,
                    'mail' => $request->case_email_payer,'mobile' => $request->case_mobile_payer,
                    'address' => $request->case_address_payer,'town' => $request->case_town_payer,
                    'province_id' => $request->case_provinces_list_payer[0],
                    'postal_code' => $request->case_cp_payer, 'person_type_id' => 5]
                );
                $idNewPayer = $newPayer->id;
            }
            // dd("Crea Persona");
            // dd($request->expiry_year);
            $cc_expiry = Carbon::createFromDate($request->expiry_year, $request->expiry_month, 1)->format('Y-m-d');
            $company = Company::from('companies as co')
                            // ->where('agent_id','=',$user->id)
                            ->leftJoin('people_inf as p','p.company_id','=','co.id')
                            ->leftJoin('users as u', 'u.person_id', '=', 'p.id')
                            ->where('u.id','=',Auth::user()->id)
                            ->select('co.*')
                            ->get()->first()->id;
            // $company = Company::where('description','like','%Best%Con%Inno%')->get()->first()->id;
            // dd($company);
            $cc_expiry = Carbon::createFromDate($request->expiry_year, $request->expiry_month, 1)->format('Y-m-d');
            // dd($cc_expiry);
            $company = Company::from('companies as co')
                            // ->where('agent_id','=',$user->id)
                            ->leftJoin('people_inf as p','p.company_id','=','co.id')
                            ->leftJoin('users as u', 'u.person_id', '=', 'p.id')
                            ->where('u.id','=',Auth::user()->id)
                            ->select('co.*')
                            ->get()->first()->id;
            //Genera el número de cuenta a traves del company que viene del form
            $company_iban = Company::from('companies as co')
                            // ->where('agent_id','=',$user->id)
                            ->where('co.id','=',$request->companies_list[0])
                            ->select('co.*')
                            ->get()->first();
            // $company = Company::where('description','like','%Best%Con%Inno%')->get()->first()->id;
            // dd($company);
            // dd((int)$request->cash_payment[0]);
            // dd($request->postpone_enroll);

            //Para generar el tipo de contrato para los términos y condiciones
            // $courseType = Course::find($request->contract_courses_list[0])->pluck('id');
            // dd($request->contract_courses_list[0]);
            $contractType = ($request->contract_courses_list[0] == 60) ? 5: 1; // 5 Comic, 1 Matriculas, 2 Prácticas (id 60 = Comic)
            
            $spreadsheet = Contract::updateOrCreate(
                ['id' => $spreadsheet_id],
                // ['id' => $request->contract_id],
                ['enrollment_number'=> $enrollment_number,
                // 'user_classroom' => $user_classroom, //Se deja null porque se hace a posteriori
                // 'pass_classroom' => $pass_classroom, //Se deja null porque se hace a posteriori
                // 'teacher_id' => (int)$request->user_id_list[0], //Se deja null porque se hace a posteriori
                'dt_created' => $request->contract_dt_creation, 
                'lead_id' => decrypt($request->case_lead_id), 
                'person_id' => $idNewPerson,
                'payer_id' => $idNewPayer,
                'company_id' => $request->companies_list[0], 
                'course_id' => $request->contract_courses_list[0], 
                'material_id' => $request->material_list, 
                // 'contract_type_id' => 1,
                'contract_type_id' => $contractType,
                //Forma de pago, por ahora null revisar luego
                'contract_payment_type_id' => $request->contract_payment_type_id, 
                //Fin forma de pago
                'observations' => $request->contract_observations,
                'management_observations' => $request->management_observations,
                'discount_voucher' => $request->bonificado,
                //Graba logica de matricula
                'enroll' => $request->enroll, 'dt_postpone_enroll_start' => $request->postpone_enroll_start, 'enroll_postpone_start' => $request->enroll_postpone_start, 
                'dt_postpone_enroll_end' => $request->postpone_enroll_end, 'enroll_postpone_end' => $request->enroll_postpone_end,  
                'bank_cab' => $request->bank_cab,
                //Inicia la forma de pago
                's_payment' => $request->s_payment,
                'pp_initial_payment' => (float)$request->pp_initial_payment,
                'pp_fee_quantity' => (int)$request->pp_fee_quantity,
                'pp_fee_value' => (float)$request->pp_fee_value,
                'pp_dt_first_payment' => $request->pp_dt_first_payment, 
                'pp_dt_final_payment' => $request->pp_dt_final_payment,
                'm_payment' => (float)$request->m_payment,
                //Medio de pago, por ahora null revisar luego
                'contract_method_type_id' => $request->contract_method_type_id, 
                //Fin medio de pago
                //La matricula esta en cobro
                'payment_completion' => 0,
                'cc_number' => $request->card_number,
                'cc_secret_code' => $request->cvv, 'cc_expiry' => $cc_expiry,
                'cc_name' => $request->card_holder_name, 
                'dd_holder_dni' => $request->dd_holder_dni, 'dd_holder_name' => $request->dd_holder_name, 
                'dd_iban' => $request->dd_iban, 'dd_bank_name' => $request->dd_bank_name, 
                't_payment_concept' => $request->t_payment_concept,'t_iban' => $company_iban->bank_account,
                'agent_id' => (int)$request->user_id_list[0],
                //Fin Prácticas
                'client_ip' => $request->client_ip, 'comercial_ip' => request()->ip(), 
                'contract_status_id' => 1, //Estado nuevo
                'validity' => $request->validity, 
                'accept_communications' => (int)$request->accept_communications,
                'accept_thirdp_com' => (int)$request->accept_thirdp_com,
                //Dejo las firmas en blanco para poder volver a firmar y la fecha de aprobación
                'signature_path' => null,
                'signature_path_payer' => null,
                'dt_approved' => null,
                'dt_approved_payer' => null
                ]
            );
            // dd("crea el contrato");
            $idNewContract = $spreadsheet->id;
            /*Crea la cuota inicial
            //hace un case para ver que valor toma la primera cuota
            */
            $firstFeeValue = 0;
            // dd((int)$request->contract_payment_type_id);
            switch((int)$request->contract_payment_type_id){
                case 1:
                    $firstFeeValue= (float)$request->s_payment;
                    break;
                case 2:
                    $firstFeeValue = (float)$request->pp_fee_value;
                    break;
                case 3:
                    $firstFeeValue= (float)$request->m_payment;
                    break;
                case 4:
                    //4
                    break;
                default:
                    //default
            }
            //Borra todas las cuotas nuevamente por si actualiza

            ContractFee::whereContractId($idNewContract)->delete();
            /*
            Crea las cuotas a partir de la cantidad de cuotas ingresadas
            */
            $fee_quantity = (int)$request->pp_fee_quantity;
            switch((int)$request->contract_payment_type_id){
                case 1: //Contado
                    $total = (float)$request->s_payment;
                    if($spreadsheet->enroll == 1){ 
                        //Si tiene matricula se suman los valores de las cuotas en las que se va a pagar la matricula
                        $total = $total + ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end);
                    }
                    $dataFeetoCreate_aux = ['fee_number' => 0,
                                        'fee_value' => $total ,
                                        'dt_payment' => $request->contract_dt_creation,
                                        'status' => 'PP', 
                                        'contract_id' => (int)$idNewContract];
                    $modeluser = ContractFee::updateOrCreate(
                        ['id'=> null],
                        $dataFeetoCreate_aux
                    );
                    break;
                case 2: //Aplazado 
                    if($spreadsheet->enroll == 1){ //Si tiene matricula le suma el valor de las cuotas
                        //Si tiene matricula se suman el valor de las cuotas
                        $total = ((float)$spreadsheet->enroll_postpone_start + (float)$spreadsheet->enroll_postpone_end);
                        // dd($spreadsheet[0]->enroll_postpone_start);
                        $i = 1;
                        if($spreadsheet->enroll_postpone_end == null){
                            
                            $dataFeetoCreate_aux = ['fee_number' => 0,
                                        'fee_value' => $total ,
                                        'dt_payment' => $request->contract_dt_creation,
                                        'status' => 'PP', 
                                        'contract_id' => (int)$idNewContract];
                        }
                        else{
                            $i = 2;
                            $dataFeetoCreate_aux = [
                                            ['fee_number' => 0,
                                                'fee_value' => $spreadsheet->enroll_postpone_start,
                                                'dt_payment' => $request->contract_dt_creation,
                                                'status' => 'PP', 
                                                'contract_id' => (int)$idNewContract
                                            ],
                                            ['fee_number' => 1,
                                                'fee_value' => $spreadsheet->enroll_postpone_end,
                                                'dt_payment' => $request->contract_dt_creation,
                                                'status' => 'PP',
                                                'contract_id' => (int)$idNewContract
                                            ]
                                            ];
                        }     
                        $modeluser = ContractFee::insert($dataFeetoCreate_aux);
                    }
                    $date_payment_aux =Carbon::createFromFormat('Y-m-d', $request->pp_dt_first_payment);
                    for($i;$i<=$fee_quantity;$i++){
                        $dataFeetoCreate_aux = ['fee_number' => $i,'fee_value' => (float)$request->pp_fee_value,
                        'dt_payment' => $date_payment_aux ,'status' => 'PP', 
                        'contract_id' => (int)$idNewContract];
                        $modeluser = ContractFee::updateOrCreate(
                            ['id'=> null],
                            $dataFeetoCreate_aux
                        ); 
                        $date_payment_aux = $date_payment_aux->addMonths(1);
                    }
                    break;
                case 3:
                    //3
                    break;
                case 4:
                    //4
                    break;
                default:
                    //default
            }

            // dd("crea las cuotas");
            // dd($spreadsheet->lead_id);
            //Al final del todo Despúes de crear las cuotas me encargo de actualizar el lead con fecha de matriculación
            Lead::find($spreadsheet->lead_id)->update([
                    // 'dt_contract' => Carbon::now()->format('Y-m-d H:i:s'), 
                    'lead_status_id'=> 9,
                    'student_first_name' => $newPerson->name ,
                    'student_last_name' =>  $newPerson->last_name,
                    'student_mobile' =>  $newPerson->mobile,
                    'student_email' => $newPerson->mail ,
                    'student_dt_birth' => $newPerson->dt_birth ,
                    'province_id' => $newPerson->province_id ,
                    'country_id' =>  $newPerson->country_id,
                    'dt_enrollment' => Carbon::now()
                    ]);
            
            // dd("finalizo todo");
            //Actualiza última conexión
            Auth::user()->update_lastlogin();
            return response()->json(['success'=>'Contrato creado correctamente']);
        }
    }

    public function uploadSepa(Request $request)
    {
        // dd($request);
        if (!$request->file('sepa_path')){
            return response()->json(['error'=>'Debe escoger un archivo.']);
        }
        request()->validate([
            'sepa_path' => 'file|max:5000|mimes:jpg,jpeg,png,pdf,docx,doc,zip,eml',
        ]);
        if ($request->hasFile('sepa_path')) {
            $path = 'sepaproof/'.$request->sepa_service_number;
            if(!Storage::exists($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $file = $request->file('sepa_path');
            $filetext = $request->file('sepa_path')->getClientOriginalName();
            $filename = pathinfo($filetext, PATHINFO_FILENAME);
            $extension = pathinfo($filetext, PATHINFO_EXTENSION);
            $fileToSave =  (str_replace(' ', '', $request->sepa_case_id)).'_'.(str_replace(' ', '', $filename)).'.'. $extension;
            $file->storeAs($path,$fileToSave, ['disk' => 'public']);
            $dataToSave['sepa_path'] = $fileToSave;
        }

        $uploadSepa = Contract::updateOrCreate(
            ['id' => $request->sepa_case_id],
            $dataToSave  
        );
        if($uploadSepa){
            //Actualiza última conexión
            Auth::user()->update_lastlogin();
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Sepa subido correctamente</p>']);    
        }        
        else{
            //Actualiza última conexión
            Auth::user()->update_lastlogin();
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Error en la actualización</p>']);    
        }
        
    }
    public function uploadID(Request $request)
    {
        // dd($request);
        if (!$request->file('id_path')){
            return response()->json(['error'=>'Debe escoger un archivo.']);
        }
        request()->validate([
            'id_path' => 'file|max:5000|mimes:jpg,jpeg,png,pdf,docx,doc,zip,eml',
        ]);
        if ($request->hasFile('id_path')) {
            $path = 'sepaproof/'.$request->id_service_number;
            if(!Storage::exists($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $file = $request->file('id_path');
            $filetext = $request->file('id_path')->getClientOriginalName();
            $filename = pathinfo($filetext, PATHINFO_FILENAME);
            $extension = pathinfo($filetext, PATHINFO_EXTENSION);
            $fileToSave =  (str_replace(' ', '', $request->id_case_id)).'_'.(str_replace(' ', '', $filename)).'.'. $extension;
            $file->storeAs($path,$fileToSave, ['disk' => 'public']);
            $dataToSave['id_path'] = $fileToSave;
        }

        $uploadID = Contract::updateOrCreate(
            ['id' => $request->id_case_id],
            $dataToSave  
        );
        if($uploadID){
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Sepa subido correctamente</p>']);    
        }        
        else{
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Error en la actualización</p>']);    
        }
        
    }

    public function updateUserRoom(Request $request)
    { 
        
        $dataToSave['user_classroom'] = $request->user_classroom;
        
        $uploadSepa = Contract::updateOrCreate(
            ['id' => $request->user_class_case_id],
            $dataToSave  
        );
        //Actualiza última conexión
        Auth::user()->update_lastlogin();
        return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>actualización realizada con éxito</p>']);      
    }

    public function updatePassRoom(Request $request)
    { 
        
        $dataToSave['pass_classroom'] = $request->pass_classroom;
        
        $uploadSepa = Contract::updateOrCreate(
            ['id' => $request->pass_class_case_id],
            $dataToSave 
        );
        //Actualiza última conexión
        Auth::user()->update_lastlogin();
        return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>actualización realizada con éxito</p>']);      
    }

    public function updateTeacher(Request $request)
    { 
        
        $dataToSave['teacher_id'] = $request->teacher_id;
        
        $uploadSepa = Contract::updateOrCreate(
            ['id' => $request->teacher_id_case_id],
            $dataToSave  
        );
        //Actualiza última conexión
        Auth::user()->update_lastlogin();
        return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>actualización realizada con éxito</p>']);      
    }

    public function updateManagementObs(Request $request)
    {
        
        $dataToSave['management_observations'] = $request->edit_management_observations;
        
        $uploadSepa = Contract::updateOrCreate(
            ['id' => $request->management_obs_case_id],
            $dataToSave  
        );
        //Actualiza última conexión
        Auth::user()->update_lastlogin();
        return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>actualización realizada con exito</p>']);    
        
    }
    
    //Dar de baja contrato Real
    public function dropContract($spreadsheet_id)
    {
        // dd($drop_case_id);
        $drop_contract_id= decrypt($spreadsheet_id);
        $dataToSave['contract_status_id']= 6;
        $dataToSave['dt_drop']= Carbon::now()->format('Y-m-d');
        
        $dropContract = Contract::updateOrCreate(
            ['id' => $drop_contract_id],
            $dataToSave  
        );
        
        $wasChanged = $dropContract->wasChanged();
        if($wasChanged){
            //Actualiza última conexión
            Auth::user()->update_lastlogin();
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Contrato anulado con exito</p>']);    
        }
        else{
            //Actualiza última conexión
            Auth::user()->update_lastlogin();
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-error"></i>Anulación no realizada</p>']);    
        }   
    }
    
    //Reactivar Lead
    public function reActivateLead($spreadsheet_id)
    {
        // dd($drop_case_id);
        $drop_contract_id= decrypt($spreadsheet_id);
        $leadIdActivate = Contract::find($drop_contract_id)->lead_id;
        $prevAgentActivate = Lead::find($leadIdActivate)->agent_id;

        /**
        1) Elimina las notas de gestión
         */
        $noteManagementActivate = ManagementNote::whereContractId($drop_contract_id)->delete();
        
        /**
        2) Elimina las cuotas generadas
         */
        $contractFeeActivate = ContractFee::whereContractId($drop_contract_id)->delete();
        
        /**
        3) Elimina el contrato por completo
         */
        $contractActivate = Contract::find($drop_contract_id)->delete();
        
        /**
        4) Actualiza el Lead a Nuevo y le asigna el comercial "Sin"
         */
         $dt_assign_massive = Carbon::now()->toDateTimeString();
         $dataToUpdate  = [
            'lead_status_id'        => 1, //Nuevo
            'lead_sub_status_id'    => null, //se vacia el subestado
            'dt_assignment'         => $dt_assign_massive ,
            'agent_id'              => 7, //sin asignar
            'dt_last_update'        => null, //se vacia la útima modificación porque para el comercial será "un nuevo estado"
            'dt_call_reminder'      => null, //se vacia la fecha de recordación llamadaporque para el comercial será "un nuevo estado"
            'prev_agent_id'         => $prevAgentActivate  
        ];
        $leadActivate =  Lead::updateOrCreate(
            ['id' => (int)$leadIdActivate],
            $dataToUpdate
        );

        Auth::user()->update_lastlogin();
        return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Lead Reactivado Exitosamente</p>']);   
    }

    //Dar de baja contrato
    public function cancelContract(Request $request)
    {
        // dd($drop_case_id);
        $cancel_case_id= decrypt($request->cancel_case_id);
        $dataToSave['case_status_id']= 6;
        
        $cancelContract = Contract::updateOrCreate(
            ['id' => $cancel_case_id],
            $dataToSave  
        );
        
        $wasChanged = $cancelContract->wasChanged();
        if($wasChanged){
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>anulado  con exito</p>']);    
        }
        else{
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-error"></i>anulación no realizada</p>']);    
        }   
    }
    
    public function updatePaymentCompletion(Request $request)
    {
        // dd($request);
        $dataToSave['payment_completion'] = $request->con_payment_completion_status;
        
        $uploadSepa = Contract::updateOrCreate(
            ['id' => $request->payment_comp_case_id],
            $dataToSave  
        );
        return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>actualización realizada con exito</p>']);    
        
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
    public function edit($id_crypt)
    {
         $id = decrypt($id_crypt);
         $edit = Contract::from('contracts as c')
                    ->where('c.id','=',$id)
                    ->leftJoin('companies as com','c.company_id','=','com.id')
                    ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                    ->leftJoin('people_inf as py','c.payer_id','=','py.id')
                    ->leftJoin('provinces as prv','pi.province_id','=','prv.id')
                    ->leftJoin('countries as cnt','pi.country_id','=','cnt.id')
                    ->leftJoin('provinces as prv_py','py.province_id','=','prv_py.id')
                    ->leftJoin('countries as cnt_py','py.country_id','=','cnt_py.id')
                    ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
                    ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
                    ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                    ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
                    ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
                    ->leftJoin('materials as ma','c.material_id','=','ma.id')
                    ->leftJoin('leads as le','c.lead_id','=','le.id')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->select('c.*','crse.name as course_name','crse.short_code as short_code','crse.pvp as crse_pvp'
                            ,'ma.name as material_name'
                            ,'pi.name as name','pi.last_name as last_name'
                            ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail','pi.phone as phone'
                            ,'pi.studies as studies','pi.profession as profession'
                            ,'pi.contract_type_id as person_contract_type'
                            ,'pi.address as address','pi.town as town', 'pi.province_id as client_province_id'
                            ,'prv.name as client_province','pi.postal_code as postal_code'
                            ,'pi.dt_birth as dt_birth', 'pi.country_id as client_country_id','cnt.name as client_country'
                            ,'pi.gender as gender'
                            ,'py.name as name_t','py.last_name as last_name_t'
                            ,'py.dni as dni_t','py.mobile as mobile_t', 'py.mail as mail_t','py.phone as phone_t'
                            ,'py.address as address_t','py.town as town_t', 'py.province_id as client_province_id_t'
                            ,'prv_py.name as client_province_t','py.postal_code as postal_code_t'
                            ,'py.dt_birth as dt_birth_t', 'py.country_id as client_country_id_t','cnt_py.name as student_country_t'
                            ,'py.gender as gender_t'
                            ,'com.id as company_id','com.name as company_name'
                            ,'us.name as agent_name'
                            ,'cpm.name as payment_method', 'cpt.name as payment_type'
                            , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                            , DB::raw('coalesce(c.s_payment,0)
                                      + coalesce(c.pp_initial_payment,0) 
                                      + (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0)) AS total'))
                    ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
                    ->get();

        $total_charged = Contract::from("contract_fees as cf")
                    ->where('cf.contract_id','=',$id)
                    ->where('cf.status','=','P')
                    ->sum('cf.fee_paid');
        $total_pending = Contract::from("contract_fees as cf")
                    ->where('cf.contract_id','=',$id)
                    ->whereIn('cf.status',['E','PP'])
                    ->sum('cf.fee_value');
                    
        $lead_id_encrypted = encrypt($edit->first()->lead_id);
        
        
        // $total_charged_pending = $total_pending->sum()
        // dd($total_pending);
        $edit['total_pending'] = $total_pending;
        $edit['total_charged'] = $total_charged;
        $edit['lead_id_encrypted'] = $lead_id_encrypted;
        // dd($edit);
        //Actualiza última conexión
        Auth::user()->update_lastlogin();
        return response()->json($edit);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id_crypt)
    {
         $id = decrypt($id_crypt);
         $edit = Contract::from('contracts as c')
                    ->where('c.id','=',$id)
                    ->leftJoin('companies as com','c.company_id','=','com.id')
                    ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                    ->leftJoin('person_studies as ps','pi.study_id','=','ps.id')
                    ->leftJoin('provinces as prv','pi.province_id','=','prv.id')
                    ->leftJoin('countries as cnt','pi.country_id','=','cnt.id')
                    ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
                    ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
                    // ->leftJoin('case_modalities as cm', 'c.case_modality_id','=','cm.id')
                    ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                    ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
                    ->leftJoin('materials as ma', 'c.material_id','=','ma.id')
                    ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
                    ->leftJoin('leads as le','c.lead_id','=','le.id')
                    ->leftJoin('users as us','c.agent_id','=','us.id')
                    ->select('c.*','crse.name as course_name'
                            // ,'crse_aux.name as aux_course_name'
                            ,'pi.name as name','pi.last_name as last_name','ps.name as person_studies'
                            ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail','pi.studies as studies'
                            ,'com.id as company_id','com.name as company_name'
                            ,'pi.address as address','pi.town as town', 'pi.province_id as student_province_id'
                            ,'prv.name as student_province','pi.postal_code as postal_code'
                            ,'pi.dt_birth as dt_birth', 'pi.country_id as student_country_id','cnt.name as student_country'
                            ,'pi.gender as gender'
                            ,'cs.name as case_status','us.name as agent_name','cs.color_class as bg_color'
                            ,'ma.name as material_name','ct.name as case_type'
                            ,'cpm.name as payment_method', 'cpt.name as payment_type'
                            , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                            , DB::raw('coalesce(c.s_payment,0) + coalesce(c.pp_initial_payment,0) + coalesce(c.m_payment,0)
                                      + (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0)) AS total'))
                    ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
                    ->get();
        $total_charged = Contract::from("contract_fees as cf")
                    ->where('cf.contract_id','=',$id)
                    ->where('cf.status','=','P')
                    ->sum('cf.fee_paid');
        $total_pending = Contract::from("contract_fees as cf")
                    ->where('cf.contract_id','=',$id)
                    ->whereIn('cf.status',['E','PP'])
                    ->sum('cf.fee_value');
        
        // $total_charged_pending = $total_pending->sum()
        // dd($total_pending);
        $edit['total_pending'] = $total_pending;
        $edit['total_charged'] = $total_charged;
        $edit[0]['lead_note_id_crypt'] = encrypt($edit->first()->lead_id);
        $edit[0]['id_crypt'] = encrypt($edit->first()->id);
        $urlpath = 'storage/contractdocuments/'.$edit->first()->enrollment_number.'/';
        $fileList = [];
        $dir = public_path($urlpath);
        if (is_dir($dir)){
            if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if($file != '' && $file != '.' && $file != '..'){
                $file_path = $urlpath.$file;
                if(!is_dir($file_path)){
                    $size = filesize($file_path);
                    $fileList[] = ['name'=>$file, 'size'=>$size, 'path'=>$file_path];
                }
                }
            }
            closedir($dh);
            }
        }
        $edit['documents'] = $fileList;
        //Actualiza última conexión
        Auth::user()->update_lastlogin();
        return response()->json($edit);
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

    public function generateBlankSepa()
    {
        $data = ['title' => 'Orden SEPA'];
        $title= 'Orden Sepa';
        //Prepare and render our PDF header and footer views
        // $page_header_html = view()->make('pdf.invoices.partials.page-header', $data)->render();
        // $page_footer_html = view()->make('pdf.blanksepafooter')->render();
        // dd($page_footer_html);

        // $pdf = PDF::loadFile('http://www.github.com')->inline('github.pdf');
        $pdf = PDF::loadView('pdf.blanksepa',$data);
        $pdf->setPaper('a4');
        // dd($pdf);
        return $pdf->inline('blanksepa.pdf');
        // return view('pdf.blanksepa',compact('title'));
    }

    public function generateSepa($con_id_crypeted)
    {
        $case_id = decrypt($con_id_crypeted);
        // return "HOLA SEPA";
        // dd($case_id);
        $spreadsheet = Contract::from('contracts as c')
        ->where('c.id', '=',(int)$case_id)
        ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
        ->leftJoin('people_inf as py','c.payer_id','=','py.id')
        ->leftJoin('provinces as prv','pi.province_id','=','prv.id')
        ->leftJoin('countries as cnt','pi.country_id','=','cnt.id')
        ->leftJoin('provinces as prv_py','py.province_id','=','prv_py.id')
        ->leftJoin('countries as cnt_py','py.country_id','=','cnt_py.id')
        ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
        ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
        ->leftJoin('case_modalities as cm', 'c.case_modality_id','=','cm.id')
        ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
        ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
        ->leftJoin('materials as ma', 'c.material_id','=','ma.id')
        ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
        
        ->leftJoin('leads as le','c.lead_id','=','le.id')
        ->leftJoin('users as us','c.agent_id','=','us.id')
        ->select('c.*','crse.name as course_name','crse_aux.name as aux_course_name'
                ,'pi.name as name','pi.last_name as last_name'
                ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                ,'pi.address as address','pi.town as town', 'pi.province_id as student_province_id'
                ,'prv.name as student_province','pi.postal_code as postal_code'
                ,'pi.dt_birth as dt_birth', 'pi.country_id as student_country_id','cnt.name as student_country'
                
                ,'py.name as name_t','py.last_name as last_name_t'
                ,'py.dni as dni_t','py.mobile as mobile_t', 'py.mail as mail_t'
                ,'py.address as address_t','py.town as town_t', 'py.province_id as student_province_id_t'
                ,'prv_py.name as student_province_t','py.postal_code as postal_code_t'
                ,'py.dt_birth as dt_birth_t', 'py.country_id as student_country_id_t','cnt_py.name as student_country_t'
                ,'py.gender as gender_t','py.marital_status as marital_status_t', 'py.has_work as has_work_t'
                

                ,'cs.name as case_status','us.name as agent_name'
                ,'ma.name as material','cm.name as modality','ct.name as case_type'
                ,'cpm.name as payment_method', 'cpt.name as payment_type'
                , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                , DB::raw('coalesce(c.s_payment,0) +coalesce(c.pp_enroll_payment,0)
                          + coalesce(c.pp_initial_payment,0) + coalesce(c.m_payment,0)
                          + (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0)) AS total'))
        ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
        ->get();
        $spreadsheet ->toArray();
        
        if((int)$spreadsheet[0]->pp_fee_quantity > 1){
            $spreadsheet[0]['recurrente'] = 'checked';
            $spreadsheet[0]['unico'] = '';
        }else{
            $spreadsheet[0]['recurrente'] = '';
            $spreadsheet[0]['unico'] = 'checked ';
        }
        // dd($spreadsheet[0]);
        $spreadsheet[0]['title'] = 'ORDEN SEPA';

        $pdf = PDF::loadView('pdf.sepa',$spreadsheet[0]);
        $pdf->setPaper('a4');
        // dd($pdf);
        return $pdf->inline('sepa.pdf');
    }

    public function generateContract(Request $request)
    {
       
        $case_id = decrypt($request->get('crypt_id'));
        $typeRoleRequest = decrypt($request->get('type'));
        // dd($case_id);
        // return "HOLA SEPA";
        // dd($case_id);
        
        $spreadsheet = Contract::from('contracts as c')
        ->where('c.id', '=',(int)$case_id)
        ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
        ->leftJoin('companies as com','c.company_id','=','com.id')
        ->leftJoin('banks as bnk','com.bank_id','=','bnk.id')
        ->leftJoin('provinces as compro','com.province_id','=','compro.id')
        ->leftJoin('people_inf as py','c.payer_id','=','py.id')
        ->leftJoin('provinces as prv','pi.province_id','=','prv.id')
        ->leftJoin('countries as cnt','pi.country_id','=','cnt.id')
        ->leftJoin('provinces as prv_py','py.province_id','=','prv_py.id')
        ->leftJoin('countries as cnt_py','py.country_id','=','cnt_py.id')
        ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
        ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
        ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
        ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
        ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
        ->leftJoin('leads as le','c.lead_id','=','le.id')
        ->leftJoin('users as us','c.agent_id','=','us.id')
        ->leftJoin('people_inf as pro','us.person_id','=','pro.id')
        ->select('c.*','crse.name as course_name'
                ,'c.signature_path as signature_path_student'
                ,'pi.name as name','pi.last_name as last_name'
                ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                ,'pi.address as address','pi.town as town', 'pi.province_id as student_province_id'
                ,'pi.studies as studies','pi.profession as profession'
                ,'prv.name as student_province','pi.postal_code as postal_code'
                ,'pi.dt_birth as dt_birth', 'pi.country_id as student_country_id','cnt.name as student_country'
                ,'pi.gender as gender', 'cs.name as case_status','us.name as agent_name','ct.name as case_type'

                ,'py.name as name_t','py.last_name as last_name_t'
                ,'py.dni as dni_t','py.mobile as mobile_t', 'py.mail as mail_t','py.phone as phone_t'
                ,'py.address as address_t','py.town as town_t', 'py.province_id as client_province_id_t'
                ,'prv_py.name as client_province_t','py.postal_code as postal_code_t'
                ,'py.dt_birth as dt_birth_t', 'py.country_id as client_country_id_t','cnt_py.name as student_country_t'
                ,'py.gender as gender_t'

                ,'pro.name as profesional_name','pro.last_name as profesional_last_name'
                ,'cpm.name as payment_method', 'cpt.name as payment_type','com.name as company_name'
                ,'com.address as company_address' , 'com.name as legal_name', 'com.cif as legal_cif' 
                ,'bnk.name as bank_name','com.bank_account as bank_account','com.switf as swift'
                ,'com.logo_path as company_path','com.signature_path as signature_path'
                ,'com.town as company_town', 'compro.name as company_province'
                ,'com.postal_code as company_pc','com.mail as company_mail','com.phone as company_phone'
                , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                , DB::raw('(SELECT SUM(cf.fee_value) FROM contract_fees cf WHERE cf.contract_id = c.id)   AS total'))
        ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
        ->get();
        $spreadsheet ->toArray();
        if((int)$spreadsheet[0]->pp_fee_quantity > 1){
            $spreadsheet[0]['recurrente'] = 'checked';
            $spreadsheet[0]['unico'] = '';
        }else{
            $spreadsheet[0]['recurrente'] = '';
            $spreadsheet[0]['unico'] = 'checked ';
        }
        //Términos y Condiciones, Datos del contrato dinamico, en orden de aparición.
        $logo_empresa = asset('storage/uploads/contract_logos/'.$spreadsheet[0]->company_path); 
        $firma_empresa = asset('storage/uploads/contract_logos/'.$spreadsheet[0]->signature_path); 
        // dd($firma_empresa);
        $num_matricula = $spreadsheet[0]->enrollment_number;
        $fecha_matricula = '<b>FECHA: </b>&nbsp;&nbsp;&nbsp;&nbsp;'.Carbon::createFromDate($spreadsheet[0]->dt_created)->format('d/m/Y'). '&nbsp;&nbsp;&nbsp;&nbsp;<b class="line-break">Nº MATRÍCULA: </b>'.$spreadsheet[0]->enrollment_number ;
        $vigencia_contrato = '<span style="background-color: #d8ecf8" class="text-sm p-1 bordered"><b>VIGENCIA DEL CONTRATO: </b> '.(int)$spreadsheet[0]->validity / 12 . ' AÑO(s)</span>' ;
        $nombre_alumno  = $spreadsheet[0]->name;
        $dni_alumno =  $spreadsheet[0]->dni;
        $apellido_alumno =  $spreadsheet[0]->last_name;
        $naci_alumno =  Carbon::createFromDate($spreadsheet[0]->dt_birth)->format('d/m/Y');
        $dom_alumno  =  $spreadsheet[0]->address;
        $cp_alumno =  $spreadsheet[0]->postal_code;
        $pob_alumno =  $spreadsheet[0]->town;
        $tel_alumno =  $spreadsheet[0]->phone;
        $prov_alumno =  $spreadsheet[0]->student_province;
        $movil_alumno =  $spreadsheet[0]->mobile;
        $estudi_alumno =  $spreadsheet[0]->studies;
        $email_alumno =  $spreadsheet[0]->mail;
        $prof_alumno =  $spreadsheet[0]->profession;
        $curso_alumno =  '<b class="float-left">'.$spreadsheet[0]->course_name.'</b>'; 
        $nombre_compra =  $spreadsheet[0]->name_t;
        $dni_compra=  $spreadsheet[0]->dni_t;
        $apellido_compra=  $spreadsheet[0]->last_name_t;
        $naci_compra=  Carbon::createFromDate($spreadsheet[0]->dt_birth_t)->format('d/m/Y');
        $dom_compra=  $spreadsheet[0]->address_t;
        $cp_compra=  $spreadsheet[0]->postal_code_t;
        $pob_compra=  $spreadsheet[0]->town_t;
        $tel_compra=  $spreadsheet[0]->phone_t;
        $prov_compra=  $spreadsheet[0]->client_province_t;
        $movil_compra=  $spreadsheet[0]->mobile_t;
        $email_compra=  $spreadsheet[0]->mail_t;
        if($spreadsheet[0]->contract_payment_type_id == 1){ //Pago al contado
            $cuotas_mensuales = '- €';
            $enroll_text = (!is_null($spreadsheet[0]->dt_postpone_enroll_start)) ? 0: ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end);
            $importe_inicial = ($spreadsheet[0]->enroll == '1') ? $enroll_text + (float)$spreadsheet[0]->s_payment .' €' : (float)$spreadsheet[0]->s_payment .' €';
            $importe_cuotas = '';
        }
        else{
            $cuotas_mensuales = '<span><b>'.(int)$spreadsheet[0]->pp_fee_quantity .'</b> de </span>';
            $enroll_text = (!is_null($spreadsheet[0]->dt_postpone_enroll_start)) ? 0: ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end);
            $importe_inicial = ($spreadsheet[0]->enroll == '1') ? (float)$spreadsheet[0]->pp_initial_payment + $enroll_text + $spreadsheet[0]->pp_fee_value.' €' : (float)$spreadsheet[0]->pp_initial_payment + $spreadsheet[0]->pp_fee_value.' €';
            $importe_cuotas = $spreadsheet[0]->pp_fee_value.' €';
        } 
        $enroll_text_import = (!is_null($spreadsheet[0]->dt_postpone_enroll_start)) ? 'APLAZADA': ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end).' €';
        $importe_matricula =  ( $spreadsheet[0]->enroll == '1') ? $enroll_text_import  : 'BONIFICADA';
        $aplaza_matricula = '';
        if((!is_null($spreadsheet[0]->dt_postpone_enroll_start)) && ( $spreadsheet[0]->enroll == '1')){
            if($spreadsheet[0]->enroll_postpone_end == 0){
                $aplaza_matricula = 'La matrícula será pagada el día: '.Carbon::createFromDate($spreadsheet[0]->dt_postpone_enroll_start)->format('d/m/Y');
                $aplaza_matricula_valor = ((!is_null($spreadsheet[0]->dt_postpone_enroll_start)) && ( $spreadsheet[0]->enroll == '1') ) ? ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end).' €': '  ';
            }
            else{
                $aplaza_matricula = '1ra Cuota Matrícula: '.Carbon::createFromDate($spreadsheet[0]->dt_postpone_enroll_start)->format('d/m/Y').'<br>
                2da Cuota Matrícula: '.Carbon::createFromDate($spreadsheet[0]->dt_postpone_enroll_end)->format('d/m/Y');
                $aplaza_matricula_valor = ((!is_null($spreadsheet[0]->dt_postpone_enroll_start)) && ( $spreadsheet[0]->enroll == '1') ) ? ((float)$spreadsheet[0]->enroll_postpone_start).' € <br>'.(float)$spreadsheet[0]->enroll_postpone_end.' €': '  ';
        
            }
        }
        elseif ((is_null($spreadsheet[0]->dt_postpone_enroll_start)) && ( $spreadsheet[0]->enroll == '2')) {
            $aplaza_matricula = '';
            $aplaza_matricula_valor = ' ';
        }
        else{
            $aplaza_matricula = 'La matrícula se pagó el día de la firma del contrato';
            $aplaza_matricula_valor = ' ';
        }
        $importe_total = $spreadsheet[0]->total + (float)$spreadsheet[0]->pp_initial_payment .' €';
        $domiciliacion_nombres = $spreadsheet[0]->dd_holder_name;
        $domi_banco = $spreadsheet[0]->dd_bank_name;
        $observations_contract = $spreadsheet[0]->observations;
        $domi_direccion = $spreadsheet[0]->dd_holder_address;
        $domi_iban = $spreadsheet[0]->dd_iban;
        // $domi_cp = $spreadsheet[0]->dd_holder_address;
        // $domi_prov = $spreadsheet[0]->dd_holder_name;
        // $domi_poblacion = $spreadsheet[0]->dd_holder_name;
        $firma_empresa;
        $disabledStudent = '';
        $disabledPayer = '';
        $divFirma = '';
        $divFirmaPayer = '';
        // dd($typeRoleRequest);
        switch ($typeRoleRequest) {
            case 'admin':        
                break;
            case 'student':
                $disabledPayer = 'd-none';
                break;
            case 'payer':
                $disabledStudent = 'd-none';
                break;
            
            default:
                # code...
                break;
        }
        //<input type="text" name="studentSignature" id="studentSignature" class="form-control form-control-sm" placeholder="o firma con tu DNI">
        //<input type="text" name="payerSignature" id="payerSignature" class="form-control form-control-sm" placeholder="o firma con tu DNI">
        //FIRMA ESTUDIANTE
        if($spreadsheet[0]['dt_approved'] <> NULL){
            $divFirma = '<img width="200px" src="'.asset('signatures/'.$spreadsheet[0]['enrollment_number'].'/'. $spreadsheet[0]['signature_path_student']).'"/>'.
                        '<br><span><b>Fecha y Hora Firma: </b>'.$spreadsheet[0]['dt_approved'].'</span>';
            $disabledStudent = 'd-none';
        }
        //FIRMA PAGADOR
        if($spreadsheet[0]['dt_approved_payer'] <> NULL){
            $divFirmaPayer = '<img width="200px" src="'.asset('signatures/'.$spreadsheet[0]['enrollment_number'].'/'. $spreadsheet[0]['signature_path_payer']).'"/>'.
            '<br><span><b>Fecha y Hora Firma: </b>'.$spreadsheet[0]['dt_approved_payer'].'</span>';
            $disabledPayer = 'd-none';
        }

        $firma_alumno = $divFirma .'<div id="signature-pad" class="signature-pad '.$disabledStudent.'">
                            <div class="m-signature-pad--body">
                                <canvas style="border: 2px dashed #ccc"></canvas>
                            </div>
                            
                            <div class="m-signature-pad--footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-action="clear">Limpiar</button>
                                <button type="button" class="btn btn-sm btn-primary" data-action="save">Firmar</button>
                            </div>
                        </div>';
        $firma_pagador = $divFirmaPayer .'<div id="signature-pad-payer" class="signature-pad '.$disabledPayer.'">
                            <div class="m-signature-pad--body">
                                <canvas style="border: 2px dashed #ccc"></canvas>
                            </div>
                            
                            <div class="m-signature-pad--footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-action="clear">Limpiar</button>
                                <button type="button" class="btn btn-sm btn-primary" data-action="save">Firmar</button>
                            </div>
                        </div>';
        $fecha_firma;
        
        Carbon::setLocale('es');
        $fecha = Carbon::parse($spreadsheet[0]->dt_contract);
        $date = $fecha->locale(); //con esto revise que el lenguaje fuera es 
        $fecha_contrato =$fecha->day.' de '. $fecha->monthName.' de '  .$fecha->year; 
        //Fin términos y condiciones
        switch ($spreadsheet[0]->contract_payment_type_id) {
            case 1: //Un solo Pago
                $forma_de_pago = 'El pago se realizará en un solo pago de '.$spreadsheet[0]->s_payment.' euros '. 
                             'y deberá de satisfacerse antes de la celebración de la vista principal.';
                break;
            case 2: //Aplazado
                $forma_de_pago = 'El pago se realizará en '.$spreadsheet[0]->pp_fee_quantity.' PLAZO(S), el primer pago será de '.
                            $spreadsheet[0]->pp_initial_payment.' euros antes de iniciar el procedimiento, siendo los restantes pagos de '.
                            $spreadsheet[0]->pp_fee_value.' euros cada uno y deberán de satisfacerse antes de la celebración de la vista principal.';
                break;
            default:
                $forma_de_pago = 'El pago se realizará en '.$spreadsheet[0]->pp_fee_quantity.' PLAZO(S), el primer pago será del '.
                            $spreadsheet[0]->pp_initial_payment.' antes de iniciar el procedimiento, siendo los restantes pagos de '.
                            $spreadsheet[0]->pp_fee_value.' euros cada uno y deberán de satisfacerse antes de la celebración de la vista principal.';
                break;
        }       
        
        $fecha = $spreadsheet[0]->dt_created;
        $result = array();
        //Inician las condiciones
        // $terms = Term::whereCompanyId($spreadsheet[0]->company_id)->orderBy('created_at', 'desc')->get()->first()->terms;
        $terms = Term::from('terms as t')
                    ->whereRaw('? >= CAST(t.created_at as DATE)',$fecha)
                    ->whereCompanyId($spreadsheet[0]->company_id)
                    ->whereContractTypeId($spreadsheet[0]->contract_type_id)
                    ->select('t.*')
                    ->orderBy('t.created_at', 'desc')->get()->first()->terms;
        $busca = explode("|",$terms);
        $conditions = "";
        foreach($busca as $b){
            switch($b){
                case "num_matricula":
                    $conditions.='<span class="float-right text-bold h2">Nº '.$num_matricula.'</span>';
                    break;
                case "fecha_matricula":
                    $conditions.=$fecha_matricula;
                    break;
                case "vigencia_contrato":
                    $conditions.=$vigencia_contrato;
                    break;
                case "nombre_alumno":
                    $conditions.=$nombre_alumno;
                    break;
                case "dni_alumno":
                    $conditions.=$dni_alumno;
                    break;
                case "apellido_alumno":
                    $conditions.=$apellido_alumno;
                    break;
                case "naci_alumno":
                    $conditions.=$naci_alumno;
                    break;
                case "dom_alumno":
                    $conditions.=$dom_alumno;
                    break;
                case "cp_alumno":
                    $conditions.=$cp_alumno;
                    break;
                case "pob_alumno":
                    $conditions.=$pob_alumno;
                    break;
                case "tel_alumno":
                    $conditions.=$tel_alumno;
                    break;
                case "prov_alumno":
                    $conditions.=$prov_alumno;
                    break;
                case "movil_alumno":
                    $conditions.=$movil_alumno;
                    break;
                case "estudi_alumno":
                    $conditions.=$estudi_alumno;
                    break;
                case "email_alumno":
                    $conditions.=$email_alumno;
                    break;
                case "prof_alumno":
                    $conditions.=$prof_alumno;
                    break;
                case "curso_alumno":
                    $conditions.=$curso_alumno;
                    break;
                case "nombre_compra":
                    $conditions.=$nombre_compra;
                    break;
                case "dni_compra":
                    $conditions.=$dni_compra;
                    break;
                case "apellido_compra":
                    $conditions.=$apellido_compra;
                    break;
                case "naci_compra":
                    $conditions.=$naci_compra;
                    break;
                case "dom_compra":
                    $conditions.=$dom_compra;
                    break;
                case "cp_compra":
                    $conditions.=$cp_compra;
                    break;
                case "pob_compra":
                    $conditions.=$pob_compra;
                    break;
                case "tel_compra":
                    $conditions.=$tel_compra;
                    break;
                case "prov_compra":
                    $conditions.=$prov_compra;
                    break;
                case "movil_compra":
                    $conditions.=$movil_compra;
                    break;
                case "tel_alumno":
                    $conditions.=$tel_alumno;
                    break;
                case "email_compra":
                    $conditions.=$email_compra;
                    break;
                case "cuotas_mensuales":
                    $conditions.=$cuotas_mensuales;
                    break;
                case "importe_inicial":
                    $conditions.=$importe_inicial;
                    break;
                case "importe_matricula":
                    $conditions.=$importe_matricula;
                    break;
                case "bonificada_matricula":
                    $conditions.=$bonificada_matricula;
                    break;
                case "importe_cuotas":
                    $conditions.=$importe_cuotas;
                    break;
                case "importe_total":
                    $conditions.=$importe_total;
                    break;
                case "domiciliacion_nombres":
                    $conditions.=$domiciliacion_nombres;
                    break;
                case "domi_banco":
                    $conditions.=$domi_banco;
                    break;
                case "domi_direccion":
                    $conditions.=$domi_direccion;
                    break;
                case "domi_iban":
                    $conditions.=$domi_iban;
                    break;
                case "firma_alumno":
                    $conditions.=$firma_alumno;
                    break;
                case "firma_pagador":
                    $conditions.=$firma_pagador;
                    break;
                case "aplazada_matricula":
                    $conditions.=$aplaza_matricula;
                    break;
                case "aplaza_matricula_valor":
                    $conditions.=$aplaza_matricula_valor;
                    break;
                case "observaciones_contrato":
                    $conditions.=$observations_contract;
                    break;
                default:
                    $conditions.=$b;
            }
        }
        //Si el contrato esta aceptado entonces enseña el div
        $acceptedArea = '';
        $urlContract= route('contract.downloadPDF',encrypt($spreadsheet[0]["id"]));
        if($spreadsheet[0]['dt_approved'] <> NULL && $spreadsheet[0]['dt_approved_payer'] <> NULL){
            $dt_accepted = ($spreadsheet[0]['dt_approved_payer'] >= $spreadsheet[0]['dt_approved']) ? $spreadsheet[0]['dt_approved_payer'] : $spreadsheet[0]['dt_approved'];
            if($spreadsheet[0]['person_id'] == $spreadsheet[0]['payer_id']){
                $ip_accepted  = 'IP Pagador:'.$spreadsheet[0]['client_ip'];
            }else{
                $ip_accepted  = 'IP Cliente: '.$spreadsheet[0]['client_ip'].' | '. 'IP Pagador: '.$spreadsheet[0]['payer_ip'];
            }
            $acceptedArea =  '<div class="row " style="margin: auto;margin-top:-40px!important">'.
                                '<div class="col-sm-12" style="background-color:#d8ecf8;color:black;">'.
                                    '<p style="text-align:center; font-size:1.2em;"><b>ESTE CONTRATO HA SIDO ACEPTADO CORRECTAMENTE</b></p>'.
                                    '<p style="text-align:center; font-size:1.2em;"><b>Fecha y Hora de aceptación: '.$dt_accepted.'</b></p>'.
                                    '<p style="text-align:center; font-size:1.2em;"><b>'.$ip_accepted.'</b></p>'.
                                    '<div align="center"><a href="'.$urlContract.'" target="_blank" class="btn mb-2 bg-white" style="color:#3c8dbc!important;"><i style="font-size:2rem!important" class="fad fa-file-pdf "> </i> Descargar una copia del contrato</a></div>'.
                                '</div>'.
                            '</div>';
        }
        $spreadsheet[0]['acceptedArea'] = $acceptedArea;
        $spreadsheet[0]['title'] = 'Contrato GRUPO EFP';
        $spreadsheet[0]['conditions'] = $conditions;
        $spreadsheet[0]['logo_empresa'] = $logo_empresa;
        $spreadsheet[0]['firma_empresa'] = $firma_empresa;
        $spreadsheet[0]['typeRoleRequest'] = $typeRoleRequest;
        $contractF = $spreadsheet[0];
        // dd($contractF->conditions );
        return view('contracts.viewcontract',compact('contractF'));
    }

    public function generateContractPDF($con_id_crypeted)
    {
        $case_id = decrypt($con_id_crypeted);
        // dd($case_id);
        // return "HOLA SEPA";
        // dd($case_id);
        
        $spreadsheet = Contract::from('contracts as c')
        ->where('c.id', '=',(int)$case_id)
        ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
        ->leftJoin('companies as com','c.company_id','=','com.id')
        ->leftJoin('banks as bnk','com.bank_id','=','bnk.id')
        ->leftJoin('provinces as compro','com.province_id','=','compro.id')
        ->leftJoin('people_inf as py','c.payer_id','=','py.id')
        ->leftJoin('provinces as prv','pi.province_id','=','prv.id')
        ->leftJoin('countries as cnt','pi.country_id','=','cnt.id')
        ->leftJoin('provinces as prv_py','py.province_id','=','prv_py.id')
        ->leftJoin('countries as cnt_py','py.country_id','=','cnt_py.id')
        ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
        ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
        ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
        ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
        ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
        ->leftJoin('leads as le','c.lead_id','=','le.id')
        ->leftJoin('users as us','c.agent_id','=','us.id')
        ->leftJoin('people_inf as pro','us.person_id','=','pro.id')
        ->select('c.*','crse.name as course_name'
                ,'c.signature_path as signature_path_student'
                ,'pi.name as name','pi.last_name as last_name'
                ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                ,'pi.address as address','pi.town as town', 'pi.province_id as student_province_id'
                ,'pi.studies as studies','pi.profession as profession'
                ,'prv.name as student_province','pi.postal_code as postal_code'
                ,'pi.dt_birth as dt_birth', 'pi.country_id as student_country_id','cnt.name as student_country'
                ,'pi.gender as gender', 'cs.name as case_status','us.name as agent_name','ct.name as case_type'

                ,'py.name as name_t','py.last_name as last_name_t'
                ,'py.dni as dni_t','py.mobile as mobile_t', 'py.mail as mail_t','py.phone as phone_t'
                ,'py.address as address_t','py.town as town_t', 'py.province_id as client_province_id_t'
                ,'prv_py.name as client_province_t','py.postal_code as postal_code_t'
                ,'py.dt_birth as dt_birth_t', 'py.country_id as client_country_id_t','cnt_py.name as student_country_t'
                ,'py.gender as gender_t'

                ,'pro.name as profesional_name','pro.last_name as profesional_last_name'
                ,'cpm.name as payment_method', 'cpt.name as payment_type','com.name as company_name'
                ,'com.address as company_address' , 'com.name as legal_name', 'com.cif as legal_cif' 
                ,'bnk.name as bank_name','com.bank_account as bank_account','com.switf as swift'
                ,'com.logo_path as company_path','com.signature_path as signature_path'
                ,'com.town as company_town', 'compro.name as company_province'
                ,'com.postal_code as company_pc','com.mail as company_mail','com.phone as company_phone'
                , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                , DB::raw('(SELECT SUM(cf.fee_value) FROM contract_fees cf WHERE cf.contract_id = c.id)  AS total'))
        ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
        ->get();
        $spreadsheet ->toArray();
        if((int)$spreadsheet[0]->pp_fee_quantity > 1){
            $spreadsheet[0]['recurrente'] = 'checked';
            $spreadsheet[0]['unico'] = '';
        }else{
            $spreadsheet[0]['recurrente'] = '';
            $spreadsheet[0]['unico'] = 'checked ';
        }
        //Términos y Condiciones, Datos del contrato dinamico, en orden de aparición.
        $logo_empresa = asset('storage/uploads/contract_logos/'.$spreadsheet[0]->company_path); 
        $firma_empresa = asset('storage/uploads/contract_logos/'.$spreadsheet[0]->signature_path); 
        // dd($firma_empresa);
        $num_matricula = $spreadsheet[0]->enrollment_number;
        $fecha_matricula = '<b>FECHA: </b>&nbsp;&nbsp;&nbsp;&nbsp;'.Carbon::createFromDate($spreadsheet[0]->dt_created)->format('d/m/Y'). '&nbsp;&nbsp;&nbsp;&nbsp;<b class="line-break">Nº MATRÍCULA: </b>'.$spreadsheet[0]->enrollment_number ;
        $vigencia_contrato = '<span style="background-color: #d8ecf8" class="text-sm p-1 bordered"><b>VIGENCIA DEL CONTRATO: </b> '.(int)$spreadsheet[0]->validity / 12 . ' AÑO(s)</span>' ;
        $nombre_alumno  = $spreadsheet[0]->name;
        $dni_alumno =  $spreadsheet[0]->dni;
        $apellido_alumno =  $spreadsheet[0]->last_name;
        $naci_alumno =  Carbon::createFromDate($spreadsheet[0]->dt_birth)->format('d/m/Y');
        $dom_alumno  =  $spreadsheet[0]->address;
        $cp_alumno =  $spreadsheet[0]->postal_code;
        $pob_alumno =  $spreadsheet[0]->town;
        $tel_alumno =  $spreadsheet[0]->phone;
        $prov_alumno =  $spreadsheet[0]->student_province;
        $movil_alumno =  $spreadsheet[0]->mobile;
        $estudi_alumno =  $spreadsheet[0]->studies;
        $email_alumno =  $spreadsheet[0]->mail;
        $prof_alumno =  $spreadsheet[0]->profession;
        $curso_alumno =  '<b class="float-left">'.$spreadsheet[0]->course_name.'</b>'; 
        $nombre_compra =  $spreadsheet[0]->name_t;
        $dni_compra=  $spreadsheet[0]->dni_t;
        $apellido_compra=  $spreadsheet[0]->last_name_t;
        $naci_compra=  Carbon::createFromDate($spreadsheet[0]->dt_birth_t)->format('d/m/Y');
        $dom_compra=  $spreadsheet[0]->address_t;
        $cp_compra=  $spreadsheet[0]->postal_code_t;
        $pob_compra=  $spreadsheet[0]->town_t;
        $tel_compra=  $spreadsheet[0]->phone_t;
        $prov_compra=  $spreadsheet[0]->client_province_t;
        $movil_compra=  $spreadsheet[0]->mobile_t;
        $email_compra=  $spreadsheet[0]->mail_t;
        if($spreadsheet[0]->contract_payment_type_id == 1){ //Pago al contado
            $cuotas_mensuales = '- €';
            $enroll_text = (!is_null($spreadsheet[0]->dt_postpone_enroll_start)) ? 0: ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end);
            $importe_inicial = ($spreadsheet[0]->enroll == '1') ? $enroll_text + (float)$spreadsheet[0]->s_payment .' €' : (float)$spreadsheet[0]->s_payment .' €';
            $importe_cuotas = '';
        }
        else{
            $cuotas_mensuales = '<span><b>'.(int)$spreadsheet[0]->pp_fee_quantity .'</b> de </span>';
            $enroll_text = (!is_null($spreadsheet[0]->dt_postpone_enroll_start)) ? 0: ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end);
            $importe_inicial = ($spreadsheet[0]->enroll == '1') ? (float)$spreadsheet[0]->pp_initial_payment + $enroll_text + $spreadsheet[0]->pp_fee_value.' €' : (float)$spreadsheet[0]->pp_initial_payment + $spreadsheet[0]->pp_fee_value.' €';
            $importe_cuotas = $spreadsheet[0]->pp_fee_value.' €';
        } 

        $enroll_text_import = (!is_null($spreadsheet[0]->dt_postpone_enroll_start)) ? 'APLAZADA': ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end).' €';
        $importe_matricula =  ( $spreadsheet[0]->enroll == '1') ? $enroll_text_import  : 'BONIFICADA';
        $aplaza_matricula = '';
        if((!is_null($spreadsheet[0]->dt_postpone_enroll_start)) && ( $spreadsheet[0]->enroll == '1')){
            if($spreadsheet[0]->enroll_postpone_end == 0){
                $aplaza_matricula = 'La matrícula será pagada el día: '.Carbon::createFromDate($spreadsheet[0]->dt_postpone_enroll_start)->format('d/m/Y');
                $aplaza_matricula_valor = ((!is_null($spreadsheet[0]->dt_postpone_enroll_start)) && ( $spreadsheet[0]->enroll == '1') ) ? ((float)$spreadsheet[0]->enroll_postpone_start + (float)$spreadsheet[0]->enroll_postpone_end).' €': '  ';
            }
            else{
                $aplaza_matricula = '1ra Cuota Matrícula: '.Carbon::createFromDate($spreadsheet[0]->dt_postpone_enroll_start)->format('d/m/Y').'<br>
                2da Cuota Matrícula: '.Carbon::createFromDate($spreadsheet[0]->dt_postpone_enroll_end)->format('d/m/Y');
                $aplaza_matricula_valor = ((!is_null($spreadsheet[0]->dt_postpone_enroll_start)) && ( $spreadsheet[0]->enroll == '1') ) ? ((float)$spreadsheet[0]->enroll_postpone_start).' € <br>'.(float)$spreadsheet[0]->enroll_postpone_end.' €': '  ';
            }
            
        }
        elseif ((is_null($spreadsheet[0]->dt_postpone_enroll_start)) && ( $spreadsheet[0]->enroll == '2')) {
            $aplaza_matricula = '';
            $aplaza_matricula_valor = ' ';
        }
        else{
            $aplaza_matricula = 'La matrícula se pagó el día de la firma del contrato';
            $aplaza_matricula_valor = ' ';
        }
        $importe_total = $spreadsheet[0]->total + (float)$spreadsheet[0]->pp_initial_payment .' €';
        $domiciliacion_nombres = $spreadsheet[0]->dd_holder_name;
        $domi_banco = $spreadsheet[0]->dd_bank_name;
        $observations_contract = $spreadsheet[0]->observations;
        $domi_direccion = $spreadsheet[0]->dd_holder_address; 
        $domi_iban = $spreadsheet[0]->dd_iban;
        // $domi_cp = $spreadsheet[0]->dd_holder_address;
        // $domi_prov = $spreadsheet[0]->dd_holder_name;
        // $domi_poblacion = $spreadsheet[0]->dd_holder_name;  
        $firma_empresa;
        $disabledStudent = '';
        $disabledPayer = '';
        $divFirma = '';
        $divFirmaPayer = '';
        //<input type="text" name="studentSignature" id="studentSignature" class="form-control form-control-sm" placeholder="o firma con tu DNI">
        //<input type="text" name="payerSignature" id="payerSignature" class="form-control form-control-sm" placeholder="o firma con tu DNI">
        //FIRMA ESTUDIANTE
        if($spreadsheet[0]['dt_approved'] <> NULL){
            $divFirma = '<img src="https://efpsales.com/signatures/'.$spreadsheet[0]['enrollment_number'].'/'.$spreadsheet[0]['signature_path_student'].'"style="width:100%; max-width:300px;">'.
                        '<br><span><b>Fecha y Hora Firma: </b>'.$spreadsheet[0]['dt_approved'].'</span>';
            $disabledStudent = 'd-none';
        }
        //FIRMA PAGADOR
        if($spreadsheet[0]['dt_approved_payer'] <> NULL){
            $divFirmaPayer = '<img src="https://efpsales.com/signatures/'.$spreadsheet[0]['enrollment_number'].'/'.$spreadsheet[0]['signature_path_payer'].'" style="width:100%; max-width:300px;">'.
            '<br><span><b>Fecha y Hora Firma: </b>'.$spreadsheet[0]['dt_approved_payer'].'</span>';
            $disabledPayer = 'd-none';
        }
        $firma_alumno = $divFirma ;
        $firma_pagador = $divFirmaPayer ;
        $fecha_firma;
        
        Carbon::setLocale('es');
        $fecha = Carbon::parse($spreadsheet[0]->dt_contract);
        $date = $fecha->locale(); //con esto revise que el lenguaje fuera es 
        $fecha_contrato =$fecha->day.' de '. $fecha->monthName.' de '  .$fecha->year; 
        //Fin términos y condiciones
        switch ($spreadsheet[0]->contract_payment_type_id) {
            case 1: //Un solo Pago
                $forma_de_pago = 'El pago se realizará en un solo pago de '.$spreadsheet[0]->s_payment.' euros '. 
                             'y deberá de satisfacerse antes de la celebración de la vista principal.';
                break;
            case 2: //Aplazado
                $forma_de_pago = 'El pago se realizará en '.$spreadsheet[0]->pp_fee_quantity.' PLAZO(S), el primer pago será de '.
                            $spreadsheet[0]->pp_initial_payment.' euros antes de iniciar el procedimiento, siendo los restantes pagos de '.
                            $spreadsheet[0]->pp_fee_value.' euros cada uno y deberán de satisfacerse antes de la celebración de la vista principal.';
                break;
            default:
                $forma_de_pago = 'El pago se realizará en '.$spreadsheet[0]->pp_fee_quantity.' PLAZO(S), el primer pago será del '.
                            $spreadsheet[0]->pp_initial_payment.' antes de iniciar el procedimiento, siendo los restantes pagos de '.
                            $spreadsheet[0]->pp_fee_value.' euros cada uno y deberán de satisfacerse antes de la celebración de la vista principal.';
                break;
        }       
        
        $fecha = $spreadsheet[0]->dt_created;
        $result = array();
        //Inician las condiciones
        // $terms = Term::whereCompanyId($spreadsheet[0]->company_id)->get()->first()->terms;
        $terms = Term::from('terms as t')
                    ->whereRaw('? >= CAST(t.created_at as DATE)',$fecha)
                    ->whereCompanyId($spreadsheet[0]->company_id)
                    ->whereContractTypeId($spreadsheet[0]->contract_type_id)
                    ->select('t.*')
                    ->orderBy('t.created_at', 'desc')->get()->first()->terms;
        $busca = explode("|",$terms);
        $conditions = "";
        foreach($busca as $b){
            switch($b){
                case "num_matricula":
                    $conditions.='<span class="float-right text-bold h2">Nº '.$num_matricula.'</span>';
                    break;
                case "fecha_matricula":
                    $conditions.=$fecha_matricula;
                    break;
                case "vigencia_contrato":
                    $conditions.=$vigencia_contrato;
                    break;
                case "nombre_alumno":
                    $conditions.=$nombre_alumno;
                    break;
                case "dni_alumno":
                    $conditions.=$dni_alumno;
                    break;
                case "apellido_alumno":
                    $conditions.=$apellido_alumno;
                    break;
                case "naci_alumno":
                    $conditions.=$naci_alumno;
                    break;
                case "dom_alumno":
                    $conditions.=$dom_alumno;
                    break;
                case "cp_alumno":
                    $conditions.=$cp_alumno;
                    break;
                case "pob_alumno":
                    $conditions.=$pob_alumno;
                    break;
                case "tel_alumno":
                    $conditions.=$tel_alumno;
                    break;
                case "prov_alumno":
                    $conditions.=$prov_alumno;
                    break;
                case "movil_alumno":
                    $conditions.=$movil_alumno;
                    break;
                case "estudi_alumno":
                    $conditions.=$estudi_alumno;
                    break;
                case "email_alumno":
                    $conditions.=$email_alumno;
                    break;
                case "prof_alumno":
                    $conditions.=$prof_alumno;
                    break;
                case "curso_alumno":
                    $conditions.=$curso_alumno;
                    break;
                case "nombre_compra":
                    $conditions.=$nombre_compra;
                    break;
                case "dni_compra":
                    $conditions.=$dni_compra;
                    break;
                case "apellido_compra":
                    $conditions.=$apellido_compra;
                    break;
                case "naci_compra":
                    $conditions.=$naci_compra;
                    break;
                case "dom_compra":
                    $conditions.=$dom_compra;
                    break;
                case "cp_compra":
                    $conditions.=$cp_compra;
                    break;
                case "pob_compra":
                    $conditions.=$pob_compra;
                    break;
                case "tel_compra":
                    $conditions.=$tel_compra;
                    break;
                case "prov_compra":
                    $conditions.=$prov_compra;
                    break;
                case "movil_compra":
                    $conditions.=$movil_compra;
                    break;
                case "tel_alumno":
                    $conditions.=$tel_alumno;
                    break;
                case "email_compra":
                    $conditions.=$email_compra;
                    break;
                case "cuotas_mensuales":
                    $conditions.=$cuotas_mensuales;
                    break;
                case "importe_inicial":
                    $conditions.=$importe_inicial;
                    break;
                case "importe_matricula":
                    $conditions.=$importe_matricula;
                    break;
                case "bonificada_matricula":
                    $conditions.=$bonificada_matricula;
                    break;
                case "importe_cuotas":
                    $conditions.=$importe_cuotas;
                    break;
                case "importe_total":
                    $conditions.=$importe_total;
                    break;
                case "domiciliacion_nombres":
                    $conditions.=$domiciliacion_nombres;
                    break;
                case "domi_banco":
                    $conditions.=$domi_banco;
                    break;
                case "domi_direccion":
                    $conditions.=$domi_direccion;
                    break;
                case "domi_iban":
                    $conditions.=$domi_iban;
                    break;
                case "firma_alumno":
                    $conditions.=$firma_alumno;
                    break;
                case "firma_pagador":
                    $conditions.=$firma_pagador;
                    break;
                case "aplazada_matricula":
                    $conditions.=$aplaza_matricula;
                    break;
                case "aplaza_matricula_valor":
                    $conditions.=$aplaza_matricula_valor;
                    break;
                case "observaciones_contrato":
                    $conditions.=$observations_contract;
                    break;
                default:
                    $conditions.=$b;
            }
        }
        //Si el contrato esta aceptado entonces enseña el div
        $acceptedArea = '';
        $urlContract= route('contract.downloadPDF',encrypt($spreadsheet[0]["id"]));
        if($spreadsheet[0]['dt_approved'] <> NULL && $spreadsheet[0]['dt_approved_payer'] <> NULL){
            $dt_accepted = ($spreadsheet[0]['dt_approved_payer'] >= $spreadsheet[0]['dt_approved']) ? $spreadsheet[0]['dt_approved_payer'] : $spreadsheet[0]['dt_approved'];
            if($spreadsheet[0]['person_id'] == $spreadsheet[0]['payer_id']){
                $ip_accepted  = 'IP Pagador:'.$spreadsheet[0]['client_ip'];
            }else{
                $ip_accepted  = 'IP Cliente: '.$spreadsheet[0]['client_ip'].' | '. 'IP Pagador: '.$spreadsheet[0]['payer_ip'];
            }
            $acceptedArea =  '<div class="row " style="margin: auto;margin-top:-40px!important">'.
                                '<div class="col-sm-12" style="background-color:#d8ecf8;color:black;">'.
                                '<p style="text-align:center; font-size:1.2em;"><b>ESTE CONTRATO HA SIDO ACEPTADO CORRECTAMENTE</b></p>'.
                                '<p style="text-align:center; font-size:1.2em;"><b>Fecha y Hora de aceptación: '.$dt_accepted.'</b></p>'.
                                '<p style="text-align:center; font-size:1.2em;"><b>'.$ip_accepted.'</b></p>'.
                                '</div>'.
                            '</div>';
        }
        $spreadsheet[0]['acceptedArea'] = $acceptedArea;
        $spreadsheet[0]['title'] = 'Contrato BV Abogados';
        $spreadsheet[0]['conditions'] = $conditions;
        $spreadsheet[0]['logo_empresa'] = $logo_empresa;
        $spreadsheet[0]['firma_empresa'] = $firma_empresa;
        $contractF = $spreadsheet[0];
        // dd($contractF->conditions );
        $pdf = PDF::loadView('contracts.viewcontractpdf',$contractF);
        $pdf->setOption('margin-left',5);
        $pdf->setOption('margin-right',5);
        $pdf->setOption('margin-bottom',0);
        $pdf->setPaper('LEGAL');
        // dd($pdf);
        return $pdf->inline('contrato.pdf');
        
    }

    public function acceptContract(Request $request)
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
        $path = 'signatures/'.$request->enrollment_number.'/';
        if(!Storage::exists($path)){
            File::makeDirectory($path, 0777, true, true);
        }
        // $decoded_image->storeAs($path,$decoded_image, ['disk' => 'public']);
        // dd($request->enrollment_number);
        file_put_contents($path.$filename, $decoded_image);
        // Text of the alter to confirm that the data is posted
        // return response()->json(['success'=>'Data is successfully added']);
        // dd($request->contract_id);

        /*
        * Lógica para determinar si acepta parcial o total dependiendo de los roles
        * de Pagador y Estudiante
        * Aceptado Parcial: (contracts:11 , leads: 12)
        * Aceptado          (contracts:2  , leads: 11)
        */
        $contractF = Contract::find((int)$request->contract_id);
        $mailData = Contract::from('contracts as c')
                            ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
                            ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                            ->leftJoin('people_inf as py','c.payer_id','=','py.id')
                            ->leftJoin('leads as le','c.lead_id','=','le.id')
                            ->leftJoin('users as us','c.agent_id','=','us.id')
                            ->leftJoin('people_inf as pro','us.person_id','=','pro.id')
                            ->where('c.id','=',$contractF->id)
                            ->select('c.*','crse.name as course_name','le.student_email as client_email'
                                    
                                    ,'pi.name as client_name','pi.last_name as client_last_name'
                                    ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'

                                    ,'py.name as payer_name','py.last_name as payer_last_name'
                                    ,'py.dni as payer_dni','py.mobile as payer_mobile', 'py.mail as payer_mail'
                                    
                                    ,'pi.address as address','pi.town as town', 'pi.province_id as client_province_id'
                                    ,'pi.postal_code as postal_code'
                                    ,'pi.dt_birth as dt_birth', 'pi.country_id as client_country_id'
                                    ,'us.email as agent_mail'
                                    ,'pro.name as agent_name','pro.last_name as agent_lastname')
                            ->get()->toArray();
        $dataToUpdate=[];
        if ($contractF->person_id == $contractF->payer_id) {//El estudiante es quien realiza el Pago
            Lead::find($contractF->lead_id)->update(['lead_status_id'=> 4]);
            $dataToUpdate  = ['contract_status_id' => 3,
                            'dt_approved' => Carbon::now()->format('Y-m-d H:i:s'),
                            'dt_approved_payer' => Carbon::now()->format('Y-m-d H:i:s'),
                            'signature_path' => $filename,
                            'signature_path_payer' => $filename,
                            'payer_ip' => request()->ip(), 
                            'client_ip' => request()->ip(), 
                            'accept_communications' => (int)$request->information,
                            'accept_thirdp_com' => (int)$request->informationtp
            ]; 
            $mailData[0]['estado_contrato'] = 'Aceptado';
            $contractF->update($dataToUpdate);
            //Envia el correo a la administracion, genera correo y envía notificación
            $mailData[0]['url_contract'] = route('case.generate', ['crypt_id'=>encrypt((int)$request->contract_id), 'type'=>encrypt('admin')]);
            $mailData[0]['dt_accept'] = Carbon::now()->format('Y-m-d H:i:s');
            $mailData[0]['aceptado_por'] = $mailData[0]['client_name'] . ' '. $mailData[0]['client_last_name'];
            // $mailData[0]['ip_aceptado'] = $mailData[0]['payer_ip'];
            $mailData[0]['ip_aceptado'] = $contractF->payer_ip;
            Mail::to($mailData[0]['client_email'])
                ->bcc([$mailData[0]['agent_mail'],'info@grupoefp.com','systemskcd@gmail.com'])
                ->send(new acceptContract($mailData[0]));
        }
        else{//El estudiante es diferente del Pagador
            /*
            * Ahora, hay que validar si el contrato ya ha sido aceptado por la otra persona
            * En caso contrario, debe colocar como aceptado parcial 
            */
            switch ($typeRoleRequest) {
                case 'student':
                    /*
                    * Verifica que el pagador haya aceptado o no
                    */
                    $dataToUpdate  = ['dt_approved' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'client_ip' => request()->ip(), 
                                    'signature_path' => $filename,
                                    'accept_communications' => (int)$request->information,
                                    'accept_thirdp_com' => (int)$request->informationtp
                                    ];
                    if($contractF->dt_approved_payer == NULL){//No ha sido aprobado por el pagador
                        $dataToUpdate['contract_status_id'] = 11;
                        $mailData[0]['estado_contrato'] = 'Aceptado Parcial';
                        Lead::find($contractF->lead_id)->update(['lead_status_id'=> 12]);
                    }else{//ambos aprobaron
                        $dataToUpdate['contract_status_id'] = 3;
                        $mailData[0]['estado_contrato'] = 'Aceptado';
                        Lead::find($contractF->lead_id)->update(['lead_status_id'=> 4]);
                    }
                    $contractF->update($dataToUpdate);
                    //Envia el correo a la administracion, genera correo y envía notificación
                    $mailData[0]['url_contract'] = route('case.generate', ['crypt_id'=>encrypt((int)$request->contract_id), 'type'=>encrypt('admin')]);
                    $mailData[0]['dt_accept'] = Carbon::now()->format('Y-m-d H:i:s');
                    $mailData[0]['aceptado_por'] = $mailData[0]['client_name'] . ' '. $mailData[0]['client_last_name'];
                    $mailData[0]['ip_aceptado'] = $contractF->client_ip;
                    Mail::to($mailData[0]['client_email'])
                        ->bcc([$mailData[0]['agent_mail'],'info@grupoefp.com','systemskcd@gmail.com'])
                        ->send(new acceptContract($mailData[0]));
                    break;
                case 'payer':
                    $dataToUpdate  = ['dt_approved_payer' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'payer_ip' => request()->ip(),
                                    'signature_path_payer' => $filename,
                                    'accept_communications' => (int)$request->information,
                                    'accept_thirdp_com' => (int)$request->informationtp
                                    ];
                    if($contractF->dt_approved == NULL){//No ha sido aprobado por el estudiante
                        $dataToUpdate['contract_status_id'] = 11;
                        $mailData[0]['estado_contrato'] = 'Aceptado Parcial';
                        Lead::find($contractF->lead_id)->update(['lead_status_id'=> 12]);
                    }else{//ambos aprobaron
                        $dataToUpdate['contract_status_id'] = 3;
                        $mailData[0]['estado_contrato'] = 'Aceptado';
                        Lead::find($contractF->lead_id)->update(['lead_status_id'=> 4]);
                    }
                    $contractF->update($dataToUpdate);
                    //Envia el correo a la administracion, genera correo y envía notificación
                    $mailData[0]['url_contract'] = route('case.generate', ['crypt_id'=>encrypt((int)$request->contract_id), 'type'=>encrypt('admin')]);
                    $mailData[0]['dt_accept'] = Carbon::now()->format('Y-m-d H:i:s');
                    $mailData[0]['aceptado_por'] = $mailData[0]['payer_name'] . ' '. $mailData[0]['payer_last_name'];
                    $mailData[0]['ip_aceptado'] = $contractF->payer_ip;
                    Mail::to($mailData[0]['client_email'])
                    ->bcc([$mailData[0]['agent_mail'],'info@grupoefp.com','systemskcd@gmail.com'])
                    ->send(new acceptContract($mailData[0]));
                    break;
            }
        }
        return response()->json(['success'=>'Contrato Aceptado Exitosamente.']);
    }

    public function openingContract(Request $request)
    {
        // dd($request);
        //Guarda en la bd el contrato y lo actualiza
        $contractF = Contract::find((int)$request->contract_id);
        $mailData = Contract::from('contracts as c')
                ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
                ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                ->leftJoin('people_inf as py','c.payer_id','=','py.id')
                ->leftJoin('leads as le','c.lead_id','=','le.id')
                ->leftJoin('users as us','c.agent_id','=','us.id')
                ->leftJoin('people_inf as pro','us.person_id','=','pro.id')
                ->where('c.id','=',$contractF->id)
                ->select('c.*','crse.name as course_name','le.student_email as client_email'
                        
                        ,'pi.name as client_name','pi.last_name as client_last_name'
                        ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'

                         ,'py.name as payer_name','py.last_name as payer_last_name'
                        ,'py.dni as payer_dni','py.mobile as payer_mobile', 'py.mail as payer_mail'
                        
                        ,'pi.address as address','pi.town as town', 'pi.province_id as client_province_id'
                        ,'pi.postal_code as postal_code'
                        ,'pi.dt_birth as dt_birth', 'pi.country_id as client_country_id'
                        ,'us.email as agent_mail'
                        ,'pro.name as agent_name','pro.last_name as agent_lastname')
                ->get()->toArray();
        // dd($mailData[0]['payer_id'] <> $mailData[0]['person_id']);
        $dataToUpdate = [];
        $mailData[0]['url_contract'] = route('case.generate', ['crypt_id'=>encrypt((int)$request->contract_id), 'type'=>encrypt('admin')]);
        switch ($request->roletype) {
            case 'student':
                if($contractF->dt_opening == NULL){ //Solo cuando lo abre por primera vez
                    $mailData[0]['dt_apertura'] = Carbon::now()->format('Y-m-d H:i:s');
                    $mailData[0]['abierto_por'] = $mailData[0]['client_name'] . ' '. $mailData[0]['client_last_name'];
                    $dataToUpdate  = ['dt_opening' => Carbon::now()->format('Y-m-d H:i:s')];
                    //Envia el correo a la administracion
                    Mail::to('info@grupoefp.com')
                    ->bcc(['systemskcd@gmail.com'])
                    ->send(new openContract($mailData[0]));
                }
                break;
            case 'payer':
                if($contractF->dt_opening_payer == NULL){ //Solo cuando lo abre por primera vez
                    $mailData[0]['dt_apertura'] = Carbon::now()->format('Y-m-d H:i:s');
                    $mailData[0]['abierto_por'] = $mailData[0]['payer_name'] . ' '. $mailData[0]['payer_last_name'];
                    $dataToUpdate  = ['dt_opening_payer' => Carbon::now()->format('Y-m-d H:i:s')];
                    //Envia el correo a la administracion
                    Mail::to('info@grupoefp.com')
                    ->bcc(['systemskcd@gmail.com'])
                    ->send(new openContract($mailData[0]));
                }
                break;
            
            default:
                # code...
                break;
        }
        $contractF->update($dataToUpdate);
        
    }

    public function viewPayments($con_id_crypeted)
    {
        $case_id = decrypt($con_id_crypeted);
        $user;
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return view('auth.login');
        }
        $matchThese = ['case_id' => $case_id];
        $case_payments = ContractFee::from('contract_fees as cf')
                ->where($matchThese)
                ->leftJoin('contracts as c','cf.contract_id','c.id')
                ->select('cf.*','c.service_number as service_number')
                ->orderByRaw(DB::Raw("cf.created_at, cf.fee_number desc"))
                ->get();
        return DataTables::of($case_payments)
                    ->addColumn('pathimage', function($row){   
                        $url = asset('paymentsproof/'.$row->service_number.'/'. $row->path);
                        $btn= '<a class="btn btn-block btn-social btn-google btn-xs" style="padding: 0px 0px;" href="'.$url.'" target="_blank"><b><i class="fa fa-file-pdf-o"></i></b>'.$row->path.'</a>';
                        return $btn;
                        // return Storage::url($row->path);
                    })
                    ->addColumn('acciones', function($row){
                        return '<a href="javascript:void(0)" class="btn btn-success btn-xs editPayment" data-container="body" data-feenumber = "'.$row->fee_number.'"data-id="'.$row->id.'" ><i class="fa fa-pencil fa-xs text-lightblue"></i> Modificar</a></li>';
                    })
                    ->rawColumns(['acciones','pathimage'])
                    ->make(true);
    }

    public function sendEmailReminder(Request $request, $id)
    {
        $user = User::findOrFail($id);

        Mail::send('emails.reminder', 'desarrollo2@campusyformacion.com', function ($m) use ($user) {
            $m->from('hello@app.com', 'Your Application');
            $m->to($user->email, $user->name)->subject('Your Reminder!');
        });
    }


    /**
    Enviar Contrato
     */

    public function sendEmail($con_id_crypeted)
    {
        // dd($request->drop_reasons_list[0]);
        $spreadsheet_id= decrypt($con_id_crypeted);
        $mailData = Contract::from('contracts as c')
                ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
                ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                ->leftJoin('people_inf as py','c.payer_id','=','py.id')
                ->leftJoin('leads as le','c.lead_id','=','le.id')
                ->leftJoin('users as us','c.agent_id','=','us.id')
                ->leftJoin('people_inf as pro','us.person_id','=','pro.id')
                ->where('c.id','=',$spreadsheet_id)
                ->select('c.*','crse.name as course_name','le.student_email as client_email'
                        
                        ,'pi.name as client_name','pi.last_name as client_last_name'
                        ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'

                         ,'py.name as payer_name','py.last_name as payer_last_name'
                        ,'py.dni as payer_dni','py.mobile as payer_mobile', 'py.mail as payer_mail'
                        
                        ,'pi.address as address','pi.town as town', 'pi.province_id as client_province_id'
                        ,'pi.postal_code as postal_code'
                        ,'pi.dt_birth as dt_birth', 'pi.country_id as client_country_id'
                        ,'us.email as agent_mail'
                        ,'pro.name as agent_name','pro.last_name as agent_lastname')
                ->get()->toArray();
        // dd($mailData[0]['payer_id'] <> $mailData[0]['person_id']);
        $mailData[0]['client_full_name'] = $mailData[0]['client_name'] . ' '. $mailData[0]['client_last_name'];
        $mailData[0]['url_contract'] = route('case.generate', ['crypt_id'=>encrypt($spreadsheet_id), 'type'=>encrypt('student')]);
        Mail::to('info@grupoefp.com')
            ->cc($mailData[0]['client_email'])
            ->bcc([$mailData[0]['agent_mail'],'systemskcd@gmail.com'])
            ->send(new sendContract($mailData[0]));
        if($mailData[0]['payer_id'] <> $mailData[0]['person_id']){
            $mailData[0]['client_full_name'] = $mailData[0]['payer_name'] . ' '. $mailData[0]['payer_last_name'];
            $mailData[0]['url_contract'] = route('case.generate', ['crypt_id'=>encrypt($spreadsheet_id), 'type'=>encrypt('payer')]);
            Mail::to('info@grupoefp.com')
                ->cc($mailData[0]['payer_mail'])
                ->bcc([$mailData[0]['agent_mail'],'systemskcd@gmail.com'])
                ->send(new sendContract($mailData[0]));
        }
  
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function chargesAdministration(Request $request)
    {
        $user;
        $companies;
        $roles;
        $now = Carbon::now();
       $spreadsheet;
        if (Auth::check()) {
            $user = Auth::user();
            $companies = User::find($user->id)->companies;
            // dd($companies);
            $filter_list = Contract::from ('contracts as cf')
                        ->leftJoin('leads as ldf','cf.lead_id','=','ldf.id')
                        ->leftJoin('users as usf', 'ldf.agent_id', '=','usf.id')
                        ->select('cf.dt_created as dt_contract', 'cf.service_id as service_id_filter'
                            ,'ldf.province_id as province_id_filter'
                            ,'ldf.agent_id as agent_id_filter'
                            ,'cf.contract_status_id as case_status_id_filter'
                            ,'cf.contract_type_id as case_type_id_filter'
                            ,'cf.contract_payment_type_id as case_payment_type_id_filter'
                            ,'cf.contract_method_type_id as case_method_type_id_filter'
                            , DB::raw('(SELECT max(cff.dt_payment) FROM case_fees cff WHERE cff.case_id = cf.id) AS max_dt_payment')
                            , DB::raw('(SELECT min(cff.dt_payment) FROM case_fees cff WHERE cff.case_id = cf.id) AS min_dt_payment')
                        )
                        // ->where('cf.id','=',1361)
                        ->orderByRaw(DB::Raw("cf.created_at, cf.id desc"))
                        ->get();
            $case_status_filter = array ('PP','P','Z','E');
            $dt_case_from = (!empty($_GET["dt_case_from"])) ? ($_GET["dt_case_from"]) : $filter_list->min('min_dt_payment');
            $dt_case_to = (!empty($_GET["dt_case_to"])) ? ($_GET["dt_case_to"]) : $filter_list->max('max_dt_payment');
            $dt_case_paid_from = (!empty($_GET["dt_case_paid_from"])) ? ($_GET["dt_case_paid_from"]) : NULL;
            $dt_case_paid_to = (!empty($_GET["dt_case_paid_to"])) ? ($_GET["dt_case_paid_to"]) : NULL;
            $agent_list_filter = (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : $filter_list->pluck('agent_id_filter')->unique();
            $case_status_id_filter = (!empty($_GET["case_status_filter"])) ? ($_GET["case_status_filter"]) : $case_status_filter;
            $case_type_id_filter = (!empty($_GET["case_type_list_filter"])) ? ($_GET["case_type_list_filter"]) : $filter_list->pluck('case_type_id_filter')->unique();
            $case_method_list_filter = (!empty($_GET["case_method_list_filter"])) ? ($_GET["case_method_list_filter"]) : $filter_list->pluck('case_method_type_id_filter')->unique();
            $case_payment_type_list_filter = (!empty($_GET["case_payment_type_list_filter"])) ? ($_GET["case_payment_type_list_filter"]) : $filter_list->pluck('case_payment_type_id_filter')->unique();
            $courses_list_filter = (!empty($_GET["courses_list_filter"])) ? ($_GET["courses_list_filter"]) : $filter_list->pluck('course_id_filter')->unique();
            $case_charged_list_filter = (!empty($_GET["case_charged_filter"])) ? ($_GET["case_charged_filter"]) : null;
            $case_fee_type_list_filter_aux = (!empty($_GET["case_fee_type_filter"])) ? ($_GET["case_fee_type_filter"]) : null;
            // dd($dt_case_paid_to);
            $provinces_list_filter = (!empty($_GET["provinces_list_filter"])) ? ($_GET["provinces_list_filter"]) : $filter_list->pluck('province_id_filter')->unique();      
            if ($user->hasRole('superadmin')){
                // dd("admin");
                // DB::enableQueryLog();
                $spreadsheet = Contract::from('contracts as c')
                         ->whereIn('c.company_id',$companies->pluck('id'))
                         ->where('c.contract_status_id','=',3)
                         ->leftJoin('contract_fees as cf','cf.contract_id','=','c.id')
                         ->leftJoin('companies as com','c.company_id','=','com.id')
                         ->leftJoin('provinces as compro','com.province_id','=','compro.id')
                         ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                         ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
                         ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
                         ->leftJoin('case_modalities as cm', 'c.case_modality_id','=','cm.id')
                         ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                         ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
                         ->leftJoin('materials as ma', 'c.material_id','=','ma.id')
                         ->leftJoin('courses as crse', 'c.course_id', '=','crse.id') 
                         
                         ->leftJoin('leads as le','c.lead_id','=','le.id')
                         ->leftJoin('users as us','c.agent_id','=','us.id')
                        //  ->whereMonth('cf.dt_payment','=',$now->month) 
                        //  ->whereYear('cf.dt_payment','=',$now->year)
                         ->WhereIn('crse.id',$courses_list_filter)
                         ->WhereIn('le.agent_id',$agent_list_filter)
                         ->WhereIn('c.contract_type_id',$case_type_id_filter)
                         ->WhereIn('cf.status',$case_status_id_filter)
                         ->WhereIn('le.province_id',$provinces_list_filter)
                         ->WhereIn('c.contract_method_type_id',$case_method_list_filter)
                         ->WhereIn('c.contract_payment_type_id',$case_payment_type_list_filter)
                        //  ->whereBetween('cf.dt_payment', [$dt_case_from, $dt_case_to])
                        //  ->whereBetween('cf.dt_paid', [$dt_case_paid_from, $dt_case_paid_to])
                         ->groupBy('c.service_number')  
                         ->select('c.*','crse.name as course_name','crse_aux.name as aux_course_name'
                                 ,'pi.name as name','pi.last_name as last_name'
                                 ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                                 ,'cs.short_name as case_status','us.name as agent_name','cs.color_class as bg_color'
                                 ,'ma.name as material','cm.name as modality','ct.name as case_type'
                                 ,'cpm.name as payment_method', 'cpt.name as payment_type','cpm.class_color as bg_color_status'
                                 ,'com.name as company_name', 'com.address as company_address' 
                                 ,'com.town as company_town', 'compro.name as company_province','cf.dt_payment as fee_dt_payment'
                                 ,'com.postal_code as company_pc'  ,'cf.fee_value as fee_value'
                                 , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                                 , DB::raw('coalesce(c.s_payment,0) +coalesce(c.pp_enroll_payment,0)
                                           + coalesce(c.pp_initial_payment,0) + coalesce(c.m_payment,0)
                                           + (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0)) AS total'));
                        //  ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
                        //  ->get();
                    // dd(DB::getQueryLog());
             }
             else{
                $spreadsheet = Contract::from('contracts as c')
                         ->whereIn('c.company_id',$companies->pluck('id'))
                         ->where('c.contract_status_id','=',3)
                         ->leftJoin('contract_fees as cf','cf.contract_id','=','c.id')
                         ->leftJoin('companies as com','c.company_id','=','com.id')
                         ->leftJoin('provinces as compro','com.province_id','=','compro.id')
                         ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                         ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
                         ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
                         ->leftJoin('case_modalities as cm', 'c.case_modality_id','=','cm.id')
                         ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                         ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
                         ->leftJoin('materials as ma', 'c.material_id','=','ma.id')
                         ->leftJoin('courses as crse', 'c.course_id', '=','crse.id') 
                         
                         ->leftJoin('leads as le','c.lead_id','=','le.id')
                         ->leftJoin('users as us','c.agent_id','=','us.id')
                         ->where('us.id','=',$user->id)
                         ->WhereIn('crse.id',$courses_list_filter)
                         ->WhereIn('le.agent_id',$agent_list_filter)
                         ->WhereIn('c.contract_type_id',$case_type_id_filter)
                         ->WhereIn('cf.status',$case_status_id_filter)
                         ->WhereIn('le.province_id',$provinces_list_filter)
                         ->WhereIn('c.contract_method_type_id',$case_method_list_filter)
                         ->WhereIn('c.contract_payment_type_id',$case_payment_type_list_filter)
                        //  ->whereBetween('cf.dt_payment', [$dt_case_from, $dt_case_to])
                         ->groupBy('c.service_number')  
                         ->select('c.*','crse.name as course_name','crse_aux.name as aux_course_name'
                                 ,'pi.name as name','pi.last_name as last_name'
                                 ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                                 ,'cs.short_name as case_status','us.name as agent_name','cs.color_class as bg_color'
                                 ,'ma.name as material','cm.name as modality','ct.name as case_type'
                                 ,'cpm.name as payment_method', 'cpt.name as payment_type','cpm.class_color as bg_color_status'
                                 ,'com.name as company_name', 'com.address as company_address' 
                                 ,'com.town as company_town', 'compro.name as company_province','cf.dt_payment as fee_dt_payment'
                                 ,'com.postal_code as company_pc'  ,'cf.fee_value as fee_value'
                                 , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                                 , DB::raw('coalesce(c.s_payment,0) + coalesce(c.pp_enroll_payment,0)
                                           + coalesce(c.pp_initial_payment,0) + coalesce(c.m_payment,0)
                                           + (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0)) AS total'));
             }
             if($dt_case_paid_from == NULL && $dt_case_paid_to == NULL){
                $spreadsheet->whereBetween('cf.dt_payment', [$dt_case_from, $dt_case_to]);
             }
             else{
                $spreadsheet->whereBetween('cf.dt_paid', [$dt_case_paid_from, $dt_case_paid_to]);
             }
             if (!is_null($case_charged_list_filter)){
                //  dd($case_charged_list_filter);
                 if($case_charged_list_filter == '1'){
                    $spreadsheet->whereNotExists(function ($query) {
                        $query->select("cf.*")
                              ->from('contract_fees as cf')
                              ->whereIn('cf.status',['E','PP'])
                              ->whereRaw('cf.contract_id = c.id');
                    });
                }elseif($case_charged_list_filter == '2'){
                    $spreadsheet->whereExists(function ($query) {
                        $query->select("cf.*")
                              ->from('contract_fees as cf')
                              ->where('cf.status','=','P')
                              ->whereRaw('cf.contract_id = c.id');
                    });
                }else{
                    $spreadsheet->whereExists(function ($query) {
                        $query->select("cf.*")
                              ->from('contract_fees as cf')
                              ->whereRaw('cf.contract_id = c.id');
                    });
                }
             }
             if(!is_null($case_fee_type_list_filter_aux)){
                // dd($case_fee_type_list_filter_aux);
                if($case_fee_type_list_filter_aux == '1'){
                    $spreadsheet->where('cf.fee_number','=',0);
                }elseif($case_fee_type_list_filter_aux == '2'){
                    $spreadsheet->where('cf.fee_number','>=',1);          
                }else{
                    $spreadsheet->where('cf.fee_number','>=',0); 
                }
             } 
             if ($request->ajax()) {
                // dd($spreadsheet->first());
                
                return DataTables::of($spreadsheet)
                        ->addColumn('total_pending',function($row) {
                            $total_pending = Contract::from("case_fees as cf")
                            ->where('cf.contract_id','=',$row->id)
                            ->whereIn('cf.status',['E','PP'])
                            ->sum('cf.fee_value');
                            // dd($total_pending);
                            return $total_pending;
                        })
                        ->addColumn('crypt_case_id',function($row){
                            return encrypt($row->id);
                        })
                        ->addColumn('acciones', function($row){
                            $user = Auth::user();
                            /**
                             * Anterior
                             *  $btn= $btn.'<div class="btn-group">
                             * <button type="button" class="btn btn-success btn-xs">Acciones</button>
                             *        <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown">
                                        * <span class="caret"></span>
                                        * <span class="sr-only">Toggle Dropdown</span>
                                    * * </button>
                                    * <ul class="dropdown-menu nav navbar-nav" role="menu" style="font-size: 0.82em; padding:3px 5px; position:static; min-width:auto">';
    
                                    * $btn = $btn . '</ul></div>';
                             */
                            $btn= '<ul style="margin-block-end: 0em;padding-inline-start:0px" align="center">
                                    <li class="dropdown btn btn-success btn-xs" style="color:white;">
                                        <a style="color:white;padding:3px 5px;" href="#" class="dropdown-toggle" data-toggle="dropdown">Acciones &nbsp;<b class="caret"></b></a>
                                        <ul class="dropdown-menu" style="left:-80;background-color:white; color:gray;">';      
                            if($row->case_status_id == 1){                            
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="editContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-pencil fa-xs text-lightblue"></i> Editar</a></li>';
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="deleteContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-close fa-xs text-lightblue"></i> Anular Contrato</a></li>';
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="sendContract" data-container="body" data-object="'.encrypt($row->id).'" ><i class="fa fa-close fa-xs text-lightblue"></i> Enviar Contrato</a></li>';
                            }
                            if( ($row->payment_method == 'Domiciliación Bancaria') && ($row->case_status_id == 2 || $row->case_status_id == 3)){
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px; " href="'.route('download.sepa',encrypt($row->id)).'" target="_blank"  data-container="body" data-id="'.$row->id.'" ><i class="fa fa-eur fa-xs text-lightblue"></i> Descargar SEPA</a></li>';
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px; " href="javascript:void(0)" class="uploadSepa" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-upload fa-xs text-lightblue"></i> Subir SEPA</a></li>';
                            }
                            if ($user->hasRole('superadmin')){
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="dropContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-trash fa-xs text-lightblue"></i> Dar de Baja</a></li>';
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="smsContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-comment fa-xs text-lightblue"></i> Enviar SMS</a></li>';
                            }
                            $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" target="_blank" href="'.route('contract.generate', ['crypt_id'=>encrypt($row->id), 'type'=>'admin']).'" class="urlContract" data-container="body" data-id="'.$row->id.'" ><i class="fa fa-search fa-xs text-lightblue"></i> Ver Contrato</a></li>';
                            $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="linkContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-copy fa-xs text-lightblue"></i> Copiar Link</a></li>';
                            if($row->case_status_id <> 1){
                                if ($user->hasRole('superadmin')){
                                    $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="uploadID" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-file-image-o fa-xs text-lightblue"></i> Subir DNI</a></li>';
                                }
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="contractFeePayments" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-eur fa-xs text-lightblue"></i> Cobros</a></li>';
                            }
                            $btn = $btn .'</ul></li></ul>';
                            return $btn;
                        })
                        // ->rawColumns(['acciones','checkbox'])
                        ->rawColumns(['acciones','total_pending','crypt_case_id'])
                        ->make(true);
                }
                return view('contractchargesadmin');
        }
        else{
            return view('auth.login');
        }
        
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unpaidAdministration(Request $request)
    {
        $user;
        $companies;
        $roles;
        $now = Carbon::now();
       $spreadsheet;
        if (Auth::check()) {
            $user = Auth::user();
            $companies = User::find($user->id)->companies;
            // dd($companies);
            $filter_list = Contract::from ('contracts as cf')
                        ->leftJoin('leads as ldf','cf.lead_id','=','ldf.id')
                        ->leftJoin('users as usf', 'ldf.agent_id', '=','usf.id')
                        ->select('cf.dt_created as dt_contract', 'cf.service_id as service_id_filter'
                            ,'ldf.province_id as province_id_filter'
                            ,'ldf.agent_id as agent_id_filter'
                            ,'cf.contract_status_id as case_status_id_filter'
                            ,'cf.contract_type_id as case_type_id_filter'
                            ,'cf.contract_payment_type_id as case_payment_type_id_filter'
                            ,'cf.contract_method_type_id as case_method_type_id_filter'
                            , DB::raw('(SELECT max(cff.dt_payment) FROM case_fees cff WHERE cff.case_id = cf.id) AS max_dt_payment')
                            , DB::raw('(SELECT min(cff.dt_payment) FROM case_fees cff WHERE cff.case_id = cf.id) AS min_dt_payment')
                        )
                        // ->where('cf.id','=',1361)
                        ->orderByRaw(DB::Raw("cf.created_at, cf.id desc"))
                        ->get();
            $case_status_filter = array ('PP','P','Z','E');
            $dt_case_from = (!empty($_GET["dt_case_from"])) ? ($_GET["dt_case_from"]) : $filter_list->min('min_dt_payment');
            $dt_case_to = (!empty($_GET["dt_case_to"])) ? ($_GET["dt_case_to"]) : $filter_list->max('max_dt_payment');
            $dt_case_paid_from = (!empty($_GET["dt_case_paid_from"])) ? ($_GET["dt_case_paid_from"]) : $filter_list->min('min_dt_payment');
            $dt_case_paid_to = (!empty($_GET["dt_case_paid_to"])) ? ($_GET["dt_case_paid_to"]) : $filter_list->max('max_dt_payment');
            $agent_list_filter = (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : $filter_list->pluck('agent_id_filter')->unique();
            $case_status_id_filter = (!empty($_GET["case_status_filter"])) ? ($_GET["case_status_filter"]) : $case_status_filter;
            $case_type_id_filter = (!empty($_GET["case_type_list_filter"])) ? ($_GET["case_type_list_filter"]) : $filter_list->pluck('case_type_id_filter')->unique();
            $case_method_list_filter = (!empty($_GET["case_method_list_filter"])) ? ($_GET["case_method_list_filter"]) : $filter_list->pluck('case_method_type_id_filter')->unique();
            $case_payment_type_list_filter = (!empty($_GET["case_payment_type_list_filter"])) ? ($_GET["case_payment_type_list_filter"]) : $filter_list->pluck('case_payment_type_id_filter')->unique();
            $courses_list_filter = (!empty($_GET["courses_list_filter"])) ? ($_GET["courses_list_filter"]) : $filter_list->pluck('course_id_filter')->unique();
            $case_charged_list_filter = (!empty($_GET["case_charged_filter"])) ? ($_GET["case_charged_filter"]) : null;
            $case_fee_type_list_filter_aux = (!empty($_GET["case_fee_type_filter"])) ? ($_GET["case_fee_type_filter"]) : null;
            // dd($dt_case_paid_to);
            $provinces_list_filter = (!empty($_GET["provinces_list_filter"])) ? ($_GET["provinces_list_filter"]) : $filter_list->pluck('province_id_filter')->unique();      
            if ($user->hasRole('superadmin')){
                // dd("admin");
                // DB::enableQueryLog();
                $spreadsheet = Contract::from('contracts as c')
                         ->whereIn('c.company_id',$companies->pluck('id'))
                         ->where('c.contract_status_id','=',3)
                         ->leftJoin('contract_fees as cf','cf.contract_id','=','c.id')
                         ->leftJoin('companies as com','c.company_id','=','com.id')
                         ->leftJoin('provinces as compro','com.province_id','=','compro.id')
                         ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                         ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
                         ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
                         ->leftJoin('case_modalities as cm', 'c.case_modality_id','=','cm.id')
                         ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                         ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
                         ->leftJoin('materials as ma', 'c.material_id','=','ma.id')
                         ->leftJoin('courses as crse', 'c.course_id', '=','crse.id') 
                         
                         ->leftJoin('leads as le','c.lead_id','=','le.id')
                         ->leftJoin('users as us','c.agent_id','=','us.id')
                         ->WhereIn('crse.id',$courses_list_filter)
                         ->WhereIn('le.agent_id',$agent_list_filter)
                         ->WhereIn('c.contract_type_id',$case_type_id_filter)
                         ->WhereIn('cf.status',$case_status_id_filter)
                         ->WhereIn('le.province_id',$provinces_list_filter)
                         ->WhereIn('c.contract_method_type_id',$case_method_list_filter)
                         ->WhereIn('c.contract_payment_type_id',$case_payment_type_list_filter)
                         ->whereBetween('cf.dt_payment', [$dt_case_from, $dt_case_to])
                         ->where('cf.status','=','E')
                         ->groupBy('c.service_number')  
                         ->select('c.*','crse.name as course_name','crse_aux.name as aux_course_name'
                                 ,'pi.name as name','pi.last_name as last_name'
                                 ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                                 ,'cs.short_name as case_status','us.name as agent_name','cs.color_class as bg_color'
                                 ,'ma.name as material','cm.name as modality','ct.name as case_type'
                                 ,'cpm.name as payment_method', 'cpt.name as payment_type','cpm.class_color as bg_color_status'
                                 ,'com.name as company_name', 'com.address as company_address' 
                                 ,'com.town as company_town', 'compro.name as company_province'
                                 ,'com.postal_code as company_pc','cf.fee_value as fee_value'  
                                 , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                                 , DB::raw('(SELECT SUM(cf.fee_value)) AS total_unpaid')
                                 , DB::raw('(SELECT max(cff.dt_payment) FROM case_fees cff WHERE cff.case_id = c.id and cff.status = "E") AS dt_unpaid_from')
                                 , DB::raw('(SELECT count(cf.fee_value)) AS total_fees'));
             }
             else{
                $spreadsheet = Contract::from('contracts as c')
                         ->whereIn('c.company_id',$companies->pluck('id'))
                         ->where('c.contract_status_id','=',3)
                         ->leftJoin('contract_fees as cf','cf.contract_id','=','c.id')
                         ->leftJoin('companies as com','c.company_id','=','com.id')
                         ->leftJoin('provinces as compro','com.province_id','=','compro.id')
                         ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                         ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
                         ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
                         ->leftJoin('case_modalities as cm', 'c.case_modality_id','=','cm.id')
                         ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                         ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
                         ->leftJoin('materials as ma', 'c.material_id','=','ma.id')
                         ->leftJoin('courses as crse', 'c.course_id', '=','crse.id') 
                         
                         ->leftJoin('leads as le','c.lead_id','=','le.id')
                         ->leftJoin('users as us','c.agent_id','=','us.id')
                         ->where('us.id','=',$user->id)
                         ->WhereIn('crse.id',$courses_list_filter)
                         ->WhereIn('le.agent_id',$agent_list_filter)
                         ->WhereIn('c.contract_type_id',$case_type_id_filter)
                         ->WhereIn('cf.status',$case_status_id_filter)
                         ->WhereIn('le.province_id',$provinces_list_filter)
                         ->WhereIn('c.contract_method_type_id',$case_method_list_filter)
                         ->WhereIn('c.contract_payment_type_id',$case_payment_type_list_filter)
                         ->whereBetween('cf.dt_payment', [$dt_case_from, $dt_case_to])
                         ->where('cf.status','=','E')
                         ->groupBy('c.service_number')  
                         ->select('c.*','crse.name as course_name','crse_aux.name as aux_course_name'
                                 ,'pi.name as name','pi.last_name as last_name'
                                 ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                                 ,'cs.short_name as case_status','us.name as agent_name','cs.color_class as bg_color'
                                 ,'ma.name as material','cm.name as modality','ct.name as case_type'
                                 ,'cpm.name as payment_method', 'cpt.name as payment_type','cpm.class_color as bg_color_status'
                                 ,'com.name as company_name', 'com.address as company_address' 
                                 ,'com.town as company_town', 'compro.name as company_province'
                                 ,'com.postal_code as company_pc','cf.fee_value as fee_value' 
                                 , DB::raw('TIMESTAMPDIFF(YEAR, pi.dt_birth, CURDATE()) AS age')
                                 , DB::raw('(SELECT SUM(cf.fee_value)) AS total_unpaid')
                                 , DB::raw('(SELECT max(cff.dt_payment) FROM case_fees cff WHERE cff.case_id = c.id and cff.status = "E") AS dt_unpaid_from')
                                 , DB::raw('(SELECT count(cf.fee_value)) AS total_fees'));
             }
             if (!is_null($case_charged_list_filter)){
                //  dd($case_charged_list_filter);
                 if($case_charged_list_filter == '1'){
                    $spreadsheet->whereNotExists(function ($query) {
                        $query->select("cf.*")
                              ->from('contract_fees as cf')
                              ->whereIn('cf.status',['E','PP'])
                              ->whereRaw('cf.contract_id = c.id');
                    });
                }elseif($case_charged_list_filter == '2'){
                    $spreadsheet->whereExists(function ($query) {
                        $query->select("cf.*")
                              ->from('contract_fees as cf')
                              ->where('cf.status','=','P')
                              ->whereRaw('cf.contract_id = c.id');
                    });
                }else{
                    $spreadsheet->whereExists(function ($query) {
                        $query->select("cf.*")
                              ->from('contract_fees as cf')
                              ->whereRaw('cf.contract_id = c.id');
                    });
                }
             }
             if(!is_null($case_fee_type_list_filter_aux)){
                // dd($case_fee_type_list_filter_aux);
                if($case_fee_type_list_filter_aux == '1'){
                    $spreadsheet->where('cf.fee_number','=',0);
                }elseif($case_fee_type_list_filter_aux == '2'){
                    $spreadsheet->where('cf.fee_number','>=',1);          
                }else{
                    $spreadsheet->where('cf.fee_number','>=',0); 
                }
             } 
             if ($request->ajax()) {
                // dd($spreadsheet->get()->first());
                
                return DataTables::of($spreadsheet)
                        ->addColumn('total_pending',function($row) {
                            $total_pending = Contract::from("case_fees as cf")
                            ->where('cf.contract_id','=',$row->id)
                            ->whereIn('cf.status',['E','PP'])
                            ->sum('cf.fee_value');
                            // dd($total_pending);
                            return $total_pending;
                        })
                        ->addColumn('crypt_case_id',function($row){
                            return encrypt($row->id);
                        })
                        ->addColumn('total_fees',function($row){
                            return $row->total_fees;
                        })
                        ->addColumn('total_unpaid',function($row){
                            return $row->total_unpaid;
                        })
                        ->addColumn('dt_unpaid_from',function($row){
                            return $row->dt_unpaid_from;
                        })
                        ->addColumn('acciones', function($row){
                            $user = Auth::user();
                            $btn= '<ul style="margin-block-end: 0em;padding-inline-start:0px" align="center">
                                    <li class="dropdown btn btn-success btn-xs" style="color:white;">
                                        <a style="color:white;padding:3px 5px;" href="#" class="dropdown-toggle" data-toggle="dropdown">Acciones &nbsp;<b class="caret"></b></a>
                                        <ul class="dropdown-menu" style="left:-80;background-color:white; color:gray;">';      
                            /**
                            Si es tutor solo puede ver la opción de crear notas 
                             */
                            if ($user->hasRole('teacher')){

                            }
                            else{
                                if($row->case_status_id == 1){                            
                                    $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="editContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-pencil fa-xs text-lightblue"></i> Editar</a></li>';
                                    $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="deleteContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-close fa-xs text-lightblue"></i> Eliminar</a></li>';
                                }
                                if( ($row->payment_method == 'Domiciliación Bancaria') && ($row->case_status_id == 2 || $row->case_status_id == 3)){
                                    $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px; " href="'.route('download.sepa',encrypt($row->id)).'" target="_blank"  data-container="body" data-id="'.$row->id.'" ><i class="fa fa-eur fa-xs text-lightblue"></i> Descargar SEPA</a></li>';
                                    $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px; " href="javascript:void(0)" class="uploadSepa" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-upload fa-xs text-lightblue"></i> Subir SEPA</a></li>';
                                }
                                if ($user->hasRole('superadmin')){
                                    $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="dropContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-trash fa-xs text-lightblue"></i> Dar de Baja</a></li>';
                                    $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="smsContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-comment fa-xs text-lightblue"></i> Enviar SMS</a></li>';
                                }
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" target="_blank" href="'.route('contract.generate', encrypt($row->id)).'" class="urlContract" data-container="body" data-id="'.$row->id.'" ><i class="fa fa-search fa-xs text-lightblue"></i> Ver Contrato</a></li>';
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="linkContract" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-copy fa-xs text-lightblue"></i> Copiar Link</a></li>';
                                $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="managementNote" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-clone fa-xs text-lightblue"></i> Notas de Gestión</a></li>';
                                if($row->case_status_id <> 1){
                                    $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="contractFeePayments" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-eur fa-xs text-lightblue"></i> Cobros</a></li>';
                                }
                            }
                            $btn = $btn .'</ul></li></ul>';
                            return $btn;
                        })
                        // ->rawColumns(['acciones','checkbox'])
                        ->rawColumns(['acciones','total_fees','total_unpaid','total_pending','crypt_case_id'])
                        ->make(true);
                }
                return view('contractunpaidadmin');
        }
        else{
            return view('auth.login');
        }
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function internships(Request $request)
    {
        $user;
        $companies;
        $roles;
        $spreadsheet;
        // dd("HOLA");
        if (Auth::check()) {
            $user = Auth::user();
            $companies = User::find($user->id)->companies;
            // dd($companies);
            $filter_list = Contract::from ('contracts as cf')
                        ->where('cf.contract_type_id','=',2)
                        ->select('cf.dt_created as dt_contract', 'cf.course_id as course_id_filter'
                            ,'cf.request_province_id as province_id_filter'
                            ,'cf.agent_id as agent_id_filter'
                            ,'cf.educational_center_id as ecenter_id_filter'
                            ,'cf.contract_status_id as contract_status_id_filter'
                            ,'cf.contract_type_id as contract_type_id_filter'
                            ,'cf.contract_payment_type_id as contract_payment_type_id_filter'
                            ,'cf.contract_method_type_id as contract_method_type_id_filter'
                        )
                        // ->where('cf.id','=',1361)
                        ->orderByRaw(DB::Raw("cf.created_at, cf.id desc"))
                        ->get();
            $filter_contract_id = (!empty($_GET["filter_contract_id"])) ? ($_GET["filter_contract_id"]) : null;
            $dt_contract_from = (!empty($_GET["dt_contract_from"])) ? ($_GET["dt_contract_from"]) : $filter_list->min('dt_contract');
            $dt_contract_to = (!empty($_GET["dt_contract_to"])) ? ($_GET["dt_contract_to"]) : $filter_list->max('dt_contract');
            $agent_list_filter = (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : $filter_list->pluck('agent_id_filter')->unique();
            $ecenter_id_filter = (!empty($_GET["contract_ecenter_list_filter"])) ? ($_GET["contract_ecenter_list_filter"]) : $filter_list->pluck('ecenter_id_filter')->unique();
            $spreadsheet_status_id_filter = (!empty($_GET["contract_status_filter"])) ? ($_GET["contract_status_filter"]) : $filter_list->pluck('contract_status_id_filter')->unique();
            // $spreadsheet_type_id_filter = (!empty($_GET["contract_type_list_filter"])) ? ($_GET["contract_type_list_filter"]) : $filter_list->pluck('contract_type_id_filter')->unique();
            $spreadsheet_type_id_filter = [2];
            $spreadsheet_method_list_filter = (!empty($_GET["contract_method_list_filter"])) ? ($_GET["contract_method_list_filter"]) : $filter_list->pluck('contract_method_type_id_filter')->unique();
            $spreadsheet_payment_type_list_filter = (!empty($_GET["contract_payment_type_list_filter"])) ? ($_GET["contract_payment_type_list_filter"]) : $filter_list->pluck('contract_payment_type_id_filter')->unique();
            $courses_list_filter = (!empty($_GET["courses_list_filter"])) ? ($_GET["courses_list_filter"]) : $filter_list->pluck('course_id_filter')->unique();
            $provinces_list_filter = (!empty($_GET["provinces_list_filter"])) ? ($_GET["provinces_list_filter"]) : $filter_list->pluck('province_id_filter')->unique();      

            
            if ($user->hasRole('superadmin')){
                // dd($spreadsheet_type_id_filter);
                $spreadsheet = Contract::from('contracts as c')
                        ->whereIn('c.company_id',$companies->pluck('id'))
                        ->leftJoin('companies as com','c.company_id','=','com.id')
                        ->leftJoin('provinces as compro','com.province_id','=','compro.id')
                        ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                        ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
                        ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
                        ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                        ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
                        ->leftJoin('materials as ma', 'c.material_id','=','ma.id')
                        ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
                        ->leftJoin('users as us','c.agent_id','=','us.id')
                        ->leftJoin('internship_status as ist','c.internship_studies_status_id','=','ist.id')
                        ->leftJoin('educational_centers as ecen','c.educational_center_id','=','ecen.id')
                        ->leftJoin('provinces as prov_req','c.request_province_id','=','prov_req.id')
                        ->WhereIn('crse.id',$courses_list_filter)
                        ->WhereIn('c.contract_type_id',$spreadsheet_type_id_filter)
                        ->WhereIn('c.educational_center_id',$ecenter_id_filter)
                        ->WhereIn('c.contract_status_id',$spreadsheet_status_id_filter)
                        ->WhereIn('c.request_province_id',$provinces_list_filter)
                        ->WhereIn('c.contract_method_type_id',$spreadsheet_method_list_filter)
                        ->WhereIn('c.contract_payment_type_id',$spreadsheet_payment_type_list_filter)
                        ->whereBetween('c.dt_created', [$dt_contract_from, $dt_contract_to])
                        ->select('c.*','crse.name as course_name'
                                ,'pi.name as student_name','pi.last_name as student_last_name'
                                ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                                ,'cs.short_name as contract_status','us.name as agent_name','cs.color_class as bg_color'
                                ,'ma.name as material','ct.name as contract_type'
                                ,'cpm.name as payment_method', 'cpt.name as payment_type','ecen.name as ecenter'
                                ,'com.name as company_name', 'com.address as company_address' ,'prov_req.name as prov_req'
                                ,'com.town as company_town', 'compro.name as company_province'
                                ,'com.postal_code as company_pc' ,'ist.name as internship_status');
                        //  ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
                        //  ->get();
                    // dd(DB::getQueryLog());
            }
            else{
                $spreadsheet = Contract::from('contracts as c')
                        ->whereIn('c.company_id',$companies->pluck('id'))
                        ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                        ->leftJoin('companies as com','c.company_id','=','com.id')
                        ->leftJoin('provinces as compro','com.province_id','=','compro.id')
                        ->leftJoin('contract_types as ct','c.contract_type_id','=','ct.id')
                        ->leftJoin('contract_states as cs', 'c.contract_status_id','=','cs.id')
                        ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                        ->leftJoin('contract_payment_types as cpt', 'c.contract_payment_type_id','=','cpt.id')
                        ->leftJoin('materials as ma', 'c.material_id','=','ma.id')
                        ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
                        ->leftJoin('leads as le','c.lead_id','=','le.id')
                        ->leftJoin('users as us','le.agent_id','=','us.id')
                        ->where('us.id','=',$user->id)
                        ->WhereIn('crse.id',$courses_list_filter)
                        ->WhereIn('le.agent_id',$agent_list_filter)
                        ->WhereIn('c.contract_type_id',$spreadsheet_type_id_filter)
                        ->WhereIn('c.contract_status_id',$spreadsheet_status_id_filter)
                        ->WhereIn('le.province_id',$provinces_list_filter)
                        ->whereBetween('c.dt_created', [$dt_contract_from, $dt_contract_to])
                        ->select('c.*','crse.name as course_name' 
                                ,'pi.name as student_name','pi.last_name as student_last_name'
                                ,'pi.dni as dni','pi.mobile as mobile', 'pi.mail as mail'
                                ,'cs.short_name as contract_status','us.name as agent_name','cs.color_class as bg_color'
                                ,'ma.name as material','ct.name as contract_type'
                                ,'cpm.name as payment_method', 'cpt.name as payment_type'
                                ,'com.name as company_name', 'com.address as company_address' 
                                ,'com.town as company_town', 'compro.name as company_province'
                                ,'com.postal_code as company_pc');
                        //  ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
                        //  ->get();
            }
            if(!is_null($filter_contract_id)){  
                $filter_contract_id = decrypt($filter_contract_id);              
                // $lead_id_request = (int)$lead_id_request_aux;
                $spreadsheet->where('c.lead_id','=',$filter_contract_id);
                // $lead_id_request = strval(decrypt($lead_id_request_aux));
                // dd(($lead_id_request)); 
            }
            if ($request->ajax()) {
                // dd($spreadsheet->first());
                
                return DataTables::of($spreadsheet)
                        ->addColumn('total_pending',function($row) {
                            $total_pending = Contract::from("contract_fees as cf")
                            ->where('cf.contract_id','=',$row->id)
                            ->whereIn('cf.status',['E','PP'])
                            ->sum('cf.fee_value');
                            // dd($total_pending);
                            return $total_pending;
                        })
                        ->addColumn('total',function($row) {
                            $total = Contract::from("contract_fees as cf")
                            ->where('cf.contract_id','=',$row->id)
                            ->sum('cf.fee_value');
                            return $total;
                        })
                        ->addColumn('acciones', function($row){
                            $user = Auth::user();
                            $statusAllowed = array(2,3); //Estados permitidos para crear cobros
                            $btn ='<div class="dropdown show" align="center">
                                    <a  data-disabled="true" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fad  fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';   
    
                            if($row->contract_status_id == 1){  //Cuando no se ha aceptado creado el contrato                          
                                $btn = $btn. '<a class="dropdown-item caseEdit" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-pencil mr-1 text-lightblue"></i> Editar</a>';
                                $btn = $btn. '<a class="dropdown-item caseDrop" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-trash mr-1 text-lightblue"></i> Anular Contrato</a>';
                                $btn = $btn. '<a class="dropdown-item sendContract" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-envelope-open-text mr-1 text-lightblue"></i> Enviar Contrato E-Mail</a>';
                            }
                            $btn = $btn. '<a class="dropdown-item caseGenerateContract" target="_blank" data-object="'.encrypt($row->id).'"" href="'.route('case.generate',['crypt_id'=>encrypt($row->id), 'type'=>encrypt('admin')]).'"><i class="fad  fa-fw fa-sm fa-search mr-1 text-lightblue"></i> Ver Contrato</a>';
                            $btn = $btn.'</div></div>';
    
                            return $btn;
                        })
                        // ->rawColumns(['acciones','checkbox'])
                        ->rawColumns(['acciones','total_pending','total'])
                        ->make(true);
                }
                $pageTitle ='Prácticas - '.$companies->pluck('name')[0];
                // dd($call_reminder_charges);
                return view('internships.index',compact('pageTitle'));
        }
        else{
            return view('auth.login');
        }
        
        
    }
    
}
