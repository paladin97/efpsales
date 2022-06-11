@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Leads</li>
            </ol>
        </div>
        </div>
    </div>
@stop
@section('content')
{{-- <audio src="{{asset('storage/uploads/notification.mp3')}}" id="my_audio" loop="loop" autoplay="autoplay"></audio> --}}
{{-- Carga los modal --}}
@include('contracts.modals.contract_crud')
@include('layouts.modals.whatsapptemplate')
@include('leads.modals.lead_crud')
@include('leads.modals.lead_upload')
@include('leads.modals.lead_management')
@include('leads.modals.lead_check_events')
@include('leads.modals.lead_story')
@include('leads.modals.lead_mass_assign')
{{-- Fin carga --}}
<div class="card-deck">
    <div class="card card-lightblue card-outline col-sm-3">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Instrucciones</h4>
        </div>
        <div class="card-body p-1 m-0">
            <ol>
                <li>Para filtrar la información debe escoger entre los diferentes filtros, las opciones requeridas.</li>
                <li>Una vez haya escogido las opciones de filtrado, presione el botón <span class="badge badge-pill bg-lightblue"><i class="fad fa-binoculars"></i> Filtrar</span>.</li>
                <li>Para limpiar los filtros, presione el botón <span class="badge badge-pill bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Filtros</span> y luego el botón <span class="badge badge-pill bg-lightblue"><i class="fad fa-binoculars"></i> Filtrar</span>.</li>
                <li>Para refrescar la información, presione el botón <span class="badge badge-pill bg-lightblue"><i class="fad fa-sync"></i> Refrescar Info</span>.</li>
              </ol>
        </div>
    </div>
    <div class="card card-lightblue card-outline col-sm-9" id="card-filter">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-rotate-90 fa-sliders-h text-bold text-lightblue text-lg"></i> Establece los Filtros..</h4>
            {{-- <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus fa-beat mt-n3"></i>
                </button>
              </div> --}}
        </div>
        <div class="card-body">
            <form id="advancefilter">
                @php
                    $lead_id = "";
                    if(isset($_GET['lead_id_request'])){
                        $lead_id=$_GET['lead_id_request'];
                    }
                @endphp
                <div class="row" style="margin:auto;display:none;">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>ID de LEAD</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-info"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="filter_lead_id" value="{{$lead_id}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin: auto;">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Fecha de asignación</label>
                            <div class="input-group">
                                <input type="text" name="ranges" class="form-control form-control-sm pull-right" id="dt_reception_filter">
                                <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_assignment_from">
                                <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_assignment_to">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Curso</label>
                            <select class="form-control form-control-sm select_list_filter" id="courses_list_filter" name="courses_list[]" multiple>
                                @foreach(App\Models\Course::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Provincia</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="provinces_list_filter" name="provinces_list_filter[]">
                                @foreach (App\Models\Province::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('superadmin'))  
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Origen Lead</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="lead_origin_filter" name="lead_origin_filter[]">
                                @foreach (App\Models\LeadOrigin::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif 
                </div>
                <div class="row mt-n2" style="margin: auto;"> 
                    @if(Auth::user()->hasRole('superadmin'))                             
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Comercial</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="agent_list_filter" name="agent_list[]">
                                @foreach (App\Models\User::all() as $cData)
                                    @if ($cData->hasRole('comercial'))
                                        <option value="{{$cData->id}}">{{$cData->name}} {{$cData->last_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>                             
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Comercial Anterior</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="prev_agent_list_filter" name="prev_agent_list_filter[]">
                                @foreach (App\Models\User::all() as $cData)
                                    @if ($cData->hasRole('comercial'))
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>    
                    @endif                       
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Estado</label>
                            <select class="form-control form-control-sm select_list_filter" id="lead_status_filter" name="lead_status[]" multiple>
                                @foreach(App\Models\LeadStatus::all()->sortBy('name') as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id ="div_lead_sub_status_filter" class="col-sm-2" style="display:none;">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Sub Estado</label>
                                <select multiple class="form-control form-control-sm select_list_filter" id="lead_sub_status_filter" name="lead_sub_status[]">
                                    <option value="">Seleccione primero un estado</option>
                                </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer mt-n3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="text" id="btnFiterSubmitSearch" class="btn bg-lightblue rounded-right"><i class="fad fa-binoculars"></i> Filtrar</button>
                <button type="text" id="btnFiterRefresh" class="btn ml-1 mr-1 rounded-left rounded-right bg-lightblue"><i class="fad fa-sync"></i> Refrescar Info</button>
                <button type="text" id="btnCleanLeadID" class="btn bg-lightblue rounded-left"><i class="fad fa-eraser"></i> Limpiar Filtros</button>   
            </div>
            @if(isset($_GET['lead_id_request']))
                <a href="{{url('leadcrud')}}" target="_self" type="text" class="btn bg-lightblue"><i class="fad fa-redo"></i> Ver Todos los Leads</a>
            @endif
        </div>
    </div>
</div>
<br>

<div class="card card-lightblue card-outline">
    <div class="card-header pl-3 pr-3 pt-2 pb-0">
        <h4><i class="fad fa-id-card fa-lg text-lightblue"></i> {!!$pageTitle!!} <i style="font-size:50%;" class="fas fa-lg fa-angle-double-down text-bold text-lightblue"></i></h4>
    </div>
    <div class="card-body">
        <div class="row" style="margin: auto;">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="small-table table-sm table-bordered table-striped row-border cell-border order-column compact table data-table " cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">&nbsp;&nbsp;<input name="select_all" value="1" type="checkbox"></th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th class="text-center">CURSO</th>
                                <th class="text-center">ORIGEN</th>
                                <th class="text-center">NOMBRE</th>
                                <th class="text-center">EMAIL</th>
                                <th class="text-center">TELÉFONO</th>
                                <th class="text-center">PROVINCIA</th>
                                <th class="text-center">FECHA ASIGNACIÓN</th>
                                <th class="text-center">ÚLTIMA ACTUALIZACIÓN</th>
                                <th class="text-center">ESTADO</th>
                                <th class="text-center">SUB ESTADO</th>
                                <th class="text-center" width="90px">ASESOR</th>
                                <th data-priority="1">ACCIONES</th>                                                  
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@push('js')
<script src="{{ asset('js/components/datatable.js')}}"></script>
<script src="{{ asset('js/components/general-layout.js')}}"></script>
<script>
    
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

                    
});
</script>
{{-- Carga funciones js del módulo --}}
@include('leads.js.jsfunctions')
@include('leads.js.jsfunctionsManagement')
@include('leads.js.jsfunctionsStory')
@include('leads.js.jsfunctionsCaseManagement')
@include('leads.js.jsfunctionsSendDossier')
@include('leads.js.jsfunctionsSendDossierMatBonf')
@include('leads.js.jsfunctionsMassAssign')
@include('leads.js.jsfunctionsCheckEvents')
@include('leads.js.jsfunctionsSendWhatsappTemplate')
{{-- Fin carga --}}
@endpush