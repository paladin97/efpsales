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
            <h1 align="center"></h1>
          </div>
        </div>
    </div>
@stop

@section('content')
{{-- Carga los modal --}}
{{-- @include('financialgraph.modals.home')
@include('financialgraph.modals.calendar') --}}
{{-- Fin carga --}}
<div class="card card-lightblue card-outline">
  <div class="card-header pl-3 pr-3 pt-2 pb-0 text-center">
    <h4><i class="fad fa-calculator-alt text-bold text-lightblue text-lg"></i> {{$pageTitle}}</h4>
  </div>
  <div class="card-body">
    <form id="advancefilter">
        <div class="row d-flex justify-content-center" style="margin: auto;">
          <div class="col-sm-3">
              <div class="form-group">
                <label for="name" class="mb-n1">Seleccione un Año <label class="text-red">(*)</label></label>
                <select  class="form-control form-control-sm select_list_filter" id="year_filter" name="year_filter">
                    <option selected value="{{Carbon\Carbon::now()->format('Y')}}">{{Carbon\Carbon::now()->format('Y')}}</option>
                    @for ($i = 1; $i <= 5; $i++) 
                        <option value="{{Carbon\Carbon::now()->subYears($i)->format('Y')}}">{{Carbon\Carbon::now()->subYears($i)->format('Y')}}</option>
                    @endfor  
                </select>
              </div>
          </div>
          <div class="col-sm-3">
              <div class="form-group">
                  <label for="name" class="mb-n1">Seleccione un Trimestre <label class="text-red">(*)</label></label>
                  <select class="form-control form-control-sm select_list_filter" id="quarter_filter" name="quarter_filter">
                    <option selected value="5">-TODOS-</option>
                    @for ($i = 1; $i < 5; $i++) 
                        <option value="{{$i}}">{{$i.'T'}}</option>
                    @endfor  
                  </select>
              </div>
          </div>
        </div>
        <hr>
        <h4 class="text-center text-lightblue"><i class="fad fa-chevron-double-down"></i> Segmentación <i class="fad fa-chevron-double-down"></i></h4>
        <div class=" p-2 " style="margin: auto;margin: auto;background: #3c8dbc5c;">
          <div class="row d-flex justify-content-center" >
            <div class="col-sm-3">
              <div class="form-group">
                  <label for="name" class="mb-n1">Asesor<label class="text-red">&nbsp;</label></label>
                  <select multiple class="form-control form-control-sm select_list_filter" id="agent_list_filter" name="agent_list_filter[]">
                    @foreach (App\Models\User::all() as $cData)
                        @if ($cData->hasRole('comercial'))
                            <option value="{{$cData->id}}">{{$cData->name}}</option>
                        @endif
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="name" class="mb-n1">Método de Pago<label class="text-red">&nbsp;</label></label>
                <select multiple class="form-control form-control-sm select_list_filter" id="contract_method_type_list_filter" name="contract_method_type_list_filter">
                    <option value="">Seleccione</option>
                    @foreach(App\Models\ContractPaymentMethod::all() as $cData)
                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                  <label for="name" class="mb-n1">Comunidad Autónoma<label class="text-red">&nbsp;</label></label>
                  <select multiple class="form-control form-control-sm select_list_filter" id="contract_aucom_filter" name="contract_aucom_filter[]">
                      @foreach (App\Models\AutonomousCommunity::all() as $cData)
                          <option value="{{$cData->id}}">{{$cData->name}}</option>
                      @endforeach
                  </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                  <label for="name" class="mb-n1">Provincia<label class="text-red">&nbsp;</label></label>
                  <select multiple class="form-control form-control-sm select_list_filter" id="contract_province_filter" name="contract_province_filter[]">
                      @foreach (App\Models\Province::all() as $cData)
                          <option value="{{$cData->id}}">{{$cData->name}}</option>
                      @endforeach
                  </select>
              </div>
            </div>
          </div>
          <div class="row d-flex justify-content-center">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="name" class="mb-n1">Área<label class="text-red">&nbsp;</label></label>
                <select multiple class="form-control form-control-sm select_list_filter" id="course_area_filter" name="course_area_filter">
                    <option value="">Seleccione</option>
                    @foreach(App\Models\CourseArea::all() as $cData)
                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                  <label for="name" class="mb-n1">Curso<label class="text-red">&nbsp;</label></label>
                  <select multiple class="form-control form-control-sm select_list_filter" id="course_filter" name="course_filter[]">
                      @foreach (App\Models\Course::all() as $cData)
                          <option value="{{$cData->id}}">{{$cData->name}}</option>
                      @endforeach
                  </select>
              </div>
            </div>
          </div>
        </div>
    </form>
  </div>
</div>

<div class="card-footer mt-n3">
    <div class="btn-group" role="group" aria-label="Basic example">
        <button type="text" id="btnFiterSubmitSearch" class="btn bg-lightblue rounded-right"><i class="fad fa-file-spreadsheet"></i> Generar Informe</button> 
    </div>
</div>
<br>

<div class="card card-lightblue card-outline">
  <div class="card-body">
      <div class="row" style="margin: auto;">
          <div class="col-sm-12">
              <div class="table-responsive">
                  <table class="small-table table-sm table-bordered table-striped row-border cell-border order-column compact table data-table " cellspacing="0" width="100%">
                      <thead>
                          <tr>
                              <th class="text-center"></th>
                              <th class="text-center">AÑO</th> {{-- debe --}}
                              <th class="text-center">TRIMESTRE</th> {{-- debe --}}
                              <th class="text-center">ÁREA</th> {{-- debe --}}
                              <th class="text-center">CURSO</th> {{-- debe --}}
                              <th class="text-center">TOTAL VENTAS </th>{{-- debe --}}
                              <th class="text-center">VENTAS EN EURO</th></th>{{-- debe --}}
                              <th class="text-center">COMISIÓN ASESOR(es) </th>                                                    
                          </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th colspan="7" style="text-align:right">Total:</th>
                            <th></th>
                        </tr>
                    </tfoot>
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
@include('admin.spreadsheet.js.jsfunctions')
{{-- @include('financialgraph.js.jsfunctionsgraph') --}}
{{-- Fin carga --}}
@endpush
