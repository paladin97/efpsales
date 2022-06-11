<?php

namespace App\Http\Controllers;

use App\Models\{User,Lead,LeadStatus,Role,Company,EventMaster,AccessCompany,LeadNote,LeadHistoryChange,LeadNoteCategory};
use Illuminate\Http\Request;
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LeadNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index($lead_id)
     {
         
         if (Auth::check()) {
            $user = Auth::user();
            //TryCatch para detectar si viene encriptado el id o no.. Sino viene encriptado,viene del calendario
            try {
                $lead = Lead::find(decrypt($lead_id));
                if ($user->hasRole('superadmin')){
                    $matchThese = ['lead_id' => decrypt($lead_id)];
                }
                else{
                    $matchThese = ['user_id' => $user->id, 'lead_id' => decrypt($lead_id)];
                }
            } catch (DecryptException $e) {
                $lead = Lead::find($lead_id);
                if ($user->hasRole('superadmin')){
                    $matchThese = ['lead_id' => $lead_id];
                }
                else{
                    $matchThese = ['user_id' => $user->id, 'lead_id' => $lead_id];
                }
            }   
            
            $leadNotes = Lead::from('lead_notes as le')
                    ->where($matchThese)
                    ->leftJoin('lead_status as ls','le.lead_status_id','ls.id')
                    ->leftJoin('lead_sub_status as lss','le.lead_sub_status_id','lss.id')
                    ->leftJoin('users as us','le.user_id','us.id')
                    ->select('le.*','ls.name as status','lss.name as sub_status','us.name as user_note_name');
            // dd($leadNotes->get());
            return DataTables::of($leadNotes)
                        ->addColumn('acciones', function($row){
                            return '<a href="javascript:void(0)" class="btn bg-lightblue btn-xs editNote" data-container="body" data-object = "'.$row->id.'" ><i class="fad fa-edit"></i> Modificar</a></li>';    
                        })
                        // ->rawColumns(['acciones','checkbox'])
                        ->rawColumns(['acciones'])
                        ->make(true);
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
             'lead_notes_via' => ['required'],
             'leads_status_list' => ['required'],
             'lead_notes_observation' => ['required'],
         ];
         $customMessages = [
             'lead_notes_via.required' => 'El medio es obligatorio',
             'leads_status_list.required' => 'El estado es obligatorio',
             'lead_notes_observation.required' => 'La observación es obligatoria',
             
         ];
         
         $validator = Validator::make($request->all(),$rules,$customMessages);
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
            // dd($request);
            //Actualmente solo se puede crear una nota con subestado Nulo, sino es Nulo viene subestado
            $lead_sub_status_id = NULL;
            (int)$lead_status_id = $request->leads_status_list ?? NULL ;
            //2 -> Cerrado
            //3 -> Pendiente
            //5 -> Volver a Llamar
            //7 -> No contesta
            //8 -> Pend contestación
            //13 -> Agendado
            //14 -> Pend. Matricula
            if($request->leads_status_list == 2 
                    || $request->leads_status_list == 3 || $request->leads_status_list == 5 
                    || $request->leads_status_list == 7 || $request->leads_status_list == 8 
                    || $request->leads_status_list == 13 || $request->leads_status_list == 14){
                (int)$lead_sub_status_id = $request->leads_sub_status_list[0] ?? NULL ;
            }
            // dd($lead_sub_status_id);
            $dt_call_reminder = Carbon::parse($request->dt_call_reminder)->format('Y-m-d H:i:s');
            $dt_call_reminder_end =  Carbon::parse($dt_call_reminder)->addHours(1)->format('Y-m-d H:i:s');
            // dd($dt_call_reminder);
            if ($request->has('dt_call_reminder')) {
            // Verifica si viene la fecha repetida para que salga un error
                // dd($lead_sub_status_id);
                $calendarDuplicate = LeadNote::from('lead_notes as ln')
                                        ->leftJoin('leads as l','ln.lead_id','=','l.id')
                                        ->where('l.agent_id','=',Auth::user()->id)
                                        ->whereRaw('? = DATE_FORMAT(ln.dt_call_reminder, "%Y-%m-%d %H:%i")',Carbon::parse($request->dt_call_reminder)->format('Y-m-d H:i'))
                                        ->select('ln.*')
                                        ->get();
            }
            // dd($calendarDuplicate);
            if(!$calendarDuplicate->isEmpty()){
                return response()->json(['error'=>['Ya existe una llamada programada en este horario '.Carbon::parse($request->dt_call_reminder)->format('d/m/Y H:i')]]);
            }
            $user = Auth::user();
            $leadNote = LeadNote::updateOrCreate(
                ['id' => $request->id_note],
                ['lead_id' => decrypt($request->lead_id_notes), 'user_id' => $user->id, 
                'sent_method' => $request->lead_notes_via, 
                'lead_status_id' => $lead_status_id,
                'lead_sub_status_id' => $lead_sub_status_id,
                'dt_call_reminder' => $dt_call_reminder, 
                'observation' => $request->lead_notes_observation]
            );
            //Actualiza el estado y sub estado del lead
            // dd($dt_call_reminder);
            $leadSubStatusToUpdate = ($leadNote->lead_sub_status_id == NULL) ? NULL : (int)$leadNote->lead_sub_status_id;
            //  dd(($leadNote->lead_sub_status_id == NULL));
            $leadToModify = Lead::find((int)$leadNote->lead_id);
            $leadToModify->lead_status_id = (int)$leadNote->lead_status_id;
            $leadToModify->lead_sub_status_id = $leadSubStatusToUpdate;
            $leadToModify->dt_call_reminder = $leadNote->dt_call_reminder;
            
            if (!$user->hasRole('superadmin')){
                //El administrador no genera fecha de modificación
                $leadToModify->dt_last_update = Carbon::now()->format('Y-m-d H:i:s');
            }
            $leadToModify->save();
            //  dd($leadToModify);
            //Lógica para crear el evento
            //Saca el color del estado
            $leadStatusColor = LeadStatus::find($leadToModify->lead_status_id);
            if($lead_status_id == 2 || $lead_status_id == 5 || $lead_status_id == 3 || $lead_status_id == 8 || $lead_status_id == 13|| $lead_status_id == 14){//Estados que requieren crear un evento de volver a llamar
                //Primero Borro todas las notas creadas de ese Lead
                EventMaster::whereLeadId(decrypt($request->lead_id_notes))->delete();
                //Luego crea el evento
                if ($lead_status_id != 2){//Si es cerrado entonces no crea mas eventos
                        EventMaster::updateOrCreate(
                        ['id' => $request->event_id],
                        ['user_id' => $user->id, 'lead_id'=> decrypt($request->lead_id_notes)
                        ,'type_id' => 1
                        ,'title' => '['.$leadToModify->student_first_name . ' '.$leadToModify->student_last_name.'] '.
                                        $request->lead_notes_observation
                        ,'start' => $dt_call_reminder, 'end' => $dt_call_reminder_end
                        ,'color' => $leadStatusColor->hexa_color, 'text_color' => '#FFFFFF'
                        ,'allDay' => 0,]
                    );
                }
            }
            $leadNotesCount = LeadNote::whereLeadId($request->lead_id_notes)->get()->count();
            // dd($leadNotesCount==1);
            if($leadNotesCount == 1){
                $leadToModify->dt_activation = Carbon::now()->format('Y-m-d H:i:s');
                $leadToModify->save();
                //Actualiza última conexión
                Auth::user()->update_lastlogin();
                return response()->json(['success'=>'Nota creada correctamente.']);
            }    
            else{
                //Actualiza última conexión
                Auth::user()->update_lastlogin();
                return response()->json(['success'=>'Nota creada correctamente.']);
            }
         }
 
         
         
     }

     public function story(Request $request)
     {
         $rules = [
             'lead_notes_via_story' => ['required'],
             'lead_notes_story_observation' => ['required'],
         ];
         $customMessages = [
             'lead_notes_via_story.required' => 'El medio es obligatorio',
             'lead_notes_story_observation.required' => 'La observación es obligatoria',
         ];
         
         $validator = Validator::make($request->all(),$rules,$customMessages);
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
            $user = Auth::user();
            $leadNote = LeadNote::updateOrCreate(
                ['id' => $request->id_note_story],
                ['lead_id' => decrypt($request->lead_id_notes_story), 'user_id' => $user->id, 
                'sent_method' => $request->lead_notes_via_story, 
                'lead_status_id' => 4,
                // 'lead_sub_status_id' => $lead_sub_status_id,
                // 'dt_call_reminder' => $dt_call_reminder, 
                'observation' => $request->lead_notes_story_observation]
            );
            //Actualiza el estado y sub estado del lead
            // dd($dt_call_reminder);
            // $leadSubStatusToUpdate = ($leadNote->lead_sub_status_id == NULL) ? NULL : (int)$leadNote->lead_sub_status_id;
            //  dd(($leadNote->lead_sub_status_id == NULL));
            $leadToModify = Lead::find((int)$leadNote->lead_id);
            $leadToModify->lead_status_id = 4;

            if (!$user->hasRole('superadmin')){
                //El administrador no genera fecha de modificación
                $leadToModify->dt_last_update = Carbon::now()->format('Y-m-d H:i:s');
            }
            $leadToModify->save();
            //  dd($leadToModify);
            
            $leadNotesCount = LeadNote::whereLeadId($request->lead_id_notes_story)->get()->count();
            // dd($leadNotesCount==1);
            if($leadNotesCount == 1){
                $leadToModify->dt_activation = Carbon::now()->format('Y-m-d H:i:s');
                $leadToModify->save();
                //Actualiza última conexión
                Auth::user()->update_lastlogin();
                return response()->json(['success'=>'Nota creada correctamente.']);
            }    
            else{
                //Actualiza última conexión
                Auth::user()->update_lastlogin();
                return response()->json(['success'=>'Nota creada correctamente.']);
            }
         }
 
         
         
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeadNote  $leadNote
     * @return \Illuminate\Http\Response
     */
    public function show(LeadNote $leadNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeadNote  $leadNote
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
     {
         $leadNote = LeadNote::find($id);
         return response()->json($leadNote);
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeadNote  $leadNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadNote $leadNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeadNote  $leadNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadNote $leadNote)
    {
        //
    }
}
