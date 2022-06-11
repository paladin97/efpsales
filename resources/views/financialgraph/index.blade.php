@extends('adminlte::page')

@section('title', $pageTitle)
<style>
  canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
  .widget p{
    display: inline-block;
    line-height: 1em;

  }
  .fecha {
    font-family: Oswald, Arial;
    font-size:1.3em;
    width: 100%;
  }
  .reloj {
    font-family: Oswald, Arial;
    width: 100%;
    font-size: 4em;
    border: 2px solid black;
  }
</style>
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2 my-auto">
          <div class="col-sm-12">
            <h1 align="center"><i class="fad fa-chart-area text-bold text-lightblue text-lg"></i> Estadístícas y Comparativas</h1>
          </div>
        </div>
    </div>
@stop

@section('content')
{{-- Carga los modal --}}
@include('financialgraph.modals.home')
@include('financialgraph.modals.calendar')
{{-- Fin carga --}}
{{-- Graficos de ventas --}}

<div class="row">
    <div class="col-3 col-sm-3 d-none">
      <div class="card card-body elevation-3 p-2">
        <h4 align="center"><i class="fad fa-chart-bar text-bold text-lightblue text-lg"></i> Ventas Anuales </h4>
        <div class="table-responsive">
          <table class="small-table table-sm table table-striped table-home" cellspacing="0" width="100%">
              <thead class="table-primary">
                  <tr>
                    <th>AÑO</th>
                    <th>TOTAL VENTAS</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>2019</td>
                    <td>{!!$yearSales!!}</td>
                  </tr>
                  <tr>
                    <td>2020</td>
                    <td>{!!$yearSales!!}</td>
                  </tr>
                  <tr>
                    <td>2021</td>
                    <td>{!!$yearSales!!}</td>
                  </tr>
              </tbody>
          </table>
        </div>
      </div>
    </div>
  <div class="col-md-12">
    <div class="card" id="card-filter">
      <div class="card-header pl-3 pr-3 pt-2 pb-0">
        <h5><i class="fad fa-chart-line text-bold text-lightblue text-lg"></i> Comparativa Ventas Anuales de {{$companiesName}}
          <button type="button" class="btn bg-info btn-sm text-xs float-right mt-3" data-card-widget="collapse" style="margin-top: 1px !important;">
            <i class="fad fa-minus"></i>
          </button>
        </h5>
      </div>
      <div class="card-body p-2">
        <canvas id="canvasYDT" style="display: block; height: 350px; width: 1067px;" width="1173" height="350" class="chartjs-render-monitor"></canvas>
      </div>
    </div>
  </div>
</div>
<div class="card" id="card-filter">
  <div class="card-header pl-3 pr-3 pt-2 pb-0">
    <h5><i class="fad fa-chart-line text-bold text-lightblue text-lg"></i> Ingresos Por Asesor (€) 
      <button type="button" class="btn bg-info btn-sm text-xs float-right mt-3" data-card-widget="collapse" style="margin-top: 1px !important;">
      <i class="fad fa-minus"></i>
      </button>
    </h5>
  </div>
  <div class="card-body p-2">
    <form id="graphFilter" name="graphFilter" class="form-horizontal mb-n3"  enctype="multipart/form-data">
      <div class="row d-flex justify-content-center" style="margin: auto;">
        <div class="col-sm-4 my-auto">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Filtrar por rango de fecha</label>
            <div class="col-sm-7">
              <input type="text" name="ranges" class="form-control form-control-sm pull-right" id="dt_sells_filter">
              <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_sells_from" name="dt_sells_from">
              <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_sells_to"  name="dt_sells_to">
            </div>
          </div>
        </div>
        <div class="col-sm-4 my-auto">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Filtrar por asesor</label>
            <div class="col-sm-8">
              <select multiple class="form-control form-control-sm select_list_filter" id="agent_list_filter" name="agent_list_filter[]">
                  @foreach (App\Models\User::all() as $cData)
                      @if ($cData->hasRole('comercial'))
                          <option value="{{$cData->id}}">{{$cData->name}}</option>
                      @endif
                  @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="col-sm-1">
          <div class="btn-group" role="group" aria-label="Basic example">
            <a href="javascript:void(0);" id="btnFilterGraph" class="btn rounded btn-sm bg-lightblue btnFilterGraph"><i class="fad fa-binoculars"></i> Filtrar</a>
            <a href="javascript:void(0);" id="btnCleanLeadID" class="ml-1 rounded btn btn-sm bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Filtros</a> 
          </div>
        </div>
      </div>
    </form>
    <canvas id="canvasY" style="display: block; height: 350px; width: 1067px;" width="1173" height="350" class="chartjs-render-monitor"></canvas>
  </div>
</div>

<div class="card" id="card-filter">
  <div class="card-header pl-3 pr-3 pt-2 pb-0">
    <h5><i class="fad fa-chart-line text-bold text-lightblue text-lg"></i> Matrículas Por Asesor
      <button type="button" class="btn bg-info btn-sm text-xs float-right mt-3" data-card-widget="collapse" style="margin-top: 1px !important;">
        <i class="fad fa-minus"></i>
      </button>
    </h5>
  </div>
  <div class="card-body p-2">
    <form id="graphFilterM" name="graphFilterM" class="form-horizontal mb-n3"  enctype="multipart/form-data">
      <div class="row d-flex justify-content-center" style="margin: auto;">
        <div class="col-sm-4 my-auto">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Filtrar por rango de fecha</label>
            <div class="col-sm-7">
              <input type="text" name="rangesM" class="form-control form-control-sm pull-right" id="dt_sells_filterM">
              <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_sells_fromM" name="dt_sells_fromM">
              <input  type="hidden" class="form-control form-control-sm pull-right" id="dt_sells_toM"  name="dt_sells_toM">
            </div>
          </div>
        </div>
        <div class="col-sm-4 my-auto">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Filtrar por asesor</label>
            <div class="col-sm-8">
              <select multiple class="form-control form-control-sm select_list_filter" id="agent_list_filterM" name="agent_list_filter[]">
                  @foreach (App\Models\User::all() as $cData)
                      @if ($cData->hasRole('comercial'))
                          <option value="{{$cData->id}}">{{$cData->name}}</option>
                      @endif
                  @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="col-sm-1">
          <div class="btn-group" role="group" aria-label="Basic example">
            <a href="javascript:void(0);" id="btnFilterGraphM" class="btn rounded btn-sm bg-lightblue btnFilterGraphM"><i class="fad fa-binoculars"></i> Filtrar</a>
            <a href="javascript:void(0);" id="btnCleanLeadIDM" class="ml-1 rounded btn btn-sm bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Filtros</a> 
          </div>
        </div>
      </div>
    </form>
    <canvas id="canvas" style="display: block; height: 350px; width: 1067px;" width="1173" height="350" class="chartjs-render-monitor"></canvas>
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
@include('financialgraph.js.jsfunctions')
@include('financialgraph.js.jsfunctionsgraph')
{{-- Fin carga --}}
@endpush
