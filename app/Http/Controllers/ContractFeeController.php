<?php

namespace App\Http\Controllers;

use App\Models\{PersonInf,Lead,Role,Company,AccessCompany,Contract,ContractFee,User};
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF,File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;

class ContractFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function viewFeePayments($contract_id)
    {
        $user;
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return view('auth.login');
        }
        $matchThese = ['contract_id' => $contract_id];
        $contract_payments = ContractFee::from('contract_fees as cf')
                ->where($matchThese)
                ->leftJoin('contracts as c','cf.contract_id','c.id')
                ->leftJoin('contract_fees_status as cfs','cf.status','cfs.short_name')
                ->leftJoin('people_inf as pi','c.person_id','pi.id')
                ->select('cf.*','c.enrollment_number as enrollment_number','cfs.color_class as color_class'
                    ,'pi.name as client_name','pi.last_name as client_last_name'
                    ,'cfs.name as status_name','c.contract_method_type_id as contract_method_type_id')
                ->orderByRaw(DB::Raw("cf.created_at, cf.fee_number desc"))
                ->get();
        return DataTables::of($contract_payments)
                    ->addColumn('pathimage', function($row){
                        // dd($row);
                        if($row->fee_path){
                            $url = asset('storage/paymentsproof/'.$row->enrollment_number.'/'. $row->fee_path);
                            // $btn= '<a class="btn btn-block btn-social btn-twitter btn-xs"   href="'.$url.'" target="_blank"><b><i class="fa fa-file"></i></b>'.$row->fee_path.'</a>';
                            $btn= '<a class="btn btn-block bg-gradient-info btn-xs"   href="'.$url.'" target="_blank"><b><i class="fad fa-lg fa-receipt"></i></b> Ver Comprobante</a>';
                        }else{
                            $btn= '<a class="btn btn-block bg-gradient-danger btn-xs disabled"   href="javascript:void(0)" ><b><i class="fad fa-lg fa-do-not-enter"></i></b> Sin comprobante</a>';
                        }
                        
                        return $btn;
                        // return Storage::url($row->path);
                    })
                    ->addColumn('pathunpaid', function($row){
                        // dd($row);
                        if($row->fee_unpaid_path){
                            $url = asset('storage/paymentsproof/'.$row->enrollment_number.'/'. $row->fee_unpaid_path);
                            // $btn= '<a class="btn btn-block btn-social btn-twitter btn-xs"   href="'.$url.'" target="_blank"><b><i class="fa fa-file"></i></b>'.$row->fee_path.'</a>';
                            $btn= '<a class="btn btn-block bg-gradient-info btn-xs"   href="'.$url.'" target="_blank"><b><i class="fad fa-lg fa-receipt"></i></b> Ver Comprobante</a>';
                        }else{
                            $btn= '<a class="btn btn-block bg-gradient-danger btn-xs disabled"   href="javascript:void(0)" ><b><i class="fad fa-lg fa-do-not-enter"></i></b>Sin comprobante</a>';
                        }
                        
                        return $btn;
                        // return Storage::url($row->path);
                    })
                    ->addColumn('acciones', function($row){
                        if($row->contract_method_type_id == 2 && $row->fee_number > 0){
                            if(Auth::user()->hasRole('superadmin')){
                                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editPayment" data-container="body" data-feenumber = "'.$row->fee_number.'"data-id="'.$row->id.'" ><i class="fad fa-edit fa-lg"></i> Modificar</a></li>';    
                            }
                            else{
                                return '<a disabled href="javascript:void(0)" class="btn btn-info btn-sm editPayment disabled" data-container="body" data-feenumber = "'.$row->fee_number.'"data-id="'.$row->id.'" ><i class="fad fa-edit fa-lg"></i> Modificar</a></li>';
                            }
                        }
                        else{
                            return '<a href="javascript:void(0)" class="btn btn-info btn-sm editPayment" data-container="body" data-feenumber = "'.$row->fee_number.'"data-id="'.$row->id.'" ><i class="fad fa-edit fa-lg"></i> Modificar</a></li>';
                        }
                    })
                    ->addColumn('crypt_contract_id',function($row){
                        return encrypt($row->id);
                    })
                    ->rawColumns(['acciones','pathimage','pathunpaid','crypt_contract_id'])
                    ->make(true);
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
         $rules= array();
         $rules['fee_paid_value']= ['required'];
         // dd($request->fee_payment_status);
         if (Auth::user()->hasRole('superadmin')) {
             if($request->fee_payment_status == 'P'){
                 $rules['fee_payment_dt_paid'] = ['required','date','nullable'];
             }
             else{
                 $rules['fee_payment_dt_unpaid'] = ['required','date','nullable'];
                 $rules['fee_unpaid_reason'] = ['required'];
             }
         }else{
             if(!$request->fee_payment_status[0]){
                 $rules['fee_payment_dt_unpaid'] = ['required','date','nullable'];
                 $rules['fee_unpaid_reason'] = ['required'];
             }
             else{
                 $rules['fee_payment_dt_paid'] = ['required','date','nullable'];
             }
         }
         $customMessages = [
             'fee_paid_value.required' => 'El valor cobrado es obligatorio',
             'fee_payment_dt_paid.required' => 'La fecha de pago es obligatoria',
             'fee_payment_dt_paid.date' => 'La fecha de pago debe tener el formato correcto',
             'fee_payment_dt_unpaid.required' => 'La fecha de impago o rechazado es obligatoria',
             'fee_payment_dt_unpaid.date' => 'La fecha de impago o rechazado debe tener el formato correcto',
             'fee_unpaid_reason.required' => 'La razón de impago o rechazado es obligatoria'
         ];
         $validator = Validator::make($request->all(),$rules,$customMessages);
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
             // dd($request->observations);
             // dd(!$request->fee_payment_status[0]);
             request()->validate([
                 'path' => 'file|max:5000|mimes:jpg,jpeg,png,pdf,docx,doc,zip,eml',
             ]);
             $fileToSave="";
             if (Auth::user()->hasRole('superadmin')) {
                 if($request->fee_payment_status == 'P'){
                     $dataToSave['status'] = $request->fee_payment_status;
                     $dataToSave['dt_unpaid'] = $request->fee_payment_dt_unpaid;
                     $dataToSave['dt_paid'] = $request->fee_payment_dt_paid;
                     $dataToSave['fee_paid'] = $request->fee_paid_value;
                     $dataToSave['reason_unpaid'] = null;
                 }
                 else{
                     $dataToSave['status'] = $request->fee_payment_status;
                     $dataToSave['dt_unpaid'] = $request->fee_payment_dt_unpaid;
                     $dataToSave['dt_paid'] = null;
                     $dataToSave['fee_paid'] = null;
                     $dataToSave['reason_unpaid'] = $request->fee_unpaid_reason;
                 }
                 
                 // dd($request);
             }else{
                 if(!$request->fee_payment_status[0]){
                     $dataToSave['status'] = 'E';
                     $dataToSave['dt_unpaid'] = $request->fee_payment_dt_unpaid;
                     $dataToSave['dt_paid'] = null;
                     $dataToSave['fee_paid'] = null;
                     $dataToSave['reason_unpaid'] = $request->fee_unpaid_reason;
                 }
                 else{
                     $dataToSave['status'] = 'Z';
                     $dataToSave['dt_paid'] = $request->fee_payment_dt_paid;
                     $dataToSave['fee_paid'] = $request->fee_paid_value;
                     $dataToSave['dt_unpaid'] = null;
                     $dataToSave['reason_unpaid'] = null;
                 }
             }
             
             if ($request->hasFile('fee_path')) {
                 $path = 'paymentsproof/'.$request->enrollment_number;
                 // dd(!Storage::exists($path));
                 if(!Storage::exists($path)){
                     File::makeDirectory($path, 0777, true, true);
                 }
                 $file = $request->file('fee_path');
                 $filetext = $request->file('fee_path')->getClientOriginalName();
                 $filename = pathinfo($filetext, PATHINFO_FILENAME);
                 $extension = pathinfo($filetext, PATHINFO_EXTENSION);
                 $fileToSave =  (str_replace(' ', '', $request->contract_id_fee)).'_'.(str_replace(' ', '', $filename)).'_1.'. $extension;
                 // dd($fileToSave);
                 $file->storeAs($path,$fileToSave, ['disk' => 'public']);
                 $dataToSave['fee_path'] = $fileToSave;
                 $dataToSave['fee_unpaid_path'] = null;
             }
             elseif ($request->hasFile('fee_unpaid_path')) {
                 $path = 'paymentsproof/'.$request->enrollment_number;
                 // dd(!Storage::exists($path));
                 if(!Storage::exists($path)){
                     File::makeDirectory($path, 0777, true, true);
                 }
                 $file = $request->file('fee_unpaid_path');
                 $filetext = $request->file('fee_unpaid_path')->getClientOriginalName();
                 $filename = pathinfo($filetext, PATHINFO_FILENAME);
                 $extension = pathinfo($filetext, PATHINFO_EXTENSION);
                 $fileToSaveUnpaid =  (str_replace(' ', '', $request->contract_id_fee)).'_'.(str_replace(' ', '', $filename)).'_1.'. $extension;
                 // dd($fileToSave);
                 $file->storeAs($path,$fileToSaveUnpaid, ['disk' => 'public']);
                 $dataToSave['fee_unpaid_path'] = $fileToSaveUnpaid;
                 $dataToSave['fee_path'] = null;
             }
 
             $editFee = ContractFee::updateOrCreate(
                 ['id' => $request->contract_id_fee],
                 $dataToSave  
             );
             if($editFee){
                 //Obtiene el contrato a editar
                 $contractToUpdate = Contract::from('contracts as c')
                     ->leftJoin('leads as le', 'c.lead_id','le.id')
                     ->where('c.enrollment_number','=',$request->enrollment_number)
                     ->select('c.*')
                     ->get()->first();
 
                 // Actualiza el estado del lead a matriculado cuando sube la primera cuota unicamente
                 // dd($request->fee_payment_status);
                 if($editFee->fee_number == 0 && $request->fee_payment_status[0]){
                     // dd("entro al otro");
                     $leadToUpdate = Lead::from('leads as le')
                         ->leftJoin('contracts as c', 'le.id','c.lead_id')
                         ->where('c.enrollment_number','=',$request->enrollment_number)
                         ->select('le.*')
                         ->get()->first();
                     Lead::find((int)$leadToUpdate->id)->update(['lead_status_id'=> 4,
                                                                 'dt_payment'=>$request->fee_payment_dt_paid
                                                                 ]);
 
                 // dd($contractToUpdate);
                     if ($contractToUpdate->contract_type_id == 1) { //Solo cambia el estado a contratos de cursos
                         Contract::find((int)$contractToUpdate->id)->update(['contract_status_id'=>3,'dt_paid'=>$request->fee_payment_dt_paid]);
                     }
                     
                 }
                 //Sino, entonces vuelve y deja el estado en Aceptado y no Matriculado
                 elseif ($editFee->fee_number == 0 && !$request->fee_payment_status[0]) {
                     $leadToUpdate = Lead::from('leads as le')
                         ->leftJoin('contracts as c', 'le.id','c.lead_id')
                         ->where('c.enrollment_number','=',$request->enrollment_number)
                         ->select('le.*')
                         ->get()->first();
                     Lead::find((int)$leadToUpdate->id)->update(['lead_status_id'=> 3]);
 
                 }
                 // dd($contractToUpdate);
                 
                 return response()->json(['success'=>'Cuota actualizada correctamente']);    
             }        
             else{
                 return response()->json(['error'=>'Error en la actualización']);    
             }
         }
         
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContractFee  $contractFee
     * @return \Illuminate\Http\Response
     */
    public function show(ContractFee $contractFee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContractFee  $contractFee
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
     {
         $edit = ContractFee::from('contract_fees as c')
                         ->where('c.id','=',$id)
                         ->leftJoin('contracts as con','c.contract_id','=','con.id')
                         ->select('c.*','con.enrollment_number as enrollment_number')
                         ->orderByRaw(DB::Raw('c.created_at, c.id desc'))
                         ->get()->first();
 
         return response()->json($edit);
     }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContractFee  $contractFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContractFee $contractFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContractFee  $contractFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContractFee $contractFee)
    {
        //
    }
}
