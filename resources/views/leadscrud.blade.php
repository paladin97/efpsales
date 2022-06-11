@extends('adminlte::page')
<style>
    /* table th {text-align:center} */
    /* table.dataTable tbody td {
        vertical-align: middle!important;
    } */
    table.dataTable,
    table.dataTable th,
    table.dataTable td {
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
    }

    .data-table {
        width: 100%;
    }
    .dataTables_scrollHeadInner {
        margin: 0 auto;
    }
    
    .select2-selection { overflow: hidden; }
    .select2-selection__rendered { white-space: normal; word-break: break-all; }
    div.scroll {
        margin:4px, 4px; 
        padding:4px; 
        height: 200px; 
        overflow-x: hidden; 
        overflow-x: auto; 
    } 
    th {
        border-top: 1px solid #dddddd;
        border-bottom: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
    }
    
    th:first-child {
        border-left: 1px solid #dddddd;
    }
    .select2{
        width: 100%!important;
    }
    .select2-selection--multiple{
        margin-right: 20px!important;
    }
    @media only screen and (max-width: 480px) {
        .input-group > .select2-container {
            width: 100% !important;
        }
    }
</style>
@section('title', 'IONA Sales')

@section('content_header')
    {{-- <h1>Bienvenido {{Auth::user()->name}}</h1> --}}
