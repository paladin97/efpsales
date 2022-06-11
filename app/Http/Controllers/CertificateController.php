<?php

namespace App\Http\Controllers;

use App\Models\{Certificate,User,PersonInf,Role,Company,AccessCompany, Course};
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF,File,Mail;
use Illuminate\Http\Request;
use App\Mail\sendCertificate as sendCertificate;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
         // dd(Auth::user()->hasRole('superadmin'));
         // echo $mytime->toDateTimeString();
         if (Auth::check()) {
             // (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : Certificate::all()->pluck('id');
             $user = Auth::user();
 
             // dd($user->permissions->toArray()[0]['id']);
             $companies = User::find($user->id)->companies;
            //  dd($companies->pluck('id'));
             $filter_list = Certificate::from ('contracts as c')
                        //   ('certificates as cert')
                        //  ->leftJoin('contracts as c','cert.contract_id','c.id')
                        ->leftJoin('certificates as cert','cert.contract_id','c.id')
                        ->leftJoin('people_inf as pi','c.person_id','pi.id')
                        ->leftJoin('courses as crse','c.course_id','crse.id')
                        ->where('c.contract_status_id','=',3) //Matriculado
                        ->whereRaw('((DATEDIFF(NOW(),DATE_ADD(c.dt_created,  INTERVAL c.validity MONTH)) >= c.validity) OR (EXISTS (select cert.* from certificates as cert where cert.contract_id = c.id)))')
                        ->select('c.*','c.id as contract_id','crse.area_id as area_id'
                            ,'crse.type_id as type_id','pi.id as person_id','crse.id as course_id')
                        ->orderByRaw(DB::Raw("c.created_at, c.id desc"))
                        ->get();
             // dd($filter_list->pluck('course_id_filter')->unique());
            //  dd($filter_list->pluck('dt_created'));
             $cert_contract_filter = (!empty($_GET["cert_contract_filter"])) ? ($_GET["cert_contract_filter"]) : $filter_list->pluck('contract_id')->unique();
             $student_filter = (!empty($_GET["student_filter"])) ? ($_GET["student_filter"]) : $filter_list->pluck('person_id')->unique();
             $course_filter = (!empty($_GET["course_filter"])) ? ($_GET["course_filter"]) : $filter_list->pluck('course_id')->unique();
             $certificate_area_filter = (!empty($_GET["course_area_filter"])) ? ($_GET["course_area_filter"]) : $filter_list->pluck('area_id')->unique();
             $certificate_type_filter = (!empty($_GET["course_type_filter"])) ? ($_GET["course_type_filter"]) : $filter_list->pluck('type_id')->unique();
            //   dd($companies->pluck('id'));
            //  dd($filter_list);
             if ($user->hasRole('superadmin')){
                 // DB::enableQueryLog();
                 $certificate = Certificate::from ('contracts as c')
                        //  ('certificates as cert')
                        // ->leftJoin('contracts as c','cert.contract_id','c.id')
                        ->leftJoin('certificates as cert','cert.contract_id','c.id')
                        ->leftJoin('courses as crse','c.course_id','crse.id')
                        ->leftJoin('course_areas as cra','crse.area_id','cra.id')
                        ->leftJoin('people_inf as pi','c.person_id','pi.id')
                        ->WhereIn('crse.area_id',$certificate_area_filter)
                        ->WhereIn('crse.type_id',$certificate_type_filter)
                        ->WhereIn('pi.id',$student_filter)
                        ->WhereIn('crse.id',$course_filter)
                        ->WhereIn('c.id',$cert_contract_filter)
                        ->WhereIn('c.company_id',$companies->pluck('id'))
                        ->select('c.*','c.enrollment_number as enrollment_number'
                                , 'pi.name as name','pi.last_name as last_name'
                                , 'c.id as contract_id','crse.name as course_name'
                                , 'crse.area_id as area_id','crse.type_id as type_id'
                                , 'cra.name as course_area'
                                , DB::raw('(CASE WHEN (SELECT 1 FROM certificates as cert WHERE cert.contract_id = c.id) = 1 THEN "GENERADO" ELSE "PEND. GENERAR" END) as cert_status'));
                 }else{
                    $certificate = Certificate::from ('contracts as c')
                        //  ('certificates as cert')
                        // ->leftJoin('contracts as c','cert.contract_id','c.id')
                        ->leftJoin('courses as crse','c.course_id','crse.id')
                        ->leftJoin('course_areas as cra','crse.area_id','cra.id')
                        ->leftJoin('people_inf as pi','c.person_id','pi.id')
                        ->WhereIn('crse.area_id',$certificate_area_filter)
                        ->WhereIn('crse.type_id',$certificate_type_filter)
                        ->WhereIn('pi.id',$student_filter)
                        ->WhereIn('crse.id',$course_filter)
                        ->WhereIn('c.id',$cert_contract_filter)
                        ->WhereIn('c.company_id',$companies->pluck('id'))
                        ->select('c.*','c.enrollment_number as enrollment_number'
                                , 'pi.name as name','pi.last_name as last_name'
                                , 'c.id as contract_id','crse.name as course_name'
                                , 'crse.area_id as area_id','crse.type_id as type_id'
                                , 'cra.name as course_area'
                                , DB::raw('(CASE WHEN (SELECT 1 FROM certificates as cert WHERE cert.contract_id = c.id) = 1 THEN "GENERADO" ELSE "PEND. GENERAR" END) as cert_status'));
                 }
                 if ($request->ajax()) {
                     // dd($lead->first());
                     // dd(DataTables::of($lead)->make(true));
                     return DataTables::of($certificate)
                                ->addColumn('acciones', function($row){
                                    
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    
                                    if($row->cert_status == 'GENERADO'){
                                        $btn = $btn. ' <a class="dropdown-item viewCertificate" target="_blank" href="'.route('certificate.downloadPDF', encrypt($row->id)).'" title = "Generar" > <i class="fad fa-file-export text-lightblue text-lightblue"></i> Ver Certificado</a>';
                                        $btn = $btn. ' <a class="dropdown-item sendCert" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-mail-bulk mr-1 text-lightblue" ></i> Enviar Certificado Email</a>';
                                        // $btn = $btn. ' <a class="dropdown-item deleteCertificate" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue text-lightblue"></i> Eliminar</a>';
                                    }else{
                                        $btn = $btn. ' <a class="dropdown-item generateCertificate" target="_blank" href="'.route('certificate.downloadPDF', encrypt($row->id)).'" title = "Generar" > <i class="fad fa-file-export text-lightblue text-lightblue"></i> Generar Certificado</a>';
                                    }
                                    $btn = $btn.'</div></div>';

                                    return $btn;
                                })
                                // ->rawColumns(['acciones','checkbox'])
                                ->rawColumns(['acciones'])
                                ->make(true);
                     }
                 
                 // dd($call_reminder_leads);
                 $pageTitle ='Certificados - '.$companies->pluck('name')[0];
                 return view('admin.certificates.index',compact('pageTitle'));
             // dd($companies);
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
         $rules = [
             'gencert_contract' => ['required']
         ];
         $customMessages = [
             'gencert_contract.required' => 'El contrato es obligatorio',
         ];
 
         $validator = Validator::make($request->all(),$rules,$customMessages);
         
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
             
            //  $is_primary = $request->newcrse_is_primary == 'on' ? 1 : 0;
            //  $is_secondary = $request->newcrse_is_secondary == 'on' ? 1 : 0;
             Certificate::updateOrCreate(
                 ['id' => $request->cert_id],
                 ['contract_id' => (int)$request->gencert_contract]
             );        
             return response()->json(['success'=>'Certificado actualizado correctamente.']);
         }
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function show(Certificate $certificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function edit(Certificate $certificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certificate $certificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificate $certificate)
    {
        //
    }

    public function generateCertificatePDF($con_id_crypeted)
    {
        
        $footer = '<div style="text-align: right;color:gray;"><br><p> Libro N libro Registro N registro </p></div>';
        $contract_id = decrypt($con_id_crypeted);
        //Primero inserta el certificado en la tabla, sino existe lo creo, si existe entonces sigo
        $checkCert = Certificate::whereContractId($contract_id)->get();
        if($checkCert->isEmpty()){//si es vacio lo inserto
            Certificate::updateOrCreate(
                ['id' => NULL],
                ['contract_id' => (int)$contract_id]
            ); 
        }
         
        //Luego ya lo puedo generar
        $certificate = Certificate::from ('certificates as cert')
                        ->leftJoin('contracts as c','cert.contract_id','c.id')
                        ->leftJoin('companies as com','c.company_id','com.id')
                        ->leftJoin('provinces as compro','com.province_id','compro.id')
                        ->leftJoin('courses as crse','c.course_id','crse.id')
                        ->leftJoin('people_inf as pi','c.person_id','pi.id')
                        ->Where('cert.contract_id','=',$contract_id)
                        ->select('cert.*','c.enrollment_number as enrollment_number'
                                ,'c.signature_path as signature_path_student'
                                ,'pi.name as name','pi.last_name as last_name'
                                ,'pi.dni as dni','crse.program as program'
                                ,'c.id as contract_id','crse.name as course_name'
                                ,'com.name as company_name','com.leg_rep_full_name as ceo'
                                ,'com.address as com_address','com.town as com_town','compro.name as com_province'
                                ,'com.postal_code as com_postal_code','com.leg_rep_nif as leg_rep_nif'
                                ,'crse.duration as duration','c.created_at as contract_date'
                                ,'crse.area_id as area_id','crse.type_id as type_id')
                        ->get();
        $certificate->toArray();
        // dd($certificate);
        Carbon::setLocale('es');
        $fecha = Carbon::parse($certificate[0]->contract_date);
        $fechahoy = Carbon::now();
        $date = $fecha->locale(); //con esto revise que el lenguaje fuera es 
        $fecha_cert =$fecha->day.' de '. $fecha->monthName.' de '  .$fecha->year; 
        $fecha_cert_hoy =$fechahoy->day.' de '. $fechahoy->monthName.' de '  .$fechahoy->year; 
        $certificate[0]['date_letter'] = $fecha_cert;
        $certificate[0]['date_today'] = $fecha_cert_hoy;
        $certificate[0]['student_signature'] = asset('signatures/'.$certificate[0]['enrollment_number'].'/'. $certificate[0]['signature_path_student']);
        $certificateF = $certificate[0];
        // dd($certificate[0]->course_name);
        $pdf = PDF::loadView('admin.certificates.viewcertificate',$certificateF)
                ->setOption('page-size','A4')
                ->setOption('images', true)
                ->setOption('margin-left',10)
                ->setOption('margin-top',20)
                ->setOption('margin-right',10)
                ->setOption('margin-bottom',1)
                ->setOption('orientation','landscape');
                // ->setOption('footer-html', $footer);
        return $pdf->inline('certificado.pdf');
    }

    /**
    Enviar Certificado
     */

     public function sendCert($crypt_cert_id)
     {
        $footer = '<div style="text-align: right;color:gray;"><br><p> Libro N libro Registro N registro </p></div>';
        $cert_id = decrypt($crypt_cert_id);
        $certificate = Certificate::from ('certificates as cert')
                        ->leftJoin('contracts as c','cert.contract_id','c.id')
                        ->leftJoin('companies as com','c.company_id','com.id')
                        ->leftJoin('provinces as compro','com.province_id','compro.id')
                        ->leftJoin('courses as crse','c.course_id','crse.id')
                        ->leftJoin('people_inf as pi','c.person_id','pi.id')
                        ->Where('c.id','=',$cert_id)
                        ->select('cert.*','c.enrollment_number as enrollment_number'
                                ,'c.signature_path as signature_path_student'
                                ,'pi.name as name','pi.last_name as last_name','pi.mail as student_email'
                                ,'pi.dni as dni','crse.program as program'
                                ,'c.id as contract_id','crse.name as course_name'
                                ,'com.name as company_name','com.leg_rep_full_name as ceo'
                                ,'com.address as com_address','com.town as com_town','compro.name as com_province'
                                ,'com.postal_code as com_postal_code','com.leg_rep_nif as leg_rep_nif'
                                ,'crse.duration as duration','c.created_at as contract_date'
                                ,'crse.area_id as area_id','crse.type_id as type_id')
                        ->get();
        $certificate->toArray();
        // Carbon::setLocale('es');
        // dd($certificate);
        $fecha = Carbon::parse($certificate[0]['contract_date']);
        $fechahoy = Carbon::now();
        $date = $fecha->locale(); //con esto revise que el lenguaje fuera es 
        $fecha_cert =$fecha->day.' de '. $fecha->monthName.' de '  .$fecha->year; 
        $fecha_cert_hoy =$fechahoy->day.' de '. $fechahoy->monthName.' de '  .$fechahoy->year; 
        $certificate[0]['date_letter'] = $fecha_cert;
        $certificate[0]['date_today'] = $fecha_cert_hoy;
        $certificate[0]['client_full_name'] = $certificate[0]['name'] . ' '. $certificate[0]['last_name'];
        $certificate[0]['student_signature'] = asset('signatures/'.$certificate[0]['enrollment_number'].'/'. $certificate[0]['signature_path_student']);
        $certificateF = $certificate[0];
         $pdf = PDF::loadView('admin.certificates.viewcertificate',$certificateF)
         ->setOption('page-size','A4')
         ->setOption('images', true)
         ->setOption('margin-left',10)
         ->setOption('margin-top',20)
         ->setOption('margin-right',10)
         ->setOption('margin-bottom',1)
         ->setOption('orientation','landscape');
         $pdf_data = $pdf->output();
        //  dd($certificate[0]);
         $certificate[0]['attachment'] = $pdf_data;
         Mail::to($certificate[0]['student_email'])
            ->bcc(['info@grupoefp.com','systemskcd@gmail.com'])
            ->send(new sendCertificate($certificate[0]));
         
     }
}
