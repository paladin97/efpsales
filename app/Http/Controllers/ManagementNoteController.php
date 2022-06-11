<?php

namespace App\Http\Controllers;

use App\Models\{EventMaster,User,Lead,ManagementNote,Contract,Role,Company,AccessCompany,LeadHistoryChange,managementNoteCategory};
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Illuminate\Support\Facades\Storage;
use PDF,File,Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagementNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($contract_id)
    {  
        $user;
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return view('auth.login');
        }
        $contract = Contract::find(decrypt($contract_id));
        if ($user->hasRole('superadmin')){
            $matchThese = ['contract_id' => decrypt($contract_id)];
        }
        else{
            //categoría 4 solo tutor
            $matchThese = ['mn.management_note_category_id' => 4, 'mn.contract_id' => decrypt($contract_id)];
        }
        $managementNotes = Contract::from('management_notes as mn')
                ->leftJoin('management_note_categories as mnc','mn.management_note_category_id','=','mnc.id')
                ->leftJoin('users as us','mn.user_id','=','us.id')
                ->leftJoin('contracts as c','mn.contract_id','=','c.id')
                ->where($matchThese)
                ->select('mn.*','mnc.name as category','us.name as user_note_name','mnc.color_class as color'
                        ,'c.enrollment_number as enrollment_number')
                ->get();
        return DataTables::of($managementNotes) 
            ->addColumn('pathnote', function($row){
                // dd($row);
                if($row->path){
                    $url = asset('storage/managementnotes/'.$row->enrollment_number.'/'. $row->path);
                    $btn= '<a class="btn btn-block bg-gradient-info btn-xs"   href="'.$url.'" target="_blank"><b><i class="fad fa-lg fa-file"></i></b> Ver Soporte</a>';
                }else{
                    $btn= '<a class="btn btn-block bg-gradient-danger btn-xs disabled"   href="javascript:void(0)" ><b><i class="fad fa-lg fa-do-not-enter"></i></b>Sin Soporte</a>';
                }
                
                return $btn;
                // return Storage::url($row->path);
            })
            ->addColumn('acciones', function($row){
                return '<a href="javascript:void(0)" class="btn  bg-gradient-danger btn-xs deleteManagementNote" data-container="body" data-id="'.$row->id.'" ><i class="fad fa-lg fa-trash-alt"></i> Eliminar</a></li>'.
                        '<a href="javascript:void(0)" class="ml-3 btn  bg-lightblue btn-xs editManagementNote" data-container="body" data-id="'.$row->id.'" ><i class="fad fa-lg fa-edit"></i> Modificar</a></li>';
            })
            ->rawColumns(['pathnote','acciones'])
            ->make(true);
    }

     public function list(Request $request){
        $user;
        if (Auth::check()) {
            $user = Auth::user();
            $companies = User::find($user->id)->companies;
            $filter_list =   ManagementNote::from('management_notes as mn')
            ->leftJoin('management_note_categories as mnc','mn.management_note_category_id','=','mnc.id')
            ->leftJoin('contracts as c','mn.contract_id','=','c.id')
            ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
            ->select('mn.*','mnc.id as category_id','crse.id as course_id_filter')
            ->get();
            
            $dt_contract_from = (!empty($_GET["dt_contract_from"])) ? ($_GET["dt_contract_from"]) : $filter_list->min('created_at');
            $dt_contract_to = (!empty($_GET["dt_contract_to"])) ? ($_GET["dt_contract_to"]) : $filter_list->max('created_at');
            // dd($dt_contract_from);
            $courses_list_filter = (!empty($_GET["courses_list_filter"])) ? ($_GET["courses_list_filter"]) : $filter_list->pluck('course_id_filter')->unique();
            $mn_contact_type = array ('Entrante','Saliente');
            $mn_contact_method = array ('Télefono','E-mail','Whatsapp','Portal');
            $mn_category_filter = (!empty($_GET["mn_category_filter"])) ? ($_GET["mn_category_filter"]) : $filter_list->pluck('category_id')->unique();
           
            $managementNotes = ManagementNote::from('management_notes as mn')
                ->leftJoin('management_note_categories as mnc','mn.management_note_category_id','=','mnc.id')
                ->leftJoin('users as us','mn.user_id','=','us.id')
                ->leftJoin('contracts as c','mn.contract_id','=','c.id')
                ->leftJoin('contract_payment_methods as cpm', 'c.contract_method_type_id','=','cpm.id')
                ->leftJoin('courses as crse', 'c.course_id', '=','crse.id')
                ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                ->whereIn('c.company_id',$companies->pluck('id'))
                ->WhereIn('crse.id',$courses_list_filter)
                ->WhereIn('mnc.id',$mn_category_filter)
                ->WhereIn('mn.contact_type',$mn_contact_type)
                ->WhereIn('mn.contact_method',$mn_contact_method)
                ->whereBetween('mn.created_at', [$dt_contract_from, $dt_contract_to])
                ->groupBy('c.enrollment_number')  
                ->select('mn.*','mnc.name as category','us.name as user_note_name'
                        ,'c.enrollment_number as enrollment_number', 'pi.name as student_name','pi.last_name as student_last_name'
                        ,'pi.mobile as mobile', 'pi.mail as mail','crse.name as course_name','cpm.name as payment_method');
            if ($request->ajax()) {
                return DataTables::of($managementNotes) 
                    ->addColumn('pathnote', function($row){
                        // dd($row);
                        if($row->path){
                            $url = asset('storage/managementnotes/'.$row->enrollment_number.'/'. $row->path);
                            // $btn= '<a class="btn btn-block btn-social btn-twitter btn-xs"   href="'.$url.'" target="_blank"><b><i class="fa fa-file"></i></b>'.$row->fee_path.'</a>';
                            $btn= '<a class="btn btn-block btn-social btn-twitter btn-xs"   href="'.$url.'" target="_blank"><b><i class="fa fa-file"></i></b>Ver Soporte</a>';
                        }else{
                            $btn= '<a class="btn btn-block btn-social btn-google btn-xs"   href="javascript:void(0)" ><b><i class="fa fa-close"></i></b>Sin Soporte</a>';
                        }
                        
                        return $btn;
                        // return Storage::url($row->path);
                    })
                    ->addColumn('crypt_contract_id', function($row){
                        return encrypt($row->contract_id);
                    })
                    ->addColumn('acciones', function($row){
                        $btn= '<ul style="margin-block-end: 0em;padding-inline-start:0px" align="center">
                                <li class="dropdown btn btn-success btn-xs" style="color:white;">
                                    <a style="color:white;padding:3px 5px;" href="#" class="dropdown-toggle" data-toggle="dropdown">Acciones &nbsp;<b class="caret"></b></a>
                                    <ul class="dropdown-menu" style="left:-80;background-color:white; color:gray;">';
                        $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="deleteManagementNote" data-container="body" data-id="'.$row->id.'" ><i class="fa fa-trash fa-xs"></i> Eliminar</a></li>';   
                        $btn = $btn. '<li><a style="padding: 2px 2px 5px 5px;" href="javascript:void(0)" class="managementNote" data-container="body" data-id="'.encrypt($row->id).'" ><i class="fa fa-clone fa-xs"></i>Gestionar Notas</a></li>';
                        $btn = $btn .'</ul></li></ul>';
                        return $btn;
                    })
                    ->rawColumns(['pathnote','acciones','crypt_contract_id'])
                    ->make(true);
            }
            else{
                return view('managementnotescrud');
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
         // dd($request);
         $rules = [
             'management_notes_method' => ['required'],
             'management_notes_type' => ['required'],
             'management_category.*' => ['required'],
             'management_notes_observation' => ['required'],
         ];
         $customMessages = [
             'management_notes_method.required' => 'El medio es obligatorio',
             'management_notes_type.required' => 'El tipo es obligatorio',
             'management_category.0.required' => 'La categoría es obligatoria',
             'management_notes_observation.required' => 'La observación es obligatoria',
             
         ];
         $validator = Validator::make($request->all(),$rules,$customMessages);
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
             // dd($request);
             $management_dt_reminder = Carbon::parse($request->management_dt_reminder)->format('Y-m-d H:i:s');
             // dd($dt_call_reminder);
             $user = Auth::user();
             $dataToSave['contract_id'] = $request->contract_id_notes;
             $dataToSave['user_id'] = $user->id;
             $dataToSave['management_note_category_id'] = $request->management_category[0];
             $dataToSave['contact_type'] = $request->management_notes_type;
             $dataToSave['contact_method'] = $request->management_notes_method;
             $dataToSave['dt_reminder'] = $management_dt_reminder;
             $dataToSave['observations'] = $request->management_notes_observation;
             if ($request->hasFile('management_file')) {
                 $path = 'managementnotes/'.$request->contract_enrollment_notes;
                 // dd(!Storage::exists($path));
                 if(!Storage::exists($path)){
                     File::makeDirectory($path, 0777, true, true);
                 }
                 $file = $request->file('management_file');
                 $filetext = $request->file('management_file')->getClientOriginalName();
                 $filename = pathinfo($filetext, PATHINFO_FILENAME);
                 $extension = pathinfo($filetext, PATHINFO_EXTENSION);
                 $fileToSave =  (str_replace(' ', '', $request->contract_id_notes)).'_'.(str_replace(' ', '', $filename)).'_1.'. $extension;
                 // dd($fileToSave);
                 $file->storeAs($path,$fileToSave, ['disk' => 'public']);
                 $dataToSave['path'] = $fileToSave;
             }
 
             $managementNote = ManagementNote::updateOrCreate(
                 ['id' => $request->id_note],
                 $dataToSave
             );
             


            //Lógica para crear el evento
            $LeadContract = Contract::find($managementNote->contract_id);
            $LeadModify = Lead::find($LeadContract->lead_id);
            $dt_call_reminder = Carbon::parse($management_dt_reminder)->format('Y-m-d H:i:s');
            $dt_call_reminder_end =  Carbon::parse($management_dt_reminder)->addHours(1)->format('Y-m-d H:i:s');

            if($request->management_category[0]== 4 || $request->management_category[0] == 9){//Estados que requieren crear un evento
                //Primero Borro todas las notas creadas de ese Lead
                EventMaster::whereLeadId($LeadContract->lead_id)->delete();

                EventMaster::updateOrCreate(
                    ['id' => $request->event_id],
                    ['user_id' => $user->id, 'lead_id'=> $LeadContract->lead_id
                    ,'type_id' => 2
                    ,'title' => '['.$LeadModify->student_first_name . ' '.$LeadModify->student_last_name.'] '.
                                    $request->management_notes_observation
                    ,'start' => $dt_call_reminder, 'end' => $dt_call_reminder_end
                    , 'text_color' => '#FFFFFF'
                    ,'allDay' => 0,]
                );
            }
            //Actualiza última conexión
            Auth::user()->update_lastlogin();
            return response()->json(['success'=>'Nota creada correctamente.']);
         }
 
         
         
     }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ManagementNote  $managementNote
     * @return \Illuminate\Http\Response
     */
    public function show(ManagementNote $managementNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ManagementNote  $managementNote
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
     {
         $managementNote = ManagementNote::find($id);
         return response()->json($managementNote);
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ManagementNote  $managementNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManagementNote $managementNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ManagementNote  $managementNote
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
     {
         ManagementNote::find($id)->delete();
         return response()->json(['success'=>'Registro eliminado correctamente.']);
     }
}
