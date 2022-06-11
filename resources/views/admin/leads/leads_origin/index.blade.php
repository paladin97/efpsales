@extends('adminlte::page')

@section('title',$pageTitle)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Origen del Lead</li>
        </ol>
    </div>
    </div>
</div>
@stop
@section('content')
{{-- Carga los modal --}}
@include('admin.leads.leads_origin.modals.leads_origin_crud')
{{-- Fin carga --}}

<div class="row justify-content-md-center" style="margin:auto;">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="fad fa-globe-americas text-lightblue"></i> Origen del Lead</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover data-table" id="origen" style="width:100%;">
                    <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th>Nombre</th>
                            <th align="center">Acciones</th>
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
@include('admin.leads.leads_origin.js.jsfunctions')
{{-- Fin carga --}}
@endpush