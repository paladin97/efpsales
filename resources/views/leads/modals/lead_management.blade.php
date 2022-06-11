<div class="modal modal-wide fade" id="leadNotesModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="leadsNotesHeading">Notas de gestión</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times  text-white"></i></span>
                </button>        
            </div>     
            <div class="modal-body">
                <form id="leadFormNotes" name="leadFormNotes" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="lead_id_notes" id="lead_id_notes">
                    <input type="hidden" name="id_note" id="id_note">
                    <div class="card-deck">
                        <div class="card card-lightblue card-outline col-sm-8">
                            <div class="card-body p-1">
                                <div class="row mt-1" style="margin: auto;">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Medio de contacto <label class="text-red">(*)</label></label>
                                            <select style="width:100%;" class="form-control form-control-sm select_list" id="lead_notes_via" name="lead_notes_via" maxlength="250">
                                                <option value="Télefono" selected="selected">Télefono</option>
                                                <option value="Email">E-mail</option>
                                                <option value="Whatsapp">Whatsapp</option>
                                                <option value="Portal">Videollamada</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Estado<label class="text-red">(*)</label></label>
                                            <select style="width:100%;" class="form-control form-control-sm select_list" id="leads_status_list" name="leads_status_list" maxlength="250">
                                                <option value="">Seleccione una estado</option>
                                                @if(Auth::user()->hasRole('superadmin'))
                                                    @foreach(App\Models\LeadStatus::all()->sortBy('name') as $cData)
                                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                    @endforeach 
                                                @else
                                                    {{-- No mostrar ACEPTADO PARCIAL, CONTRATO ACEPTADO, PTE. ACEPT. CONTRATO y MATRÍCULA --}}
                                                    @foreach(App\Models\LeadStatus::whereNotIn('id',[1,4,9,11,12])->orderBy('name','ASC')->get() as $cData)
                                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                    @endforeach
                                                @endif 
                                            </select>
                                        </div>
                                    </div>
                                    <div id="div_leads_sub_status_list" style="display: none;" class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Sub Estado <label class="text-red">&nbsp;</label></label>
                                                <select style="width:100%;" class="form-control form-control-sm select_list" id="leads_sub_status_list" name="leads_sub_status_list[]">
                                                    @foreach(App\Models\LeadSubStatus::all()->sortByDesc('name') as $cData)
                                                        <option value="{{$cData->id}}" selected="selected">{{$cData->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Fecha Actuación <label class="text-lightblue"> <a href="javascript:void(0)" class="check_calendar text-xs"><i class="fas fa-calendar-check fa-xs"></i> Rev.</a></label></label>
                                            <input type="datetime-local" class="form-control form-control-sm " id="dt_call_reminder" name="dt_call_reminder" placeholder="Ingrese una fecha" value="" maxlength="250">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin: auto;">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name" class="mb-n1">Observaciones<label class="text-red">(*)</label></label>
                                            <textarea  id="lead_notes_observation" name = "lead_notes_observation" class="form-control" style="height:auto;" rows="5" maxlength="2000" placeholder="Ingrese una observación."></textarea> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-lightblue card-outline col-sm-4">
                            <div class="card-body p-1">
                                <div id="leadInfo"></div>
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        <button type="submit" class="btn bg-lightblue" id="saveBtnLeadNotes" value="lead-notes">Crear Nota</button>
                    </div>
                    <p class="text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i>Información. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                    <ol id="leadNoteError">
                            
                    </ol> 
                    <div class="card card-lightblue card-outline">
                        <div class="card-body">
                            <h3 align="center">Histórico de Notas</h3>
                            <div class="row"  style="margin:auto;">
                                <div class="col-sm-12">
                                    <table class="small-table table-sm stripe row-border order-column compact table table-striped data-table-notes" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Fecha Gestión</th>
                                                {{-- <th>Hora de Gestión</th> --}}
                                                <th>Fecha de Actuación</th>
                                                {{-- <th>Hora de Actuación</th> --}}
                                                <th>Medio de contacto</th>
                                                <th>Estado</th>
                                                <th>Sub Estado</th>
                                                <th>Observaciones</th>
                                                <th>Usuario</th>
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
                    </div></div>
                </form>
                
            </div>
        </div>
    </div>
</div>