@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Reportes Rápidos</li>
        </ol>
    </div>
    </div>
</div>
@stop
@section('content')
{{-- Carga los modal --}}
{{-- Fin carga --}}
<div class="row justify-content-md-center" style="margin:auto;">
    <div class="card card-lightblue card-outline col-sm-9" id="card-filter">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4 align="center"><i class="fad fa-rotate-90 fa-sliders-h text-bold text-lightblue text-lg"></i> Establece los Filtros..</h4>
        </div>
        <div class="card-body" align="center">
            <form id="advancefilter"  method="post" action="{{route('reports.generate')}}">
                @csrf
                <div class="row  justify-content-md-center" style="margin: auto;">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Rango de fechas <label class="text-red">(*)</label></label>
                            <div class="input-group">
                                <input type="text" name="ranges" class="form-control form-control-sm pull-right" id="dt_contract_filter">
                                <input  type="hidden" class="form-control pull-right" id="dt_report_from" name="dt_report_from">
                                <input  type="hidden" class="form-control pull-right" id="dt_report_to" name="dt_report_to">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Reporte <label class="text-red">(*)</label></label>
                            <div class="input-group">
                                <select class="form-control form-control-sm select_list_filter" id="report_list_filter" name="report_list_filter">
                                    <option value="">Seleccione un reporte</option>
                                    @foreach (App\Models\Report::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            <button type="submit" id="btnFiterSubmitSearch" class="btn bg-lightblue"><i class="fad fa-play"></i> Generar</button>
            </form>
            <div class="col-sm-12" id="reportResponse">
                @if($postMessage <> '')
                    {!!$postMessage!!}
                @else
                    <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                @endif 
            </div>
        </div>
    </div>
</div>
<div class="row">
    
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
@include('admin.reports.js.jsfunctions')
{{-- Fin carga --}}
@endpush
