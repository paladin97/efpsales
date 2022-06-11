<?php

namespace App\Http\Controllers;

use App\Models\{EventMaster,Company,User,LeadNote};
use Illuminate\Http\Request;
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('superadmin')){ 
                $events = EventMaster::from('event_masters as ev')
                            ->leftJoin('leads as le','ev.lead_id','=','le.id')
                            ->leftJoin('courses as crse','le.course_id','=','crse.id')
                            ->leftJoin('provinces as pro','le.province_id','=','pro.id')
                            ->leftJoin('users as us','le.agent_id','=','us.id')
                            ->leftJoin('lead_status as ls','le.lead_status_id','=','ls.id')
                            ->where('ev.type_id','=',1)
                            ->select('ev.*','le.student_first_name as student_first_name','le.student_last_name as student_last_name'
                                    ,'ls.color_class as status_color','ls.name as status_name','le.student_mobile as student_mobile'
                                    ,'us.name as agent_name','ls.id as status_id','le.agent_id as agent_id','ev.color as hexa_color'
                                    ,'pro.name as province_name','crse.name as course_name','le.student_email as email'
                            )
                            ->orderBy('created_at','desc')
                            ->get();
            }else{
                $events = EventMaster::from('event_masters as ev')
                            ->leftJoin('leads as le','ev.lead_id','=','le.id')
                            ->leftJoin('courses as crse','le.course_id','=','crse.id')
                            ->leftJoin('provinces as pro','le.province_id','=','pro.id')
                            ->leftJoin('users as us','le.agent_id','=','us.id')
                            ->leftJoin('lead_status as ls','le.lead_status_id','=','ls.id')
                            ->where('ev.user_id','=',$user->id)
                            ->where('ev.type_id','=',1)
                            ->select(
                                    'ev.start','ev.end','ev.user_id','ev.color','ev.lead_id','ev.allDay','ev.text_color','ev.type_id'
                                    // ,DB::raw('CONCAT("<b>", ev.title, "</b>") as title')
                                    ,'ev.title'
                                    ,'le.student_first_name as student_first_name','le.student_last_name as student_last_name'
                                    ,'ls.color_class as status_color','ls.name as status_name','le.student_mobile as student_mobile'
                                    ,'us.name as agent_name','ls.id as status_id','le.agent_id as agent_id','ev.color as hexa_color'
                                    ,'pro.name as province_name','crse.name as course_name','le.student_email as email'
                            )
                            ->orderBy('ev.created_at','desc')
                            ->get();
            }
            // $pageTitle ='Calendario - '.$companies->pluck('name')[0];
            return response()->json($events);
            
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
     public function contract(Request $request)
     {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('superadmin')){ 
                $events = EventMaster::from('event_masters as ev')
                            ->leftJoin('leads as le','ev.lead_id','=','le.id')
                            ->leftJoin('courses as crse','le.course_id','=','crse.id')
                            ->leftJoin('provinces as pro','le.province_id','=','pro.id')
                            ->leftJoin('users as us','le.agent_id','=','us.id')
                            ->leftJoin('lead_status as ls','le.lead_status_id','=','ls.id')
                            ->where('ev.type_id','=',2)
                            ->select('ev.*','le.student_first_name as student_first_name','le.student_last_name as student_last_name'
                                    ,'ls.color_class as status_color','ls.name as status_name','le.student_mobile as student_mobile'
                                    ,'us.name as agent_name','ls.id as status_id','le.agent_id as agent_id','ev.color as hexa_color'
                                    ,'pro.name as province_name','crse.name as course_name','le.student_email as email'
                                    ,'le.id as lead_id_contact'
                            )
                            ->orderBy('created_at','desc')
                            ->get();
            }else{
                $events = EventMaster::from('event_masters as ev')
                            ->leftJoin('leads as le','ev.lead_id','=','le.id')
                            ->leftJoin('courses as crse','le.course_id','=','crse.id')
                            ->leftJoin('provinces as pro','le.province_id','=','pro.id')
                            ->leftJoin('users as us','le.agent_id','=','us.id')
                            ->leftJoin('lead_status as ls','le.lead_status_id','=','ls.id')
                            ->where('ev.user_id','=',$user->id)
                            ->where('ev.type_id','=',2)
                            ->select('ev.*','le.student_first_name as student_first_name','le.student_last_name as student_last_name'
                                    ,'ls.color_class as status_color','ls.name as status_name','le.student_mobile as student_mobile'
                                    ,'us.name as agent_name','ls.id as status_id','le.agent_id as agent_id','ev.color as hexa_color'
                                    ,'pro.name as province_name','crse.name as course_name','le.student_email as email'
                                    ,'le.id as lead_id_contact'
                            )
                            ->orderBy('created_at','desc')
                            ->get();
            }
            // $pageTitle ='Calendario - '.$companies->pluck('name')[0];
            return response()->json($events);
            
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
     public function create(Request $request)
     {  
        $rules = [
            'title' => ['required'],
            'start' => ['required'],
            'end' => ['required'],
            'allDay' => ['required'],
        ];
        $customMessages = [
            'title.required' => 'El título es obligatorio',
            'start.required' => 'La fecha de inicio obligatoria',
            'end.required' => 'La fecha de finalización es obligatoria',
            'allDay.required' => 'La duración del evento es obligatoria',
            
        ];
        $validator = Validator::make($request->all(),$rules,$customMessages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{
            $user = Auth::user();
            EventMaster::updateOrCreate(
                ['id' => $request->event_id],
                ['user_id' => $user->id, 'lead_id'=> $request->client_list
                ,'title' => $request->title, 'start' => $request->start, 'end' => $request->end
                ,'color' => $request->color, 'text_color' => $request->text_color
                ,'allDay' => $request->allDay,]
            );
            //Actualiza el lastLoginat
            //Actualiza última conexión
            Auth::user()->update_lastlogin();
            return response()->json(['success'=>'Evento creado correctamente.']);
        }
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
     * @param  \App\Models\EventMaster  $eventMaster
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request)
    {   
        $where = array('id' => $request->id);
        $updateArr = ['title' => $request->title,'start' => $request->start, 'end' => $request->end];
        $event  = EventMaster::where($where)->update($updateArr);
 
        return Response::json($event);
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventMaster  $eventMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
        $event = EventMaster::where('id',$id)->delete();

        return response()->json(['success'=>'Evento Eliminado Correctamente.']);
    }

    public function getEventsByAgent(Request $request)
    {
        // dd($request->all());
        $event = EventMaster::from('event_masters as ev')
                        ->leftJoin('leads as le','ev.lead_id','=','le.id')
                        ->leftJoin('courses as crse','le.course_id','=','crse.id')
                        ->leftJoin('provinces as pro','le.province_id','=','pro.id')
                        ->leftJoin('users as us','le.agent_id','=','us.id')
                        ->leftJoin('lead_status as ls','le.lead_status_id','=','ls.id')
                        ->where('ev.user_id',Auth::user()->id)
                        ->whereRaw('? = DATE_FORMAT(ev.start, "%Y-%m-%d")',Carbon::parse($request->dateToCheck)->format('Y-m-d'))
                        ->select('ev.*','le.student_first_name as student_first_name','le.student_last_name as student_last_name'
                                ,'ls.color_class as status_color','ls.name as status_name','le.student_mobile as student_mobile'
                                ,'us.name as agent_name','ls.id as status_id','le.agent_id as agent_id'
                                ,'pro.name as province_name','crse.name as course_name','le.student_email as email'
                                ,'le.id as lead_id_contact'
                        )
                        ->orderBy('ev.start','desc')
                        ->get();
        // dd(Carbon::parse($request->dateToCheck)->format('Y-m-d'));
        return Response::json($event);
    }
}
