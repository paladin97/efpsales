@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Matrículas</li>
            </ol>
        </div>
        </div>
    </div>
@stop
@section('content')
{{-- Carga los modal --}}
@include('contracts.modals.contract_calendar')
@include('contracts.modals.contract_crud')
@include('contracts.modals.contract_payments_crud')
@include('contracts.modals.contract_documents_upload')
@include('contracts.modals.contract_notes_crud')
@include('contracts.modals.contract_financial')
@include('contracts.modals.contract_view_detail')
@include('contracts.modals.contract_user_pass')
@include('contracts.modals.contract_pass_room')
@include('contracts.modals.contract_teacher')
@include('layouts.modals.whatsapptemplate')
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
                <li>Una vez haya escogido las opciones de filtrado, presione el botón <span class="badge bg-lightblue"><i class="fad fa-binoculars"></i> Filtrar</span>.</li>
                <li>Para limpiar los filtros, presione el botón <span class="badge bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Filtros</span> y luego el botón <span class="badge bg-lightblue"><i class="fad fa-binoculars"></i> Filtrar</span>.</li>
            </ol>
            @if(Auth::user()->hasRole('superadmin'))
            <h3><a class="viewContractCalendar text-lightblue text-lg" style="cursor:pointer;"><i class="fad fa-calendar-alt text-bold text-lightblue text-lg"></i> Ver Calendario de Tutorías o Paralización de Aula</a></h3>
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
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Fecha Contrato</label>
                            <div class="input-group">
                                <input type="text" name="ranges" class="form-control form-control-sm pull-right" id="dt_contract_filter">
                                <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_contract_from">
                                <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_contract_to">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Forma de Pago</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="contract_payment_type_list_filter" name="contract_payment_type_list_filter[]">
                                <option value="">Seleccione</option>
                                @foreach(App\Models\ContractPaymentType::all()->sortBy('name')  as $cData)
                                <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Estado</label>
                            <select class="form-control form-control-sm select_list_filter" id="contract_status_filter" name="contract_status_filter[]" multiple>
                                @foreach(App\Models\ContractState::whereContractTypeId(1)->orderBy('name','ASC')->get()  as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-n2" style="margin: auto;">
                    @if(Auth::user()->hasRole('superadmin'))                             
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Comercial</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="agent_list_filter" name="agent_list_filter[]">
                                @foreach (App\Models\User::all() as $cData)
                                    @if ($cData->hasRole('comercial'))
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Método de Pago</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="contract_method_type_list_filter" name="contract_method_type_list_filter">
                                <option value="">Seleccione</option>
                                @foreach(App\Models\ContractPaymentMethod::all() as $cData)
                                <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Provincia</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="contract_province_filter" name="contract_province_filter[]">
                                @foreach (App\Models\Province::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-n2" style="margin:auto">
                    <div class="col-sm-12">
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
                                <th class="text-center">USUARIO </th>
                                <th class="text-center">CONTRASEÑA </th>
                                <th class="text-center">PROFESOR </th>
                                {{-- <th class="text-center">TIPO CONTRATO</th> --}}
                                <th class="text-center">FORMA DE PAGO</th>{{-- debe --}}
                                <th class="text-center">ESTADO</th>{{-- debe --}}
                                <th class="text-center" data-priority="1">IMPORTE</th>{{-- debe --}}
                                <th class="text-center" data-priority="1">PENDIENTE</th> {{-- debe --}}
                                <th class="text-center" data-priority="1" style="width: 60px">FECHA CONTRATO</th>{{-- debe --}}
                                <th class="text-center" data-priority="1" style="width: 60px">ASIGNACIÓN LEAD</th>
                                <th class="text-center"  style="width: 90px" data-priority="1">ASESOR </th>{{-- debe --}}
                                <th class="text-center"  style="width: 90px" data-priority="1">COMUNICACIONES </th>
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
@include('contracts.js.jsfunctions')
@include('contracts.js.jsfunctionsPayments')
@include('contracts.js.jsfunctionsEditContract')
@include('contracts.js.jsfunctionsReEnrollContract')
@include('contracts.js.jsfunctionsViewContract')
@include('contracts.js.jsfunctionsSendContract')
@include('contracts.js.jsfunctionsfinancial')
@include('contracts.js.jsfunctionsDropContract')
@include('contracts.js.jsfunctionsReactivateContract')
@include('contracts.js.jsfunctionsDocumentsUpload')
@include('contracts.js.jsfunctionsManagementNote')
@include('contracts.js.jsFunctionsViewDetails')
@include('contracts.js.jsfunctionsSendWhatsappTemplate')
{{-- Fin carga --}}
@endpush