<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Auth;
use DataTables;
use DB, Validator;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {

            if ($request->ajax()) {
                $status = Quote::all();
                return DataTables::of($status)
                                ->addColumn('acciones', function($row){
                                    $btn ='<div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                                    $btn = $btn. ' <a class="dropdown-item editQuote" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                                    $btn = $btn. ' <a class="dropdown-item deleteQuote" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                                    $btn = $btn.'</div></div>';
            
                                    return $btn;
                                })
                                // ->rawColumns(['acciones','checkbox'])
                                ->rawColumns(['acciones'])
                                ->make(true);
                }
                return view('admin.quotes.index');
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
        $quote_id_array = $request->input('leadstatus_id');
        $leadstatus = Quote::whereIn('id', $quote_id_array);
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
            'quote' => ['required'],  
            'author' => ['required'] 
        ];
        $customMessages = [
            'quote.required' => 'El campo nombre es obligatorio',
            'author.required' => 'El campo autor es obligatorio',
        ];
        $validator = Validator::make($request->all(),$rules,$customMessages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{
            Quote::updateOrCreate(
                ['id' => $request->quote_id],
                ['quote' => $request->quote
                 ,'description' => $request->description
                 ,'author' => $request->author]
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
        $quote = Quote::find($id);

        return response()->json($quote);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $leadStatus)
    {
        //
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // dd(Banks::find($id));
        Quote::find($id)->delete();
        return response()->json(['success'=>'Frase eliminada correctamente.']);
    }
}

