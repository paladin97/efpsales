<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{User,Company, PersonInf};
use Carbon\Carbon;
use Auth;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use DB, Validator;



class CompanyController extends Controller
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
            $company_id = PersonInf::from('people_inf as p')
                                ->leftJoin('users as us','us.person_id','=','p.id')
                                ->where('us.id','=',$user->id)
                                ->select('p.*')
                                ->first()->company_id;


            if ($request->ajax()) {

                $company = Company::from('companies as co')
                                            ->leftJoin('provinces as p', 'co.province_id','=','p.id')
                                            ->leftJoin('banks as b', 'co.bank_id','=','b.id')
                                            ->select('co.*','p.name as province_name','b.name as bank_name')
                                            ->orderByRaw(DB::Raw("co.created_at desc"))
                                            ->get();

                // $companies = Company::all();
                return DataTables::of($company)
                                ->addColumn('acciones', function($row){
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    $btn = $btn. ' <a class="dropdown-item editCompany" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                                    $btn = $btn. ' <a class="dropdown-item deleteCompany" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                                    $btn = $btn.'</div></div>';

                                    return $btn;
                                })
                                // ->rawColumns(['acciones','checkbox'])
                                ->rawColumns(['acciones'])
                                ->make(true);
                }
                $pageTitle ='Empresas - '.$companies->pluck('name')[0];
                return view('admin.company.index',compact('pageTitle'));
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
       
    }
    /**
     * Massive Delete
     *
     * @return \Illuminate\Http\Response
     */
    function massremove(Request $request)
    {
        // dd($request);
        $company_id_array = $request->input('company_id');
        $company = Company::whereIn('id', $company_id_array);
        if($company->delete()){
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
        // dd($request);
        $rules = [
            'name' => ['required'],
            'cif' => ['required'],   
        ];
        $customMessages = [
                'name.required' => 'El campo nombre es obligatorio',
                'cif.required' => 'El campo cif es obligatorio',
        ];
        $validator = Validator::make($request->all(),$rules,$customMessages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{
            
            Company::updateOrCreate(
                ['id' => $request->company_id],
                ['name' => $request->name,'description' => $request->description, 'mail' => $request->mail, 'phone' => $request->phone, 
                'cif' => $request->cif, 'address' => $request->address, 'town' => $request->town, 'postal_code' => $request->postal_code, 
                'leg_rep_nif' => $request->leg_rep_nif, 'leg_rep_full_name' => $request->leg_rep_full_name, 'logo_path' => $request->logo_path, 
                'bank_account' => $request->bank_account,'switf' => $request->switf, 'province_id' => $request->company_province, 
                'bank_id' => $request->company_bank,'url_facebook' => $request->url_facebook,'url_instagram' => $request->url_instagram,
                'url_website' => $request->url_website,'url_business' => $request->url_business
                ]
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
        $company = Company::find($id);

        return response()->json($company);
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
        Company::find($id)->delete();
        return response()->json(['success'=>'Empresa eliminada correctamente.']);
    }

    public function cancelCompany(Request $request)
    {
        // dd($drop_contract_id);
        $cancel_company_id= decrypt($request->cancel_company_id);
        // $dataToSave['contract_status_id']= 6;
        
        $cancelCompany = Company::updateOrCreate(
            ['id' => $cancel_company_id],
            $dataToSave  
        );
        
        $wasChanged = $cancelCompany->wasChanged();
        if($wasChanged){
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>anulado  con exito</p>']);    
        }
        else{
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-error"></i>anulación no realizada</p>']);    
        }   
    }
}