@stop
@section('content')
<div id="initialAlertMessage" style="display:none;" class="row">
    <div class="col-sm-12">
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h3 style="margin-top:-10px;"><i class="icon fa fa-warning"></i> ¡IMPORTANTE!</h3>
            <p style="font-size:1.2em;">• Recordaros que, si tenéis Leads en estado NUEVO o NUEVO CAMPAÑA o no han sido contactados 4 días después de su recepción, estos serán eliminados de su CRM y no podrán ser contactados nuevamente por usted.</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-success box-solid">
            <div align="center" class="box-header with-border">
                <h3 align="center" class="box-title"><i class="fa fa-rotate-90 fa-sliders"></i>  ESTABLECER FILTROS</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <p><strong><code style="color: #441351">• Escoja los parámetros para acotar su búsqueda y finalice con el boton <b>Filtrar</b>.</code></strong></p>
                <p><strong><code style="color: #441351">• Para limpiar los filtros presione el botón <b>"Limpiar Filtros"</b> y posteriormente el botón <b>Filtrar</b>.</code></strong></p>
                <form id="advancefilter">
                <div class="row" style="margin: auto;">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Fecha de asignación</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="ranges" class="form-control pull-right" id="dt_reception_filter">
                                <input  type="hidden" class="form-control pull-right" id="dt_assignment_from">
                                <input  type="hidden" class="form-control pull-right" id="dt_assignment_to">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Curso</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-graduation-cap"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="courses_list_filter" name="courses_list[]">
                                    <option value="">Seleccione un curso</option>
                                    @foreach(App\Models\Course::where('is_primary',1)->orderBy('name','ASC')->get() as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Provincia</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                                </div>
                                <select multiple class="form-control select_list_filter"id="provinces_list_filter" name="provinces_list[]">
                                    <option value="">Seleccione una provincia</option>
                                    @foreach(App\Models\Province::all()->sortBy('name') as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin:auto;">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Estado</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-exchange"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="lead_status_filter" name="lead_status[]">
                                    <option value="">Seleccione</option>
                                    @foreach(App\Models\LeadStatus::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id ="div_lead_sub_status_filter" class="col-sm-2" style="display:none;">
                        <div class="form-group">
                            <label>Sub Estado</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-exchange"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="lead_sub_status_filter" name="lead_sub_status[]">
                                    <option value="">Seleccione primero un estado</option>
                                    {{-- @foreach(App\Models\LeadSubStatus::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <p class="text-red" style="font-size:70%">*(Solo si selecciona Nulo)</p>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('admin'))                             
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Comercial</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="agent_list_filter" name="agent_list[]">
                                    <option value="">Seleccione un comercial</option>
                                    @foreach (App\Models\User::all()->sortBy('name') as $cData)
                                        @if ($cData->hasRole('comercial'))
                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Comercial anterior</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="prev_agent_list_filter" name="prev_agent_list[]">
                                    <option value="">Seleccione un comercial</option>
                                    @foreach (App\Models\User::all()->sortBy('name') as $cData)
                                        @if ($cData->hasRole('comercial'))
                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>    
                </div>
                <div class="row" style="margin:auto;">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Origen Lead</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="lead_origin_list_filter" name="lead_origin_list[]">
                                    <option value="">Seleccione un origen</option>
                                    @foreach (App\Models\LeadOriginType::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id ="div_lead_sub_origen_filter" class="col-sm-2" style="display:none;">
                        <div class="form-group">
                            <label>Sub Origen</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="lead_sub_origin_list_filter" name="lead_sub_origin_list[]">
                                    <option value="">Seleccione un origen primero</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tipo Lead</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="lead_type_list_filter" name="lead_type_list[]">
                                    <option value="">Seleccione un tipo</option>
                                    
                                </select>
                            </div>
                        </div>
                    </div>  
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Área</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                                </div>
                                <select multiple class="form-control select_list_filter" id="course_area_list_filter" name="course_area_type_list[]">
                                    <option value="">Seleccione una área</option>
                                    @foreach (App\Models\CourseArea::where('id','<>',44)->orderBy('name','ASC')->get()  as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>  
                </div>
                @else
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Fecha volver a llamar</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="ranges_reminder" class="form-control pull-right" id="dt_reminder_filter">
                                <input  type="hidden" class="form-control pull-right" id="dt_reminder_from">
                                <input  type="hidden" class="form-control pull-right" id="dt_reminder_to">
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                </form>
                <button type="text" id="btnFiterSubmitSearch" class="btn btn-success"><i class="fa fa-filter"></i> Filtrar</button>
                <button type="text" id="btnCleanLeadID" class="btn btn-success"><i class="fa fa-close"></i> Limpiar Filtros</button>
            </div>
        </div>
    </div>
</div>
<br>
{{-- <h3 align="center">LEADS</h3> --}}
<div>
    <h2>Gestión de LEADS</h2>
    <strong>
        <p><code style="color: #441351">• Para desglosar toda la información haga click en el "+" en la parte izquierda.</code>
        <br><code style="color: #441351" class="add-control" data-ask="3">• Para realizar cualquier acción sobre el registro, haga click sobre el icono “Acciones” en la última columna.</code></p>
    </strong>
</div>
<div class="box box-success box-solid">
    <div class="box-body">
        <table class="small-table stripe row-border cell-border order-column compact table table-striped table-condensed data-table" cellspacing="0" width="100%">  
            <thead>
                <tr>
                    <th>&nbsp;&nbsp;<input name="select_all" value="1" type="checkbox"></th>
                    <th></th>
                    <th></th>
                    {{-- <th>Área</th> --}}
                    <th>Curso</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Tlfno</th>
                    <th>e-mail</th>
                    <th>Provincia</th>
                    <th>Fecha asignación</th>
                    <th>Fecha últ modificación</th>
                    <th>Estado</th>
                    <th>Sub Estado</th>
                    <th>Comercial</th>
                    <th data-priority="1">Acciones</th> 
                    <th>Volver a Llamar</th>                                                 
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal modal-wide fade" id="ajaxModel" role="dialog" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">     
            <div class="modal-content">     
                <div class="modal-header">  
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>     
                    <h4 class="modal-title" id="modelHeading"></h4>      
                </div>    
                <div class="modal-body">    
                    <form id="leadForm" name="leadForm" class="form-horizontal" enctype="multipart/form-data">   
                        <input type="hidden" name="lead_id" id="lead_id">
                        <input type="hidden" name="dt_creation" id="dt_creation">
                        <input type="hidden" name="lead_dt_assignment_hidden" id="lead_dt_assignment_hidden">
                        <input type="hidden" name="lead_dt_reception_hidden" id="lead_dt_reception_hidden">
                        <input type="hidden" name="lead_dt_last_update_hidden" id="lead_dt_last_update_hidden">
                        <input type="hidden" name="lead_agent_id_hidden" id="lead_agent_id_hidden">
                        <input type="hidden" name="lead_status_id_hidden" id="lead_status_id_hidden">
                        <input type="hidden" name="lead_origin_id_hidden" id="lead_origin_id_hidden">
                        <input type="hidden" name="lead_type_id_hidden" id="lead_type_id_hidden">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                     <label for="name" class="col-sm-12 control-label">Curso  <label class="text-red">(*)</label></label>
                                     <div class="col-sm-12">
                                        <select style="width:100%;"  class="form-control select_list" id="modal_courses_list" name="modal_courses_list[]" maxlength="250">
                                            <option value="">Seleccione un curso</option>
                                            @foreach(App\Models\Course::whereIsPrimary(1)->orderBy('name','ASC')->get() as $cData)
                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Nombre(s)  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Ingrese los nombres" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Apellido(s)  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Ingrese los apellidos" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Email  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Ingrese el email" value="" maxlength="250" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Móvil  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Ingrese un teléfono" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Provincia  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <select style="width:100%;" class="form-control select_list"id="modal_provinces_list" name="modal_provinces_list[]">
                                            <option value="">Seleccione una provincia</option>
                                            @foreach(App\Models\Province::all()->sortBy('name') as $cData)
                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">País  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <select style="width:100%;" class="form-control select_list" id="countries_list" name="countries_list[]">
                                            <option value="">Seleccione una país</option>
                                                @foreach(App\Models\Country::all()->sortBy('name') as $cData)
                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Fecha nacimiento</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" id="dt_birth" name="dt_birth" placeholder="Ingrese una fecha" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Situación laboral</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="laboral_situation" name="laboral_situation" placeholder="Ingrese la situación laboral" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->hasRole('admin'))
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Fecha de recepción  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <input type="datetime-local" class="form-control" id="dt_reception" name="dt_reception" placeholder="Ingrese una fecha" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Fecha de asignación  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <input type="datetime-local" class="form-control" id="dt_assignment_edit" name="dt_assignment_edit" placeholder="Ingrese una fecha" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Origen  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <select style="width:100%;" class="form-control select_list" id="leads_origins_list" name="leads_origins_list[]">
                                            <option value="">Seleccione una origen</option>
                                            @foreach(App\Models\LeadOrigin::all()->sortBy('name') as $cData)
                                                <option value="{{$cData->id}}">{{$cData->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Comercial  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <select style="width:100%;" class="form-control select_list" id="agents_list" name="agents_list[]">
                                            <option value="">Seleccione un comercial</option>
                                            @foreach (App\Models\User::all()->sortBy('name') as $cData)
                                                @if ($cData->hasRole('comercial'))
                                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Tipo Lead  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <select style="width:100%;" class="form-control select_list" id="leads_types_list" name="leads_types_list[]">
                                            <option value="">Seleccione un tipo</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Fecha de Ult modificación  <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <input type="datetime-local" class="form-control" id="dt_last_update" name="dt_last_update" placeholder="Ingrese una fecha" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Vaciar Fecha </label></label>
                                    <div class="col-sm-12">
                                        <input type="checkbox"  id="dt_last_update_null" name="dt_last_update_null[]" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                 <div class="form-group">
                                    <label for="name" class="col-sm-1 control-label">Observaciones</label>
                                    <div class="col-sm-12">
                                        <textarea id="observations" name = "observations" class="form-control" style="height:auto;" rows="3" maxlength="5000" placeholder="Ingrese una observación."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <i class="fa fa-warning"></i>
                                <h3 class="box-title">Información</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <p class="text-justify">Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                                <small>
                                    <ol id="leadCreateOrUpdateError">

                                    </ol>
                                </small>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create-product">Guardar Cambios</button>
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

<div class="modal modal-wide fade" id="leadNotesModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">     
                <div class="modal-header">  
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>     
                    <h4 class="modal-title" id="leadNotesHeading"></h4>      
                </div>    
                <div class="modal-body">
                    <h4 align="center">Datos de contacto</h4>   
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Nombres</label>
                                <p class="text-lightblue h5" id="ln_student_name"></p>
                                <small class="form-control-feedback">  </small>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Apellidos</label>
                                <p class="text-lightblue h5" id="ln_student_last_name"></p>
                                <small class="form-control-feedback">  </small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Télefono</label>
                                <p class="text-lightblue h5" id="ln_student_mobile"></p>
                                <small class="form-control-feedback">  </small>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Correo</label>
                                <p class="text-lightblue h5" id="ln_student_email"></p>
                                <small class="form-control-feedback">  </small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Provincia</label>
                                <p class="text-lightblue h5" id="ln_student_province"></p>
                                <small class="form-control-feedback">  </small>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Curso</label>
                                <p class="text-lightblue h5" id="ln_student_course"></p>
                                <small class="form-control-feedback">  </small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4 align="center">Ingresa los datos</h4>
                    <form id="leadFormNotes" name="leadFormNotes" class="form-horizontal" enctype="multipart/form-data">   
                        <input type="hidden" name="lead_id_notes" id="lead_id_notes">
                        <input type="hidden" name="dt_reception_hidden" id="dt_reception_hidden">
                        <input type="hidden" name="agent_id_hidden" id="agent_id_hidden">
                        <input type="hidden" name="origin_id_hidden" id="origin_id_hidden">
                        <input type="hidden" name="type_id_hidden" id="type_id_hidden">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Medio de contacto <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <select style="width:100%;" class="form-control" id="lead_notes_via" name="lead_notes_via" maxlength="250">
                                            <option value="Télefono" selected="selected">Télefono</option>
                                            <option value="Email">E-mail</option>
                                            <option value="Whatsapp">Whatsapp</option>
                                            <option value="Portal">Portal</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Estado <label class="text-red">(*)</label></label>
                                    <div class="col-sm-12">
                                        <select  style="width:100%;" class="form-control select_list" id="leads_status_list" name="leads_status_list[]">
                                            <option value="">Seleccione una estado</option>
                                            @if(Auth::user()->hasRole('admin'))
                                                @foreach(App\Models\LeadStatus::all()->sortBy('name') as $cData)
                                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                @endforeach
                                            @else
                                                {{-- No mostrar nuevo y matriculado ni pendiente de aceptación --}}
                                                @foreach(App\Models\LeadStatus::whereNotIn('id',[1,2,11])->orderBy('name','ASC')->get() as $cData)
                                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                @endforeach
                                            @endif 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="div_leads_sub_status_list" style="display: none;" class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Sub Estado <label class="text-red"> &nbsp;</label></label>
                                    <div class="col-sm-12">
                                        <select style="width:100%;" class="form-control select_list" id="leads_sub_status_list" name="leads_sub_status_list[]">
                                            <option value="">Seleccione...</option>
                                            @foreach(App\Models\LeadSubStatus::all()->sortBy('name') as $cData)
                                                <option value="{{$cData->id}}">{{$cData->name}}</option>
                                            @endforeach
                                        </select>
                                    <p class="text-red" style="font-size:70%">*(Solo si selecciona Nulo)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group" id="dt_call_reminder_element" style="display:none">
                                    <label for="name" class="col-sm-12 control-label">Fecha nueva llamada <label class="text-red"> &nbsp;</label></label>
                                    <div class="col-sm-12">
                                        <input type="datetime-local" class="form-control" id="dt_call_reminder" name="dt_call_reminder" placeholder="Ingrese una fecha" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Observaciones <label class="text-red">(*)</label></label>
                                            <div class="col-sm-12">
                                                <textarea id="lead_notes_observation" name = "lead_notes_observation" class="form-control" style="height:auto;" rows="5" maxlength="250" placeholder="Ingrese una observación."></textarea>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <i class="fa fa-warning"></i>
                                <h3 class="box-title">Información</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <p class="text-justify">Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                                <small>
                                    <ol id="leadNoteError">
                                        
                                    </ol>
                                </small>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="row" style="margin: auto;" align="right">
                            <button type="submit" class="btn btn-primary" id="saveBtnLeadNotes" value="lead-notes">Crear Nota</button>
                        </div>
                        <hr>
                        <h3 align="center">Histórico de Notas</h3>
                        <div class="row"  style="margin:auto;">
                            <div class="col-sm-12">
                                <table id="datatable" class="small-table stripe row-border order-column compact table table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Fecha Gestión</th>
                                            <th>Hora</th>
                                            <th>Medio de contacto</th>
                                            <th>Estado</th>
                                            <th>Sub Estado</th>
                                            <th>Observaciones</th>
                                            <th>Usuario</th>
                                            <th>ID</th>
                                        </tr>
                                    </thead>
                                </table>
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

<div class="modal modal-wide fade" id="leadUploadModal" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">     
            <div class="modal-content">     
                <div class="modal-header" align="center">  
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="glyphicon glyphicon-remove-circle fa-2x"></span>
                    </button>     
                    <h3 align="center" class="modal-title" id="leadUploadHeading"><b>Subir LEADS en EXCEL </b>  </h3>
                    {{-- <a class="btn btn-block btn-social" style="background-color:green; color:white;"><i class="fa fa-file-excel-o"></i>Subir LEADS formato EXCEL </a> --}}
                </div>    
                <div class="modal-body">    
                    <div class="row" style="margin:auto;">
                        <div class="col-sm-4">
                            <a href="{{ asset('storage/documents/IonaSalesPlantilla.xls') }}" target="_blank"><button style="background-color:green; color:white;" class="btn btn-sm"><i class="fa fa-download fa-lg"></i> </i>&nbsp; Bajar Plantilla</button></a>
                        </div>
                    </div>
                    <form id="leadImportForm" name="leadImportForm" class="form-horizontal" enctype="multipart/form-data">
                        <div class="row" style="margin:auto;">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Archivo</label>
                                    <div class="col-sm-12">
                                        <input type="file"  class="form-control" id="fileupload" name="fileupload" placeholder="" value="" maxlength="250">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">&nbsp;</label>
                                    <div class="col-sm-12">
                                        <button type="button" href="javascript:void(0)" class="btn btn-primary btn-sm" id="sendBtnUpload" value="lead-notes"><i class="fa fa-cloud-upload"></i>&nbsp; Subir</button>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="alert alert-dismissible successUploadDiv" style="display: none;background-color: green;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <div id="alertMessageUploadLeads" align="center" style="color:white;"></div>
                                </div>
                                <div class="alert alert-dismissible alert-warning warningUploadDiv" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <div id="alertMessageUploadLeadsWarning" align="center" style="color:white;"></div>
                                </div>
                                <div class="alert alert-dismissible bg-red errorUploadDiv" style="display: none">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <div id="alertMessageUploadLeadsError" align="center" style="color:white;"></div>
                                </div>
                            </div>
                        </div>        
                    </form>
                </div>
                <div class="modal-footer">
                    
                    <p>
                    {{-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> --}}
                    
                </div>
            </div>
        </div>
</div>
<div class="modal modal-wide fade" id="leadMassiveAssignModal" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">     
        <div class="modal-content">     
            <div class="modal-header" align="center">  
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="glyphicon glyphicon-remove-circle fa-2x"></span>
                </button>     
                <h3 align="center" class="modal-title" id="leadUploadHeading"><b>Asignar LEADS </b>  </h3>
            </div>    
            <div class="modal-body">
                <form id="leadMassiveAssignForm" name="leadMassiveAssignForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="lead_id" id="lead_id_massive_assign">
                    <div id="lead-assign">
                    </div>        
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="saveBtnMassiveAssign" value="create-product">Guardar Cambios</button>
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

<div class="modal modal-wide fade" id="enrollModal" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">     
        <div class="modal-content">  
            <div class="modal-header">  
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>     
                <h4 class="modal-title" id="modelHeadingEnroll"></h4>      
            </div> 
            <div class="modal-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">Datos del Cliente</a></li>
						<li><a href="#tab2" id="tutor" data-toggle="tab" aria-expanded="false">Datos del Pagador</a></li>
						<li><a href="#tab3" data-toggle="tab" aria-expanded="false">Datos del curso</a></li>
						<li><a href="#tab4" data-toggle="tab" aria-expanded="false">Forma de pago</a></li>
						<li><a href="#tab5" data-toggle="tab" aria-expanded="false">Medios de pago</a></li>
						<li><a href="#tab6" data-toggle="tab" aria-expanded="false">Observaciones</a></li>
						<li><a href="#tab7" data-toggle="tab" aria-expanded="false">Otros factores</a></li>
                    </ul>
					<form id="leadFormEnroll" name="leadFormEnroll" class="form-horizontal" enctype="multipart/form-data">
						<div class="tab-content">
							<input type="hidden" name="contract_lead_id" id="contract_lead_id">
							<input type="hidden" name="contract_id" id="contract_id">
							<input type="hidden" name="contract_type_id" id="contract_type_id">
                            <input type="hidden" name="contract_dt_creation" id="contract_dt_creation">
                            <input type="hidden" name="contract_enroll_nmber" id="contract_enroll_nmber">
							<div class="tab-pane active" id="tab1">
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Nombres <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<input type="text" class="form-control" id="contract_first_name" name="contract_first_name" placeholder="Ingrese los nombres" value="" maxlength="250">
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Apellidos <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<input type="text" class="form-control" id="contract_last_name" name="contract_last_name" placeholder="Ingrese los apellidos" value="" maxlength="250">
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">NIE/DNI/PASS <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<input type="text" class="form-control" id="contract_identifier" name="contract_identifier" placeholder="Ingrese el documento" value="" maxlength="250">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Domilicio <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<input type="text" class="form-control" id="contract_address" name="contract_address" placeholder="Ingrese la dirección" value="" maxlength="250">
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Población <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<input type="text" class="form-control" id="contract_town" name="contract_town" placeholder="Ingrese la población" value="" maxlength="250">
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Provincia <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;" class="form-control select_list"id="contract_provinces_list" name="contract_provinces_list[]">
													<option value="">Seleccione..</option>
													@foreach(App\Models\Province::all()->sortBy('name') as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Código postal <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<input type="text" class="form-control" id="contract_cp" name="contract_cp" placeholder="Ingrese el código postal" value="" maxlength="250">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Email <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<input type="text" class="form-control" id="contract_email" name="contract_email" placeholder="Ingrese el email" value="" maxlength="250">
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Móvil <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
													<input type="text" class="form-control" id="contract_mobile" name="contract_mobile" placeholder="Ingrese el móvil" value="" maxlength="250">
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Fecha nacimiento <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<input type="date" class="form-control" id="contract_dt_birth" name="contract_dt_birth" placeholder="Ingrese una fecha" value="" maxlength="250">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Nacionalidad <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;" class="form-control select_list"id="contract_countries_list" name="contract_countries_list[]">
													<option value="71">España</option>
													@foreach(App\Models\Country::all()->sortBy('name') as $cData)
														<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Estudios <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
                                                <select style="width:100%;" class="form-control select_list"id="contract_studies" name="contract_studies">
													<option value="1">Sin Estudios</option>
													@foreach(App\Models\PersonStudy::from('person_studies as pe')
                                                                ->where('pe.id','<>',1)
                                                                ->orderBy('code','ASC')->get() as $cData)
														<option value="{{$cData->id}}">{{$cData->code}} - {{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Género <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;" class="form-control" id="gender" name="gender">
													<option value = 'M'>Masculino</option>
													<option value = 'F'>Femenino</option>
													<option value = 'P'>Pangénero</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Estado civil <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;" class="form-control"  id="marital_status" name="marital_status">
													<option value = 'NSA' selected="selected">No se aporta</option>
													<option value = 'SOL'>Soltero/a</option>
													<option value = 'CAS'>Casado/a</option>
													<option value = 'DIV'>Divorciado/a</option>
													<option value = 'VIU'>Viudo/a</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Trabajo <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;" class="form-control"  id="has_work" name="has_work">
													<option value = '0'  selected="selected">No se aporta</option>
													<option value = '1'>Si</option>
													<option value = '2'>No</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Tipo de Contrato <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;" class="form-control select_list" id="contract_person_ctype" name="contract_person_ctype">
													<option value="11">No se aporta</option>
													@foreach(App\Models\PersonContractType::from('person_contract_types as pct')
                                                                ->whereIn('pct.person_type',[1,6])
                                                                ->where('pct.id','<>',11)
                                                                ->orderBy('name','ASC')->get()->sortBy('name') as $cData)
														<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Rango de ingresos <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
                                                <select style="width:100%;" class="form-control select_list" id="contract_person_incomer" name="contract_person_incomer">
												    <option value="1">Sin ingresos</option>
													
                                                </select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab2">
								<div class="row">
									<div class="col-sm-12 text-center">
                                        <div class="form-group">
											<label for="name" style="margin-top:10px">El pago lo hará el estudiante  </label>
											<input type="checkbox" id="same_payer_person" name="same_payer_person[]" value="1" checked>
										</div>
									</div>
                                </div>
                                <div id="samepayer">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Nombres/ Razón Social</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="contract_first_name_t" name="contract_first_name_t" placeholder="Ingrese los nombres" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Apellidos/ Razón Social</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="contract_last_name_t" name="contract_last_name_t" placeholder="Ingrese los apellidos" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">NIE/DNI/PASS/CIF</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="contract_identifier_t" name="contract_identifier_t" placeholder="Ingrese el documento" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Domilicio</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="contract_address_t" name="contract_address_t" placeholder="Ingrese la dirección" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Población</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="contract_town_t" name="contract_town_t" placeholder="Ingrese la población" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Provincia</label>
                                                <div class="col-sm-12">
                                                    <select style="width:100%;" class="form-control select_list" id="contract_provinces_list_t" name="contract_provinces_list_t[]">
                                                        <option value="">Seleccione una provincia</option>
                                                        @foreach(App\Models\Province::all()->sortBy('name') as $cData)
                                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Código postal</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="contract_cp_t" name="contract_cp_t" placeholder="Ingrese el código postal" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Email</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="contract_email_t" name="contract_email_t" placeholder="Ingrese el email" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Móvil</label>
                                                <div class="col-sm-12">
                                                        <input type="text" class="form-control" id="contract_mobile_t" name="contract_mobile_t" placeholder="Ingrese el móvil" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Fecha nacimiento</label>
                                                <div class="col-sm-12">
                                                    <input type="date" class="form-control" id="contract_dt_birth_t" name="contract_dt_birth_t" placeholder="Ingrese una fecha" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Nacionalidad</label>
                                                <div class="col-sm-12">
                                                    <select style="width:100%;" class="form-control select_list" id="contract_countries_list_t" name="contract_countries_list_t[]">
                                                        <option value="">Seleccione una país</option>
                                                        @foreach(App\Models\Country::all()->sortBy('name') as $cData)
                                                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Género</label>
                                                <div class="col-sm-12">
                                                    <select style="width:100%;" class="form-control"  id="gender_t" name="gender_t">
                                                        <option value = 'M'>Masculino</option>
                                                        <option value = 'F'>Femenino</option>
                                                        <option value = 'P'>Pangénero</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Estado civil</label>
                                                <div class="col-sm-12">
                                                    <select style="width:100%;" class="form-control"  id="marital_status_t" name="marital_status_t">
                                                        <option value = 'NSA' selected="selected">No se aporta</option>
                                                        <option value = '1'>Soltero/a</option>
                                                        <option value = '1'>Casado/a</option>
                                                        <option value = '1'>Divorciado/a</option>
                                                        <option value = '2'>Viudo/a</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-12 control-label">Trabajo</label>
                                                <div class="col-sm-12">
                                                    <select style="width:100%;" class="form-control"  id="has_work_t" name="has_work_t">
                                                        <option value = '0'  selected="selected">No se aporta</option>
                                                        <option value = '1'>Si</option>
                                                        <option value = '2'>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                        </div>
                                    </div>
                                </div>
							</div>
							<div class="tab-pane" id="tab3">
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Curso <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;"  class="form-control select_list" id="contract_courses_list" name="contract_courses_list[]">
													<option value="">Seleccione un curso</option>
													@foreach(App\Models\Course::whereIsPrimary(1)->orderBy('name','ASC')->get() as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div> 
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Abona matricula <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;"  class="form-control" id="enroll" name="enroll">
													<option value = '1'>Si</option>
													<option value = '2'>No</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Bonificado <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;"  class="form-control" id="bonificado" name="bonificado">
													<option value = '0'>No</option>
													<option value = '1'>Si</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Curso complementario <label class="text-red">&nbsp;</label></label>
											<div class="col-sm-12">
												<select style="width:100%;"  class="form-control select_list" id="complementary_courses_list" name="complementary_courses_list[]" maxlength="250">
													<option value="">Seleccione una provincia</option>
													@foreach(App\Models\Course::where('is_secondary',1)->orderBy('name','ASC')->get() as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Material <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Fundae <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;"  class="form-control" id="fundae" name="fundae">
													<option value = '0' selected>No</option>
													<option value = '1'>Si</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Modalidad <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab4">
								<br>
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Forma de pago <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;"  class="form-control select_list" id="contract_payment_type_id" name="contract_payment_type_id">
													<option value="">Seleccione</option>
													@foreach(App\Models\ContractPaymentType::all()->sortBy('name') as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-9">
										<div class="row hide" id="single_payment">
											<div>
												<div class="col-sm-12">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">Un pago por importe <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" id="s_payment" name="s_payment" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row hide" id="postponed_payment">
											<div class="col-sm-12">
												<div class="row">
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="col-sm-12 control-label">Importe inicial <label class="text-red">&nbsp;</label></label>
															<div class="col-sm-12">
																<input type="text" class="form-control" id="pp_initial_payment" name="pp_initial_payment" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="col-sm-12 control-label">Nº Cuotas <label class="text-red">(*)</label></label>
															<div class="col-sm-12">
																<input type="text" class="form-control" id="pp_fee_quantity" name="pp_fee_quantity" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="col-sm-12 control-label">Importe cuotas <label class="text-red">(*)</label></label>
															<div class="col-sm-12">
																<input type="text" class="form-control" id="pp_fee_value" name="pp_fee_value" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="col-sm-12 control-label"><b>Matrícula <label class="text-red">(*)</label> </b><span class="h6"><small>(Solo si seleccionó la opción de abona matrícula)</small></span></label>
															<div class="col-sm-12">
																<input type="text" class="form-control" id="pp_enroll_payment" name="pp_enroll_payment" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="col-sm-12 control-label">Pagar desde <label class="text-red">(*)</label></label>
															<div class="col-sm-12">
																<input type="date" class="form-control" id="pp_dt_first_payment" name="pp_dt_first_payment">
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="col-sm-12 control-label">Pagar hasta <label class="text-red">&nbsp;</label></label>
															<div class="col-sm-12">
																	<input readonly type="date" class="form-control" id="pp_dt_final_payment" name="pp_dt_final_payment">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row hide" id="monthly_payment">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="name" class="col-sm-12 control-label">Importe mensual <label class="text-red">(*)</label></label>
													<div class="col-sm-12">
														<input type="text" class="form-control" id="m_payment" name="m_payment" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab5">
								<br>
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="col-sm-12 control-label">Método de pago <label class="text-red">(*)</label></label>
											<div class="col-sm-12">
												<select style="width:100%;"  class="form-control select_list" id="contract_method_type_id" name="contract_method_type_id">
													<option value="">Seleccione</option>
													@foreach(App\Models\ContractPaymentMethod::all()->sortBy('name') as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-9">
										<div class="hide" id="contract_method_type_1">
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">Nombre <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" id="card_holder_name" name="card_holder_name" placeholder='Nombre en la tarjeta'>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">Nº de la tarjeta <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" id="card_number" name="card_number" placeholder='____ ____ ____ ____'>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="row">
														<label for="name" class="col-sm-12 control-label">Fecha expiración <label class="text-red">(*)</label></label>
														<div class="col-sm-6">
															<select class="form-control col-sm-2" name="expiry_month" id="expiry_month">
                                                                @for ($i = 1; $i <= 12; $i++)
                                                                    <option value="{{sprintf("%02d", $i)}}">{{sprintf("%02d", $i)}}</option>
                                                                @endfor
															</select>
														</div>
														<div class="col-sm-6">
															<select class="form-control" name="expiry_year" id="expiry_year">
                                                                    <option value="{{Carbon\Carbon::now()->year}}">{{Carbon\Carbon::now()->year}}</option>
                                                                @for ($i = 1; $i < 10; $i++)
                                                                    <option value="{{Carbon\Carbon::now()->addYear($i)->year}}">{{Carbon\Carbon::now()->addYear($i)->year}}</option>
                                                                @endfor
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">CVV <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" name="cvv" id="cvv" placeholder="Código de seguridad">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div id="contract_method_type_2" class="hide">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">Titular de la cuenta  <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" id="dd_holder_name" name="dd_holder_name" placeholder='Ingrese nombres y apellidos'>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">DNI/NIE/PASS <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" id="dd_holder_dni" name="dd_holder_dni" placeholder='Ingrese el DNI/NIE/PASS'>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">IBAN <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" id="dd_iban" name="dd_iban" placeholder="____ ____ ____ ____ ____ __">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">Banco <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" id="dd_bank_name" name="dd_bank_name" placeholder='Nombre del banco'>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="hide" id="contract_method_type_3">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">Datos de ingreso <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="text" class="form-control" id="t_payment_concept" name="t_payment_concept">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">Cuenta de ingreso <label class="text-red">&nbsp;</label></label>
														<div class="col-sm-12">
															<input readonly type="text" class="form-control" id="t_iban" name="t_iban" value="EL IBAN ESTA ASOCIADO A LA EMPRESA QUE ESCOJA PARA ESTE CONTRATO">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="hide" id="contract_method_type_4">
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<label for="name" class="col-sm-12 control-label">Pago en efectivo <label class="text-red">(*)</label></label>
														<div class="col-sm-12">
															<input type="checkbox"  id="cash_payment" name="cash_payment[]" value="1">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab6">
								<div class="row">
									<div class="col-sm-12"> 
										<label for="name" class="col-sm-12 control-label">Observaciones <label class="text-red">(*)</label></label>
										<div class="col-sm-12">
                                            <textarea id="contract_observations" name = "contract_observations" class="form-control" style="height:auto;" rows="3" maxlength="250" placeholder="Ingrese una observación.">- </textarea>
										</div>                                                 
									</div>
                                </div>
                                @if(Auth::user()->hasRole('admin'))	
                                    <div class="row">
                                        <div class="col-sm-12"> 
                                            <label for="name" class="col-sm-12 control-label">Observaciones de Gestión</label>
                                            <div class="col-sm-12">
                                                <textarea id="management_observations" name = "management_observations" class="form-control" style="height:auto;" rows="3" maxlength="250" placeholder="Ingrese una observación.">- </textarea>
                                            </div>                                                 
                                        </div>
                                    </div>
                                @endif
							</div>
							<div class="tab-pane" id="tab7">
								<div class="row">
									<div class="col-sm-4"> 
										<label for="name" class="col-sm-12 control-label">Vigencia del contrato <label class="text-red">(*)</label></label>
										<div class="col-sm-12">
											<select class="form-control" id="validity" name="validity">
												<option value="24">2 años</option>
												<option value="12">1 año</option>
												<option value="10">10 meses</option>
												<option value="6">6 meses</option>
												<option value="3">3 meses</option>
												<option value="1">1 mes</option>
											</select>
										</div>                                                 
                                    </div>
                                    <div class="col-sm-4"> 
										<label for="name" class="col-sm-12 control-label">Empresa <label class="text-red">(*)</label></label>
										<div class="col-sm-12">
											<select class="form-control select_list_filter" id="companies_list" name="companies_list[]">
                                                <option value="">Seleccione un curso</option>
                                                @foreach(App\Models\Company::from('companies as com')
                                                            ->leftJoin('access_companies as ascom','com.id','ascom.company_id')
                                                            ->where('ascom.user_id','=',Auth::user()->id)
                                                            ->select('com.*')
                                                            ->orderBy('name','ASC')->get() as $cData)
                                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                @endforeach
                                            </select>
										</div>                                                 
									</div>
								</div>
								<div class="row" style="margin:auto;">
									<div class="col-sm-12"> 
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
            </div>
            <div class="row" style="margin:auto;">
                <div class="col-sm-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <i class="fa fa-warning"></i>
                            <h3 class="box-title">Información</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <p class="text-justify">Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                            <small>
                                <ol id="leadEnrollError">

                                </ol>
                            </small>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
			<div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="saveBtnEnroll" value="create-product"><i class="fa fa-check-square-o"></i> Matricular</button>
				{{-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> --}}
			</div>
        </div>
    </div>
</div>
@stop
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js"></script>
<link rel="stylesheet" href="{{ asset('css/jquery-confirm.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
    function updateDataTableSelectAllCtrl(table){
       var $table             = table.table().node();
       var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
       var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
       var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);
    
       // If none of the checkboxes are checked
       if($chkbox_checked.length === 0){
          chkbox_select_all.checked = false;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = false;
          }
    
       // If all of the checkboxes are checked
       } else if ($chkbox_checked.length === $chkbox_all.length){
          chkbox_select_all.checked = true;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = false;
          }
    
       // If some of the checkboxes are checked
       } else {
          chkbox_select_all.checked = true;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = true;
          }
       }
    }

    function format ( d ) {
        //Organizamos las 4 fechas
        var dt_reception = moment(d.dt_reception).isValid()? moment(d.dt_reception).format('DD/MM/YYYY') : ' ';
        var hr_reception = moment(d.dt_reception).isValid()? moment(d.dt_reception).format('HH:mm:ss') : ' ';
        var dt_assignment = moment(d.dt_assignment).isValid()? moment(d.dt_assignment).format('DD/MM/YYYY') : ' ';
        var hr_assignment = moment(d.dt_assignment).isValid()? moment(d.dt_assignment).format('HH:mm:ss') : ' ';
        var dt_activation = moment(d.dt_activation).isValid()? moment(d.dt_activation).format('DD/MM/YYYY') : ' ';
        var hr_activation = moment(d.dt_activation).isValid()? moment(d.dt_activation).format('HH:mm:ss') : ' ';
        var dt_enrollment = moment(d.dt_enrollment).isValid()? moment(d.dt_enrollment).format('DD/MM/YYYY') : ' ';
        var hr_enrollment = moment(d.dt_enrollment).isValid()? moment(d.dt_enrollment).format('HH:mm:ss') : ' ';
        var dt_payment = moment(d.dt_payment).isValid()? moment(d.dt_payment).format('DD/MM/YYYY') : ' ';
        var hr_payment = moment(d.dt_payment).isValid()? moment(d.dt_payment).format('HH:mm:ss') : ' ';
        let prev_agent_name = (d.prev_agent_name ==null) ? ' ' : d.prev_agent_name;
        let observations = (d.observations ==null) ? ' ' : d.observations;
        // console.log(d);
    // `d` is the original data object for the row
        let format;
        format = '<div class="row" style="margin:auto;">'+
			'<div class="col-sm-12">'+
				'<div class="box box-success bg-gray box-solid">'+
				   '<div class="box-body">'+
						'<table class="small-table stripe row-border order-column compact table table-striped" width="99%">'+
							'<tr>'+
								'<td><b>Área: </b>'+ d.area_name +'</td>'+
								'<td><b>País: </b>'+ d.country_name +'</td>'+
								'<td><b>Horario preferencia: </b>FALTA</td>'+
								'<td><b>Fecha de contrato: </b>'+ dt_enrollment +'</td>'+
								'<td><b>Fecha primer pago: </b>'+ dt_payment +'</td>'+
								// '<td> </td>'+
							'</tr>'
                            '<tr>'+
                                '<td colspan="5"> FALTA'+
                                    
                                '</td>'+
                            '</tr>';
							@if(Auth::user()->hasRole('admin'))							
                                format += ''+
									'<tr>'+
										'<td><b>Fecha recepción: </b>'+ dt_reception +'</td>'+
										'<td><b>Hora recepción: </b>'+ hr_reception +'</td>'+
										'<td><b>Fecha activación: </b>'+ dt_activation +'</td>'+
										'<td><b>Hora activación: </b>'+ hr_activation +'</td>'+
										'<td><b>Origen: </b>'+ d.lead_origins_name +'</td>'+
									'</tr>'+ 
									'<tr>'+
										'<td><b>Estado: </b><span class="badge '+d.bg_color+'">'+d.lead_status_name +'</span></td>'+
										'<td><b>Sub-Estado: </b>'+ ((d.lead_sub_status_name == null)? ' ' : d.lead_sub_status_name) +'</td>'+
										'<td><b>Tipo: </b>'+ d.lead_type_name +'</td>'+
										'<td><b>Comercial anterior: </b>'+ ((d.prev_agent_name == null)? ' ' : d.prev_agent_name) +'</td>'+
										'<td><b>Coste: </b>'+ d.lead_cost +'</td>'+
									'</tr>';
							@endif
							format += ''+
								'<tr>'+
									'<td colspan="5" align="center"><b>OBSERVACIONES</b></td>'+
								'</tr>'+ 
								'<tr>'+
									'<td colspan="5">'+ observations +'</td>'+
								'</tr>'+ 
						'</table>' +
						'<div class="row">'+
								'<div class="col-sm-12">'+
									'<h4 class="m-t-2" align="center">Detalle de la Gestión</h4> <strong>'+
								'</div>'+
						'</div>'+
						'<div class="row" style="margin:auto;">'+
							'<div class="col-sm-12">'+
								'<a class="btn btn-success btn-xs leadNotes" data-container="body" data-id="'+d.id+'"><i class="fa fa-plus fa-xs"></i> Añadir Nota de gestión</a>'+
								'<table id="datatables" class="small-table stripe row-border order-column compact table table-striped  data-table_'+d.id+'" style="width:100%">'+
									'<thead>'+
										'<tr>'+
											'<th>Fecha Gestión</th>'+
											'<th>Hora</th>'+
											'<th>Medio de contacto</th>'+
											'<th>Estado</th>'+
											'<th>Sub Estado</th>'+
											'<th>Observaciones</th>'+
											'<th>Usuario</th>'+
											'<th>ID</th>'+
										'</tr>'+
									'</thead>'+
								'</table>'+
							'</div>'+
						'</div>'+
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>';
return format;
    }

    // $(document).on('shown.bs.modal', '#leadNotesModal', function(e) {
    //     $('#leads_status_list').on('select2:select', function (e) {    
    //         var status_id = [$(this).val()];
    //         // console.log(status_id);
    //         if (status_id.includes('10')){
    //             $("#dt_call_reminder_element").show();
    //         }
    //         else{
    //             $("#dt_call_reminder_element").hide();
    //         }
    //         $.get("{{url('api/substatus')}}", 
    //         {option: status_id}, 
    //         function(data) {    
    //             var lead_sub_status_filter = $('#leads_sub_status_list');
    //             lead_sub_status_filter.empty();
    //             // console.log(data);
    //             $.each(data, function(index, element) {
    //                 lead_sub_status_filter.append("<option value='"+ element.id +"'>" + element.name + "</option>");
    //             });
    //         });
    //     });
    // });
    
    $(document).ready( function () {
        moment.locale('es');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Funcionalidad para que el dropdown no se esconda debajo del elemento
        $(document).click(function (event) {
            //hide all our dropdowns
            $('.dropdown-menu[data-parent]').hide();

        });
        $(document).on('click', '.data-table [data-toggle="dropdown"]', function () {
            // if the button is inside a modal
            if ($('body').hasClass('modal-open')) {
                throw new Error("This solution is not working inside a responsive table inside a modal, you need to find out a way to calculate the modal Z-index and add it to the element")
                return true;
            }

            $buttonGroup = $(this).parent();
            if (!$buttonGroup.attr('data-attachedUl')) {
                var ts = +new Date;
                $ul = $(this).siblings('ul');
                $ul.attr('data-parent', ts);
                $buttonGroup.attr('data-attachedUl', ts);
                $(window).resize(function () {
                    $ul.css('display', 'none').data('top');
                });
            } else {
                $ul = $('[data-parent=' + $buttonGroup.attr('data-attachedUl') + ']');
            }
            if (!$buttonGroup.hasClass('open')) {
                $ul.css('display', 'none');
                return;
            }
            dropDownFixPosition($(this).parent(), $ul);
            function dropDownFixPosition(button, dropdown) {
                var dropDownTop = button.offset().top + button.outerHeight();
                dropdown.css('top', dropDownTop + "px");
                dropdown.css('left', button.offset().left-80 + "px");
                dropdown.css('position', "absolute");

                dropdown.css('width', dropdown.width());
                dropdown.css('heigt', dropdown.height());
                dropdown.css('display', 'block');
                dropdown.appendTo('body');
            }
        });


        //Control de versión Móvil y Desktop
        // $windowWidth = $(window).width();
        // if($windowWidth >= 800){
        //     $('.data-table').css('width', '108%');
        //     $('.innerpagemaxwidth').css('margin-left','-50px');
        //     $('.innerpagemaxwidth').css('margin-right','50px');
        //     $('.innerpagemaxwidth').css('width','108%');
        //     $('.pagemaxwidth').css('margin-left','-50px');
        // }
        // else{
        //     $('.data-table').css('width', '100%');
        //     $('.innerpagemaxwidth').css('margin-left','0px!important');
        //     $('.innerpagemaxwidth').css('margin-right','0px!important');
        //     $('.innerpagemaxwidth').css('width','100%!important');
        //     $('.pagemaxwidth').css('margin-left','0px!important');
        //     $('.table.dataTable').css('overflow-x', 'scroll !important');
        // }
        // $(window).on('resize', function() {
        //     $windowWidth = $(window).width();
        //     if($windowWidth >= 800){
        //         $('.data-table').css('width', '108%');
        //         $('.innerpagemaxwidth').css('margin-left','-50px');
        //         $('.innerpagemaxwidth').css('margin-right','50px');
        //         $('.innerpagemaxwidth').css('width','108%!important');
        //         $('.pagemaxwidth').css('margin-left','-50px');
        //     }
        //     else{
        //         $('.data-table').css('width', '100%');
        //         $('.innerpagemaxwidth').css('margin-left','0px!important');
        //         $('.innerpagemaxwidth').css('margin-right','0px!important');
        //         $('.innerpagemaxwidth').css('width','100%!important');
        //         $('.pagemaxwidth').css('margin-left','0px!important');
        //         $('.table.dataTable').css('overflow-x', 'scroll !important');
        //     }
        // });

        $(window).on('resize', function() {
            $('.data-table').css('width', '100%');
            $('.data-table').DataTable().draw(true);
        });
    
        $("#dt_last_update_null").click(function() {
            $("#lead_dt_last_update_hidden").val(null);
            $("#dt_last_update").val(null);
        });

        $('#leads_status_list').on('select2:select', function (e) {    
            // console.log("cambio estado lead");
            var status_id = [$(this).val()];
            // console.log(status_id);
            if(status_id.includes('4')){
                $('#div_leads_sub_status_list').show();
            }else{
                $('#div_leads_sub_status_list').hide();
                $("#leads_sub_status_list").empty().trigger("change");
            }
            $("#leads_sub_status_list").empty().trigger("change");
            if (['10','26'].includes(status_id[0])){
                $("#dt_call_reminder_element").show();
                $("#dt_call_reminder").val(new Date().toJSON().slice(0,19));
            }
            else{
                $("#dt_call_reminder_element").hide();
            }
            if($(this).select2('val') == 4){
                // console.log("netro");
                $.get("{{url('api/substatus')}}",
                {option: status_id},  
                function(data) {    
                    var lead_sub_status_filter = $('#leads_sub_status_list');
                    lead_sub_status_filter.empty();
                    // console.log(data);
                    $.each(data, function(index, element) {
                        lead_sub_status_filter.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                    });
                });
            }
        });
        //Las alertas de volver a llamar
        @if(empty($call_reminder_leads))
            // whatever you need to do here
        @else 
            $.alert({
                    columnClass: 'xlarge',
                    containerFluid: true, 
                    backgroundDismiss: false,
                    type: 'blue',
                    show: 'slide',
                    title: '<div style="text-align:center;"><b><i class="fa fa-whatsapp fa-sm"></i> Volver a llamar</b></div>',
                    content: '' +
                    '<form action="" class="formName">' +
                    '<div class="box box-solid">'+
                        '<div class="box-header with-border">'+
                            '<h4 class="box-title">Los siguientes alumnos estan señalados para volver a llamar</h4>'+
                        '</div>'+
                        '<div class="box-body text-left">'+
                            '<div class="scroll">'+
                                '<table class="small-table stripe row-border cell-border order-column compact table table-striped  data-table" style="width:100%">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th>#</th>'+
                                            '<th>Alumno</th>'+
                                            '<th>Curso</th>'+
                                            '<th>Télefono</th>'+
                                            '<th>Recordatorio llamada</th>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>'+
                                        @foreach($call_reminder_leads as $call_reminder_lead)
                                            '<tr>'+
                                                '<td>{{(int)$loop->index + 1}}</td>'+
                                                '<td>{{$call_reminder_lead['student_first_name']}} {{$call_reminder_lead['student_first_name']}}</td>'+
                                                '<td>{{$call_reminder_lead['course_name']}}</td>'+
                                                '<td><a href="tel:+34{{$call_reminder_lead['student_mobile']}}">{{$call_reminder_lead['student_mobile']}}</a></td>'+
                                                '<td>{{$call_reminder_lead['reminder_date']}} - {{$call_reminder_lead['reminder_hour']}} </td>'+
                                            '</tr>'+
                                        @endforeach
                                    '</tbody>'+
                                '</table>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '</form>'
                });
        @endif
        //Las alertas de volver a llamar y respuesta aplazada antiguas
        @if(empty($call_reminder_leads_old))
            // whatever you need to do here
        @else 
            $.alert({
                columnClass: 'xlarge',
                containerFluid: true, 
                backgroundDismiss: false,
                type: 'red',
                show: 'slide',
                title: '<div style="text-align:center;"><b><i class="fa fa-whatsapp fa-sm"></i> Volver a llamar / Respuesta aplazada</b></div>',
                content: '' +
                '<form action="" class="formName">' +
                    '<div class="box box-solid">'+
                        '<div class="box-header with-border">'+
                            '<h4 class="box-title text-red"><i class="icon fa fa-warning fa-lg"></i> Los siguientes alumnos tienen 30 días o más sin ser contactados, por favor gestionar</h4>'+
                        '</div>'+
                        '<div class="box-body text-left">'+
                            '<div class="scroll">'+
                                '<table class="small-table stripe row-border cell-border order-column compact table table-striped  data-table" style="width:100%">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th>#</th>'+
                                            '<th>Alumno</th>'+
                                            '<th>Curso</th>'+
                                            '<th>Télefono</th>'+
                                            '<th>Email</th>'+
                                            '<th>Recordatorio llamada</th>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>'+
                                        @foreach($call_reminder_leads_old as $call_reminder_lead_old)
                                            '<tr>'+
                                                '<td>{{(int)$loop->index + 1}}</td>'+
                                                '<td>{{$call_reminder_lead_old['student_first_name']}} {{$call_reminder_lead_old['student_first_name']}}</td>'+
                                                '<td>{{$call_reminder_lead_old['course_name']}}</td>'+
                                                '<td><a href="tel:+34{{$call_reminder_lead_old['student_mobile']}}">{{$call_reminder_lead_old['student_mobile']}}</a></td>'+
                                                '<td><a href="mailto:{{$call_reminder_lead_old['student_email']}}">{{$call_reminder_lead_old['student_email']}}</a></td>'+
                                                '<td>{{$call_reminder_lead_old['reminder_date']}} - {{$call_reminder_lead_old['reminder_hour']}} </td>'+
                                            '</tr>'+
                                        @endforeach
                                    '</tbody>'+
                                '</table>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</form>'
            });
            //Se quita la restricción mientras se define la directriz 20/10/2020
            // $("#lead_status_filter").val([10,26]).trigger('change');
            // $('.data-table').DataTable().draw(true);
            // $("#dt_reminder_from").val('2019-01-01 00:00:00');
            // $("#dt_reminder_to").val(moment().subtract(1, 'months').format('YYYY-MM-DD HH:mm:ss'));
            // var dt_ini_reminder = moment($("#dt_reminder_from").val()).format('DD/MM/YYYY');
            // var dt_end_reminder = moment($("#dt_reminder_to").val()).format('DD/MM/YYYY');
            // $('#dt_reminder_filter').daterangepicker();
            // $('#dt_reminder_filter').data('daterangepicker').setStartDate(dt_ini_reminder);
            // $('#dt_reminder_filter').data('daterangepicker').setEndDate(dt_end_reminder);

        @endif
        //Para mostrar el filtro de sub estado
        $('#lead_status_filter').change(function(){
            var status_id = ($(this).val());
            // console.log(status_id.includes(4));
            if(status_id.includes('4')){
                $('#div_lead_sub_status_filter').show();
            }else{
                $('#div_lead_sub_status_filter').hide();
            }
            $.get("{{url('api/substatus')}}", 
            {option: $(this).val()}, 
            function(data) {    
                var lead_sub_status_filter = $('#lead_sub_status_filter');
                lead_sub_status_filter.empty();
                // console.log(data);
                $.each(data, function(index, element) {
                    lead_sub_status_filter.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                });
                
            });
        });
        
        $('#lead_origin_list_filter').change(function(){
            var status_id = ($(this).val());
            // console.log(status_id.includes(4));
            $('#div_lead_sub_origen_filter').show();
            $.get("{{url('api/subfilter')}}", 
            {option: $(this).val()}, 
            function(data) {    
                var lead_sub_origin_list_filter = $('#lead_sub_origin_list_filter');
                lead_sub_origin_list_filter.empty();
                // console.log(data);
                $.each(data, function(index, element) {
                    lead_sub_origin_list_filter.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                });
                
            });
        });
        
        $('.data-table').on('show.bs.dropdown', function () {
            if ($(window).width()>=800){
                $('.dataTables_scrollBody').css( "overflow", "inherit" );
            }   
        });

        $('.data-table').on('hide.bs.dropdown', function () {
            if ($(window).width()>=800){
                $('.dataTables_scrollBody').css( "overflow", "auto" );
            } 
            
        });

        $("#initialAlertMessage").slideDown(300).delay(8000).slideUp(300);

        $("#leadNotesModal").on('shown.bs.modal', function(){
            //Poner la fecha de volver a llamar hoy
            // $("#dt_call_reminder").val(new Date().toJSON().slice(0,19));
            $("#datatable").DataTable().responsive.recalc();
        });
        // $(".modal-wide").on("show.bs.modal", function() {
        //     var height = $(window).height() - 200;
        //     $(this).find(".modal-body").css("max-height", height);
        // });
        
        // //Para activar el boton guardar solo en el ultimo pane
        // $("a[href='#tab7']").on('shown.bs.tab', function(e) {
        //     $("#saveBtnEnroll").show();
        // });
        // $("a[href='#tab7']").on('hidden.bs.tab', function(e) {
        //     $("#saveBtnEnroll").hide();
        // });

        //Para mostrar la información del tutor si es menor de edad
        $('input[name=contract_dt_birth]').change(function(){
            var birthday = moment($(this).val()).format('YYYY-MM-DD');
            if(moment().diff(birthday, 'years')<18){
                $('#tutor').show();
            }
            else{
                $('#tutor').hide();
            }
            // console.log('años:', moment().diff(birthday, 'years'));
            // $('#tutor').toggle();
        });
        //Para mostrar la información del tutor si es menor de edad
        $("a[href='#tab2']").on('shown.bs.tab', function(e) {
            $("#same_payer_person").prop( "checked", true );
        });
        $('#same_payer_person').change(function() {
            if(this.checked != true){
                $('#samepayer').show();
            }
            else{
                $('#samepayer').hide();
            }
        });

        $('input[name="ranges"]').daterangepicker({
            autoUpdateInput: false,
            opens: 'left',
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                "language":'es',
                "regional" :['es'],
                "format": "DD/MM/YYYY HH:mm",  
                "timeFormat":'H:i',
                "separator": " - ",
                "applyLabel": "Aplicar",
                "cancelLabel": "Limpiar",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Personalizado",
                "daysOfWeek": [ "Do","Lu", "Ma","Mi", "Ju","Vi","Sa"],
                "monthNames": ["Enero", "Febrero", "Marzo","Abril","Mayo", "Junio", "Julio","Agosto","Septiembre", "Octubre","Noviembre", "Diciembre"],
                // "firstDay": 1
            }  
        }, function(start, end, label) {
            $("#dt_assignment_from").val(start.format('YYYY-MM-DD HH:mm:ss'));
            $("#dt_assignment_to").val(end.format('YYYY-MM-DD HH:mm:ss'));
            // console.log("A new date selection was made: " + start.format('YYYY-MM-DD HH:mm:ss') + ' to ' + end.format('YYYY-MM-DD HH:mm:ss'));
        }); 

        $('input[name="ranges_reminder"]').daterangepicker({
            dateFormat: "dd/mm/yyyy",
            autoUpdateInput: false,
            opens: 'left',
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                "regional" :['es'],
                "format": "DD/MM/YYYY HH:mm",  
                "timeFormat":'H:i',
                "separator": " - ",
                "applyLabel": "Aplicar",
                "cancelLabel": "Limpiar",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Personalizado",
                "daysOfWeek": [ "Do","Lu", "Ma","Mi", "Ju","Vi","Sa"],
                "monthNames": ["Enero", "Febrero", "Marzo","Abril","Mayo", "Junio", "Julio","Agosto","Septiembre", "Octubre","Noviembre", "Diciembre"],
                // "firstDay": 1
            }  
        }, function(start, end, label) {
            $("#dt_reminder_from").val(start.format('YYYY-MM-DD HH:mm:ss'));
            $("#dt_reminder_to").val(end.format('YYYY-MM-DD HH:mm:ss'));
            // console.log("A new date selection was made: " + start.format('YYYY-MM-DD HH:mm:ss') + ' to ' + end.format('YYYY-MM-DD HH:mm:ss'));
        }); 

        $('#dt_reception_filter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#dt_reminder_filter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#dt_reception_filter').on('cancel.daterangepicker', function(ev, picker) {
            //do something, like clearing an input
            $('#dt_reception_filter').val('');
            $('#dt_assignment_from').val('');
            $('#dt_assignment_to').val('');
        });

        $('#dt_reminder_filter').on('cancel.daterangepicker', function(ev, picker) {
            //do something, like clearing an input
            $('#dt_reminder_filter').val('');
            $('#dt_reminder_from').val('');
            $('#dt_reminder_to').val('');
        });  

        // Inicio Filtro Autocompletados
        $('.select_list').select2({
            placeholder: 'Seleccione',
            width: '100%',
            language: {
                // You can find all of the options in the language files provided in the
                // build. They all must be functions that return the string that should be
                // displayed.
                inputTooShort: function () {
                    return "Debe introducir dos o más carácteres...";
                    },
                inputTooLong: function(args) {
                // args.maximum is the maximum allowed length
                // args.input is the user-typed text
                return "Ha ingresado muchos carácteres...";
                },
                errorLoading: function() {
                return "Error cargando resultados";
                },
                loadingMore: function() {
                return "Cargando más resultados";
                },
                noResults: function() {
                return "No se ha encontrado ningún registro";
                },
                searching: function() {
                return "Buscando...";
                },
                maximumSelected: function(args) {
                // args.maximum is the maximum number of items the user may select
                return "Error cargando resultados";
                }
            }
        });
        
        //Los select list del filtro no se deben borrar 
        $('.select_list_filter').select2({
            placeholder: 'Seleccione',
            width: '100%',
            language: {
                // You can find all of the options in the language files provided in the
                // build. They all must be functions that return the string that should be
                // displayed.
                inputTooShort: function () {
                    return "Debe introducir dos o más carácteres...";
                    },
                inputTooLong: function(args) {
                // args.maximum is the maximum allowed length
                // args.input is the user-typed text
                return "Ha ingresado muchos carácteres...";
                },
                errorLoading: function() {
                return "Error cargando resultados";
                },
                loadingMore: function() {
                return "Cargando más resultados";
                },
                noResults: function() {
                return "No se ha encontrado ningún registro";
                },
                searching: function() {
                return "Buscando...";
                },
                maximumSelected: function(args) {
                // args.maximum is the maximum number of items the user may select
                return "Error cargando resultados";
                }
            }
        });  
        // Fin Autocompletados

        var rows_selected = [];
        var table = $('.data-table').DataTable({
            destroy:true,
            processing: true,
            serverSide: true,
            // scrollX: true,
            // scrollCollapse: true,
            // fixedHeader: true,
            pagingType: 'full_numbers',
            // autoWidth : false,
            StateSave: true,
            ajax: {
               url:  "{{ route('leadcrud.index') }}",
               type: 'GET',
               data: function (data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (! data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                data.dt_assignment_from = $("#dt_assignment_from").val();
                data.dt_assignment_to = $("#dt_assignment_to").val();
                data.dt_reminder_from = $("#dt_reminder_from").val();
                data.dt_reminder_to = $("#dt_reminder_to").val();
                data.agent_list_filter = $("#agent_list_filter").val();
                data.prev_agent_list_filter = $("#prev_agent_list_filter").val();
                data.lead_type_list_filter = $("#lead_type_list_filter").val();
                data.course_area_list_filter = $("#course_area_list_filter").val();
                data.lead_sub_status_filter = $("#lead_sub_status_filter").val();
                data.lead_origin_list_filter = $("#lead_origin_list_filter").val();
                data.lead_sub_origin_list_filter = $("#lead_sub_origin_list_filter").val();
                data.lead_status_filter = $("#lead_status_filter").val();
                data.courses_list_filter = $("#courses_list_filter").val();
                data.provinces_list_filter = $("#provinces_list_filter").val();
                delete data.search.regex;
                }
            },
            dom: "<'toolbar'>"+
                "<'row'<'col-md-6'l>>"+
                "<'row'<'col-md-6'B><'col-md-6'f>>" +
                "<'row' <'col-md-2'<'toolbar_sub'>> >"+
                    "<'row'<'col-md-12'tr>><'row'<'col-md-12'ip>>",
            lengthMenu: [
                [ 50, 10, 25, 100,-1 ],
                [ '50 registros', '10 registros', '25 registros', '100 registros',  'Mostrar todo' ]
            ],
            buttons: [
                {
                    extend: 'excel',
                    title: '',
                    filename: 'Leads',
                    text:  '<i class="fa fa-file-excel-o fa-lg" data-ask="2" data-toggle="tooltip"  title="Descargar Excel"></i> ',
                    className: 'btn btn-primary btn-sm hidden',
                    init: function(api, node, config) {
                        $(node).removeClass('dt-button')
                    },
                    exportOptions: {
                        // header: false,
                        columns: [3,4,5,6,7,8,9,10,11,12,13]
                    }
                },
                {
                    extend: 'pdf',
                    filename: 'Leads',
                    text:  '<i class="fa fa-file-pdf-o fa-lg" data-ask="2"  data-toggle="tooltip"   title="Descargar PDF"></i> ',
                    className: 'btn btn-primary btn-sm hidden',
                    init: function(api, node, config) {
                        $(node).removeClass('dt-button')
                    },
                    exportOptions: {
                        columns: [3,4,5,6,7,8,9,10,11,12,13]
                    }
                }
            ],
            order: [[ 9, 'desc' ]],
            language: {
                search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    zeroRecords: "No se ha encontrado ningún registro",
                    info: "Mostrando la página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de los registros totales de _MAX_)",
                    processing: '<img src="{{asset("images/loading.gif")}}">',
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columns: [
                { data: 'id', name: 'le.id'}, 
                { data: null, searchable: false, orderable: false,width: '1%',className: 'details-control dt-body-center',
                  defaultContent: '<i class = "fa fa-plus-square" title="Ver Detalle"></i>'},
                { data: null, searchable: false, orderable: false,width: '1px',className: 'details-control dt-body-center'},
                // { data: 'area_name', name: 'crar.name'}, 
                { data: 'course_name', name: 'crse.name'},
                { data: 'student_first_name', name: 'le.student_first_name'},
                { data: 'student_last_name', name: 'le.student_last_name'},
                { data: 'student_mobile', name: 'le.student_mobile'},
                { data: 'student_email', name: 'le.student_email',className: 'details-control dt-body-center'},
                { data: 'province_name', name: 'prov.name'},
                { data: 'dt_assignment', name: 'le.dt_assignment'},
                { data: 'dt_last_update', name: 'le.dt_last_update'},
                { data: 'lead_status_name', name: 'ls.name'},
                { data: 'lead_sub_status_name', name: 'lss.name',width: '15%'},
                { data: 'agent_name', name: 'us.name'},
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false},      
                { data: 'dt_call_reminder', name: 'le.dt_call_reminder'}      
                ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    width: '2px',
                    className: 'dt-body-center ',
                    render: function (data, type, full, meta){
                        return '<input type="checkbox">';
                    }
                },
                { 
                    targets: 15,
                    searchable: true, 
                    orderable: false,
                    visible: false,
                    className: 'dt-body-center ',
                    // render: function ( data, type, row ) {
                    //     return data ? moment(row['dt_call_reminder']).format("DD/MM/YYYY") : " ";
                    // },
                },
                {
                    targets: [9,10,11,12,13],
                    className: 'dt-body-center'
                },
                // { targets: 3,width: '20%'},               
                // { targets: 7,width: '20px'}, 
                          
                {
                    render: function ( data, type, row ) {
                        return data ? moment(data).format("DD/MM/YYYY HH:mm:ss") : " ";
                    }, targets: [9,10]
                },
                {
                    render: function ( data, type, row ) {
                        // console.log(row);
                        if(row['lead_status_id']==10 || row['lead_status_id']==26){
                            return '<span class="badge '+row['bg_color']+'"><b>'+data+'</b>'
                                    +'<br> Día: ' + moment(row['dt_call_reminder']).format("DD/MM/YYYY")
                                    +'<br> Hora: ' + moment(row['dt_call_reminder']).format("HH:mm:ss")
                                    +'</span>';
                        }
                        return '<span class="badge '+row['bg_color']+'">'+data+'</span>';
                    }, targets: [11]
                },
               
                { targets: [13,12],visible:false},             
            ],
            rowCallback: function(row, data, dataIndex){
                // Get row ID
                var rowId = data['id'];
                // console.log(data['id']);
                // If row ID is in the list of selected row IDs
                if($.inArray(rowId, rows_selected) !== -1){
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            },
            drawCallback: function( settings ) {
                var column = table.column(13); 
                @if(Auth::user()->hasRole('admin'))
                    column.visible(true);
                @endif 
                var column2 = table.column(12); 
                @if(Auth::user()->hasRole('comercial'))
                    @if(Auth::user()->id <> 11)
                        column2.visible(true);
                    @endif
                @endif
                
                // $(".dataTables_scrollHeadInner").css({"width":"100%"});
                // $(".data-table").css({"width":"100%"});  
               
            },
            initComplete: function (settings, json) {  
                $(".data-table").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");            
            },
        });
        
        table.columns.adjust();

        table.on( 'draw.dt', function () {
            var PageInfo = table.page.info();
                table.column(2, { page: 'current' }).nodes().each( function (cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                } );
            PermisosUsuario.load();
        } );
        
        // table.on( 'order.dt search.dt', function () {
        //     table.column(2, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //         cell.innerHTML = i+1;
        //     } );
        // } ).draw();

        //Limpiar filtros
        $('#btnCleanLeadID').click(function(){
            $(".select_list_filter").val([]).trigger("change");
            $("#dt_reminder_filter").val('');
            $("#dt_reminder_to").val('');
            $("#dt_reminder_from").val('');
            $("#dt_reception_filter").val('');
            $("#dt_assignment_from").val('');
            $("#dt_assignment_to").val('');
        });

        $('#btnFiterSubmitSearch').click(function(){
            // console.log($("#dt_assignment_from").val());
            $('.data-table').DataTable().draw(true);
        });
        @if(Auth::user()->hasRole('admin'))
            $("div.toolbar").html(
                        '<p></p>'+
                        '<ul style="padding-inline-start:0px">'+
                            '<li class="dropdown btn btn-success btn-sm bg-success">'+
                                '<a style="color:white;padding:0px 0px; position:static;" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones Leads &nbsp;<b class="caret"></b></a>'+
                                '<ul class="dropdown-menu" >'+
                                    '<li><a style="padding: 2px 2px 5px 5px;" name="createNewLead" id="createNewLead" href="javascript:void(0)" data-container="body"><i class="fa fa-plus-circle fa-xs"></i> Crear Lead</a></li></span>'+
                                    '<li><a style="padding: 2px 2px 5px 5px;" name="leadReport" id="leadReport" href="javascript:void(0)" data-container="body"><i class="fa fa-pie-chart fa-xs"></i> Informe Leads</a></li>'+
                                    '<li><a style="padding: 2px 2px 5px 5px;" class="uploadLead" name="uploadLead" id="uploadLead" href="javascript:void(0)" data-container="body"><i class="fa fa-upload fa-xs"></i> Subir Leads</a></li>'+
                                '</ul>'+
                            '</li>'+
                        '</ul>');
            $("div.toolbar_sub").html('<ul style="padding-inline-start:0px">'+
                            '<li class="dropdown btn btn-success btn-sm" style="border-color:gray; background-color:gray; color:white;">'+
                                '<a style="color:white;padding:0px 0px; position:static;" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acción en Lote &nbsp;<b class="caret"></b></a>'+
                                '<ul class="dropdown-menu">'+
                                    '<li><a style="padding: 2px 2px 5px 5px;"name="bulk_asign" id="bulk_asign" href="javascript:void(0)" data-container="body"><i class="fa fa-check fa-xs"></i> Asignar Comercial</a></li></span>'+
                                    '<li><a style="padding: 2px 2px 5px 5px;" name="bulk_delete" id="bulk_delete" href="javascript:void(0)" data-container="body"><i class="fa fa-close fa-xs"></i> Eliminar</a></li>'+
                                '</ul>'+
                            '</li>'+
                        '</ul>');
        @else
            $("div.toolbar").html(
                        '<p></p>'+
                        '<ul style="padding-inline-start:0px">'+
                            '<li class="dropdown btn btn-success btn-sm bg-success">'+
                                '<a style="color:white;padding:0px 0px; position:static;" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones Leads &nbsp;<b class="caret"></b></a>'+
                                '<ul class="dropdown-menu" >'+
                                    '<li><a style="padding: 2px 2px 5px 5px;" name="createNewLead" id="createNewLead" href="javascript:void(0)" data-container="body"><i class="fa fa-plus-circle fa-xs"></i> Crear Lead</a></li></span>'+
                                '</ul>'+
                            '</li>'+
                        '</ul>');                  
        @endif
       // Add event listener for opening and closing details
        $('.data-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var i = $(this).find('i');
            
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                i.removeClass('fa-minus-square');
                i.addClass('fa-plus-square');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
                i.removeClass('fa-plus-square');
                i.addClass('fa-minus-square');
            }
            var lead_id_to_note = row.data().id;
            var table_notes = $(".data-table_"+row.data().id).DataTable({                
                destroy:true,
                processing: true,
                serverSide: true,
                // pagingType: "full_numbers",
                // scrollX: true,
                // responsive: {
                //     details: {
                //         renderer: function ( api, rowIdx, columns ) {
                //             var data = $.map( columns, function ( col, i ) {
                //                 return col.hidden ?
                //                     '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                //                         '<td><strong>'+col.title+':'+'</strong></td> '+
                //                         '<td>'+col.data+'</td>'+
                //                     '</tr>' :
                //                     '';
                //             } ).join('');
        
                //             return data ?
                //                 $('<table/>').append( data ) :
                //                 false;
                //         }
                //     }
                // },
                ajax:  "{{ url('leads/notes/') }}"+"/"+lead_id_to_note,
                dom: 'lfrtip',
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 registros', '25 registros', '50 registros', 'Mostrar todo' ]
                ],
                order: [[ 0, 'desc' ]],
                language: {
                    search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros por página",
                        zeroRecords: "No se ha encontrado ningún registro",
                        info: "Mostrando la página _PAGE_ de _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrado de los registros totales de _MAX_)",
                        processing: '<img src="{{asset("images/loading.gif")}}">',
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                },
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'sent_method', name: 'sent_method'},
                    { data: 'status', name: 'status'},
                    { data: 'sub_status', name: 'sub_status'},
                    { data: 'observation', name: 'observation'},
                    { data: 'user_note_name', name: 'user_note_name'},
                    { data: 'id', name: 'id'},
                    ],
                columnDefs: [
                    {
                        "render": function ( data, type, row ) {
                            // var text = parseInt(data)  ? data : " ";
                            // console.log(data);
                            return moment(data).format("DD/MM/YYYY");
                        },"targets": 0
                    },
                    {
                        "render": function ( data, type, row ) {
                            // var text = parseInt(data)  ? data : " ";
                            // console.log(data);
                            return moment(data).format("HH:mm:ss");
                        }, "targets": 1
                    },
                    { targets: 6, visible: false},
                    {
                        targets: 7,
                        visible:false,
                        searchable:false
                    }
                ],
                drawCallback: function( settings ) {
                    var column = table_notes.column(6);
                    @if(Auth::user()->hasRole('admin'))
                        column.visible(true);
                    @endif
                },
                initComplete: function (settings, json) {  
                    $(".data-table_"+row.data().id).wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");            
                },
            }).responsive.recalc();
            table_notes.responsive.recalc();
        });

        $('.bnk_checkbox').on('click', 'tr', function () {
            var id = this.id;
            var index = $.inArray(id, selected);
    
            if ( index === -1 ) {
                selected.push( id );
            } else {
                selected.splice( index, 1 );
            }
            $(this).toggleClass('selected');
        } );

        $('#createNewLead').click(function () {
            $('#ajaxModel').modal('show');
            $('#saveBtn').val("create-product");
            $('#lead_id').val('');
            $('#leadForm').trigger("reset");
            var fechaLoad = moment().format('YYYY-MM-DD');
            var fecha_asignacion = moment().format('YYYY-MM-DDTHH:mm:ss');
            $('#dt_creation').val(fechaLoad);
            $('#lead_dt_assignment_hidden').val(fecha_asignacion);
            $('#lead_dt_reception_hidden').val(fecha_asignacion);
            //Siempre es referido para el comercial
            $('#lead_origin_id_hidden').val(56);
            $('#lead_agent_id_hidden').val({{Auth::user()->id}});
            //Siempre es BBDD para el comercial
            $('#lead_type_id_hidden').val(4);
            $('#dt_reception').val(fecha_asignacion);
            //El estado es 1 cuando es nuevo
            $('#lead_status_id_hidden').val(1);
            $('#modelHeading').html("Crear nuevo Lead");
            var maxDate = moment( moment().format('YYYY-MM-DD') + ' 23:59:00' ).format('YYYY-MM-DDTHH:mm:ss');
            $('#dt_reception').attr('max', maxDate);
            $('#ajaxModel').on('shown.bs.modal', function (e) {
                $(".select_list").val([]).trigger("change");
                $("#countries_list").data('select2').trigger('select', {
                    data: {"id": 71, 'text':"España"}
                });
            });
            
        });

        $('body').on('click', '.editLead', function () {
            $("#leadCreateOrUpdateError").html("");
            $('#ajaxModel').modal('show');
            $('#leadForm').trigger("reset");
            var lead_id = $(this).data('id');
            $.get("{{route('leadcrud.index') }}" +'/' + lead_id +'/edit', function (data) {
                // console.log(data[0]);
                var fechaSplit = moment(data[0].dt_reception).format('YYYY-MM-DDTHH:mm:ss');
                $('#modelHeading').html("Editar LEAD");
                var maxDate = moment( moment().format('YYYY-MM-DD') + ' 23:59:00' ).format('YYYY-MM-DDTHH:mm:ss');
                $('#dt_reception').val(fechaSplit);
                $('#dt_reception').attr('max', maxDate);
                $('#saveBtn').val("edit-enroll");
                $("#lead_dt_reception_hidden").val(fechaSplit);
                $("#lead_dt_assignment_hidden").val(data[0].dt_assignment);
                $("#lead_dt_last_update_hidden").val(data[0].dt_last_update);
                $('#lead_id').val(data[0].id);
                $('#ajaxModel').on('shown.bs.modal', function (e) {
                    /*Se rellenan los campos de tipo select*/
                    $("#modal_courses_list").data('select2').trigger('select', {
                            data: {"id": data[0].course_id, 'text':data[0].course_name}
                    });
                    $("#modal_provinces_list").data('select2').trigger('select', {
                            data: {"id": data[0].province_id, 'text':data[0].province_name}
                    });
                    $("#countries_list").data('select2').trigger('select', {
                            data: {"id": data[0].country_id, 'text':data[0].country_name}
                    });
                    @if(Auth::user()->hasRole('admin'))
                        // $('#dt_reception').attr('max', maxDate);
                        $("#dt_assignment_edit").val(moment(data[0].dt_assignment).format('YYYY-MM-DDTHH:mm:ss'));
                        $("#dt_last_update").val(moment(data[0].dt_last_update).format('YYYY-MM-DDTHH:mm:ss'));
                        $("#leads_origins_list").data('select2').trigger('select', {
                            data: {"id": data[0].leads_origin_id, 'text':data[0].lead_origins_name}
                        });
                        $("#agents_list").data('select2').trigger('select', {
                                data: {"id": data[0].agent_id, 'text':data[0].agent_name}
                        });
                        $("#leads_types_list").data('select2').trigger('select', {
                            data: {"id": data[0].lead_type_id, 'text':data[0].lead_type_name}
                        });
                    @endif
                    /*Fin relleno*/
                });
                $('#first_name').val(data[0].student_first_name);
                $('#last_name').val(data[0].student_last_name);
                $('#email').val(data[0].student_email);
                $('#mobile').val(data[0].student_mobile);
                $('#observations').val(data[0].observations);
                @if(Auth::user()->hasRole('comercial'))
                    $('#observations').attr('readonly',true);
                @endif
                $('#dt_enrollment').val(data[0].enrollment_date);
                $('#dt_payment').val(data[0].payment_date);
                $('#dt_birth').val(data[0].student_dt_birth);
                $('#laboral_situation').val(data[0].student_laboral_situation);
                $('#lead_status_id_hidden').val(data[0].lead_status_id);
                $('#lead_sub_status_id_hidden').val(data[0].lead_sub_status_id);
                $('#lead_origin_id_hidden').val(data[0].leads_origin_id);
                $('#lead_agent_id_hidden').val(data[0].agent_id);
                $('#lead_type_id_hidden').val(data[0].lead_type_id);
                //Muestra el modal                   
                
            });
        });

        //Matricula Alumno
        $('body').on('click', '.enrollLead', function () {
            $("#leadEnrollError").html("");
            $('#leadFormEnroll').trigger("reset");
            var contract_lead_id = $(this).data('id');
            $('#contract_lead_id').val(contract_lead_id);
            $('#contract_type_id').val('1');
            var fechaLoad = moment().format('YYYY-MM-DD');
            $('#contract_dt_creation').val(fechaLoad);
            //Siempre oculta la información del tutor
            $('#samepayer').hide();
            // console.log('años:', moment().diff(birthday, 'years'));
            // $('#tutor').toggle();
            $.get("{{route('leadcrud.index') }}" +'/' + contract_lead_id +'/edit', function (data) {
                console.log(data[0]);
                // var fechaSplit = data[0].creation_date.split(/[-]/);
                // var fechaLoad = new Date();
                // fechaLoad =  fechaSplit[0]+'-'+fechaSplit[1]+'-'+fechaSplit[2]+'T'+fechaSplit[3]+':'+fechaSplit[4];
                $('#modelHeadingEnroll').html("Matricular Alumno");
                //se colocan los datos del alumno en el formulario de matriculación
                $("#contract_first_name").val(data[0].student_first_name);
                $("#contract_last_name").val(data[0].student_last_name);
                $("#contract_dt_birth").val(data[0].student_dt_birth);
                $("#contract_provinces_list").data('select2').trigger('select', {
                        data: {"id": data[0].province_id, 'text':data[0].province_name}
                });
                $("#contract_courses_list").data('select2').trigger('select', {
                        data: {"id": data[0].course_id, 'text':data[0].course_name}
                });
                $("#material_list").data('select2').trigger('select', {
                    data: {"id": 6, 'text':"NO"}
                });
                $("#contract_modality_id").data('select2').trigger('select', {
                    data: {"id": 1, 'text':"Online"}
                });
                $("#companies_list").data('select2').trigger('select', {
                    data: {"id": data[0].company_id, 'text':data[0].company_name}
                });
                $("#contract_email").val(data[0].student_email);
                $("#contract_mobile").val(data[0].student_mobile);
                $('#saveBtnEnroll').val("edit-enroll");
                $('#enrollModal').modal('show');
            })
        });

        //Subir Leads
        $('body').on('click', '.uploadLead', function () {
            $(".warningUploadDiv").hide();
            $(".successUploadDiv").hide();
            $(".errorUploadDiv").hide();
            // $('#leadForm').trigger("reset");
            $("#fileupload").filestyle({
                size: "sm",
                buttonName : 'btn-primary',
                buttonText : '&nbsp; Escoger'
            });
            
            $('#leadUploadModal').modal('show');
             
        });

        //Notas LEAD
        $('body').on('click', '.leadNotes', function () { 
            $("#leadNoteError").html("");
            $('#leadNotesModal').modal('show');
            $('#leadFormNotes').trigger("reset");
            $("#lead_id_notes").val($(this).data('id'));
            var lead_id = $(this).data('id');
            $.get("{{route('leadcrud.index') }}" +'/' + lead_id +'/edit', function (data) {
                // console.log(data[0]);
                $('#agent_id_hidden').val(data[0].agent_id);
                $('#origin_id_hidden').val(data[0].leads_origin_id);
                $('#type_id_hidden').val(data[0].lead_type_id);
                var lead_id_to_note = data[0].id;
                $('#leadNotesHeading').html("<b>Gestionar LEAD<b>");
                $('#leadNotesModal').on('shown.bs.modal', function (e) {
                $.when(
                    $("#leads_status_list").val(data[0].lead_status_id).trigger('change.select2')
                    // $("#leads_status_list").data('select2').trigger('select', {
                    //     data: {"id": data[0].lead_status_id, 'text':data[0].lead_status_name}
                    // })
                ).then(function(){
                    if(data[0].lead_status_id==10 || data[0].lead_status_id==26){
                        $("#dt_call_reminder").val(moment(data[0].dt_call_reminder).format("YYYY-MM-DDTHH:mm:ss"));
                        $("#dt_call_reminder_element").show();  
                    }
                    else{
                        $("#dt_call_reminder_element").hide();
                    }
                    if(data[0].lead_status_id==4){
                        $("#leads_sub_status_list").val(data[0].lead_sub_status_id).trigger('change.select2');
                        $('#div_leads_sub_status_list').show();
                    }
                    else{
                        $('#div_leads_sub_status_list').hide();
                    }
                    // $("#leads_sub_status_list").val(data[0].lead_sub_status_id).trigger('change.select2');
                    // $("#leads_sub_status_list").data('select2').trigger('select', {
                    //     data: {"id": data[0].lead_sub_status_id, 'text':data[0].lead_sub_status_name}
                    // });
                });
                
                    // $('#leads_status_list').on('select2:select', function (e) {    
                    //     var status_id = [$(this).val()];
                    //     if(status_id.includes('4')){
                    //         $('#div_leads_sub_status_list').show();
                    //     }else{
                    //         $('#div_leads_sub_status_list').hide();
                    //     }
                    //     $("#leads_sub_status_list").empty().trigger("change");
                    //     if (status_id.includes('10')){
                    //         $("#dt_call_reminder_element").show();
                    //     }
                    //     else{
                    //         $("#dt_call_reminder_element").hide();
                    //     }
                    //     if($(this).select2('val') == 4){
                    //         // console.log("netro");
                    //         $.get("{{url('api/substatus')}}",
                    //         {option: status_id},  
                    //         function(data) {    
                    //             var lead_sub_status_filter = $('#leads_sub_status_list');
                    //             lead_sub_status_filter.empty();
                    //             // console.log(data);
                    //             $.each(data, function(index, element) {
                    //                 lead_sub_status_filter.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                    //             });
                    //         });
                    //     }
                    // });                  
                });
                // Verifica que el estado sea volver a llamar para activar al campo de volver a llamar
                
                $("#ln_student_name").html(data[0].student_first_name);
                $("#ln_student_last_name").html(data[0].student_last_name);
                $("#ln_student_mobile").html(data[0].student_mobile);
                $("#ln_student_email").html(data[0].student_email);
                $("#ln_student_course").html(data[0].course_name);
                $("#ln_student_province").html(data[0].province_name);
                var table_notes = $('#datatable').DataTable({
                    destroy:true,
                    processing: true,
                    serverSide: true,
                    pagingType: "full_numbers",
                    // scrollX: true,
                    // responsive: {
                    //     details: {
                    //         renderer: function ( api, rowIdx, columns ) {
                    //             var data = $.map( columns, function ( col, i ) {
                    //                 return col.hidden ?
                    //                     '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                    //                         '<td><strong>'+col.title+':'+'</strong></td> '+
                    //                         '<td>'+col.data+'</td>'+
                    //                     '</tr>' :
                    //                     '';
                    //             } ).join('');
            
                    //             return data ?
                    //                 $('<table/>').append( data ) :
                    //                 false;
                    //         }
                    //     }
                    // },
                    ajax:  "{{ url('leads/notes/') }}"+"/"+lead_id_to_note,
                    dom: 'lfrtip',
                    lengthMenu: [
                        [ 10, 25, 50, -1 ],
                        [ '10 registros', '25 registros', '50 registros', 'Mostrar todo' ]
                    ],
                    order: [[ 0, 'desc' ]],
                    language: {
                        search: "Buscar:",
                            lengthMenu: "Mostrar _MENU_ registros por página",
                            zeroRecords: "No se ha encontrado ningún registro",
                            info: "Mostrando la página _PAGE_ de _PAGES_",
                            infoEmpty: "No hay registros disponibles",
                            infoFiltered: "(filtrado de los registros totales de _MAX_)",
                            processing: '<img src="{{asset("images/loading.gif")}}">',
                            paginate: {
                                first: "Primero",
                                last: "Ultimo",
                                next: "Siguiente",
                                previous: "Anterior"
                            }
                    },
                    columns: [
                        { data: 'created_at', name: 'created_at'},
                        { data: 'created_at', name: 'created_at'},
                        { data: 'sent_method', name: 'sent_method'},
                        { data: 'status', name: 'status'},
                        { data: 'sub_status', name: 'sub_status'},
                        { data: 'observation', name: 'observation'},
                        { data: 'user_note_name', name: 'user_note_name'},
                        { data: 'id', name: 'id'},
                        ],
                    columnDefs: [
                        {
                            "render": function ( data, type, row ) {
                                // var text = parseInt(data)  ? data : " ";
                                // console.log(data);
                                return moment(data).format("DD/MM/YYYY");
                            },"targets": 0
                        },
                        {
                            "render": function ( data, type, row ) {
                                // var text = parseInt(data)  ? data : " ";
                                // console.log(data);
                                return moment(data).format("HH:mm:ss");
                            }, "targets": 1
                        },
                        { targets: 6, visible: false},
                        {
                            targets: 7,
                            visible:false,
                            searchable:false
                        }
                    ],
                    drawCallback: function( settings ) {
                        var column = table_notes.column(6);
                        @if(Auth::user()->hasRole('admin'))
                            column.visible(true);
                        @endif
                    },
                    initComplete: function (settings, json) {  
                        $('#datatable').wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");            
                    },
                }).responsive.recalc();
                table_notes.responsive.recalc();
                               
            })
        });

        $('#saveBtn').click(function (e) {
            $("#leadCreateOrUpdateError").html("");
            e.preventDefault();
            $(this).html('<div class="overlay">Enviando..<i class="fa fa-refresh fa-spin"></i></div>');
            $(this).attr("disabled", "disabled");
            var formData = new FormData($('#leadForm')[0]);
            $.ajax({ 
                data: formData,      
                url: "{{ route('leadcrud.store') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#leadForm').trigger("reset");
                        $(".select_list").val([]).trigger("change");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Guardar Cambios');
                        $('#saveBtn').removeAttr("disabled", "disabled");
                        table.draw(false);
                    }else{
                        $.each( data.error, function( key, value ) {
                            $("#leadCreateOrUpdateError").append('<li><label class="text-red">'+value+'</label></li>');
                            // $("#"+key+"_error").text(value);
                        });
                        $('#saveBtn').html('Guardar Cambios');
                        $('#saveBtn').removeAttr("disabled", "disabled");
                    }
                }
                // });
                // success: function (data) {
                //     $('#leadForm').trigger("reset");
                //     $(".select_list").val([]).trigger("change");
                //     $('#ajaxModel').modal('hide');
                //     $('#saveBtn').html('Guardar Cambios');
                //     $('#saveBtn').removeAttr("disabled", "disabled");
                //     table.draw();
                //     },
                // error: function (data) {
                //     console.log('Error:', data);
                //     $(".alert").removeClass('hide');
                //     $(".alert").addClass('show');
                //     $(".alert").slideDown(300).delay(3000).slideUp(300);
                //     $('#saveBtn').html('Guardar Cambios');
                //     $('#saveBtn').removeAttr("disabled", "disabled");
                //     }
            });
        });

        $('#saveBtnEnroll').click(function (e) {
            $("#leadEnrollError").html("");
            e.preventDefault();
            $(this).html('<div class="overlay">Enviando..<i class="fa fa-refresh fa-spin"></i></div>');
            $(this).attr("disabled", "disabled");
            var formData = new FormData($('#leadFormEnroll')[0]);
            $.ajax({ 
                data: formData,      
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#leadFormEnroll').trigger("reset");
                        $(".select_list").val([]).trigger("change");
                        $('#enrollModal').modal('hide');
                        $('#saveBtnEnroll').html('<i class="fa fa-check-square-o"></i> Matricular');
                        $('#saveBtnEnroll').removeAttr("disabled", "disabled");
                        table.draw(false);
                    }else{
                        $.each( data.error, function( key, value ) {
                            $("#leadEnrollError").append('<li><label class="text-red">'+value+'</label></li>');
                            // $("#"+key+"_error").text(value);
                        });
                        $('#saveBtnEnroll').html('<i class="fa fa-check-square-o"></i> Matricular');
                        $('#saveBtnEnroll').removeAttr("disabled", "disabled");
                    }
                }
                // success: function (data) {
                //     $('#leadFormEnroll').trigger("reset");
                //     $(".select_list").val([]).trigger("change");
                //     $("#alert_message_contract").html(data['success']);
                //     $(".alert").slideDown(300).delay(3000).slideUp(300);
                //     setTimeout(function() {
                //         $('#enrollModal').modal('hide');
                //     }, 3000);
                //     // $('#enrollModal').modal('hide');
                //     $('#saveBtnEnroll').html('Guardar Cambios');
                //     $('#saveBtnEnroll').removeAttr("disabled", "disabled");
                //     table.draw();
                //     },
                // error: function (data) {
                //     console.log('Error:', data);
                //     $(".alert").removeClass('hide');
                //     $(".alert").addClass('show');
                //     $(".alert").slideDown(300).delay(3000).slideUp(300);
                //     $('#saveBtnEnroll').html('Guardar Cambios');
                //     $('#saveBtnEnroll').removeAttr("disabled", "disabled");
                //     }
            });
        });

        $('#saveBtnLeadNotes').click(function (e) {
            $("#leadNoteError").html("");
            e.preventDefault();
            $(this).html('<div class="overlay">Enviando..<i class="fa fa-refresh fa-spin"></i></div>');
            $(this).attr("disabled", "disabled");
            var formData = new FormData($('#leadFormNotes')[0]);
            $.ajax({ 
                data: formData,      
                url: "{{ route('leadnotecrud.store') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#leadFormNotes').trigger("reset");
                        $(".select_list").val([]).trigger("change");
                        $("#alert_message_note_lead").html(data['success']);
                        $(".alert").slideDown(300).delay(3000).slideUp(300);
                        $('#saveBtnLeadNotes').html('Crear Nota');
                        $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
                        $('#datatable').DataTable().draw(false);
                        $('#datatables').DataTable().draw(false);
                    }else{
                        $.each( data.error, function( key, value ) {
                            $("#leadNoteError").append('<li><label class="text-red">'+value+'</label></li>');
                            // $("#"+key+"_error").text(value);
                        });
                        $(".alert").removeClass('hide');
                        $(".alert").addClass('show');
                        $(".alert").slideDown(300).delay(3000).slideUp(300);
                        $('#saveBtnLeadNotes').html('Crear Nota');
                        $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
                    }
                },
                error: function (data){
                    console.log('Error:', data);
                    $('#saveBtnLeadNotes').html('Guardar Cambios');
                    $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
                }
                // success: function (data) {
                //     $('#leadFormNotes').trigger("reset");
                //     $(".select_list").val([]).trigger("change");
                //     $("#alert_message_note_lead").html(data['success']);
                //     $(".alert").slideDown(300).delay(3000).slideUp(300);
                //     $('#saveBtnLeadNotes').html('Crear Nota');
                //     $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
                //     $('#datatable').DataTable().draw();
                //     $('#datatables').DataTable().draw();
                //     },
                // error: function (data) {
                //     console.log('Error:', data);
                //     $(".alert").removeClass('hide');
                //     $(".alert").addClass('show');
                //     $(".alert").slideDown(300).delay(3000).slideUp(300);
                //     $('#saveBtnLeadNotes').html('Guardar Cambios');
                //     $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
                //     }
            });
        });


        $('#sendBtnUpload').click(function (e) {
            e.preventDefault();
            $(this).html('<div class="overlay">Enviando..<i class="fa fa-refresh fa-spin"></i></div>');
            $(this).attr("disabled", "disabled");
            var formData = new FormData($('#leadImportForm')[0]);
            $.ajax({ 
                data: formData,      
                url: "{{ route('lead.importexcel') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    // console.log(data);
                    if($.isEmptyObject(data.error)){
                        if(!($.isEmptyObject(data[1]['warning']))){
                            $('#leadImportForm').trigger("reset");
                            $("#alertMessageUploadLeadsWarning").html(data[1]['warning']);
                            $(".warningUploadDiv").show(300);
                            $('#sendBtnUpload').html('<i class="fa fa-cloud-upload"></i>&nbsp; Subir');
                            $('#sendBtnUpload').removeAttr("disabled", "disabled");
                        }
                        if(!($.isEmptyObject(data[0]['success']))){
                            $('#leadImportForm').trigger("reset");
                            $("#alertMessageUploadLeads").html(data[0]['success']);
                            $(".successUploadDiv").show(300);
                            $('#sendBtnUpload').html('<i class="fa fa-cloud-upload"></i>&nbsp; Subir');
                            $('#sendBtnUpload').removeAttr("disabled", "disabled");
                        }
                        if(!($.isEmptyObject(data[2]['error']))){
                            $('#leadImportForm').trigger("reset");
                            $("#alertMessageUploadLeadsError").html(data[2]['error']);
                            $(".errorUploadDiv").show(300);
                            $('#sendBtnUpload').html('<i class="fa fa-cloud-upload"></i>&nbsp; Subir');
                            $('#sendBtnUpload').removeAttr("disabled", "disabled");
                        }
                    }
                    else{
                        $('#leadImportForm').trigger("reset");
                        $("#alertMessageUploadLeadsError").html(data['error']);
                        $('#sendBtnUpload').html('<i class="fa fa-cloud-upload"></i>&nbsp; Subir');
                        $('#sendBtnUpload').removeAttr("disabled", "disabled");
                        $(".errorUploadDiv").slideDown(300).delay(3000).slideUp(300);
                        
                    }   
                }
            });
        });

        $('#bulk_delete').click(function(){
            var id = [];
            if(confirm("Esta seguro de realizar esta acción?")){
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    id.push(rowId);
                });
                // $('.dt-checkboxes:checked').each(function(){
                //     id.push($(this).val());
                // });
                if(id.length > 0){
                    $.ajax({
                        method:"GET",
                        data:{lead_id:id},
                        success:function(data){
                            // alert(data);
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
                else
                {
                    alert("Por favor seleccione por lo menos una casilla");
                }
            }
        });


        //Asignar Leads

        var tableAssign = $('.table-assign').DataTable({ 
            processing: true,
            // scrollX: true,
            autoWidth: true,
            pagingType: 'full_numbers',
            language: {
                search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    zeroRecords: " ",
                    info: "Mostrando la página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de los registros totales de _MAX_)",
                    processing: '<img src="{{asset("images/loading.gif")}}">',
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
            },
            order: [[ 0, 'asc' ]],
        });

        $("#leadMassiveAssignModal").on('shown.bs.modal', function(){
            var tableAssign = $('.table-assign').DataTable();
            tableAssign.columns.adjust().draw();
            
        });

        $("#leadMassiveAssignModal").on('hidden.bs.modal', function(){
            $("#lead-assign").empty();
            
        });

        $('body').on('click', '#bulk_asign', function () {
            var id = [];
            if(confirm("Esta seguro de realizar esta acción?")){
                if(rows_selected.length > 0){
                    var bar = new Promise((resolve, reject) => {
                        rows_selected.forEach((value, index, array) => {
                            $.get("{{route('leadcrud.index') }}" +'/' + value +'/edit', function (data) {
                                // console.log(data[0].course_id);
                                $("#lead-assign").append(
                                            '<div class="row" style="margin:auto;">'+
                                            '<div class="col-sm-5">'+
                                                    '<div class="form-group">'+
                                                        '<label for="name" class="col-sm-12 control-label">Datos estudiante, curso y provincia (Comercial Ant: ' + 
                                                            ((data[0].prev_agent == null) ? ' ' : data[0].prev_agent)
                                                        +')</label>'+
                                                        '<div class="col-sm-12">'+
                                                            '<span>' +
                                                                +(index+1)+'. '+data[0].student_first_name  +  ' '  +
                                                                                data[0].student_last_name   + ' | ' +
                                                                                data[0].student_mobile      + ' | ' +
                                                                                '<b>'+data[0].course_name   + ' | ' +
                                                                                data[0].province_name +'</b>'+
                                                            '</span>' +
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-sm-3">'+
                                                    '<div class="form-group">'+
                                                        '<label for="name" class="col-sm-12 control-label">Comercial<label class="text-red">(*)</label></label>'+
                                                        '<div class="col-sm-12">'+
                                                            '<select style="width:100%;" class="form-control assign_list" id="leads_agent_assign_list" name="leads_agent_assign_list[]">'+
                                                                '<option value="47" selected>Sin asignar</option>'+
                                                                @foreach (App\Models\User::all()->sortBy('name') as $cData)
                                                                    @if ($cData->hasRole('comercial'))
                                                                    '<option value="{{$cData->id}}">{{$cData->name}}</option>'+
                                                                    @endif
                                                                @endforeach
                                                            '</select>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-sm-2">'+
                                                    '<div class="form-group">'+
                                                        '<label for="name" class="col-sm-12 control-label">Estado  <label class="text-red">(*)</label></label>'+
                                                        '<div class="col-sm-12">'+
                                                            '<select style="width:100%;" class="form-control assign_list" id="leads_status_assign_list" name="leads_status_assign_list[]">'+
                                                                '<option value="1" selected>Nuevo</option>'+
                                                                @foreach(App\Models\LeadStatus::all()->sortBy('name') as $cData)
                                                                    '<option value="{{$cData->id}}">{{$cData->name}}</option>'+
                                                                @endforeach
                                                            '</select>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div> '+
                                                '<div class="col-sm-2">'+
                                                    '<div class="form-group">'+
                                                        '<label for="name" class="col-sm-12 control-label">Tipo Lead  <label class="text-red">(*)</label></label>'+
                                                        '<div class="col-sm-12">'+
                                                            '<select style="width:100%;" class="form-control assign_list" id="leads_type_assign_list" name="leads_type_assign_list[]">'+
                                                                '<option value="4" selected>BBDD</option>'+
                                                                
                                                            '</select>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+  
                                            '</div>'
                                );
                                $('.assign_list').select2();
                            });  
                            if (index === array.length -1) resolve();
                        });
                    });
                    bar.then(() => {
                        console.log('All Loaded!');
                        $('.assign_list').select2({
                            placeholder: 'Seleccione',
                            language: {
                                // You can find all of the options in the language files provided in the
                                // build. They all must be functions that return the string that should be
                                // displayed.
                                inputTooShort: function () {
                                    return "Debe introducir dos o más carácteres...";
                                    },
                                inputTooLong: function(args) {
                                // args.maximum is the maximum allowed length
                                // args.input is the user-typed text
                                return "Ha ingresado muchos carácteres...";
                                },
                                errorLoading: function() {
                                return "Error cargando resultados";
                                },
                                loadingMore: function() {
                                return "Cargando más resultados";
                                },
                                noResults: function() {
                                return "No se ha encontrado ningún registro";
                                },
                                searching: function() {
                                return "Buscando...";
                                },
                                maximumSelected: function(args) {
                                // args.maximum is the maximum number of items the user may select
                                return "Error cargando resultados";
                                }
                            }
                        });
                    });
                    $("#lead_id_massive_assign").val(rows_selected);
                    $('#leadMassiveAssignModal').modal('show');
                }else{
                    alert("Por favor seleccione por lo menos una casilla");
                }
                
            }
            
             
        });

        $('#saveBtnMassiveAssign').click(function (e) {
            e.preventDefault();
            $(this).html('<div class="overlay">Enviando..<i class="fa fa-refresh fa-spin"></i></div>');
            $(this).attr("disabled", "disabled");
            var formData = new FormData($('#leadMassiveAssignForm')[0]);
            $.ajax({ 
                data: formData,      
                
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#leadMassiveAssignForm').trigger("reset");
                    $(".select_list").val([]).trigger("change");
                    $('#saveBtnMassiveAssign').html('Guardar Cambios');
                    $('#saveBtnMassiveAssign').removeAttr("disabled", "disabled");
                    $('#leadMassiveAssignModal').modal('hide');
                    $("#leadsToModify").html('');
                    rows_selected = [];
                    table.draw(false);
                    },
                error: function (data) {
                    // console.log('Error:', data);
                    // $(".alert").removeClass('hide');
                    // $(".alert").addClass('show');
                    // $(".alert").slideDown(300).delay(3000).slideUp(300);
                    $('#saveBtnMassiveAssign').html('Guardar Cambios');
                    $('#saveBtnMassiveAssign').removeAttr("disabled", "disabled");
                    }
            });
        });

       

        $('#leadMassiveAssignModal').on('hidden.bs.modal', function () {
            $("#leadsToModify").html('');
        });

        $('body').on('click', '.deleteLead', function () {
            var lead_id = $(this).data("id");
            confirm("Esta seguro de borrar este Lead?");
            $.ajax({
                type: "DELETE",
                url: "{{ route('leadcrud.store') }}"+'/'+lead_id,
                success: function (data) {
                    rows_selected = [];
                    table.draw(false);
                    },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

        $("#contract_payment_type_id").change(function () {
            // console.log($(this).val());
            switch($(this).val()) {
                case '1':
                    $("#single_payment").removeClass('hide');
                    $("#postponed_payment").addClass('hide');
                    $("#monthly_payment").addClass('hide');
                    break;
                case '2':
                    $("#single_payment").addClass('hide');
                    $("#postponed_payment").removeClass('hide');
                    $("#monthly_payment").addClass('hide');
                    break;
                case '3':
                    $("#single_payment").addClass('hide');
                    $("#postponed_payment").addClass('hide');
                    $("#monthly_payment").removeClass('hide');
                    break;
                case '':
                    $("#single_payment").addClass('hide');
                    $("#postponed_payment").addClass('hide');
                    $("#monthly_payment").addClass('hide');
                    break;
                default:
                    // code block
            }
            
        });

        $("#contract_method_type_id").change(function () {
            // console.log($(this).val());
            switch($(this).val()) {
                case '1':
                    $('#card_number').mask('0000 0000 0000 0000');
                    $('#contract_method_type_1').removeClass('hide');
                    $('#contract_method_type_2').addClass('hide');
                    $('#contract_method_type_3').addClass('hide');
                    $('#contract_method_type_4').addClass('hide');
                    break;
                case '2':
                    $('#debit_iban').mask('SS00 0000 0000 0000 0000 0000');
                    $('#contract_method_type_2').removeClass('hide');
                    $('#contract_method_type_1').addClass('hide');
                    $('#contract_method_type_3').addClass('hide');
                    $('#contract_method_type_4').addClass('hide');
                    break;
                case '3':
                    $('#contract_method_type_3').removeClass('hide');
                    $('#contract_method_type_1').addClass('hide');
                    $('#contract_method_type_2').addClass('hide');
                    $('#contract_method_type_4').addClass('hide');
                    break;
                case '4':
                    $('#contract_method_type_4').removeClass('hide');
                    $('#contract_method_type_1').addClass('hide');
                    $('#contract_method_type_2').addClass('hide');
                    $('#contract_method_type_3').addClass('hide');
                    break;
                case '':
                    $('#contract_method_type_4').addClass('hide');
                    $('#contract_method_type_1').addClass('hide');
                    $('#contract_method_type_2').addClass('hide');
                    $('#contract_method_type_3').addClass('hide');
                    break;
                default:
                    // code block
            }
            
        });
                
        
        //Fin controlar botones
        
        //Controla Fecha fin
        $("#pp_dt_first_payment").change(function () {
            initial = $(this).val();
            fQuantity = $("#pp_fee_quantity").val() ? $("#pp_fee_quantity").val() -1 : 0;
            $("#pp_dt_final_payment").val(moment(initial).add(fQuantity,'month').format('YYYY-MM-DD'));
        });

        // Handle click on checkbox
        $('.data-table tbody').on('click', 'input[type="checkbox"]', function(e){
            // console.log("check");
            var $row = $(this).closest('tr');
            // Get row data
            var data = table.row($row).data()['id'];
            // Get row ID
            var rowId = data;
            // console.log(data);
            // Determine whether row ID is in the list of selected row IDs
            var index = $.inArray(rowId, rows_selected);
            // If checkbox is checked and row ID is not in list of selected row IDs
            if(this.checked && index === -1){
                rows_selected.push(rowId);
            // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1){
                rows_selected.splice(index, 1);
            }
            if(this.checked){
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on table cells with checkboxes
        $('.data-table').on('click', 'td:first-child, th:first-child', function(e){
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', table.table().container()).on('click', function(e){
            if(this.checked){
                $('.data-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('.data-table tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        table.on('draw', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
        });

        $('#leadNotesModal').on('hidden.bs.modal', function () {
            $("#dt_call_reminder_element").hide();
            $("#div_leads_sub_status_list").hide();
            table.draw(false);
        }); 
        
        $('#leadUploadModal').on('hidden.bs.modal', function () {
            table.draw(false);
        });
        
            
            
        // });
});
</script>
@endpush