<?php

namespace App\Http\Controllers;

use App\Models\{User,Term};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB, Validator; 


class TermController extends Controller
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
            // dd($user->permissions->toArray()[0]['id']);
            $companies = User::find($user->id)->companies;
            if ($request->ajax()) {
                // $services = Service::all();  le
                
                $terms = Term::from('terms as term')
                        ->leftJoin('companies as com','term.company_id','=','com.id')
                        ->leftJoin('contract_types as ct','term.contract_type_id','=','ct.id')
                        ->whereIn('term.company_id',$companies->pluck('id'))
                        ->select('term.*','com.name as term_company_name','ct.name as contract_type');


                return DataTables::of($terms)
                                ->addColumn('acciones', function($row){
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    $btn = $btn. ' <a class="dropdown-item editTerm" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                                    $btn = $btn. ' <a class="dropdown-item deleteTerm" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                                    $btn = $btn.'</div></div>';

                                    return $btn;
                                })
                                ->rawColumns(['acciones'])
                                ->make(true);
            }
            $pageTitle ='Términos y Condiciones - '.$companies->pluck('name')[0];
            return view('admin.terms.index',compact('pageTitle')); 
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
            'modal_term_company' => ['required'],
            'modal_term_ctype' => ['required'],
            'editordata' => ['required'],  
        ];
        $customMessages = [
            'modal_term_company.required' => 'La empresa es obligatoría',
            'modal_term_ctype.required' => 'El tipo de contrato es obligatorío',
            'editordata.required' => 'Los términos son obligatorios',
        ];
        $validator = Validator::make($request->all(),$rules,$customMessages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{
            Term::updateOrCreate(
                ['id' => $request->term_id],
                ['company_id' => $request->modal_term_company, 
                 'contract_type_id' => $request->modal_term_ctype,
                 'terms' => $request->editordata]
            );        
            return response()->json(['success'=>'Se creó el registro correctamente.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function show(Term $term)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
     {
         $term = Term::find($id);
 
         return response()->json($term);
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $term)
    {
        //
    }
}
