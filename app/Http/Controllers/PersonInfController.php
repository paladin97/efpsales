<?php

namespace App\Http\Controllers;

use App\Models\{User,PersonInf,Role,Company,AccessCompany, Course};
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PersonInfController extends Controller
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
            // (!empty($_GET["agent_list_filter"])) ? ($_GET["agent_list_filter"]) : PersonInf::all()->pluck('id');
            $user = Auth::user();

            // dd($user->permissions->toArray()[0]['id']);
            $companies = User::find($user->id)->companies;
            //  dd($companies->pluck('id'));
            $filter_list = PersonInf::from('people_inf as pi')
                        ->select('pi.*')
                        ->orderByRaw(DB::Raw("pi.created_at, pi.id desc"))
                        ->get();
            //  dd($filter_list);
            
            $company_filter = (!empty($_GET["company_filter"])) ? ($_GET["company_filter"]) : $filter_list->pluck('company_id')->unique();
            $person_type_filter = (!empty($_GET["person_type_filter"])) ? ($_GET["person_type_filter"]) : $filter_list->pluck('person_type_id')->unique();
            $province_filter = (!empty($_GET["province_filter"])) ? ($_GET["province_filter"]) : $filter_list->pluck('province_id')->unique();
            
            //Para las estadisticas
            $personByRole = PersonInf::from('people_inf as pi')
                                ->leftJoin('person_types as pty','pi.person_type_id','=','pty.id')
                                ->select('pty.name', 
                                    DB::raw('count(*) as count')
                                )
                                ->groupBy('pty.name')
                                ->get()->toArray();
            // dd($personByRole);

            //  dd($filter_list);
             if ($user->hasRole('superadmin')){
                // DB::enableQueryLog();
                $person_inf =  PersonInf::from('people_inf as pi')
                    ->leftJoin('person_types as pty', 'pi.person_type_id', '=','pty.id')
                    ->leftJoin('provinces as pro', 'pi.province_id', '=','pro.id')
                    ->leftJoin('countries as cnt', 'pi.country_id', '=','cnt.id')
                    ->leftJoin('liquidation_models as limo', 'pi.liquidation_model_id', '=','limo.id')
                    ->leftJoin('banks as ba', 'pi.bank_id', '=','ba.id')
                    ->leftJoin('companies as com', 'pi.company_id', '=','com.id')
                    ->WhereIn('pi.company_id',$company_filter)
                    ->WhereIn('pi.person_type_id',$person_type_filter)
                    ->WhereIn('pi.province_id',$province_filter)
                    ->select('pi.*','pro.name as province_name','cnt.name as country_name','limo.name as liquidation_name'
                            ,'com.name as company_name','ba.name as bank_name','pty.name as person_type_name'
                            , DB::raw("CONCAT(pi.address, ' - ',pi.town,' - ',pro.name,' - ',pi.postal_code,' . ' ,cnt.name) as address_name")
                            );
            }else{
                $person_inf = PersonInf::from('people_inf as pi')
                ->leftJoin('person_types as pty', 'pi.person_type_id', '=','pty.id')
                ->leftJoin('provinces as pro', 'pi.province_id', '=','pro.id')
                ->leftJoin('countries as cnt', 'pi.country_id', '=','cnt.id')
                ->leftJoin('liquidation_models as limo', 'pi.liquidation_model_id', '=','limo.id')
                ->leftJoin('banks as ba', 'pi.bank_id', '=','ba.id')
                ->leftJoin('companies as com', 'pi.company_id', '=','com.id')
                ->WhereIn('pi.company_id',$companies->pluck('id'))
                ->WhereIn('pi.person_type_id',$person_type_filter)
                ->WhereIn('pi.province_id',$province_filter)
                ->select('pi.*','pro.name as province_name','cnt.name as country_name','limo.name as liquidation_name'
                        ,'com.name as company_name','ba.name as bank_name','pty.name as person_type_name'
                        );
            }
                //  dd($person_inf->get());
                if ($request->ajax()) {
                    // dd($lead->first());
                    // dd(DataTables::of($lead)->make(true));
                    return DataTables::of($person_inf)
                        ->addColumn('acciones', function($row){
                            $btn ='<div class="dropdown" align="center">
                                    <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                            <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                            $btn = $btn. ' <a class="dropdown-item editPerson" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pen text-lightblue"></i> Editar</a>';
                            $btn = $btn. ' <a class="dropdown-item deletePerson" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                            $btn = $btn.'</div></div>';

                            return $btn;
                        })
                            // ->rawColumns(['acciones','checkbox'])
                            ->rawColumns(['acciones'])
                            ->make(true);
                    }
                $pageTitle ='Información Personal - '.$companies->pluck('name')[0];
                // dd($call_reminder_leads);
                return view('admin.people_inf.index',compact('personByRole','pageTitle'));
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
     function massremove(Request $request)
     {
         // dd($request);
         $person_inf_id_array = $request->input('course_id');
         $leads = Lead::whereIn('id', $person_inf_id_array);
         if($leads->delete()){
              return response()->json(['success'=>'Registros eliminados correctamente.']);
         }
     }
     function massAssign(Request $request)
     {
         // dd($request);
         $person_inf_id_array = $arr=explode(",",$request->input('course_id'));
         // dd($person_inf_id_array);
         // DB::enableQueryLog();
         $leadsToUpdate = Lead::whereIn('id', $person_inf_id_array)->get();
         // dd(DB::getQueryLog());
         // dd($leadsToUpdate);
         foreach ($leadsToUpdate as $item => $lead) {
             // dd($request);
             $dataToUpdate  = [
                 'lead_status_id'        => $request->leads_status_assign_list[$item],
                 'lead_sub_status_id'    => null, //se vacia el subestado
                 // 'dt_reception'       => $request->dt_reception_assign_list,
                 'dt_assignment'         => Carbon::now()->toDateTimeString(),
                 // 'leads_origin_id'    => $request->leads_origins_assign_list[0],
                 'agent_id'              => $request->leads_agent_assign_list[$item],
                 'lead_type_id'          => $request->leads_type_assign_list[$item],
                 'dt_last_update'        => null, //se vacia la útima modificación porque para el comercial será "un nuevo estado"
                 'dt_call_reminder'      => null, //se vacia la fecha de recordación llamadaporque para el comercial será "un nuevo estado"
                 'prev_agent_id'         => $lead->agent_id                
             ];
             // dd($prev_agent_id);
             Lead::updateOrCreate(
                 ['id' => (int)$lead->id],
                 $dataToUpdate
             ); 
         }
         return response()->json(['success'=>'Registros asignados correctamente.']);
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
             'newcrse_company' => ['required'],
             'newcrse_name' => ['required'],
             'newcrse_area' => ['required'],
             'newcrse_tipo' => ['required'],
             'newcrse_pvp' => ['required'],
             'newcrse_duration' =>  ['required','numeric']
         ];
         $customMessages = [
             'newcrse_company.required' => 'La empresa es obligatoria',
             'newcrse_name.required' => 'El nombre es obligatorio',
             'newcrse_area.required' => 'El área es obligatorio',
             'newcrse_tipo.required' => 'El tipo es obligatorio',
             'newcrse_pvp.required' => 'El P.V.P es obligatorio',
             'newcrse_duration.required' => 'La duración es obligatoria',
             'newcrse_duration.numeric' => 'La duración debe ser en formato numerico'
         ];
 
         $validator = Validator::make($request->all(),$rules,$customMessages);
         
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
             
            //  $is_primary = $request->newcrse_is_primary == 'on' ? 1 : 0;
            //  $is_secondary = $request->newcrse_is_secondary == 'on' ? 1 : 0;
             PersonInf::updateOrCreate(
                 ['id' => $request->course_id],
                 ['company_id' => (int)$request->newcrse_company, 
                 'name' => $request->newcrse_name,'pvp' => $request->newcrse_pvp,
                 'duration' => $request->newcrse_duration, 'status'=>1, 'type_id' => (int)$request->newcrse_tipo,
                 'area_id'=>(int)$request->newcrse_area, 'program' => $request->editordata
                 ]
             );        
             return response()->json(['success'=>'Modelo de Liquidación creado/actualizado correctamente.']);
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
      public function edit($id)
      {
        //   $edit = PersonInf::from('courses as crse')
        //              ->where('crse.id','=',$id)
        //              ->leftJoin('course_areas as ar', 'crse.area_id', '=','ar.id')
        //              ->leftJoin('course_types as ty', 'crse.type_id', '=','ty.id')
        //              ->leftJoin('companies as com', 'crse.company_id', '=','com.id')
        //              ->select('crse.*','ar.name as course_area_name','ty.duration as course_type_duration'
        //                      ,'ty.name as course_type_name','com.name as course_company_name'
        //                      , DB::raw('(SELECT max(fir.increase) FROM fee_increase_ranges fir 
        //                                  WHERE fir.company_id = com.id 
        //                                  AND crse.pvp between fir.fee_value_from and fir.fee_value_to
        //                                  AND year(now()) = year(fir.created_at)) AS increase'))
        //              ->orderByRaw(DB::Raw("crse.created_at desc"))
        //              ->get();
        //   return response()->json($edit);
        $person_inf = PersonInf::find($id);

        return response()->json($person_inf);
      }

      public function cancelCourse(Request $request)
    {
        // dd($drop_contract_id);
        $cancel_course_id= decrypt($request->cancel_course_id);
        // $dataToSave['contract_status_id']= 6;
        
        $cancelCourse = PersonInf::updateOrCreate(
            ['id' => $cancel_course_id],
            $dataToSave  
        );
        
        $wasChanged = $cancelCourse->wasChanged();
        if($wasChanged){
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>anulado  con exito</p>']);    
        }
        else{
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-error"></i>anulación no realizada</p>']);    
        }   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $person_inf
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $person_inf)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $person_inf
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $person_inf)
    {
        //
    }
}
