<?php

namespace App\Http\Controllers;

use App\Models\{User,Company,LiquidationModel};
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LiquidationModelController extends Controller
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

                $liquidationmodel = LiquidationModel::from ('liquidation_models as lqmod')
                                ->leftJoin('companies as com', 'lqmod.company_id', '=','com.id')
                                ->WhereIn('lqmod.company_id',$companies->pluck('id'))
                                ->select('lqmod.*','com.name as liquidacion_company_name');




                return DataTables::of($liquidationmodel)
                                ->addColumn('acciones', function($row){
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-lightblue"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    $btn = $btn. ' <a class="dropdown-item editLiquidationModel" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                                    $btn = $btn. ' <a class="dropdown-item deleteLiquidationmodel" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                                    $btn = $btn.'</div></div>';
            
                                    return $btn;
                                })
                                // ->rawColumns(['acciones','checkbox'])
                                ->rawColumns(['acciones'])
                                ->make(true);
            }
            $pageTitle ='Modelos de Liquidación - '.$companies->pluck('name')[0];
            return view('admin.liquidation.index',compact('pageTitle'));
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
        $liquidationmodel_id_array = $request->input('liquidation_model_id');
        $liquidationmodel = LiquidationModel::whereIn('id', $liquidationmodel_id_array);
        if($liquidationmodel->delete()){
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
        // dd($request->all());
        $rules = [
            'newlqmod_name' => ['required'],
            'newlqmod_company' => ['required'],
            'newlqmod_basesalary' => ['required'],
            'newlqmod_enrollcommission' => ['required'],
            'newlqmod_enrollbonuscommission' => ['required'],
            'newlqmod_enrolldelegation' => ['required']
        ];
        $customMessages = [
                'newlqmod_name.required' => 'El campo nombre es obligatorio',
                'newlqmod_company.required' => 'El campo empresa es obligatorio',
                'newlqmod_basesalary.required' => 'El campo salario base es obligatorio',
                'newlqmod_enrollcommission.required' => 'El campo comisión por venta es obligatorio',
                'newlqmod_enrollbonuscommission.required' => 'El campo bonus por venta es obligatorio',
                'newlqmod_enrolldelegation.required' => 'El campo comisión por delegación es obligatorio'
        ];
        $validator = Validator::make($request->all(),$rules,$customMessages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{
            LiquidationModel::updateOrCreate(
                ['id' => $request->liquidationmodel_id],
                ['name' => $request->newlqmod_name,'company_id' => (int)$request->newlqmod_company,'description' => $request->editordata,
                'base_salary' => $request->newlqmod_basesalary,'enroll_commission' => $request->newlqmod_enrollcommission,'enroll_bonus_commission' => $request->newlqmod_enrollbonuscommission,
                'enroll_delegation' => $request->newlqmod_enrolldelegation
                ]
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
        $liquidationmodel = LiquidationModel::find($id);

        return response()->json($liquidationmodel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeadOrigin  $leadOrigin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LiquidationModel $liquidationmodel)
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
        LiquidationModel::find($id)->delete();
        return response()->json(['success'=>'Modelo de liquidación eliminado correctamente.']);
    }

    public function cancelLiquidationmodel(Request $request)
    {
        // dd($drop_contract_id);
        $cancel_liquidationmodel_id= decrypt($request->cancel_liquidationmodel_id);
        // $dataToSave['contract_status_id']= 6;
        
        $cancelLiquidationModel = LiquidationModel::updateOrCreate(
            ['id' => $cancel_liquidationmodel_id],
            $dataToSave  
        );
        
        $wasChanged = $cancelLiquidationModel->wasChanged();
        if($wasChanged){
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Borrado  con éxito</p>']);    
        }
        else{
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-error"></i>Borrado no realizado</p>']);    
        }   
    }
}
