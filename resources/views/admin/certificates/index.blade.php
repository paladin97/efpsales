@extends('adminlte::page')

@section('title',$pageTitle)

@section('content_header')

@stop
@section('content')
@include('admin.certificates.modals.certificate_crud')

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
    <div class="card card-lightblue card-outline col-sm-9" id="card-filter">
        <div class="card-header pl-3 pr-3 pt-2 pb-0">
            <h4><i class="fad fa-rotate-90 fa-sliders-h text-bold text-lightblue text-lg"></i> Establece los Filtros..</h4>
        </div>
        <div class="card-body">
            <form id="advancefilter">
                <div class="row" style="margin: auto;">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Curso</label>
                            <select class="form-control form-control-sm select_list_filter" id="course_filter" name="course_filter[]" multiple>
                                @foreach(App\Models\Course::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="mb-n1">Grupo</label>
                            <select class="form-control form-control-sm select_list_filter" id="course_area_filter" name="course_area_filter[]" multiple>
                                @foreach(App\Models\CourseArea::all() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="mb-n1">Contrato</label>
                        <select class="form-control form-control-sm select_list_filter" id="cert_contract_filter" name="cert_contract_filter[]" multiple>
                            @foreach(App\Models\Contract::from('contracts as c')
                                                ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                                                ->whereContractStatusId(3)
                                                ->select('c.*', 'pi.name as student_name','pi.last_name as student_last_name')
                                                ->orderBy('enrollment_number','DESC')
                                                ->get() as $cData)
                                <option value="{{$cData->id}}">{{$cData->enrollment_number}} - {{$cData->student_name}}, {{$cData->student_last_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer mt-n3">
            <button type="text" id="btnFiterSubmitSearch" class="btn bg-lightblue"><i class="fad fa-binoculars"></i> Filtrar</button>
            <button type="text" id="btnCleanLeadID" class="btn bg-lightblue"><i class="fad fa-eraser"></i> Limpiar Filtros</button>   
        </div>
    </div>
</div>
<br>
<div class="row justify-content-md-center" style="margin:auto;">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="fad fa-file-certificate fa-lg text-lightblue"></i> Listado de {{$pageTitle}}</h2>
            </div>
            <div class="card-body">
        {{-- <table class="table table-striped table-bordered compact data-table" id="banco" style="width:100%;"> --}}
                <table class="table table-bordered table-hover data-table" id="certificates" style="width:100%;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Matrícula</th>
                            <th>Grupo</th>
                            <th>Curso</th>
                            <th>Nombre(s)</th>
                            <th>Fecha Contrato</th>
                            <th>Vigencia</th>
                            <th>Estado</th>
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
@include('admin.certificates.js.jsfunctions')
@include('admin.certificates.js.jsfunctionsSendCert')
{{-- Fin carga --}}
@endpush