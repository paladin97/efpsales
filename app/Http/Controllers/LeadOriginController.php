<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{User,LeadOrigin};
use Carbon\Carbon;
use Auth;
use DataTables;
use DB, Validator;

class LeadOriginController extends Controller
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
                $origins = LeadOrigin::all();
                return DataTables::of($origins)
                                ->addColumn('acciones', function($row){
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    $btn = $btn. ' <a class="dropdown-item editLeadOrigin" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                                    $btn = $btn. ' <a class="dropdown-item deleteLeadOrigin" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                                    $btn = $btn.'</div></div>';
            
                                    return $btn;
                                })
                                // ->rawColumns(['acciones','checkbox'])
                                ->rawColumns(['acciones'])
                                ->make(true);
            }
            $pageTitle ='Origenes de Lead - '.$companies->pluck('name')[0];
            return view('admin.leads.leads_origin.index',compact('pageTitle'));
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
        $leadorigin_id_array = $request->input('leadorigin_id');
        $leadorigin = LeadOrigin::whereIn('id', $leadorigin_id_array);
        if($leadorigin->delete()){
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
            LeadOrigin::updateOrCreate(
                ['id' => $request->leadorigin_id],
                ['name' => $request->name]
            );        
            return response()->json(['success'=>'Se creó el registro correctamente.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeadOrigin  $leadOrigin
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeadOrigin  $leadOrigin
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leadorigin = LeadOrigin::find($id);

        return response()->json($leadorigin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeadOrigin  $leadOrigin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadOrigin $leadOrigin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeadOrigin  $leadOrigin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // dd(Banks::find($id));
        LeadOrigin::find($id)->delete();
        return response()->json(['success'=>'Origen de Lead eliminado correctamente.']);
    }

    public function cancelOrigen(Request $request)
    {
        // dd($drop_contract_id);
        $cancel_origen_id= decrypt($request->cancel_origen_id);
        // $dataToSave['contract_status_id']= 6;
        
        $cancelOrigen = LeadOrigin::updateOrCreate(
            ['id' => $cancel_origen_id],
            $dataToSave  
        );
        
        $wasChanged = $cancelOrigen->wasChanged();
        if($wasChanged){
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Borrado  con éxito</p>']);    
        }
        else{
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-error"></i>Borrado no realizado</p>']);    
        }   
    }
}
