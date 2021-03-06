<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, PersonInf, UserRole, AccessCompany, PersonType};
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use DB, Validator;

class UserController extends Controller
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
                    $user = User::from('users as u')
                        ->leftJoin('people_inf as pe','u.person_id','=','pe.id')
                        ->leftJoin('companies as co', 'pe.company_id','=','co.id')
                        ->leftJoin('user_roles as ur', 'ur.user_id','=','u.id')
                        ->leftJoin('roles as r', 'ur.role_id','=','r.id')
                        ->leftJoin('access_companies as ascom','ascom.company_id','=','co.id')
                        ->where('ascom.user_id','=',$user->id)
                        ->select('u.*','co.name as company','r.name as role_name','r.slug as role_slug','u.avatar as user_avatar','u.status as user_status',
                        DB::raw('CONCAT(u.name, " ", u.last_name) as full_name')) 
                        ->orderByRaw(DB::Raw("u.created_at desc"))
                        ->get();
                    // ->toSql();
                        
                return DataTables::of($user)
                        ->addColumn('pathimage', function($row){   
                            $url = asset('storage/uploads/users/'. $row->avatar);
                            $btn = '<a href="javascript:void(0)" class="showplayerinfo" data-id='.$row->id.'><img class="profile-user-img img-responsive img-circle bg-lightblue bg-profile" style="width: 45px; height:45px;" src="'.$url.'"/></a>';
                            return $btn;
                        })
                        ->addColumn('acciones', function($row){
                            $btn ='<div class="dropdown" align="center">
                                    <a href="#" data-toggle="dropdown" class="text-secondary " aria-expanded="false">
                                            <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a href="#" data-toggle="dropdown" class="text-secondary"></a>'; 
                            $btn = $btn. ' <a class="dropdown-item editUser" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Editar" > <i class="fad fa-pencil-alt text-lightblue"></i> Editar</a>';
                            $btn = $btn. ' <a class="dropdown-item deleteUser" href="javascript:void(0)" data-container="body"  data-id="'.$row->id.'"  title = "Eliminar" > <i class="fad fa-trash text-lightblue"></i> Eliminar</a>';
                            $btn = $btn.'</div></div>';

                            return $btn;
                        })
                        // ->rawColumns(['acciones','checkbox'])
                        ->rawColumns(['acciones','pathimage'])
                        ->make(true);
                }
                $pageTitle ='Usuarios - '.$companies->pluck('name')[0];
                return view('admin.users.index',compact('pageTitle'));
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
        $user_id_array = $request->input('user_id');
        $user = User::whereIn('id', $user_id_array);
        if($user->delete()){
             return response()->json(['success'=>'Registros eliminados correctamente.']);
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
            'avatar' => ['sometimes','mimes:jpeg,png,jpg,gif,svg|max:2048'],
            'name' => ['required','regex:/[a-zA-Z???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????]{3,20}/'],
            'last_name' => ['required','regex:/[a-zA-Z???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????]{3,20}/'],
            'email' => ['required','regex:/[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/'],
            'newuser_person_type' => ['required'],
            'user_company' =>  ['required'],
            'role' =>  ['required'],
            'password' => ['sometimes'],
            ];
        $customMessages = [
            'avatar.sometimes' => 'Debe escoger un fichero',
            'avatar.mimes' => 'solo formatos jpeg,png,jpg,gif,svg, max:2048',
            'name.required' => 'Debes insertar un nombre',
            'newuser_person_type.required' => 'El tipo de persona es obligatorio',
            'name.regex' => 'El nombre debe ser entre 3 y 20 caracteres',
            'last_name.required' => 'Debes insertar un apellido',
            'last_name.regex' => 'El apellido debe ser entre 3 y 20 caracteres',
            'email.required' => 'Debes ingresar un correo electr??nico',
            'email.regex' => 'Ingrese un formato v??lido para el correo electr??nico',
            'user_company.required' => 'Debes asociar una empresa al usuario',
            'role.required' => 'Debes asignarle una rol al usuario',
            'password.sometimes' => 'La contrase??a es obligatoria',
        ];
        
        $validator = Validator::make($request->all(),$rules,$customMessages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
        }
        else{


            $user = new User();
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->profile_url = $request->profile_url;
            $user->email = $request->email;
            $user->profile_url = $request->profile_url;
            $user->password = bcrypt($request->password);
            $user->created_at = Carbon::now();
            $user->updated_at = Carbon::now();
            
            $person_type = PersonType::findOrFail($request->newuser_person_type);
            $persons_inf = $person_type->persons_inf()->get(); 
          
            $persons = collect($persons_inf)->pluck('id');
          
            $id_users = User::all()->pluck('person_id');
            
            $diff = $persons->diff($id_users);
            
            $user->person_id = $diff->first();
            $user->save();

            $user->roles()->attach($request->role);
            $user->companies()->attach($request->user_company);
            

           /*  $filename="";
            if ($request->user_id){
                $filename=User::whereId($request->user_id)->get()->first()->avatar;
            }
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename =  str_replace(' ', '', removeAccents($request->name)) . '.' . $file->getClientOriginalExtension();
                // dd($filename);
                $file->storeAs('/uploads/users/',$filename, ['disk' => 'public']);
            }
            
            $modelPersonToUpdate = ['name' => $request->name, 'profile_url' => $request->profile_url, 'last_name' => $request->last_name, 
                                    'mail'=> $request->email, 'company_id'=>$request->user_company, 'person_type_id'=> $request->newuser_person_type];

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
            $user_status = ($request->user_status == 'on') ? 1 : 2;
            $dataUserToUpdate = ['name' => $request->name, 'last_name' => $request->last_name, 'avatar'=>'no-image.png',
                                'email' => $request->email, 'profile_url' => $request->profile_url, 'status' => $user_status];
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
            
            $modelRole = UserRole::updateOrCreate(
                ['id' => $request->user_role_id],
                ['user_id' => $idNewUser, 'role_id' =>$request->user_role]
            );

            $modelAccessCompany = AccessCompany::updateOrCreate(
                ['id' => null],
                ['user_id' => $idNewUser, 'company_id' =>$request->user_company]
            );
            return response()->json(['success'=>'Operaci??n Finalizada con ??xito.']); */
       }

       return redirect()->route('user.index');
    }
