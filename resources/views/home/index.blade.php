@extends('adminlte::page')

@section('title', $pageTitle)
<style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }

    .widget p {
        display: inline-block;
        line-height: 1em;

    }

    .fecha {
        font-family: Oswald, Arial;
        font-size: 1.3em;
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
            <div class="col-sm-6">
                @if (Auth::user()->hasRole('superadmin'))
                    <h3 style="color:#9d6fd6">¡Hola {{ $info->name }} {{ $info->last_name }} ☺️!</h3>
                @else
                    <h3 style="color:#9d6fd6">¡Hola {{ $info->name }} ☺️!</h3>
                @endif
            </div>
            <div class="col-sm-6">
                <div class="text-lightblue fecha float-sm-right" align="right">
                    <i class="fad fa-calendar-alt text-bold text-lightblue text-lg"></i>
                    <span id="diade" class="text-capitalize"></span>
                    <span id="numerodia"></span> de
                    <span id="mesde" class="text-capitalize"></span> de
                    <span id="aniode"></span>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    {{-- Carga los modal --}}
    @include('home.modals.home')
    @include('home.modals.calendar')
    @include('home.modals.tutorial')
    {{-- Fin carga --}}
    {{-- Resumen Leads vs Conversiones Global --}}
    <div class="row animate__animated animate__backInLeft @php if (Auth::user()->hasRole('teacher')) echo 'd-none' @endphp">
        <div class="col-12 col-sm-4">
            <div class="bg-white p-2 elevation-3">
                <div class="p-1" align="center" style="background-color: #f6a137cf">
                    <i class="fad fa-user-friends fa-3x text-white mobile-hide"
                        style="margin-left: -120px;position: absolute;"></i>
                    <span style="color: white !important">Leads Nuevos del Mes</span>
                    <br><span style="color: white !important">{{ $countLead }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="bg-white p-2 elevation-3">
                <div class="p-1" align="center" style="background-color:  #a973e7cf">
                    <i class="fad fa-euro-sign fa-3x text-white mobile-hide"
                        style="margin-left: -120px;position: absolute;"></i>
                    <span style="color: white !important">Ventas del Mes Actual</span>
                    <br><span style="color: white !important">{{ $countContract }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="bg-white p-2 elevation-3">
                <div class="p-1" align="center" style="background-color:  #077e2278">
                    <i class="fad fa-chart-line fa-3x text-white mobile-hide"
                        style="margin-left: -120px;position: absolute;"></i>
                    <span style="color: white !important">Total Conversión del Mes Actual </span>
                    <br><span style="color: white !important">{{ $percent }} %</span>
                </div>
            </div>
        </div>
    </div>
    <div
        class="row animate__animated animate__backInRight mt-2 text-sm justify-content-md-center  @php if (Auth::user()->hasRole('teacher')) echo 'd-none' @endphp">
        <div class="col-12 col-sm-3">
            <div class="bg-white p-2 elevation-2">
                <div class="p-1" align="center" style="background-color: #e09a435e">
                    <i class="fad fa-user-friends fa-2x text-gray mobile-hide"
                        style="margin-left: -66px;position: absolute;margin-top: 5px;"></i>
                    <span style="color: black !important">Leads Reacondicionados del Mes</span>
                    <br><span style="color: black !important">{{ $countLeadReacond }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="bg-white p-2 elevation-2">
                <div class="p-1" align="center" style="background-color: #a973e76b">
                    <i class="fad fa-euro-sign fa-2x text-gray mobile-hide"
                        style="margin-left: -40px;position: absolute;margin-top: 5px;"></i>
                    <span style="color: black !important">Ventas del Mes Actual Reacondicionados</span>
                    <br><span style="color: black !important">{{ $countContractReacond }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="bg-white p-2 elevation-2">
                <div class="p-1" align="center" style="background-color:  #077e2233">
                    <i class="fad fa-chart-line fa-2x text-gray mobile-hide"
                        style="margin-left: -30px;position: absolute;margin-top: 5px;"></i>
                    <span style="color: black !important">Conversión del Mes Actual Reacondicionados</span>
                    <br><span style="color: black !important">{{ $percentReacond }} %</span>
                </div>
            </div>
        </div>
    </div>
    <br>
    {{-- Fin Resumen --}}
    {{-- Tabla detalle de conversiones comerciales --}}

    <div
        class="card-deck animate__animated animate__fadeIn @php if (Auth::user()->hasRole('teacher')) echo 'd-none' @endphp">
        <div class="card col-sm-6 elevation-3 p-2">
            <div class="card-body">
                <h4 align="center"><i class="fad fa-chart-bar text-bold text-lightblue text-lg"></i> Actividad Mensual</h4>
                <div class="table-responsive">
                    <table class="small-table table-sm table table-striped table-home alldata" cellspacing="0" width="100%">
                        <thead class="table-primary">
                            {{-- id="div-scroll" style=" height:300px; overflow: scroll;" --}}
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>ASESOR</th>
                                <th>LEADS MES ACTUAL</th>
                                <th>MATRÍCULAS</th>
                                <th>% CONVERSIÓN</th>
                                <th>LIQUIDACIÓN <br><span id="mesdeliq" class="text-uppercase"></span></th>
                                <th>LEADS <br>DE HOY</th>
                                @if (Auth::user()->hasRole('superadmin'))
                                    <th>ÚLTIMA CONEXIÓN</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            {!! $resultsTable !!}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card col-sm-6 elevation-3 p-2">
            <div class="card-body ">
                <h4 align="center"><i class="fad fa-map-marked text-bold text-lightblue text-lg"></i> Matrículas por
                    Provincia</h4>
                <div id="spainMap" style="width:100%;height:300px"></div>
                <div>
                    @foreach ($autonomousCommunityAll as $item)
                        <span class="text-xs"><span class="badge bg-primary text-white"
                                style="background-color: {{ $item->color }}!important">&nbsp;{{ $item->total }}&nbsp;</span>
                            {{ $item->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <br>
    {{-- Fin Tabla detalle --}}
    {{-- Graficos de ventas --}}
    <div class="row @php if (Auth::user()->hasRole('teacher')) echo 'd-none' @endphp">
        <div class="col-sm-6">
            <div class="card card-body elevation-3">
                <h4><i class="fad fa-chart-line text-bold text-lightblue text-lg"></i> Ingresos Por Asesor (€)<a
                        class="text-xs float-right mt-3 " href="{{ url('contractcrud') }}"><i
                            class="fad text-lightblue fa-sign-out-alt"></i>&nbsp;Administrar&nbsp; </a></h4>
                <canvas id="canvasY" style="display: block; height: 533px; width: 1067px;" width="1173" height="586"
                    class="chartjs-render-monitor"></canvas>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card card-body elevation-3">
                <h4><i class="fad fa-chart-line text-bold text-lightblue text-lg"></i> Matrículas <a
                        class="text-xs float-right mt-3 " href="{{ url('contractcrud') }}"><i
                            class="fad text-lightblue fa-sign-out-alt"></i>&nbsp;Administrar&nbsp; </a></h4>
                <canvas id="canvas" style="display: block; height: 533px; width: 1067px;" width="1173" height="586"
                    class="chartjs-render-monitor"></canvas>
            </div>
        </div>
    </div>
    {{-- Fin Graficos de ventas --}}
    {{-- Herramientas y Calendario --}}
    <div class="row">
        @include('home.tools')
        <div class="col-sm-6">
            <div class="card card-body elevation-3">
                <h4><i class="fad fa-calendar-alt text-bold text-lightblue text-lg"></i>
                    @php
                        if (Auth::user()->hasRole('teacher')) {
                            echo 'Tutorías Programadas';
                        } else {
                            echo 'Eventos Importantes';
                        }
                    @endphp
                </h4>
                <img class="calendarshow" style="cursor:pointer" src="{{ asset('storage/uploads/Calendar.png') }}"
                    width="100%" alt="">
            </div>
        </div>
        {{-- <div class="col-sm-6">
      <div class="card card-body elevation-3">
        <h4><i class="fad fa-calendar-alt text-bold text-lightblue text-lg"></i> Eventos Importantes</h4>
        <div class="calendar-schedules-home"></div>
        <div id="display-calendar-alert"></div>
        <a href="{{url('appointments')}}" class="btn btn-sm bg-lightblue float-right">Ir al calendario</a>
      </div>
    </div> --}}
    </div>
    {{-- Fin Herramientas y Calendarios
{{ \Carbon\Carbon::setlocale(LC_ALL, 'es_ES', 'es', 'ES') }}
{{ Carbon\Carbon::now()->formatLocalized("%A %d %B %Y") }} --}}
    <div class="row d-none">
        <div class="col-sm-6">
            <h4><i class="fad fa-calendar-alt text-bold text-lightblue text-lg"></i> Eventos Importantes</h4>
            <img class="calendarshow" style="cursor:pointer" src="{{ asset('storage/uploads/Calendar.png') }}"
                width="100%" alt="">
        </div>
    </div>
@stop
@push('js')
    <script src="{{ asset('js/components/datatable.js') }}"></script>
    <script src="{{ asset('js/components/general-layout.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    {{-- Carga funciones js del módulo --}}
    @include('home.js.jsfunctions')
    @include('home.js.jsfunctionsgraph')
    {{-- Fin carga --}}
@endpush
