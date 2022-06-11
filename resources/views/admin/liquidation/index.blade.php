@extends('adminlte::page')

@section('title',$pageTitle)

@section('content_header')
{{-- <style>
#banco tbody tr {
cursor: pointer;
}
</style> --}}
@stop
@section('content')
@include('admin.liquidation.modals.liquidation_crud')
<div class="row justify-content-md-center" style="margin:auto;">
    <div class="col-sm-10">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="nav-icon fad fa-file-chart-line text-lightblue"></i> Modelos de Liquidaciones</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover data-table" id="banco" style="width:100%;">
                        <thead>
                            <tr>
                                <th><input name="select_all" value="1" type="checkbox"></th>
                                <th>Nombre</th>
                                <th>Empresa</th>
                                <th>Salario Base</th>
                                <th>Comisión por Venta</th>
                                <th>Bonus por Venta</th>
                                <th>Comisión por delegación</th>
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

{{-- Carga funciones js del módulo --}}
@include('admin.liquidation.js.jsfunctions')
{{-- Fin carga --}}
@endpush