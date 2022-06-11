@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header') 
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Términos y Condiciones</li>
        </ol>
    </div>
    </div>
</div>
@stop
@section('content')
@include('admin.terms.modals.term_crud')
<div class="row justify-content-md-center" style="margin:auto;">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="nav-icon fad fa-file-alt text-lightblue"></i> Términos y Condiciones del Contrato</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover data-table" id="terms" style="width:100%;">
                        <thead>
                            <tr>
                                <th><input name="select_all" value="1" type="checkbox"></th>
                                <th>Empresa</th>
                                <th>Tipo Contrato</th>
                                <th>Fecha Versión</th>
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
@include('admin.terms.js.jsfunctions')
{{-- Fin carga --}}
@endpush