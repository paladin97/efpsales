<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{User,Bank};
use Carbon\Carbon;
use Auth;
use DataTables;
use DB, Validator;



class BankController extends Controller
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
                $banks = Bank::all();
                return DataTables::of($banks)
                                ->addColumn('acciones', function($row){
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    $btn = $btn. ' <a class="dropdown-item editBank" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt"></i> Editar</a>';
                                    $btn = $btn. ' <a class="dropdown-item deleteBank" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash"></i> Eliminar</a>';
                                    $btn = $btn.'</div></div>';

                                    return $btn;
                                })
                                ->rawColumns(['acciones'])
                                ->make(true);
            }
            $pageTitle ='Bancos - '.$companies->pluck('name')[0];
            return view('admin.bank.index',compact('pageTitle'));
        }
        else{
            return view('auth.login');
        }
    }
    /**sasdas
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }
    /**
     * Massive Delete
     *
     * @return \Illuminate\Http\Response
     */
    function massremove(Request $request)
    {
        // dd($request);
        $bank_id_array = $request->input('bank_id');
        $bank = Bank::whereIn('id', $bank_id_array);
        if($bank->delete()){
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
            Bank::updateOrCreate(
                ['id' => $request->bank_id],
                ['name' => $request->name]
            );        
            return response()->json(['success'=>'Se creó el registro correctamente.']);
        }
    }
    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bank = Bank::find($id);

        return response()->json($bank);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // dd(Banks::find($id));
        Bank::find($id)->delete();
        return response()->json(['success'=>'Banco eliminado correctamente.']);
    }

    public function cancelBank(Request $request)
    {
        // dd($drop_contract_id);
        $cancel_bank_id= decrypt($request->cancel_bank_id);
        // $dataToSave['contract_status_id']= 6;
        
        $cancelBank = Bank::updateOrCreate(
            ['id' => $cancel_bank_id],
            $dataToSave  
        );
        
        $wasChanged = $cancelBank->wasChanged();
        if($wasChanged){
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>anulado  con exito</p>']);    
        }
        else{
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-error"></i>anulación no realizada</p>']);    
        }   
    }
}