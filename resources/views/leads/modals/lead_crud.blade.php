<div class="modal fade" id="leadsModal" tabindex="-1" role="dialog" aria-labelledby="leadsHeading" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">     
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="leadsHeading">Editar Empresa</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>        
            </div>  
            <div class="modal-body">    
                <form id="leadForm" name="leadForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="lead_id" id="lead_id">
                    <input type="hidden" name="lead_dt_assignment_hidden" id="lead_dt_assignment_hidden">
                    <input type="hidden" name="lead_dt_reception_hidden" id="lead_dt_reception_hidden">
                    <input type="hidden" name="lead_agent_id_hidden" id="lead_agent_id_hidden">
                    <input type="hidden" name="lead_status_id_hidden" id="lead_status_id_hidden">
                    <input type="hidden" name="lead_sub_status_id_hidden" id="lead_sub_status_id_hidden">
                    <input type="hidden" name="lead_origin_id_hidden" id="lead_origin_id_hidden">
                    <input type="hidden" name="lead_type_id_hidden" id="lead_type_id_hidden">
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Curso <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="modal_courses_list" name="modal_courses_list">
                                    <option value="">Seleccione un servicio</option>
                                    @php

                                    $courses = "";

                                    if(Auth::user()->hasRole('superadmin')){

                                        $courses = App\Models\Course::all()->sortBy('name');

                                    }else{   

                                        $courses = App\Models\Course::from('courses as c')
                                        ->whereIn('c.company_id',Auth::user()->companies->pluck('id'))
                                        ->select('c.*')
                                        ->get()->sortBy('name');
                                        
 
                                    }

                                    @endphp

                                    @foreach($courses as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Nombre(s)  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="first_name" name="first_name" placeholder="Ingrese los nombres" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Apellido(s)  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" placeholder="Ingrese los apellidos" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Email  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Ingrese el email" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Móvil  <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="mobile" name="mobile" placeholder="Ingrese un teléfono" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Provincia  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="modal_provinces_list" name="modal_provinces_list[]">
                                    <option value="">Seleccione una provincia</option>
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
                                <label for="name" class="mb-n1">País  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="countries_list" name="countries_list[]">
                                    <option value="">Seleccione una país</option>
                                        @foreach(App\Models\Country::all()->sortBy('name') as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Fecha nacimiento <label class="text-red">&nbsp;</label></label>
                                <input type="date" class="form-control form-control-sm" id="dt_birth" name="dt_birth" placeholder="Ingrese una fecha" value="" maxlength="250">
                            </div>
                        </div>
                        @if(Auth::user()->hasRole('superadmin'))
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="mb-n1">Origen  <label class="text-red">(*)</label></label>
                                    <select style="width:100%;" class="form-control form-control-sm select_list" id="leads_origins_list" name="leads_origins_list[]">
                                        <option value="">Seleccione una origen</option>
                                            @foreach(App\Models\LeadOrigin::all()->sortBy('name') as $cData)
                                                <option value="{{$cData->id}}">{{$cData->name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                        <div class="col-sm-4" hidden>
                            <div class="form-group">
                                <label for="name" class="mb-n1">Origen  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm" id="leads_origins_list" name="leads_origins_list[]">
                                        @foreach(App\Models\LeadOrigin::whereIn('id',[3])->orderBy('name','ASC')->get() as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="col-sm-6 control-label">Observación de Interés</label>
                                <textarea id="int_observ" name = "int_observ" class="form-control form-control-sm" style="height:auto;" rows="3" maxlength="5000" placeholder="Observación de interés"></textarea>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('superadmin'))
                    <hr class="mt-1 mb-1">
                    <h3 align="center"> Administración</h3>
                    <hr class="mt-1 mb-2">
                    <div class="row mt">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Fecha de recepción  <label class="text-red">(*)</label></label>
                                <input type="datetime-local" class="form-control form-control-sm" id="dt_reception_edit" name="dt_reception_edit" placeholder="Ingrese una fecha" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Fecha de asignación  <label class="text-red">(*)</label></label>
                                <input type="datetime-local" class="form-control form-control-sm" id="dt_assignment_edit" name="dt_assignment_edit" placeholder="Ingrese una fecha" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Comercial  <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="agents_list" name="agents_list[]" disabled>
                                    <option value="">Seleccione un comercial</option>
                                    @foreach (App\Models\User::whereStatus(1)->get()->sortBy('name') as $cData)
                                        @if ($cData->hasRole('comercial'))
                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="col-sm-1 control-label">Observaciones</label>
                                <textarea id="observations" name = "observations" class="form-control form-control-sm" style="height:auto;" rows="3" maxlength="5000" placeholder="Ingrese una observación."></textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                    <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                    <small>
                        <ol id="leadCreateOrUpdateError">

                        </ol>
                    </small>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-lightblue" id="saveLeadBtn" value="create-product">Guardar Cambios</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <p>
                        <div class="alert alert-dismissible" style="display: none; background-color: green;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <div id="alert_message_lead" align="center" style="color:white;"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>