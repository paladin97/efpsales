@extends('adminlte::page')

@section('title',$pageTitle)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
        </div>
        </div>
    </div>
    
@stop
@section('content')
@include('admin.users.modals.user_crud')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="nav-icon fad fa-users-cog text-lightblue"></i> Usuarios</h2>
            </div>
            <div class="card-body">
                <div class="row" style="margin: auto;">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table compact data-table table-striped data-table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th><input name="select_all" value="1" type="checkbox"></th>
                                        <th>    </th>
                                        <th>Fecha Creación</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>GoLinks</th>
                                        <th>Empresa</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th data-priority="1">Acciones</th>                
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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
@include('admin.users.js.jsfunctions')
{{-- Fin carga --}}
@endpush