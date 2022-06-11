<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">   
        <div class="modal-content">     
            <div class="modal-header color-palette bg-lightblue">    
                <h4 class="modal-title" id="leadsHeading">Editar Usuario</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>           
            </div>     
            <div class="modal-body">    
                <form id="userForm" name="userForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="user_role_id" id="user_role_id">
                    <input type="hidden" name="person_id" id="person_id">
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Nombre <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Ingrese el nombre" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Apellidos <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" placeholder="Ingrese el apellido" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Email <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Agregue un Email" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="email_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Enlace GoLinks <label class="text-red">&nbsp;</label></label>
                                <input type="text" class="form-control form-control-sm" id="profile_url" name="profile_url" placeholder="Enlace de GoLinks" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="email_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Empresa <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm  list_select"id="user_company" name="user_company" required="">
                                    {{-- @if(Auth::user()->hasRole('superadmin')) --}}
                                        @foreach(App\Models\Company::all() as $cData)
                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                        @endforeach
                                </select>
                                <small style="font-size: 70%" id="user_club_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Tipo Empleado <label class="text-red">(*)</label></label>
                                <div class="col-sm-12">
                                    <select style="width:100%;"  class="form-control form-control-sm list_select" id="newuser_person_type" name="newuser_person_type" maxlength="250">
                                        @foreach(App\Models\PersonType::whereNotIn('id',[1,5,7])->orderBy('name','ASC')->get() as $cData)
                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Rol <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm list_select"id="user_role" name="user_role" required="">
                                    @foreach(App\Models\Role::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->slug}}</option>
                                    @endforeach
                                </select>
                                <small style="font-size: 70%" id="user_role_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Password <label class="text-red"><small>Rellenar solo si va a cambiar la clave</small></label></label>
                                <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Introduzca una contraseña" value="" maxlength="250">
                                <small style="font-size: 70%" id="password_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">&nbsp;<label class="text-red">&nbsp;</label></label>
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="user_status" name="user_status">
                                    <label class="custom-control-label" for="user_status">Estado</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                    <small>
                        <ol id="leadCreateOrUpdateError">

                        </ol>
                    </small>
            <div class="modal-footer">
                <button type="submit" class="btn bg-lightblue" id="saveBtn" value="create-user"><span>Guardar Cambios</span></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span>Cancelar</span></button>
                <p>
                    <div class="alert alert-dismissible" style="display: none; background-color: green;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <div id="alert_message_lead" align="center" style="color:white;"></div>
                    </div>
            </div>
        </div>
    </div>
</div>