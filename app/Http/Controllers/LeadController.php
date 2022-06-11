<?php

namespace App\Http\Controllers;

use App\Models\{Lead,LeadStatus,LeadSubStatus,Course,Role,Company,AccessCompany,User};
use Illuminate\Http\Request;
use DataTables,Auth,Redirect,Response,Config,DB,Validator,PDF,File,Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use Excel;
use App\Mail\sendDossier as sendDossier;
use App\Mail\sendDossierMatBonf as sendDossierMatBonf;

class LeadController extends Controller
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
             // (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : Course::all()->pluck('id');
             $user = Auth::user();
  
             // dd($user->permissions->toArray()[0]['id']);
             $companies = User::find($user->id)->companies;
            //  dd($companies);
             $filter_list = LEAD::from ('leads as ldf')
                         ->leftJoin('users as usf', 'ldf.agent_id', '=','usf.id')
                         ->leftJoin('courses as crse', 'ldf.course_id', '=','crse.id')
                         ->select('ldf.dt_assignment','ldf.dt_reception', 'ldf.course_id as course_id_filter'
                             ,'ldf.province_id as province_id_filter','ldf.lead_sub_status_id as lead_sub_status_id_filter'
                             ,'ldf.agent_id as agent_id_filter','ldf.prev_agent_id as prev_agent_list_filter'
                             ,'ldf.lead_status_id as lead_status_id_filter','ldf.lead_type_id as lead_type_id_filter'
                             ,'crse.area_id as course_area_id_filter','ldf.leads_origin_id as lead_origin_id_filter'
                             ,'ldf.dt_call_reminder'
                         )
                         ->orderByRaw(DB::Raw("ldf.created_at, ldf.id desc"))
                         ->get();
             // dd($filter_list->pluck('course_id_filter')->unique());
             // dd($_GET["dt_assignment_to"]);
             $filter_lead_id = (!empty($_GET["filter_lead_id"])) ? ($_GET["filter_lead_id"]) : null;

             $dt_assignment_from = (!empty($_GET["dt_assignment_from"])) ? ($_GET["dt_assignment_from"]) : $filter_list->min('dt_assignment');
             $dt_assignment_to = (!empty($_GET["dt_assignment_to"])) ? ($_GET["dt_assignment_to"]) : $filter_list->max('dt_assignment');
             $dt_reminder_from = (!empty($_GET["dt_reminder_from"])) ? ($_GET["dt_reminder_from"]) : NULL;
             $dt_reminder_to = (!empty($_GET["dt_reminder_to"])) ? ($_GET["dt_reminder_to"]) : NULL;
             $agent_list_filter = (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : $filter_list->pluck('agent_id_filter')->unique();
             $lead_status_filter = (!empty($_GET["lead_status_filter"])) ? ($_GET["lead_status_filter"]) : $filter_list->pluck('lead_status_id_filter')->unique();
             $courses_list_filter = (!empty($_GET["courses_list_filter"])) ? ($_GET["courses_list_filter"]) : $filter_list->pluck('course_id_filter')->unique();
             $provinces_list_filter = (!empty($_GET["provinces_list_filter"])) ? ($_GET["provinces_list_filter"]) : $filter_list->pluck('province_id_filter')->unique();
             $lead_origin_filter = (!empty($_GET["lead_origin_list_filter"])) ? ($_GET["lead_origin_list_filter"]) : $filter_list->pluck('lead_origin_id_filter')->unique();
             /**SIN USAR
                $lead_type_list_filter = (!empty($_GET["lead_type_list_filter"])) ? ($_GET["lead_type_list_filter"]) : $filter_list->pluck('lead_type_id_filter')->unique();
                $course_area_list_filter = (!empty($_GET["course_area_list_filter"])) ? ($_GET["course_area_list_filter"]) : $filter_list->pluck('course_area_id_filter')->unique(); 
             */
            //  dd($lead_origin_filter);
             if ($user->hasRole('superadmin')){
                 // DB::enableQueryLog();
                 $lead = Lead::from('leads as le')
                         ->leftJoin('courses as crse','le.course_id','=','crse.id')
                         ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                         ->leftJoin('provinces as prov', 'le.province_id', '=', 'prov.id')
                         ->leftJoin('countries as cntr', 'le.country_id','=','cntr.id')
                         ->leftJoin('lead_status as ls', 'le.lead_status_id', '=','ls.id')
                         ->leftJoin('lead_sub_status as lss', 'le.lead_sub_status_id', '=','lss.id')
                         ->leftJoin('lead_types as lt', 'le.lead_type_id', '=','lt.id')
                         ->leftJoin('users as us', 'le.agent_id', '=','us.id')
                         ->leftJoin('people_inf as pi', 'pi.id', '=','us.person_id')
                         ->leftJoin('users as us2', 'le.prev_agent_id', '=','us2.id')
                         ->leftJoin('lead_origins as lo','le.leads_origin_id','=','lo.id')
                         ->leftJoin('lead_origin_types as lot','lo.lead_origin_type_id','=','lot.id')
                         ->WhereIn('le.course_id',$courses_list_filter)
                         ->WhereIn('le.agent_id',$agent_list_filter)
                         ->WhereIn('le.province_id',$provinces_list_filter)
                         ->WhereIn('le.lead_status_id',$lead_status_filter)
                         ->WhereIn('lo.id',$lead_origin_filter)
                         ->select('le.*','crse.name as course_name','crar.name as area_name','crse.dossier_path as dossier_path'
                                 ,'prov.name as province_name', 'cntr.name as country_name'
                                 ,'ls.name as lead_status_name', 'us.name as agent_name'
                                 ,'lss.name as lead_sub_status_name','ls.color_class as bg_color','lss.color_class as bg_color_sub'
                                 ,'lt.name as lead_type_name','ls.hexa_color as hexa_color'
                                 ,'lo.name as lead_origins_name','us2.name as prev_agent_name'
                                ,DB::raw('CONCAT(le.student_first_name, " ", le.student_last_name) as full_name'));

                 }else if($user->hasRole('admin')){

                    $lead = Lead::from('leads as le')
                                 ->leftJoin('courses as crse','le.course_id','=','crse.id')
                                 ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                                 ->leftJoin('provinces as prov', 'le.province_id', '=', 'prov.id')
                                 ->leftJoin('countries as cntr', 'le.country_id','=','cntr.id')
                                 ->leftJoin('lead_status as ls', 'le.lead_status_id', '=','ls.id')
                                 ->leftJoin('lead_sub_status as lss', 'le.lead_sub_status_id', '=','lss.id')
                                 ->leftJoin('users as us', 'le.agent_id', '=','us.id')
                                 ->leftJoin('users as us2', 'le.prev_agent_id', '=','us2.id')
                                 ->leftJoin('lead_origins as lo', 'le.leads_origin_id','=','lo.id')
                                 ->leftJoin('lead_origin_types as lot','lo.lead_origin_type_id','=','lot.id')
                                 ->leftJoin('people_inf as pi', 'us.person_id', '=','pi.id')
                                 ->whereIn('pi.company_id',$companies->pluck('id'))
                                 ->WhereIn('le.course_id',$courses_list_filter)
                                 ->WhereIn('le.province_id',$provinces_list_filter)
                                 ->WhereIn('le.lead_status_id',$lead_status_filter)
                                 ->WhereIn('lo.id',$lead_origin_filter)
                                 // ->whereBetween('le.dt_assignment', [$dt_assignment_from, $dt_assignment_to])
                                 ->select('le.*','crse.id as course_id','crse.name as course_name','pi.name as emp_name','crar.name as area_name','crse.dossier_path as dossier_path'
                                     ,'prov.name as province_name', 'cntr.name as country_name'
                                     ,'lss.name as lead_sub_status_name','ls.color_class as bg_color','lss.color_class as bg_color_sub'
                                     ,'ls.name as lead_status_name', 'us.name as agent_name','ls.hexa_color as hexa_color'
                                     ,'lo.name as lead_origins_name','us2.name as prev_agent_name'
                                     ,DB::raw('CONCAT(le.student_first_name, " ", le.student_last_name) as full_name'));

                 }else{
                     $lead = Lead::from('leads as le')
                                 ->where('agent_id','=',$user->id) //comercial
                                 ->leftJoin('courses as crse','le.course_id','=','crse.id')
                                 ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                                 ->leftJoin('provinces as prov', 'le.province_id', '=', 'prov.id')
                                 ->leftJoin('countries as cntr', 'le.country_id','=','cntr.id')
                                 ->leftJoin('lead_status as ls', 'le.lead_status_id', '=','ls.id')
                                 ->leftJoin('lead_sub_status as lss', 'le.lead_sub_status_id', '=','lss.id')
                                 ->leftJoin('users as us', 'le.agent_id', '=','us.id')
                                 ->leftJoin('users as us2', 'le.prev_agent_id', '=','us2.id')
                                 ->leftJoin('lead_origins as lo', 'le.leads_origin_id','=','lo.id')
                                 ->leftJoin('lead_origin_types as lot','lo.lead_origin_type_id','=','lot.id')
                                 ->leftJoin('people_inf as pi', 'us.person_id', '=','pi.id')
                                 ->whereIn('pi.company_id',$companies->pluck('id'))
                                 ->WhereIn('le.course_id',$courses_list_filter)
                                 ->WhereIn('le.province_id',$provinces_list_filter)
                                 ->WhereIn('le.lead_status_id',$lead_status_filter)
                                 ->WhereIn('lo.id',$lead_origin_filter)
                                 // ->whereBetween('le.dt_assignment', [$dt_assignment_from, $dt_assignment_to])
                                 ->select('le.*','crse.id as course_id','crse.name as course_name','pi.name as emp_name','crar.name as area_name','crse.dossier_path as dossier_path'
                                     ,'prov.name as province_name', 'cntr.name as country_name'
                                     ,'lss.name as lead_sub_status_name','ls.color_class as bg_color','lss.color_class as bg_color_sub'
                                     ,'ls.name as lead_status_name', 'us.name as agent_name','ls.hexa_color as hexa_color'
                                     ,'lo.name as lead_origins_name','us2.name as prev_agent_name'
                                     ,DB::raw('CONCAT(le.student_first_name, " ", le.student_last_name) as full_name'));
                                 // ->orderByRaw(DB::Raw("le.created_at, le.id desc"));
                     // dd(DB::getQueryLog());
                     // dd($lead);
                 }
                //  dd($lead->get());
                //Para ver si filtro por fecha de llamar o asignación
                if($dt_reminder_from == NULL && $dt_reminder_to == NULL){
                    $lead->whereBetween('le.dt_assignment', [$dt_assignment_from, $dt_assignment_to]);
                }
                else{
                    $lead->whereBetween('le.dt_call_reminder', [$dt_reminder_from, $dt_reminder_to]);
                    $lead->whereIn('le.lead_status_id',['10','26']);
                }
                // Extraigo el comercial anterior dependiendo de si viene null o no, si es null entonces no lo filtro, sino lo filtro
                // con la siguiente funcion
                $prev_agent_list_filter = (!empty($_GET["prev_agent_list_filter"])) ? ($_GET["prev_agent_list_filter"]) : null;
                $lead_sub_status_list_filter = (!empty($_GET["lead_sub_status_filter"])) ? ($_GET["lead_sub_status_filter"]) : null;
                $lead_sub_origin_list_filter = (!empty($_GET["lead_sub_origin_list_filter"])) ? ($_GET["lead_sub_origin_list_filter"]) : null;
                if (!is_null($prev_agent_list_filter)){
                    $lead->WhereIn('le.prev_agent_id',$prev_agent_list_filter);
                }
                if (!is_null($lead_sub_status_list_filter)){
                    $lead->WhereIn('le.lead_sub_status_id',$lead_sub_status_list_filter);
                }
                // dd($lead_sub_origin_list_filter);
                if (!is_null($lead_sub_origin_list_filter)){
                    $lead->WhereIn('le.leads_origin_id',$lead_sub_origin_list_filter);
                }
                if(!is_null($filter_lead_id)){    
                    $lead->where('le.id','=',$filter_lead_id);
                }
                 if ($request->ajax()) {
                     // dd($lead->first());
                     // dd(DataTables::of($lead)->make(true));
                     return DataTables::of($lead)
                            ->addColumn('crypt_lead_id',function($row){
                                return encrypt($row->id);
                            })
                            ->addColumn('acciones', function($row){
                                //Aceptado, Pend.Acept, Matriculado
                                $statusAllowed = array(4,9,11,12);
                                $leadInContracts = Lead::from('leads as le')
                                                    ->leftJoin('contracts as c','c.lead_id','=','le.id')
                                                    ->where('c.lead_id','=',$row->id)
                                                    ->select('le.id')
                                                    ->get()->pluck('id');
                                // dd($leadInContracts[0]);
                                if($leadInContracts->isEmpty()){
                                    $leadInContracts = array(null);
                                }
                                // dd($leadInContracts[0]);
                                $user = Auth::user();
                                $btn ='<div class="dropdown show" align="center">
                                    <a  data-disabled="true" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fad  fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">'; 
                                if($row->student_mobile[0] != '+'){
                                    $urlPhone = '+34'.$row->student_mobile;
                                }
                                else {
                                    $urlPhone = $row->student_mobile;
                                }
                                $btn = $btn. '<a class="dropdown-item sendWhatsappTemplates"  data-phone="'.$urlPhone.'" href="javascript:void(0)"><i class="fab fa-whatsapp  fa-fw fa-sm mr-1 text-lightblue"></i> Enviar Plantillas Whatsapp</a>';

                                if(($row->lead_status_id <>4) && ($row->id <>$leadInContracts[0])){
                                    $urlAgentName = $row->agent_name;
                                    $urlProfile = urlencode($row->profile_url);
                                    $urlStudentName = $row->name;
                                    $urlCourse = $row->course_name;
                                    $urlcomicweb = 'https://tepuedeinteresar.com/grupoefpcursonarrativaeilustracion';
                                    
                                    $urlComic ='https://wa.me/'.$urlPhone.'?text=Hola%20soy%20'.$urlAgentName.'%20de%20GrupoEFP%0A%0ATe%20he%20llamado%20para%20asegurarme%20de%20que%20te%20ha%20llegado%20el%20enlace%20a%20la%20masterclass%20del%20pasado%20miércoles%2014%20de%20Julio%20de%20'.$urlCourse.'%20con%20el%20profesor%20Chelui.%0A%0AY%20recordarte%20que%20en%20esta%20masterclass%20tienes%20un%20gran%20regalo%20para%20ti!%20Además,%20miles%20de%20cómics%20gratuitos%20te%20esperan!!%0A'.$urlcomicweb.'%0AUn%20saludo%2C%20'.$urlAgentName;
                                    if($row->course_id ==60){
                                        $btn = $btn. '<a class="dropdown-item" target="_blank" href="'.$urlComic.'"><i class="fad fa-mobile  fa-fw fa-sm mr-1 text-lightblue"></i> Whatsapp Cómic</a>';
                                    }
                                    $urlrecordApert ='https://wa.me/'.$urlPhone.'?text=Hola%20%0A%0ASoy%20'.$urlAgentName.',%20Orientador%20de%20Formación%20de%20GrupoEFP%0A%0Ahttps%3A%2F%2Fgrupoefp.com%2F%0A%0ATe%20he%20llamado%20para%20informarte%20que%20%20abrimos%20de%20nuevo%20el%20plazo%20de%20inscripción%20%20para%20el%20'.$urlCourse.'%0A%0ASi%20no%20quieres%20quedarte%20sin%20tu%20plaza%20ponte%20en%20contacto%20conmigo.%0A%0AUn%20saludo%2C%20'.$urlAgentName;
                                    $urlCierre = 'https://wa.me/'.$urlPhone.'?text=Hola%2C%20%0Asoy%20'.$urlAgentName.'%20de%20GrupoEFP%0A%0Ahttps%3A%2F%2Fgrupoefp.com%2F%0A%0AHe%20querido%20atender%20tu%20solicitud%20para%20'.$urlCourse.'%0APero%20no%20he%20podido%20localizarte...%0A%0ASi%20todavía%20no%20estás%20matriculado%20te%20informo%20que%20esta%20semana%20se%20acaba%20el%20plazo%20de%20inscripción%20%0A%0A%20ÚLTIMAS%20PLAZAS%20con%20plan%20de%20pago%20totalmente%20personalizado%20para%20que%20elijas%20tu%20cuota%20más%20cómoda...%0A%0ASI%20NO%20QUIERES%20POSTERGARLO%20O%20DEMORARLO%20MÁS%20Y%20SI%20REALMENTE%20QUIERES%20CAMBIAR...%0A%0A%20LLÁMAME%20Y%20TE%20AYUDO%20PONERLO%20EN%20MARCHA!!';
                                    $urlText = 'https://wa.me/'.$urlPhone.'?text=Hola%20'.$urlStudentName.'%2C%20buenas%20tardes%0A%0ASoy%20'.$urlAgentName.'%20Asesoro/a%20Pedagógico/a%20de%20GrupoEFP%0A%0Ahttps%3A%2F%2Fgrupoefp.com%2F%0A%0AHe%20querido%20atender%20tu%20solicitud%20para%20'.$urlCourse.'%20pero%20no%20he%20podido%20localizarte...%0A%0AIndícame%20cuándo%20podemos%20agendar%20para%20resolver%20todas%20tus%20dudas%0A%0Ate%20dejo%20enlace%20a%20mi%20agenda%0A'.$urlProfile.'%0A%0A%20O%20si%20lo%20prefieres%20dime%20día%20y%20hora%20a%20través%20del%20whatsapp...%0A%0ASi%20has%20dejado%20de%20estar%20interesada%20también%20dímelo%20para%20cerrar%20tu%20solicitud.%0A%0A%0AUn%20saludo%2C%20'.$urlAgentName;
                                    $btn = $btn. '<a class="dropdown-item" target="_blank" href="'.$urlCierre.'"><i class="fad fa-mobile  fa-fw fa-sm mr-1 text-lightblue"></i> Whatsapp Cierre Mes</a>';
                                    $btn = $btn. '<a class="dropdown-item" target="_blank" href="'.$urlrecordApert.'"><i class="fad fa-mobile  fa-fw fa-sm mr-1 text-lightblue"></i> Whatsapp Recordatorio Apertura</a>';
                                    $btn = $btn. '<a class="dropdown-item enrollStudent" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-file-contract mr-1 text-lightblue"></i> Matricular</a>';
                                    $btn = $btn. '<a class="dropdown-item editLead"   data-object="'.encrypt($row->id).'"" href="javascript:void(0)" data-toggle="modal" data-title="Editar Lead"  ><i class="fad  fa-fw fa-sm fa-user-edit mr-1 text-lightblue"></i> Editar</a>';
                                    $btn = $btn. '<a class="dropdown-item manageLead" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-comment-alt-lines mr-1 text-lightblue" ></i> Gestionar Lead</a>';
                                    $btn = $btn. '<a class="dropdown-item" target="_blank" href="'.$urlText.'"><i class="fad fa-mobile  fa-fw fa-sm mr-1 text-lightblue"></i> Enviar Golinks</a>';
                                    if($row->dossier_path){
                                        $btn = $btn. '<a class="dropdown-item sendDossier" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-mail-bulk mr-1 text-lightblue" ></i> Enviar Dossier</a>';
                                        $btn = $btn. '<a class="dropdown-item sendDossierMatBonf" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-mail-bulk mr-1 text-lightblue" ></i> Enviar Dossier y Oferta Bonificación</a>';
                                    }
                                    else{
                                        $btn = $btn. '<a class="dropdown-item sendDossier disabled" style="cursor: not-allowed;" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-times mr-1 text-red" ></i> Enviar Dossier</a>';
                                        $btn = $btn. '<a class="dropdown-item sendDossierMatBonf disabled" style="cursor: not-allowed;" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-times mr-1 text-red" ></i> Enviar Dossier y Oferta Bonificación</a>';
                                    }
                                } 
                                
                                if(in_array($row->lead_status_id,$statusAllowed)){
                                    $btn = $btn. '<a class="dropdown-item" data-object="'.encrypt($row->id).'"" href="'.url('contractcrud').'?lead_id_request='.encrypt($row->id).'"><i class="fad fa-clipboard-check fa-fw fa-sm mr-1 text-lightblue"></i> Gestionar Matrícula</a>';
                                    $btn = $btn. '<a class="dropdown-item storyLead" data-object="'.encrypt($row->id).'"" href="javascript:void(0)"><i class="fad  fa-fw fa-sm fa-comment-alt-lines mr-1 text-lightblue" ></i> Histórico de Notas</a>';
                                }
                                $btn = $btn . '</ul></div>';
                                $btn = $btn .'</ul></li></ul>';
                                return $btn;
                            })
                            // ->rawColumns(['acciones','checkbox'])
                            ->rawColumns(['acciones'])
                            ->make(true);
                     }
                 //si es comercial devuelve las llamadas
                 $call_reminder_leads = $lead->whereIn('le.lead_status_id',[5,3,8,13,14])
                                             ->whereDate('le.dt_call_reminder','=',Carbon::now()->toDateString())
                                             ->whereAgentId($user->id)
                                             ->select('le.dt_call_reminder','le.student_first_name','ls.name as lead_status_name'
                                                     ,'ls.color_class as bg_color_class','le.id as lead_id'
                                                     ,'le.student_last_name','le.student_mobile','crse.name as course_name','lss.name as lead_sub_status_name','lss.color_class as sub_status_color'
                                                     , DB::raw("DATE_FORMAT(le.dt_call_reminder, '%d/%m/%Y') as reminder_date")
                                                     , DB::raw("DATE_FORMAT(le.dt_call_reminder, '%T') as reminder_hour")
                                                     )
                                             ->orderBy('le.dt_call_reminder', 'ASC')
                                             ->get()->toArray();
                 //Llamadas de 30 días o mas
                 $call_reminder_leads_old = Lead::from('leads as le')
                                             ->leftJoin('lead_status as ls','le.lead_status_id','=','ls.id')
                                             ->leftJoin('lead_sub_status as lss','le.lead_sub_status_id','=','lss.id')
                                             ->leftJoin('courses as crse','le.course_id','=','crse.id')
                                             ->whereIn('le.lead_status_id',[5,3,8,13,14])
                                             ->whereRaw('NOW() > le.dt_call_reminder')
                                             ->whereAgentId($user->id)
                                             ->select('le.dt_call_reminder','le.student_first_name'
                                                     ,'ls.color_class as bg_color_class','le.id as lead_id','lss.name as lead_sub_status_name'
                                                     ,'le.student_email','ls.name as lead_status_name','lss.color_class as sub_status_color'
                                                     ,'le.student_last_name','le.student_mobile','crse.name as course_name'
                                                     , DB::raw("DATE_FORMAT(le.dt_call_reminder, '%d/%m/%Y') as reminder_date")
                                                     , DB::raw("DATE_FORMAT(le.dt_call_reminder, '%T') as reminder_hour")
                                                     )
                                             ->orderBy('le.dt_call_reminder', 'DESC')
                                             ->get()->toArray();
                 // dd($call_reminder_leads_old);
                 $pageTitle ='Leads - '.$companies->pluck('name')[0];
                //  dd($companyName);
                 if($user->hasRole('comercial')){
                     // dd($call_reminder_leads);
                     return view('leads.index',compact('call_reminder_leads','call_reminder_leads_old','pageTitle'));
                 }else{
                     return view('leads.index',compact('call_reminder_leads','call_reminder_leads_old','pageTitle'));
                 }
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
      public function makecontract($id)
     {
         return "hola:".$id;
     }
     function massremove(Request $request)
     {
         // dd($request);
         $lead_id_array = $request->input('lead_id');
         $leads = Lead::whereIn('id', $lead_id_array);
         if($leads->delete()){
              return response()->json(['success'=>'Registros eliminados correctamente.']);
         }
     }
     function massassign(Request $request)
     {
        //  dd($request->input('lead_id'));
         $lead_id_array_aux = $arr=explode(",",$request->input('lead_id'));
         $lead_id_array = [];
         foreach ($lead_id_array_aux as $item) {
            array_push($lead_id_array, decrypt($item));
         }
        //  dd($lead_id_array);
         // DB::enableQueryLog();
         $leadsToUpdate = Lead::whereIn('id', $lead_id_array)->get();
         $dt_assign_massive = ($request->dt_assignment_massive) ? Carbon::parse($request->dt_assignment_massive)->format('Y-m-d H:i:s') : Carbon::now()->toDateTimeString() ;
         // dd(DB::getQueryLog());
         // dd($leadsToUpdate);
         foreach ($leadsToUpdate as $item => $lead) {
             // dd($request);
             $dataToUpdate  = [
                 'lead_status_id'        => 1,
                 'lead_sub_status_id'    => null, //se vacia el subestado
                 // 'dt_reception'       => $request->dt_reception_assign_list,
                 'dt_assignment'         => $dt_assign_massive ,
                 // 'leads_origin_id'    => $request->lead_origins_assign_list[0],
                 'agent_id'              => (int)$request->leads_agent_assign_list[0],
                //  'lead_type_id'          => $request->leads_type_assign_list[$item],
                 'dt_last_update'        => null, //se vacia la útima modificación porque para el comercial será "un nuevo estado"
                 'dt_call_reminder'      => null, //se vacia la fecha de recordación llamadaporque para el comercial será "un nuevo estado"
                 'prev_agent_id'         => $lead->agent_id                
             ];
            //  dd($dataToUpdate);
             if($lead->prev_agent_id == null || $lead->prev_agent_id == 7){
                Lead::updateOrCreate(
                    ['id' => (int)$lead->id],
                    $dataToUpdate
                ); 
             }
             else {
                $dataToUpdate['lead_type_id'] = 2;
                Lead::updateOrCreate(
                    ['id' => (int)$lead->id],
                    $dataToUpdate
                );
             }
         }
         return response()->json(['success'=>'Registros asignados correctamente.']);
     }
 
     public function generateProformPDF($con_id_crypeted)
     {
         $contract_id = decrypt($con_id_crypeted);
         
         $contract = Lead::from('leads as le')
         ->where('le.id', '=',(int)$contract_id)
         ->leftJoin('courses as crse', 'le.course_id', '=','crse.id')
         ->leftJoin('users as us','le.agent_id','=','us.id')
         ->select('le.*','crse.name as course_name','us.name as agent_name')
         ->orderByRaw(DB::Raw('c.dt_created, c.id desc'))
         ->get();
                 
         // dd($contract[0]->dt_created);
 
         //Terminos y Condiciones
         $cuenta_banco = $contract[0]->bank_account.",  de ".$contract[0]->bank_name;
         $marca_empresa = $contract[0]->company_legal_name;
         $direccion_desestimacion = $contract[0]->company_address.' - '.$contract[0]->company_pc.', '.$contract[0]->company_town .' - '.$contract[0]->company_province;
         $nombre_empresa =$contract[0]->company_legal_name;
         $fecha = $contract[0]->dt_created;
         $result = array();
         $condi = "";
         $dirurl = "";
         switch($contract[0]->contract_type_id){
             case 1: //Cursos
                 $dirurl = 'storage/termsnconditions/'.$contract[0]->company_id.'/';
                 break;
             case 2: //Prácticas
                 $dirurl = 'storage/termsnconditions/internships/'.$contract[0]->company_id.'/';
                 break;
             default:
                 $dirurl = 'storage/termsnconditions/'.$contract[0]->company_id.'/';
                 break;  
         }
         $cdir = scandir(public_path($dirurl));
         foreach ($cdir as $key => $value){
             if (!in_array($value,array(".",".."))){
                 $result[] = rtrim($value,".txt");
             }
         }
         rsort($result);
         $bandera = $result[0];
         // dd($bandera<=$fecha);
         if ($bandera<=$fecha){
             $condi = $bandera;
         }else{
             foreach ($result as $resu => $valu){
                 if ($fecha>=$valu and $bandera>=$fecha){
                     $condi = $valu;
                 }
             $bandera=$valu;	
             }  
         }
         // dd($bandera);
         if ($condi == ""){
             $tcondurl = $dirurl.$bandera.".txt";
         }else{
             $tcondurl = $dirurl.$condi.".txt";
         }
         // dd($tcondurl);
         $contents = fopen(asset($tcondurl),'r');
         $auxConditions="";
         while(!feof($contents)) {
             $auxConditions= $auxConditions . fgets($contents);
         }
         fclose($contents);
         //segmentamos por los delimitadores de texto
         $busca = explode("|",$auxConditions);
 
         $conditions = "";
         foreach($busca as $b){
             switch($b){
                 case "cuenta_banco":
                     $conditions.=$cuenta_banco;
                     break;
                 case "marca_empresa":
                         $conditions.=$marca_empresa;
                         break;
                 case "direccion_desestimacion":
                     $conditions.=$direccion_desestimacion;
                     break;
                 case "nombre_empresa":
                     $conditions.=$nombre_empresa;
                     break;
                 default:
                     $conditions.=$b;
             }
         }
 
 
         $contract[0]['conditions'] = $conditions;
         $contract[0]['accept_com'] = '';
         $contract[0]['accept_third_com'] = '';
         switch($contract[0]->contract_type_id){
             case 1: //Cursos
                 $pdf = PDF::loadView('viewcontractpdf',$contractF);
                 break;
             case 2: //Prácticas
                 $pdf = PDF::loadView('viewcontractinternpdf',$contractF);
                 break;
             default:
                 $conditions.=$b;
         }
         $pdf->setOption('margin-left',5);
         $pdf->setOption('margin-right',5);
         $pdf->setPaper('a4');
         // dd($pdf);
         return $pdf->inline('contrato.pdf');
         
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
             'modal_courses_list' => ['required'],
             'first_name' => ['required','string'],
             'last_name' => ['required','string'],
             'email' => ['required','email'],
             'mobile' => ['required','regex:/[0-9+]{9,13}/'],
             'modal_provinces_list' =>  ['required'],
             'countries_list' =>  ['required'],
             'leads_origins_list' =>  ['required']
         ];
         $customMessages = [
             'modal_courses_list.required' => 'El curso es obligatorio',
             'first_name.required' => 'El nombre es obligatorio',
             'first_name.string' => 'El nombre debe contener solo texto',
             'last_name.required' => 'El apellido es obligatorio',
             'last_name.string' => 'El apellido debe contener solo texto',
             'email.required' => 'El email es obligatorio',
             'email.email' => 'El email debe tener el formato correcto',
             'mobile.required' => 'El móvil es obligatorio',
             'mobile.regex' => 'El formato del télefono es incorrecto, debe usar: +34,0034 ó 678912345',
             'modal_provinces_list.required' => 'La provincia es obligatorio',
             'countries_list.required' => 'El país es obligatorio',
             'leads_origins_list.required' => 'El origen es obligatorio'
 
             
         ];
 
         $validator = Validator::make($request->all(),$rules,$customMessages);
         
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
             // dd($request->filled('lead_id'));
             /**
             Validación para saber si esta editando o creando un nuevo lead
             */
             if($request->filled('lead_id')){
                 $prev_agent_id = null;
                 if (decrypt($request->lead_id)){
                     $prev_agent_id = Lead::whereId(decrypt($request->lead_id))->first()->agent_id;
                 }
                 // dd($request->dt_last_update);
                 $dt_reception = $request->dt_reception_edit ?? $request->lead_dt_reception_hidden;
                 $dt_assignment = $request->dt_assignment_edit ?? $request->lead_dt_assignment_hidden;
                 $dt_last_update = $request->dt_last_update ?? $request->lead_dt_last_update_hidden;
                 (int)$agent_id = $request->agents_list[0] ?? $request->lead_agent_id_hidden ;
                 (int)$leads_origin_id = $request->leads_origins_list[0] ?? $request->lead_origin_id_hidden ;
                 (int)$lead_type_id = $request->leads_types_list[0] ?? $request->lead_type_id_hidden ;
                 (int)$lead_status_id = $request->lead_status_id_hidden ?? 1 ;
                 (int)$lead_sub_status_id = $request->lead_sub_status_id_hidden?? NULL;
                 Lead::updateOrCreate(
                     ['id' => decrypt($request->lead_id)],
                     ['dt_reception' => $dt_reception, 'dt_assignment'=> $dt_assignment, 'dt_last_update' => $dt_last_update,
                     'course_id' => (int)$request->modal_courses_list, 'student_first_name' => $request->first_name,  'student_last_name' => $request->last_name,
                     'student_mobile' => $request->mobile, 'student_email' => $request->email, 'dt_enrollment' => $request->dt_enrollment, 'dt_payment'=>$request->dt_payment,
                     'student_dt_birth' => $request->dt_birth,'student_laboral_situation' => $request->laboral_situation,'province_id' => (int)$request->modal_provinces_list[0],
                     'country_id' => (int)$request->countries_list[0],
                     'lead_status_id' => $lead_status_id,'lead_sub_status_id' => $lead_sub_status_id,
                     'lead_type_id'=>$lead_type_id,
                     'observations' => $request->observations,'int_observ' => $request->int_observ,
                     'prev_agent_id' => $prev_agent_id,'agent_id' => $agent_id, 'leads_origin_id' => $leads_origin_id
                     ]
                 );    
                 //Actualiza el lastLoginat
                 //Actualiza última conexión
                 Auth::user()->update_lastlogin(); 
                 return response()->json(['success'=>'Lead creado correctamente.']);
             }
             /**
             Sino esta editando, se inserta el comercial de origen
              */
             else{
                $leadsMatchThese = ['student_first_name' => $request->first_name, 'student_last_name' => $request->last_name
                                , 'student_mobile' => $request->mobile , 'student_email' => $request->email ]; 
            
                $infoLeadDuplicated = Lead::from('leads as le')
                            ->where($leadsMatchThese)
                            ->whereNotExists(function ($query) {
                                $query->select("c.*")
                                    ->from('contracts as c')
                                    ->whereIn('c.contract_status_id',[2,3])
                                    ->whereRaw('c.lead_id = le.id');
                                })
                            ->select('le.*')
                            ->orderByRaw(DB::Raw("le.created_at desc"))
                            ->get();
        
                if(!$infoLeadDuplicated->isEmpty()){
                    //Actualiza última conexión
                    Auth::user()->update_lastlogin();
                    return response()->json(['error'=>['Error, El lead ya existe en nuestra base de datos']]);
                }   
                else{
                    // dd($request->dt_last_update);
                    $dt_reception = $request->dt_reception_edit ?? $request->lead_dt_reception_hidden;
                    $dt_assignment = $request->dt_assignment_edit ?? $request->lead_dt_assignment_hidden;
                    $dt_last_update = $request->dt_last_update ?? $request->lead_dt_last_update_hidden;
                    (int)$agent_id = $request->agents_list[0] ?? $request->lead_agent_id_hidden ;
                    (int)$leads_origin_id = $request->leads_origins_list[0] ?? $request->lead_origin_id_hidden ;
                    (int)$lead_type_id = $request->leads_types_list[0] ?? $request->lead_type_id_hidden ;
                    (int)$lead_status_id = $request->lead_status_id_hidden ?? 1 ;
                    (int)$lead_sub_status_id = $request->lead_sub_status_id_hidden?? NULL;
                    // dd($lead_status_id);
                    Lead::updateOrCreate(
                        ['id' => null],
                        ['dt_reception' => $dt_reception, 'dt_assignment'=> $dt_assignment, 'dt_last_update' => $dt_last_update,
                        'course_id' => (int)$request->modal_courses_list, 'student_first_name' => $request->first_name,  'student_last_name' => $request->last_name,
                        'student_mobile' => $request->mobile, 'student_email' => $request->email, 'dt_enrollment' => $request->dt_enrollment, 'dt_payment'=>$request->dt_payment,
                        'student_dt_birth' => $request->dt_birth,'student_laboral_situation' => $request->laboral_situation,'province_id' => (int)$request->modal_provinces_list[0],
                        'country_id' => (int)$request->countries_list[0],
                        'lead_status_id' => $lead_status_id,'lead_sub_status_id' => $lead_sub_status_id,
                        'lead_type_id'=>$lead_type_id,
                        'observations' => $request->observations,'int_observ' => $request->int_observ,
                        'agent_id' => $agent_id, 'original_agent_id' => $agent_id, 'leads_origin_id' => $leads_origin_id
                        ]
                    );        
                    //Actualiza última conexión
                    Auth::user()->update_lastlogin();
                    return response()->json(['success'=>'Lead creado correctamente.']);
                } 
             }
         }
         
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
     public function edit($id_aux)
     {
         //TryCatch para detectar si viene encriptado el id o no.. Sino viene encriptado,viene del calendario
        try {
            $id =decrypt($id_aux);
            $edit = Lead::from('leads as le')
                    ->where('le.id','=',$id)
                    ->leftJoin('courses as crse','le.course_id','=','crse.id')
                    ->leftJoin('provinces as prov', 'le.province_id', '=', 'prov.id')
                    ->leftJoin('countries as cntr', 'le.country_id','=','cntr.id')
                    ->leftJoin('lead_status as ls', 'le.lead_status_id', '=','ls.id')
                    ->leftJoin('lead_sub_status as lss', 'le.lead_sub_status_id', '=','lss.id')
                    ->leftJoin('users as us', 'le.agent_id', '=','us.id')
                    ->leftJoin('users as prev', 'le.prev_agent_id', '=','prev.id')
                    ->leftJoin('lead_origins as lo','le.leads_origin_id','=','lo.id')
                    ->leftJoin('people_inf as pi', 'us.person_id', '=','pi.id')
                    ->leftJoin('companies as com', 'pi.company_id','=','com.id')
                    ->select('le.*','crse.name as course_name','pi.name as emp_name'
                            ,'prov.name as province_name', 'cntr.name as country_name'
                            ,'ls.name as lead_status_name', 'ls.color_class as bg_color','us.name as agent_name'
                            ,'lo.name as lead_origins_name','lss.name as lead_sub_status_name'
                            ,'pi.company_id as company_id','com.name as company_name','prev.name as prev_agent')
                    ->orderByRaw(DB::Raw("le.created_at desc"))
                    ->get();
    
            return response()->json($edit);
        } catch (DecryptException $e) {
            $edit = Lead::from('leads as le')
                    ->where('le.id','=',$id_aux)
                    ->leftJoin('courses as crse','le.course_id','=','crse.id')
                    ->leftJoin('provinces as prov', 'le.province_id', '=', 'prov.id')
                    ->leftJoin('countries as cntr', 'le.country_id','=','cntr.id')
                    ->leftJoin('lead_status as ls', 'le.lead_status_id', '=','ls.id')
                    ->leftJoin('lead_sub_status as lss', 'le.lead_sub_status_id', '=','lss.id')
                    ->leftJoin('users as us', 'le.agent_id', '=','us.id')
                    ->leftJoin('users as prev', 'le.prev_agent_id', '=','prev.id')
                    ->leftJoin('lead_origins as lo','le.leads_origin_id','=','lo.id')
                    ->leftJoin('people_inf as pi', 'us.person_id', '=','pi.id')
                    ->leftJoin('companies as com', 'pi.company_id','=','com.id')
                    ->select('le.*','crse.name as course_name','pi.name as emp_name'
                            ,'prov.name as province_name', 'cntr.name as country_name'
                            ,'ls.name as lead_status_name', 'ls.color_class as bg_color','us.name as agent_name'
                            ,'lo.name as lead_origins_name','lss.name as lead_sub_status_name'
                            ,'pi.company_id as company_id','com.name as company_name','prev.name as prev_agent')
                    ->orderByRaw(DB::Raw("le.created_at desc"))
                    ->get();
    
            return response()->json($edit);
        }
         
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
 
     /**
    Enviar Dossier
     */

    public function sendDossier($con_id_crypeted)
    {
        // dd($request->drop_reasons_list[0]);
        $id = decrypt($con_id_crypeted);
        $mailData = Lead::from('leads as le')
                ->where('le.id','=',$id)
                ->leftJoin('courses as crse','le.course_id','=','crse.id')
                ->leftJoin('course_areas as crsearea','crse.area_id','=','crsearea.id')
                ->leftJoin('provinces as prov', 'le.province_id', '=', 'prov.id')
                ->leftJoin('countries as cntr', 'le.country_id','=','cntr.id')
                ->leftJoin('lead_status as ls', 'le.lead_status_id', '=','ls.id')
                ->leftJoin('lead_sub_status as lss', 'le.lead_sub_status_id', '=','lss.id')
                ->leftJoin('users as us', 'le.agent_id', '=','us.id')
                ->leftJoin('users as prev', 'le.prev_agent_id', '=','prev.id')
                ->leftJoin('lead_origins as lo','le.leads_origin_id','=','lo.id')
                ->leftJoin('people_inf as pi', 'us.person_id', '=','pi.id')
                ->leftJoin('companies as com', 'pi.company_id','=','com.id')
                ->select('le.*','crse.name as course_name','pi.name as emp_name', 'crse.dossier_path as dossier_path'
                        ,'prov.name as province_name', 'cntr.name as country_name', 'crsearea.id as crse_area'
                        ,'ls.name as lead_status_name', 'ls.color_class as bg_color','us.name as agent_name','us.email as agent_mail'
                        ,'lo.name as lead_origins_name','lss.name as lead_sub_status_name','pi.last_name as agent_lastname','pi.phone as agent_phone'
                        ,'pi.company_id as company_id','com.name as company_name','prev.name as prev_agent')
                ->orderByRaw(DB::Raw("le.created_at desc"))
                ->get()->toArray();
        // dd($mailData[0]['payer_id'] <> $mailData[0]['person_id']);
        $mailData[0]['client_full_name'] = $mailData[0]['student_first_name'] . ' '. $mailData[0]['student_last_name'];
        $mailData[0]['attachment'] = 'https://efpsales.com/storage/dossiers/'.$mailData[0]['crse_area'].'/'.$mailData[0]['dossier_path'];
        Mail::to($mailData[0]['student_email'])
            ->bcc([$mailData[0]['agent_mail'],'info@grupoefp.com','systemskcd@gmail.com'])
            ->send(new sendDossier($mailData[0]));
    }

    public function sendDossierMatBonf($con_id_crypeted)
    {
        // dd($request->drop_reasons_list[0]);
        $id = decrypt($con_id_crypeted);
        $mailDatamatBonf = Lead::from('leads as le')
                ->where('le.id','=',$id)
                ->leftJoin('courses as crse','le.course_id','=','crse.id')
                ->leftJoin('course_areas as crsearea','crse.area_id','=','crsearea.id')
                ->leftJoin('provinces as prov', 'le.province_id', '=', 'prov.id')
                ->leftJoin('countries as cntr', 'le.country_id','=','cntr.id')
                ->leftJoin('lead_status as ls', 'le.lead_status_id', '=','ls.id')
                ->leftJoin('lead_sub_status as lss', 'le.lead_sub_status_id', '=','lss.id')
                ->leftJoin('users as us', 'le.agent_id', '=','us.id')
                ->leftJoin('users as prev', 'le.prev_agent_id', '=','prev.id')
                ->leftJoin('lead_origins as lo','le.leads_origin_id','=','lo.id')
                ->leftJoin('people_inf as pi', 'us.person_id', '=','pi.id')
                ->leftJoin('companies as com', 'pi.company_id','=','com.id')
                ->select('le.*','crse.name as course_name','pi.name as emp_name', 'crse.dossier_path as dossier_path'
                        ,'prov.name as province_name', 'cntr.name as country_name', 'crsearea.id as crse_area', 'crse.pvp as course_pvp','crse.area_id as area_course'
                        ,'ls.name as lead_status_name', 'ls.color_class as bg_color','us.name as agent_name','us.email as agent_mail'
                        ,'lo.name as lead_origins_name','lss.name as lead_sub_status_name','pi.last_name as agent_lastname','pi.mobile as agent_mobile'
                        ,'pi.company_id as company_id','com.name as company_name','prev.name as prev_agent')
                ->orderByRaw(DB::Raw("le.created_at desc"))
                ->get()->toArray();
        // dd($mailData[0]['payer_id'] <> $mailData[0]['person_id']);
        $mailDatamatBonf[0]['client_full_name'] = $mailDatamatBonf[0]['student_first_name'] . ' '. $mailDatamatBonf[0]['student_last_name'];
        $mailDatamatBonf[0]['attachment'] = 'https://efpsales.com/storage/dossiers/'.$mailDatamatBonf[0]['crse_area'].'/'.$mailDatamatBonf[0]['dossier_path'];
        $mailDatamatBonf[0]['attachment1'] = 'https://efpsales.com/storage/uploads/GARANTIA_TITULO_OFICIAL.pdf';
        $mailDatamatBonf[0]['attachment2'] = 'https://efpsales.com/storage/uploads/METODOLOGIA.pdf';
        Mail::to($mailDatamatBonf[0]['student_email'])
            ->bcc([$mailDatamatBonf[0]['agent_mail'],'info@grupoefp.com','systemskcd@gmail.com'])
            ->send(new sendDossierMatBonf($mailDatamatBonf[0]));
    }

     public function importExcel(Request $request)
     {   
         $responseWarning = '';
         $responseSuccess = '';
         $responseError = '';
         if (!$request->file('fileupload')){
             return response()->json(['error'=>'Debe escoger un archivo.']);
         }
         $import = new LeadsImport();
         // dd($import->getResults());
         // if($import->getResults()){
         //     $text = array($import->getResults());
         //     return response()->json(['error'=>$text]);
         // }
         
         // $import->sheets();
         // dd($request);
         $response = Excel::import($import,$request->file('fileupload'));
         // dd($response);
         if($import->getDuplicateResultsBool()){
             // return response()->json(['warning'=>$import->getDuplicateResults()]);
             $responseWarning = ['warning'=>$import->getDuplicateResults()];
         }
         if($import->getSuccessResultsBool()){
             // return response()->json(['warning'=>$import->getDuplicateResults()]);
             $responseSuccess = ['success'=>$import->getSuccessResults()];
         }
         if($import->getErrorResultsBool()){
             // return response()->json(['warning'=>$import->getDuplicateResults()]);
             $responseError = ['error'=>$import->getErrorResults()];
         }
         
         // return view('leadimportexport')->with('success','Leads creado correctamente');
         // return redirect()->route('lead.importexport')->with('success', 'Leads creado correctamente');
         // return response()->json(['success'=>$import->getSuccessResults()]);
        
         // return back()->with('success', 'Insert Record successfully.');
 
         //Retorna todas las respuestas
         // return response()->json(['success'=>$import->getSuccessResults(),'warning'=>$import->getDuplicateResults()];
         return response()->json([$responseSuccess,$responseWarning,$responseError]);
     }
     public function importExport()
     {
         return view('leadimportexport');
     }
}
