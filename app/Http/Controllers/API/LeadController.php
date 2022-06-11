<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Auth,DB;
use App\Models\Lead;
use App\Http\Resources\LeadResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
        $user = Auth::user();
        if ($user->hasRole('superadmin')){
         $lead = Lead::all();
         return response([ 'lead' => LeadResource::collection($lead), 'message' => 'Retrieved successfully'], 200);
        }
        else{
            return response(['message' => 'Forbidden'], 403);
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
        $user = Auth::user();
        if ($user->hasRole('api') || $user->hasRole('superadmin')){
            $rules = [
                'course_id' => ['required'],
                'student_first_name' => ['required','string'],
                'student_last_name' => ['required','string'],
                'student_email' => ['required','email'],
                'student_mobile' => ['required','regex:/[0-9+]{9,13}/'],
                'province_id' =>  ['required'],
                'country_id' =>  ['required'],
                'leads_origin_id' =>  ['required']
            ];
            $customMessages = [
                'course_id.required' => 'El curso es obligatorio',
                'student_first_name.required' => 'El nombre es obligatorio',
                'student_first_name.string' => 'El nombre debe contener solo texto',
                'student_last_name.required' => 'El apellido es obligatorio',
                'student_last_name.string' => 'El apellido debe contener solo texto',
                'student_email.required' => 'El email es obligatorio',
                'student_email.email' => 'El email debe tener el formato correcto',
                'student_mobile.required' => 'El móvil es obligatorio',
                'student_mobile.regex' => 'El formato del télefono es incorrecto, debe usar: +34,0034 ó 678912345',
                'province_id.required' => 'La provincia es obligatorio',
                'country_id.required' => 'El país es obligatorio',
                'leads_origin_id.required' => 'El origen es obligatorio'
            ];
            $validator = Validator::make($request->all(),$rules,$customMessages);
            
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->getMessageBag()->toArray()],409);
            }
            else{
                $leadsMatchThese = ['student_first_name' => $request->student_first_name, 'student_last_name' => $request->student_last_name
                                , 'student_mobile' => $request->student_mobile , 'student_email' => $request->student_email ]; 
            
                $infoLeadDuplicated = Lead::from('leads as le')
                            ->where($leadsMatchThese)
                            ->whereNotExists(function ($query) {
                                $query->select("c.*")
                                    ->from('contracts as c')
                                    ->whereIn('c.contract_status_id',[2,3])
                                    ->whereRaw('c.lead_id = le.id');
                                })
                            ->select('le.*')
                            ->orderByRaw(DB::Raw("le.created_at desc"))
                            ->get();
        
                if(!$infoLeadDuplicated->isEmpty()){
                    return response()->json(['error'=>['Error, El lead ya existe en nuestra base de datos']],409);
                }   
                // dd($lead_status_id);
                $lead = Lead::create(
                    [
                    'external_id' => $request->external_id,
                    'dt_reception' => $request->dt_reception,
                    'dt_assignment'=> $request->dt_reception, 'dt_last_update' => null,
                    'dt_enrollment' => null, 'dt_payment'=> null,
                    'course_id' => (int)$request->course_id, 
                    'student_first_name' => $request->student_first_name,  
                    'student_last_name' => $request->student_last_name,
                    'student_mobile' => $request->student_mobile, 
                    'student_email' => $request->student_email,
                    'student_dt_birth' => $request->student_dt_birth,
                    'student_laboral_situation' => $request->student_laboral_situation,
                    'province_id' => (int)$request->province_id,
                    'country_id' => (int)$request->country_id,
                    'lead_status_id' => 1,//Estado nuevo
                    'lead_type_id' => 1,//Tipo 1, nuevo
                    'lead_sub_status_id' => null,
                    'dt_call_reminder' => null,
                    'observations' => $request->observations,
                    'original_agent_id' => null, 
                    'agent_id' => 7, 'leads_origin_id' => $request->leads_origin_id
                    ]
                );     
                return response()->json([ 'lead' => new LeadResource($lead), 'message' => 'Created successfully'], 200);   
                
            }
        }
        else{
            return response()->json(['message' => 'Forbidden'], 403);
        }
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        $user = Auth::user();
        if ($user->hasRole('superadmin')){
            return response([ 'lead' => new LeadResource($lead), 'message' => 'Retrieved successfully'], 200);
        }
        else{
            return response(['message' => 'Forbidden'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {

        $lead->update($request->all());

        return response([ 'lead' => new LeadResource($lead), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response(['message' => 'Deleted']);
    }
}