//Crea acceso a la empresa, tabla access_company

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::from('users as u')
                        ->leftJoin('people_inf as pe','u.person_id','=','pe.id')
                        ->leftJoin('companies as co', 'pe.company_id','=','co.id')
                        ->leftJoin('user_roles as ur', 'ur.user_id','=','u.id')
                        ->leftJoin('roles as r', 'ur.role_id','=','r.id')
                        ->leftJoin('provinces as p', 'pe.province_id','=','p.id')
                        ->leftJoin('person_types as perty', 'perty.id', '=','pe.person_type_id')
                        ->leftJoin('countries as c', 'pe.country_id','=','c.id')  
                        ->select('u.*','r.id as role_id','r.name as role_name', 'r.slug as role_slug',
                                'co.id as company_id','co.name as company','u.avatar as user_avatar',
                                'pe.id as person_id','ur.id as user_role_id',
                                'p.name as province','c.path as country_path','co.logo_path as company_path','perty.id as person_type_id',
                                'c.name as country','co.name as company','pe.name as player_name','perty.name as person_type') 
                        ->where('u.id', '=', $id)
                        ->orderByRaw(DB::Raw("u.created_at desc"))
                        ->get();
        // dd($user);
        return response()->json($user);
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $userToDelete = User::whereId((integer)$id)->get()->first();
        $userRoleToDelete= UserRole::whereUserId((integer)$userToDelete->id)->get()->first();
        $personToDelete = PersonInf::whereId((integer)$userToDelete->person_id)->get()->first();
        // dd($userToDelete->name);

        if($userRoleToDelete->delete()){
            if($userToDelete->delete()){
                $personToDelete->delete();
            }
        }
        
        // ->delete();
        return response()->json(['success'=>'El registro se ha eliminado correctamente.']);
    }

    public function cancelUser(Request $request)
    {
        // dd($drop_contract_id);
        $user_id= decrypt($request->user_id);
        // $dataToSave['contract_status_id']= 6;
        
        $cancelUser = User::updateOrCreate(
            ['id' => $user_id],
            $dataToSave
        );
        
        $wasChanged = $cancelUser->wasChanged();
        if($wasChanged){
            return response()->json(['success'=>'<p style="margin:0;"><i class="icon fa fa-check"></i>Borrado  con ??xito</p>']);    
        }
        else{
            return response()->json(['error'=>'<p style="margin:0;"><i class="icon fa fa-error"></i>Borrado no realizado</p>']);    
        }   
    }
}
