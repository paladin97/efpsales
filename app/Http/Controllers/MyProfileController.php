<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\{User, UserRole, PersonInf, Company};
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use DB, Validator;

class MyProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {   
        if (Auth::check()) {  
            
            $user = Auth::user();
            $companies = User::find($user->id)->companies;
            $user_role = UserRole::whereUserId($user->id)->get()->first();
            if ($request->ajax()) {
                $user = User::from('users as u')
                    ->leftJoin('people_inf as pe','u.person_id','=','pe.id')
                    ->leftJoin('companies as co', 'pe.company_id','=','co.id')
                    ->leftJoin('user_roles as ur', 'ur.user_id','=','u.id')
                    ->leftJoin('roles as r', 'ur.role_id','=','r.id')
                    ->leftJoin('access_companies as ascom','ascom.company_id','=','co.id')
                    ->where('ascom.user_id','=',$user->id)
                    ->select('u.*','co.name as company','r.name as role_name','r.slug as role_slug','u.avatar as user_avatar',
                                'ur.id as user_role_id','pe.id as person_id',
                                DB::raw('CONCAT(u.name, " ", u.last_name) as full_name')) 
                    ->orderByRaw(DB::Raw("u.created_at desc"))
                    ->get();
                // ->toSql();
            }
            
            // $info = PersonInf::whereId(Auth::user()->person_id)->get()->first();
            
            $pageTitle ='Perfil - '.$companies->pluck('name')[0];
            return view('profile.index', compact('user','pageTitle','companies','user_role'));
        }
        else{
            return view('auth.login');
        }    
    }

    public function storeNShow(Request $request)
    {
        $rules = [
            'avatar' => ['sometimes','mimes:jpeg,png,jpg,gif,svg|max:2048'],
            'user_name' => ['required','regex:/[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßŒÆČŠŽ∂ð]{3,20}/'],
            'last_name' => ['required','regex:/[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßŒÆČŠŽ∂ð]{3,20}/'],
            'mail' => ['required','regex:/[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/'],
            // 'user_company' =>  ['required'],
            // 'user_role' =>  ['required'],
            'password' => ['sometimes'],
            ];
        $customMessages = [
            'avatar.sometimes' => 'Debe escoger un fichero',
            'avatar.mimes' => 'solo formatos jpeg,png,jpg,gif,svg, max:2048',
            'user_name.required' => 'Debes insertar un nombre',
            'user_name.regex' => 'El nombre debe ser entre 3 y 20 caracteres',
            'last_name.required' => 'Debes insertar un apellido',
            'last_name.regex' => 'El apellido debe ser entre 3 y 20 caracteres',
            'mail.required' => 'Debes ingresar un correo electrónico',
            'mail.regex' => 'Ingrese un formato válido para el correo electrónico',
            'user_company.required' => 'Debes asociar una empresa al usuario',
            'user_role.required' => 'Debes asignarle una rol al usuario',
            'password.sometimes' => 'La contraseña es obligatoria',
        ];
        
        $validator = Validator::make($request->all(),$rules,$customMessages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{
            $filename="no-image.png";
            if ($request->user_id){
                $filename=User::whereId($request->user_id)->get()->first()->avatar;
            }
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filetext = $request->file('avatar')->getClientOriginalName();
                $filename = pathinfo($filetext, PATHINFO_FILENAME);
                $extension = pathinfo($filetext, PATHINFO_EXTENSION);
                $filename =  (str_replace(' ', '', $request->user_name)).'_'.(str_replace(' ', '', $filename)).'_1.'. $extension;
                
                // dd($filename);
                $file->storeAs('/uploads/users/',$filename, ['disk' => 'public']);
            }
            
            $modelPersonToUpdate = ['name' => $request->user_name, 'profile_url' => $request->profile_url, 'last_name' => $request->last_name, 
                                    'mail'=> $request->mail];

            $personExists = PersonInf::find($request->person_id);
            // dd($request);
            if(!$personExists){
                $modelPersonToUpdate['avatar'] = 'no-image.png';
            }
            // dd($modelPersonToUpdate);
            $modelperson = PersonInf::updateOrCreate(
                ['id' => $request->person_id],
                $modelPersonToUpdate
            );
            
            $idNewPerson = $modelperson->id;
            $dataUserToUpdate = ['name' => $request->user_name, 'last_name' => $request->last_name, 'avatar' => $filename,
                                'email' => $request->mail, 'profile_url' => $request->profile_url];
            if($idNewPerson){
                $dataUserToUpdate['person_id'] = (integer)$idNewPerson;
            }
            if($request->password){
                $dataUserToUpdate['password'] = bcrypt($request->password);
            }
            $modeluser = User::updateOrCreate(
                ['id' => $request->user_id],
                $dataUserToUpdate
            );  
            $idNewUser = $modeluser->id;
            // $modelRole = UserRole::updateOrCreate(
            //     ['id' => $request->user_role_id],
            //     ['user_id' => $idNewUser, 'role_id' =>$request->user_role]
            // );

            // $modelAccessCompany = AccessCompany::updateOrCreate(
            //     ['id' => null],
            //     ['user_id' => $idNewUser, 'company_id' =>$request->user_company]
            // );
            $responseUser = User::from('users as u')
                    ->leftJoin('people_inf as pe','u.person_id','=','pe.id')
                    ->leftJoin('companies as co', 'pe.company_id','=','co.id')
                    ->leftJoin('user_roles as ur', 'ur.user_id','=','u.id')
                    ->leftJoin('roles as r', 'ur.role_id','=','r.id')
                    ->leftJoin('access_companies as ascom','ascom.user_id','=','u.id')
                    ->where('u.id','=',$modeluser->id)
                    ->select('u.*','co.name as company','r.name as role_name','r.slug as role_slug','u.avatar as user_avatar',
                                'ur.id as user_role_id','pe.id as person_id', 'pe.name as user_name','pe.last_name as user_last_name',
                                DB::raw('CONCAT(u.name, " ", u.last_name) as full_name')) 
                    ->orderByRaw(DB::Raw("u.created_at desc"))
                    ->get()->first();
            $responseWarning = ['showinformation'=>$responseUser];

            return response()->json([['success'=>'Registro actualizado correctamente.'],$responseWarning]);
       }
    }
}
