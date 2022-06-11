<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modalHeadingPeople">Editar</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>           
            </div>    
            <div class="modal-body">    
                <form id="peopleForm" name="peopleForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="course_id" id="course_id">
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Nombre(s)  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_first_name" name="case_first_name" placeholder="Ingrese los nombres" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Apellido(s)  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_last_name" name="case_last_name" placeholder="Ingrese los apellidos" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">NIE/DNI/PASS <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_dni" name="case_dni" placeholder="Ingrese el documento" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Domilicio  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_address" name="case_address" placeholder="Ingrese el domicilio" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Población <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_town" name="case_town" placeholder="Ingrese la provincia" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Código postal <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_cp" name="case_cp" placeholder="Ingrese el código postal" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Provincia  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list"id="case_provinces_list" name="case_provinces_list[]">
                                    <option value="">Seleccione..</option>
                                    @foreach(App\Models\Province::all()->sortBy('name') as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Email  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_email" name="case_email" placeholder="Ingrese el email"  value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Móvil  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_mobile" name="case_mobile" placeholder="Ingrese el móvil" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Télefono  <label class="text-red">&nbsp;</label></label>
                                <input type="text" class="form-control form-control-sm" id="case_phone" name="case_phone" placeholder="Ingrese el télefono" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Fecha nacimiento <label class="text-red">(*)</label></label>
                                <input type="date" class="form-control form-control-sm" id="case_dt_birth" name="case_dt_birth" placeholder="Ingrese una fecha" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Nacionalidad  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="case_countries_list" name="case_countries_list[]">
                                    <option value="71">España</option>
                                    @foreach(App\Models\Country::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Estudios  <label class="text-red">(*)</label></label>
                                <input type="input" class="form-control form-control-sm" id="case_studies" name="case_studies" placeholder="Ingrese los estudios" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Profesión  <label class="text-red">(*)</label></label>
                                <input type="input" class="form-control form-control-sm" id="case_profession" name="case_profession" placeholder="Ingrese una profesión" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2 d-none">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Género <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="case_gender" name="case_gender">
                                    <option value = 'M'>Masculino</option>
                                    <option value = 'F'>Femenino</option>
                                    <option value = 'P'>Pangénero</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Estado civil  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list"  id="case_marital_status" name="case_marital_status">
                                    <option value = 'NSA' selected="selected">No se aporta</option>
                                    <option value = 'SOL'>Soltero/a</option>
                                    <option value = 'CAS'>Casado/a</option>
                                    <option value = 'DIV'>Divorciado/a</option>
                                    <option value = 'VIU'>Viudo/a</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Empresa  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="company_list" name="company_list">
                                    <option value="">Seleccione</option>
                                    @foreach(App\Models\Company::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Tipo Persona  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="person_type_list" name="person_type_list">
                                    <option value="">Seleccione</option>
                                    @foreach(App\Models\PersonType::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Modelo Liquidación  <label class="text-red">&nbsp;</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="liquidation_list" name="liquidation_list">
                                    <option value="">Seleccione</option>
                                    @foreach(App\Models\LiquidationModel::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Banco <label class="text-red">&nbsp;</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="bank_list" name="bank_list">
                                    <option value="">Seleccione</option>
                                    @foreach(App\Models\Bank::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">IBAN  <label class="text-red">&nbsp;</label></label>
                                <input type="input" class="form-control form-control-sm" id="bank_iban" name="bank_iban" placeholder="Ingrese una profesión" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Salario  <label class="text-red">&nbsp;</label></label>
                                <input type="input" class="form-control form-control-sm" id="salary" name="salary" placeholder="Ingrese una profesión" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                </form>
                {{-- @include('partials.admin.information') --}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-lightblue" id="saveBtn" value="create-product">Guardar Cambios</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <p>
                <div class="alert alert-dismissible" style="display: none; background-color: green;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <div id="alert_message_lead" align="center" style="color:white;"></div>
                </div>
            </div>
        </div>
    </div>
</div>