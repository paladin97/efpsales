<?php

namespace App\Http\Controllers;

use App\Models\{WhatsappTemplate,WhatsappTemplateType,User};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB, Validator; 

class WhatsappTemplateController extends Controller
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
             $whatsapptemplate = WhatsappTemplate::from('whatsapp_templates as whatsapptemplate')
                     ->leftJoin('whatsapp_template_types as whatsapptype','whatsapptemplate.template_type_id','=','whatsapptype.id')
                     ->select('whatsapptemplate.*','whatsapptype.name as whats_app_type');
 
             if ($request->ajax()) {
                 // $services = Service::all();  le
                 
                 return DataTables::of($whatsapptemplate)
                                 ->addColumn('acciones', function($row){
                                     $user = Auth::user();
                                     $btn ='<div class="dropdown show" align="center">
                                     <a  data-disabled="true" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         <i class="fad  fa-fw fa-lg fa-ellipsis-v mr-1 text-lightblue"></i>
                                     </a>
                                     <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';   
                              
                                    $btn = $btn. ' <a class="dropdown-item editTemplate" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                                    $btn = $btn. ' <a class="dropdown-item deleteTemplate" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                                    
                                    $btn = $btn.'</div></div>';
                                    return $btn;
                                 })
                                 ->rawColumns(['acciones'])
                                 ->make(true);
                 }
                 $pageTitle ='Plantillas Whatsapp - '.$companies->pluck('name')[0];
                 
                 return view('admin.whatsapptemplates.index',compact('companies','pageTitle')); 
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
             'template_type_id' => ['required'],
             'template_name' => ['required'],
             'template_text' => ['required'],  
         ];
         $customMessages = [
             'template_type_id.required' => 'El tipo de Plantilla es obligatoria',
             'template_name.required' => 'El nombre de la plantilla es obligatoria',
             'template_text.required' => 'La plantilla es obligatoria',
         ];
         $validator = Validator::make($request->all(),$rules,$customMessages);
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
         }
         else{
            WhatsappTemplate::updateOrCreate(
                 ['id' => $request->template_id],
                 ['template_type_id' => $request->template_type_id
                  ,'name' => $request->template_name
                  ,'template' => $request->template_text]
             );        
             return response()->json(['success'=>'Se creó el registro correctamente.']);
         }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WhatsappTemplate  $smsNotificationTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(WhatsappTemplate $smsNotificationTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WhatsappTemplate  $smsNotificationTemplate
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
     {
         $term = WhatsappTemplate::find($id);
 
         return response()->json($term);
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WhatsappTemplate  $smsNotificationTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WhatsappTemplate $smsNotificationTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WhatsappTemplate  $smsNotificationTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $templateToDelete = WhatsappTemplate::find($id)->delete();
        return response()->json(['success'=>'El registro se ha eliminado correctamente.']);
    }
}
