@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Prácticas</li>
            </ol>
        </div>
        </div>
    </div>
@stop
@section('content')
{{-- Carga los modal --}}
@include('internships.modals.contract_crud')
@include('internships.modals.contract_payments_crud')
@include('internships.modals.contract_documents_upload')
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
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Curso</label>
                            <select class="form-control form-control-sm select_list_filter" id="services_list_filter" name="services_list_filter[]" multiple>
                                @foreach(App\Models\Course::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Estado</label>
                            <select class="form-control form-control-sm select_list_filter" id="contract_status_filter" name="contract_status_filter[]" multiple>
                                @foreach(App\Models\ContractState::whereContractTypeId(1)->orderBy('name','ASC')->get()  as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('superadmin'))                             
                    <div class="col-sm-3">
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
                </div>
                <div class="row" style="margin: auto;">
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Tipo Contrato</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="contract_type_filter" name="contract_type_filter">
                                <option value="">Seleccione</option>
                                @foreach(App\Models\ContractType::all() as $cData)
                                <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <button type="text" id="btnFiterSubmitSearch" class="btn bg-lightblue"><i class="fad fa-binoculars"></i> Filtrar</button>
            <button type="text" id="btnCleanLeadID" class="btn bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Filtros</button>   
        </div>
    </div>
</div>
<br>

<div class="card card-lightblue card-outline">
    <div class="card-header pl-3 pr-3 pt-2 pb-0">
        <h4>{{$pageTitle}}<i style="font-size:50%;" class="fas fa-xs fa-angle-double-down text-bold text-lightblue"></i></h4>
    </div>
    <div class="card-body">
        <div class="row" style="margin: auto;">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>&nbsp;&nbsp;<input name="select_all" value="1" type="checkbox"></th>
                                <th></th>
                                <th></th>
                                <th>Curso</th>
                                <th>Estado Curso</th>
                                <th>Centro de Formación</th>
                                <th>Provincia Deseada</th>
                                <th>Tipo contrato</th>
                                <th>Empresa</th>
                                <th>Finalización Curso</th>
                                <th>Matrícula</th>
                                <th>Estado</th>
                                <th>Duración (Horas)</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Móvil</th>
                                <th>Email</th>
                                <th>Importe</th>
                                <th>Pendiente</th>
                                <th>Fecha contrato</th>
                                <th>Fecha aprobación</th>
                                {{-- <th>Hora aprobación</th> --}}
                                <th>Comercial Asignado</th>
                                <th>Acciones</th>                                              
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
@include('internships.js.jsfunctions')
@include('internships.js.jsfunctionsPayments')
@include('internships.js.jsfunctionsEditContract')
@include('internships.js.jsfunctionsViewContract')
@include('internships.js.jsfunctionsSendContract')
@include('internships.js.jsfunctionsDropContract')
@include('internships.js.jsfunctionsDocumentsUpload')
{{-- Fin carga --}}
@endpush