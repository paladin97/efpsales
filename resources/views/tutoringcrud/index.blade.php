@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Alumnos</li>
            </ol>
        </div>
        </div>
    </div>
@stop
@section('content')
{{-- Carga los modal --}}
@include('tutoringcrud.modals.contract_calendar')
@include('tutoringcrud.modals.contract_crud')
@include('tutoringcrud.modals.contract_payments_crud')
@include('tutoringcrud.modals.contract_documents_upload')
@include('tutoringcrud.modals.contract_notes_crud')
@include('tutoringcrud.modals.contract_financial')
@include('tutoringcrud.modals.contract_view_detail')
@include('tutoringcrud.modals.contract_user_pass')
@include('tutoringcrud.modals.contract_pass_room')
@include('tutoringcrud.modals.contract_teacher')
{{-- Calendario --}}
@include('home.modals.calendar')
{{-- Fin carga --}}
<div class="card-deck">
    <div class="card card-lightblue card-outline col-sm-4">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Instrucciones</h4>
        </div>
        <div class="card-body">
            <ol>
                <li>Para filtrar la información debe escoger entre los diferentes filtros, las opciones requeridas.</li>
                <li>Una vez haya escogido las opciones de filtrado, presione el botón <span class="badge bg-lightblue">Filtrar</span>.</li>
                <li>Para limpiar los filtros, presione el botón <span class="badge bg-lightblue">x Limpiar Filtros</span> y luego el botón <span class="badge bg-lightblue">Filtrar</span>.</li>
            </ol>
            @if(Auth::user()->hasRole('teacher'))
            <h3><a class="viewContractCalendar text-lightblue text-lg" style="cursor:pointer;"><i class="fad fa-calendar-alt text-bold text-lightblue text-lg"></i> Ver Calendario de Tutorías</a></h3>
            @endif
        </div>
        
    </div>
    <div class="card card-lightblue card-outline col-sm-8" id="card-filter">
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
                    $contract_id = "";
                    if(isset($_GET['lead_id_request'])){
                        $contract_id=$_GET['lead_id_request'];
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
                                <input type="text" class="form-control pull-right" id="filter_contract_id" value="{{$contract_id}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin: auto;">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Fecha Contrato</label>
                            <div class="input-group">
                                <input type="text" name="ranges" class="form-control form-control-sm pull-right" id="dt_contract_filter">
                                <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_contract_from">
                                <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_contract_to">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Curso</label>
                            <select class="form-control form-control-sm select_list_filter" id="courses_list_filter" name="courses_list_filter[]" multiple>
                                @foreach(App\Models\Course::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <button type="text" id="btnFiterSubmitSearch" class="btn bg-lightblue"><i class="fad fa-binoculars"></i> Filtrar</button>
            <button type="text" id="btnCleanLeadID" class="btn bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Filtros</button>   
            @if(isset($_GET['lead_id_request']))
                <a href="{{url('contractcrud')}}" target="_self" type="text" class="btn bg-lightblue"><i class="fad fa-sync"></i> Ver Todos los Contratos</a>
            @endif
        </div>
    </div>
</div>
<br>

<div class="card card-lightblue card-outline">
    <div class="card-header pl-3 pr-3 pt-2 pb-0">
        <h4><i class="fad fa-user-graduate fa-lg text-lightblue"></i> {{$pageTitle}}<i style="font-size:50%;" class="fas fa-xs fa-angle-double-down text-bold text-lightblue"></i></h4>
    </div>
    <div class="card-body">
        <div class="row" style="margin: auto;">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="small-table table-sm table-bordered table-striped row-border cell-border order-column compact table data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">&nbsp;&nbsp;<input name="select_all" value="1" type="checkbox"></th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th class="text-center">MATRÍCULA</th> {{-- debe --}}
                                <th class="text-center">CURSO </th>{{-- debe --}}
                                <th class="text-center" style="width: 200px">NOMBRE</th>{{-- debe --}}
                                <th class="text-center">TELÉFONO </th>
                                <th class="text-center">EMAIL </th>
                                <th class="text-center" data-priority="1" style="width: 60px">FECHA CONTRATO</th>{{-- debe --}}
                                <th class="text-center"  style="width: 90px" data-priority="1">PROFESOR </th>{{-- debe --}}
                                <th class="text-center" data-priority="1">ACCIONES</th>                                               
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
@include('tutoringcrud.js.jsfunctions')
@include('tutoringcrud.js.jsfunctionsPayments')
@include('tutoringcrud.js.jsfunctionsEditContract')
@include('tutoringcrud.js.jsfunctionsViewContract')
@include('tutoringcrud.js.jsfunctionsSendContract')
@include('tutoringcrud.js.jsfunctionsfinancial')
@include('tutoringcrud.js.jsfunctionsDropContract')
@include('tutoringcrud.js.jsfunctionsDocumentsUpload')
@include('tutoringcrud.js.jsfunctionsManagementNote')
@include('tutoringcrud.js.jsFunctionsViewDetails')
{{-- Fin carga --}}
@endpush