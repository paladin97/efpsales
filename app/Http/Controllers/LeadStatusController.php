<?php

namespace App\Http\Controllers;

use App\Models\{User,LeadStatus};
use Illuminate\Http\Request;

use Carbon\Carbon;
use Auth;
use DataTables;
use DB, Validator;

class LeadStatusController extends Controller
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
            $companies = User::find($user->id)->companies;
            if ($request->ajax()) {
                $status = LeadStatus::all();
                return DataTables::of($status)
                                ->addColumn('acciones', function($row){
                                    $sistemLeadIds = array(1,9,4,11,12); //Abierto, Matriculado, Contrato Aceptado, Contrato Aceptado Parcial
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    if(in_array($row->id,$sistemLeadIds)){
                                        $btn = $btn. ' <a class="dropdown-item disabled" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-times-circle text-red"></i> Propio del Sistema</a>';
                                    }else{
                                        $btn = $btn. ' <a class="dropdown-item editLeadStatus" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                                        $btn = $btn. ' <a class="dropdown-item deleteLeadStatus" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                                    }
                                    $btn = $btn.'</div></div>';
            
                                    return $btn;
                                })
                                // ->rawColumns(['acciones','checkbox'])
                                ->rawColumns(['acciones'])
                                ->make(true);
            }
            $pageTitle ='Estados de Leads - '.$companies->pluck('name')[0];
            return view('admin.leads.lead_status.index',compact('pageTitle'));
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

    function massremove(Request $request)
    {
        // dd($request);
        $leadstatus_id_array = $request->input('leadstatus_id');
        $leadstatus = LeadStatus::whereIn('id', $leadstatus_id_array);
        if($leadstatus->delete()){
             return response()->json(['success'=>'registros eliminados correctamente.']);
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
        $rules = [
            'name' => ['required'],  
        ];
        $customMessages = [
                'name.required' => 'El campo nombre es obligatorio',
        ];
        $validator = Validator::make($request->all(),$rules,$customMessages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{
            LeadStatus::updateOrCreate(
                ['id' => $request->leadstatus_id],
                ['name' => $request->name
                 ,'description' => $request->description
                 ,'color_class' => $request->color_class]
            );        
            return response()->json(['success'=>'Se creó el registro correctamente.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leadstatus = LeadStatus::find($id);

        return response()->json($leadstatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadStatus $leadStatus)
    {
        //
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // dd(Banks::find($id));
        LeadStatus::find($id)->delete();
        return response()->json(['success'=>'Estado de Lead eliminado correctamente.']);
    }
}

