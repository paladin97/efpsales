@extends('adminlte::page')

@section('title',$pageTitle)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Empresas</li>
        </ol>
    </div>
    </div>
</div>
@stop
@section('content')
{{-- Carga los modal --}}
@include('admin.company.modals.company_crud')
{{-- Fin carga --}}

<div class="row justify-content-md-center" style="margin:auto;">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="nav-icon fad fa-warehouse-alt text-lightblue"></i> Empresas</h2>
            </div>
            
            <div class="card-body">
                
                <table class="small-table table-sm table-bordered table-striped row-border cell-border order-column wrap compact table data-table " cellspacing="0" style="width:100%;">
                    {{-- <a  role="button" class="btn btn-primary fad fa-warehouse" href="{{route('company.create')}}"></a> --}}
                    <thead>
                        <tr>
                            <th class="text-center">&nbsp;&nbsp;<input name="select_all" value="1" type="checkbox"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            {{-- <th class="text-center">Logo</th> --}}
                            <th class="text-center">Nombre</th>
                            <th class="text-center">CIF</th>
                            <th class="text-center">Representante</th>
                            <th class="text-center">DNI Representante</th>
                            <th class="text-center">Teléfono</th>
                            <th class="text-center">Email</th>
                            <th data-priority="1">Acciones</th>
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

@include('admin.company.js.jsfunctions')
{{-- Fin carga --}}
@endpush