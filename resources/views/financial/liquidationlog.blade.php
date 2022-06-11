@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Histórico de Liquidaciones</li>
            </ol>
        </div>
        </div>
    </div>
@stop
@section('content')
@include('financial.modals.contract_documents_liquidation')

{{-- Carga los modal --}}
{{-- Fin carga --}}
<div class="card-deck">
    <div class="card card-lightblue card-outline col-sm-3">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Instrucciones</h4>
        </div>
        <div class="card-body">
            <ol>
                <li>Para segmentar el histórico de liquidaciones debe escoger entre los diferentes filtros, las opciones requeridas.</li>
                <li>Una vez haya escogido las opciones de filtrado, presione el botón <span class="badge badge-pill bg-lightblue">Consultar Liquidación</span>.</li>
                <li>Para limpiar los parámetros, presione el botón <span class="badge badge-pill bg-lightblue">Limpiar Formulario</span>.</li>
              </ol>
        </div>
    </div>
    <div class="card card-lightblue card-outline col-sm-9" id="card-filter">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-rotate-90 fa-sliders-h text-bold text-lightblue text-lg"></i> Parámetros de Consulta</h4>
        </div>
        <div class="card-body">
            <form id="advancefilter">
                <div class="row" style="margin: auto;"> 
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Período Liquidación</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="dt_reception_filter" name="dt_reception_filter[]">
                                @foreach (App\Models\Liquidation::from('liquidations as li')
                                                        ->select('li.*')    
                                                        ->orderByRaw(DB::Raw("li.created_at  desc"))
                                                        ->get()->pluck('period_liq')->unique() as $cData)
                                    <option value="{{$cData}}">{{$cData}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('superadmin'))                             
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Asesor</label>
                            <select multiple class="form-control form-control-sm select_list_filter" id="agent_list_filter" name="agent_list[]">
                                @foreach (App\Models\User::from('users as us')
                                                    ->leftJoin('people_inf as pi','us.person_id','=','pi.id')
                                                    ->leftJoin('person_types as pt','pi.person_type_id','=','pt.id')
                                                    ->whereIn('pt.id',[2,6]) //Comercial (2), Profesor(6)
                                                    ->select('us.*','pt.name as person_type')->get() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}} - ({{$cData->person_type}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    @else
                        <input type="hidden" id="agent_list_filter" name="agent_list" value="{{Auth::user()->id}}">
                    @endif
                </div>
            </form>
        </div>
        <div class="card-footer mt-n3">
            <button type="text" id="btnFiterSubmitSearch" class="btn bg-lightblue"><i class="fad fa-binoculars"></i> Consultar Liquidación</button>
            <button type="text" id="btnCleanLeadID" class="btn bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Formulario</button>   
        </div>
    </div>
</div>
<br>

<div class="card card-lightblue card-outline">
    <div class="card-header pl-3 pr-3 pt-2 pb-0"> 
        <h4>{!!$pageTitle!!} <i style="font-size:50%;" class="fas fa-lg fa-angle-double-down text-bold text-lightblue"></i></h4>
    </div>
    <div class="card-body">
        <div class="row" style="margin: auto;">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="small-table table-sm table-bordered table-striped row-border cell-border order-column compact table data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Asesor</th>
                                <th>Tipo Usuario</th>
                                <th>Periodo Liquidación</th>
                                <th>Estdo Firma</th>
                                <th>Leads Asignados</th>
                                <th>Nº Ventas/Tutorías</th>
                                <th>% Conversión</th>
                                <th>Total Ventas</th>
                                <th>Comisión Ventas/Tutorías</th>
                                <th>Nº Ventas Delegación</th>
                                <th>Total Ventas Delegación</th> 
                                <th>Comisión Ventas Delegación</th> 
                                <th>Bonificaciones</th> 
                                <th>Descuentos</th> 
                                <th>Total Liquidación</th> 
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
@include('financial.js.jsfunctionsLog')
@include('financial.js.jsfunctionsDocumentsLiquidation')
{{-- Fin carga --}}
@endpush