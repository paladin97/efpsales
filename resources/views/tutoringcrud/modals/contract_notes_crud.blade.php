{{-- Modal de Notas de Gestión --}}
<div class="modal modal-wide fade" id="managementNotesModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="managementNotesHeading">Notas de tutorías</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times  text-white"></i></span>
                </button>        
            </div>     
            <div class="modal-body">
                <form id="managementFormNotes" name="managementFormNotes" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="contract_id_notes" id="contract_id_notes">
                    <input type="hidden" name="contract_enrollment_notes" id="contract_enrollment_notes">
                    <div class="card-deck">
                        <div class="card card-lightblue card-outline col-sm-8">
                            <div class="card-body p-1">
                                <div class="row mt-1" style="margin: auto;">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Medio contacto <label class="text-red">(*)</label></label>
                                            <select style="width:100%;" class="form-control form-control-sm select_list" id="management_notes_method" name="management_notes_method" maxlength="250">
                                                <option value="Télefono" selected="selected">Télefono</option>
                                                <option value="Email">E-mail</option>
                                                <option value="Whatsapp">Whatsapp</option>
                                                <option value="Portal">Video Llamada</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Tipo <label class="text-red">(*)</label></label>
                                            <select style="width:100%;" class="form-control form-control-sm select_list" id="management_notes_type" name="management_notes_type" maxlength="250">
                                                <option value="Entrante" selected="selected">Entrante</option>
                                                <option value="Saliente">Saliente</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Categoría <label class="text-red">(*)</label></label>
                                            <select  style="width:100%;" class="form-control form-control-sm select_list" id="management_category" name="management_category[]">
                                                @if(Auth::user()->hasRole('teacher')) 
                                                    @foreach(App\Models\ManagementNoteCategory::whereId(4)->orderBy('name','ASC')->get() as $cData)
                                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Seleccione una categoría</option>
                                                    @foreach(App\Models\ManagementNoteCategory::all()->sortBy('name') as $cData)
                                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1"> Fecha recordatorio <label class="text-red">(*)</label></label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="management_dt_reminder" name="management_dt_reminder" placeholder="Ingrese una fecha" value="" maxlength="250">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin: auto;">
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Observaciones<label class="text-red">(*)</label></label>
                                            <textarea  id="management_notes_observation" name = "management_notes_observation" class="form-control" style="height:auto;" rows="5" maxlength="250" placeholder="Ingrese una observación."></textarea> 
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Soporte<label class="text-red">&nbsp;</label></label>
                                            <input type="file"  class="form-control  form-control-sm" id="management_file" name="management_file" placeholder="" value="" maxlength="250">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-lightblue card-outline col-sm-4">
                            <div class="card-body p-1">
                                <div id="managementContractInfo"></div>
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        <button type="submit" class="btn bg-lightblue" id="saveBtnManagementNotes" value="lead-notes">Crear Nota</button>
                    </div>
                    <p class="text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i>Información. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                    <small>
                        <ol id="managementNoteError">
                            
                        </ol>
                    </small>
                    <div class="card card-lightblue card-outline">
                        <div class="card-body">
                            <h3 align="center">Histórico de Tutorías</h3>
                            <div class="row" style="margin:auto;">
                                <div class="col-sm-12">
                                    <table id="datatable_management" class="small-table table-sm stripe row-border order-column compact table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Fecha Gestión</th>
                                                <th>Hora</th>
                                                <th>Medio de contacto</th>
                                                <th>Tipo</th>
                                                <th>Categoría</th>
                                                <th>Observaciones</th>
                                                <th>Usuario</th>
                                                <th>Soporte</th>
                                                <th>Acciones</th>
                                                <th>ID</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><div align="right">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <p></p>
                        <div class="alert alert-dismissible" style="display: none; background-color: green;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <div id="alert_message_note_lead" align="center" style="color:white;"></div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>