@extends('adminlte::page')

@section('title',$pageTitle)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Estados de Contratos</li>
        </ol>
    </div>
    </div>
</div>
@stop
@section('content')
{{-- Carga los modal --}}
@include('admin.contract.contract_status.modals.contract_status_crud')
{{-- Fin carga --}}

<div class="row justify-content-md-center" style="margin:auto;">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="fad fa-clipboard-list-check text-lightblue"></i> Estados de Contratos</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover data-table" id="status" style="width:100%;">
                    <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th>Tipo Contrato</th>
                            <th>Nombre</th>
                            <th>Nombre corto</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
 
@stop
@push('js')
<script src="{{ asset('js/components/datatable.js')}}"></script>
<script src="{{ asset('js/components/general-layout.js')}}"></script>

{{-- Carga funciones js del módulo --}}
@include('admin.contract.contract_status.js.jsfunctions')
{{-- Fin carga --}}
@endpush