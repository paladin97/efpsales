<?php

namespace App\Http\Controllers;

use App\Models\{User,ContractState};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB, Validator;
class ContractStatusController extends Controller
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
                $status = ContractState::from('contract_states as ct')
                                        ->leftJoin('contract_types as cty','ct.contract_type_id','cty.id')
                                        ->select('ct.*','cty.name as contract_type');
                return DataTables::of($status)
                                ->addColumn('acciones', function($row){
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    $btn = $btn. ' <a class="dropdown-item editContractStatus" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                                    $btn = $btn. ' <a class="dropdown-item deleteContractStatus" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                                    $btn = $btn.'</div></div>';
            
                                    return $btn;
                                })
                                // ->rawColumns(['acciones','checkbox'])
                                ->rawColumns(['acciones'])
                                ->make(true);
            }
            $pageTitle ='Estados de Contratos - '.$companies->pluck('name')[0];
            return view('admin.contract.contract_status.index',compact('pageTitle'));
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
             'name' => ['required'],  
             'short_name' => ['required'],  
             'color_class' => ['required'],  
             'contract_type' => ['required'],  
         ];
         $customMessages = [
                 'name.required' => 'El campo nombre es obligatorio',
                 'short_name.required' => 'El campo nombre es obligatorio',
                 'color_class.required' => 'El campo nombre es obligatorio',
                 'contract_type.required' => 'El tipo de contrato es requerido',
         ];
         $validator = Validator::make($request->all(),$rules,$customMessages);
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
            ContractState::updateOrCreate(
                 ['id' => $request->contractsatus_id],
                 ['name' => $request->name
                  ,'short_name' => $request->short_name
                  ,'description' => $request->description
                  ,'color_class' => $request->color_class
                  ,'contract_type_id' => $request->contract_type]
             );        
             return response()->json(['success'=>'Se creó el registro correctamente.']);
         }
     }

    function massremove(Request $request)
    {
        // dd($request);
        $contractstatus_id_array = $request->input('contractsatus_id');
        $contractstatus = ContractState::whereIn('id', $contractstatus_id_array);
        if($contractstatus->delete()){
             return response()->json(['success'=>'registros eliminados correctamente.']);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContractState  $contractStatus
     * @return \Illuminate\Http\Response
     */
    public function show(ContractState $contractStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContractState  $contractStatus
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
     {
        $contractstatus = ContractState::find($id);
        return response()->json($contractstatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContractState  $contractStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContractState $contractStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContractState  $contractStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContractState $contractStatus)
    {
        //
    }
}
