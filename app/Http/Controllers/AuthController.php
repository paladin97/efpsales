<?php

namespace App\Http\Controllers;
use Auth,DB;
use Validator;
use App\Models\{User,Lead};
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
    /**
     * Registro de LEAD
     */
    public function insertLead(Request $request)
    {
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
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
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
                return response()->json(['error'=>['Error, El lead ya existe en nuestra base de datos']]);
            }   
            // dd($lead_status_id);
            Lead::create(
                ['dt_reception' => $request->dt_reception,
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
                'lead_sub_status_id' => null,
                'dt_call_reminder' => null,
                'observations' => $request->observations,
                'original_agent_id' => null, 
                'agent_id' => 7, 'leads_origin_id' => $request->leads_origin_id
                ]
            );        
            return response()->json([
                'message' => 'Successfully created lead!'
            ], 201);
        }
        
    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}