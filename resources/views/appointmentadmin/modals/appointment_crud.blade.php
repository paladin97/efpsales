<div class="modal modal-wide fade" id="eventModal" aria-labelledby="leadsHeading" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-50" role="document">
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="eventModalHeading">Crear Nuevo Evento</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>        
            </div>     
            <div class="modal-body">
                <form id="dayClick" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="event_id" name="event_id"> 
                    <div class="form-group">
                        <label>Nombre del Evento <label class="text-red">(*)</label></label><span id="agent_name" class="text-navy text-sm ml-2"></span>
                        {{-- <input type="text" class="form-control form-control-sm mt-n2" name="title" id="title" placeholder="Ingrese el nombre del evento"> --}}
                        <textarea class="form-control form-control-sm mt-n2" name="title" id="title" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="mb-n1">Estudiante</label><a id="call_id" class="text-lightblue text-sm ml-2"></a>
                        <select style="width:100%;"  class="form-control form-control-sm select_list" id="client_list" name="client_list" maxlength="250">
                            <option value="">Seleccione un estudiante</option>
                            @foreach (App\Models\Lead::all()->sortBy('first_name') as $cData)
                                @if (Auth::user()->hasRole('superadmin'))
                                    <option value="{{$cData->id}}">{{$cData->student_first_name}} {{$cData->student_last_name}} ({{$cData->student_email}} / {{$cData->student_mobile}})</option>
                                @else
                                    @if ($cData->agent_id == Auth::user()->id)
                                        <option value="{{$cData->id}}">{{$cData->student_first_name}} {{$cData->student_last_name}} </option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fecha de inicio <label class="text-red">(*)</label></label>
                                <input type="datetime-local" class="form-control form-control-sm mt-n2" name="start" id="start" placeholder="Ingrese la fecha de inicio">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fecha de finalización <label class="text-red">(*)</label></label>
                                <input type="datetime-local" class="form-control form-control-sm mt-n2" name="end" id="end" placeholder="Ingrese la fecha de finalización">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Duración <label class="text-red">(*)</label></label>
                        <input type="checkbox" value="1" id="allDay" name="allDay">Evento de todo el día
                        <input type="checkbox" value="0" id="allDayPartial" name="allDay">Evento parcial
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Color de fondo</label>
                                <input type="color" class="form-control form-control-sm mt-n2" name="color" id="color" value="#17a2b8" placeholder="Ingrese la fecha de finalización">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Color de texto</label>
                                <input type="color" class="form-control form-control-sm mt-n2" name="text_color" id="text_color" value="#ffffff" placeholder="Ingrese la fecha de finalización">
                            </div>
                        </div>
                    </div>
            </div>
            <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
            <small>
                <ol id="eventError"> 

                </ol>
            </small>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn bg-info d-none" id="btnViewDetail" value="create-product">Información Adicional</a>
                <button type="submit" class="btn bg-lightblue" id="saveBtnEvent" value="create-product">Guardar Cambios</button>
                <button type="button" class="btn btn-danger" id="DeleteBtnEvent" value="create-product">Eliminar Evento</button>
            </div>
            </form>
        </div>
    </div>
</div>