@extends('adminlte::page')


@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Administrador de frases</li>
        </ol>
    </div>
    </div>
</div>
@stop
@section('content')
{{-- Carga los modal --}}
@include('admin.quotes.modals.quotemanager')
{{-- Fin carga --}}

<div class="row justify-content-md-center" style="margin:auto;">
    <div class="col-sm-10">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="fad fa-quote-left text-lightblue"></i> Administrador de frases</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover data-table" id="status" style="width:100%;">
                    <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th>Frase</th>
                            <th>Descripción</th>
                            <th>Autor</th>
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
@include('admin.quotes.js.jsfunctions')
{{-- Fin carga --}}
@endpush