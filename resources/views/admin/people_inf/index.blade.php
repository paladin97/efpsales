@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Cursos</li>
        </ol>
    </div>
    </div>
</div>
@stop
@section('content')
@include('admin.people_inf.modals.people_inf_crud')

<div class="card-deck">
    <div class="card card-lightblue card-outline col-sm-3">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Instrucciones</h4>
        </div>
        <div class="card-body">
            <ol>
                <li>Para filtrar la información debe escoger entre los diferentes filtros, las opciones requeridas.</li>
                <li>Una vez haya escogido las opciones de filtrado, presione el botón <span class="badge badge-pill bg-lightblue">Filtrar</span>.</li>
                <li>Para limpiar los filtros, presione el botón <span class="badge badge-pill bg-lightblue">Limpiar Filtros</span> y luego el botón <span class="badge badge-pill bg-lightblue">Filtrar</span>.</li>
              </ol>
        </div>
    </div>
    <div class="card card-lightblue card-outline col-sm-7" id="card-filter">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-rotate-90 fa-sliders-h text-bold text-lightblue text-lg"></i> Establece los Filtros..</h4>
        </div>
        <div class="card-body">
            <form id="advancefilter">
                <div class="row" style="margin: auto;">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Empresa</label>
                            <select class="form-control form-control-sm select_list_filter" id="company_filter" name="company_filter[]" multiple>
                                @foreach(App\Models\Company::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Tipo Persona</label>
                            <select class="form-control form-control-sm select_list_filter" id="person_type_filter" name="person_type_filter[]" multiple>
                                @foreach(App\Models\PersonType::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Provincia</label>
                            <select class="form-control form-control-sm select_list_filter" id="province_filter" name="province_filter[]" multiple>
                                @foreach(App\Models\Province::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer mt-n3">
            <button type="text" id="btnFiterSubmitSearch" class="btn bg-lightblue"><i class="fad fa-binoculars"></i> Filtrar</button>
            <button type="text" id="btnCleanLeadID" class="btn bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Filtros</button>   
        </div>
    </div>
    <div class="card card-lightblue card-outline col-sm-2">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-chart-pie-alt text-bold text-lightblue text-lg"></i>Personas por Rol</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="chart-responsive"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <canvas id="pieChart" height="153" width="307" class="chartjs-render-monitor" style="display: block; height: 134px; width: 268px;"></canvas>
              </div>
            </div>
          </div>
          <ul class="chart-legend clearfix" id="graphGroup"></ul>
        </div>
    </div>
</div>
<br>

{{-- <div class="row"> --}}
    <div class="card-deck">
        <div class="card card-lightblue card-outline col-sm-12">
            <div class="card-header pl-3 pr-3 pt-2 pb-0">
                <h2><i class="nav-icon fad fa-user-friends text-lightblue"></i> Información Personal ƎFP Sales</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover data-table" id="banco" style="width:100%;">
                    <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th></th>
                            <th></th>
                            <th>Empresa</th>
                            <th>Tipo Persona</th>
                            <th>Nombres</th>
                            <th>DNI</th>
                            <th>Móvil</th>
                            <th>Télefono</th>
                            <th>E-Mail</th>
                            <th>Provincia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
{{-- </div> --}}

@stop
@push('js')
<script src="{{ asset('js/components/datatable.js')}}"></script>
<script src="{{ asset('js/components/general-layout.js')}}"></script>

{{-- Carga funciones js del módulo --}}
@include('admin.people_inf.js.jsfunctions')
{{-- Fin carga --}}
@endpush