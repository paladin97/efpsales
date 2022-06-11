@extends('adminlte::page')

@section('title', $pageTitle)

<style>
    .text-muted { 
        font-size: 120% !important;
    }
    .pignose-calendar.pignose-calendar-dark {
        background-color:#1d5c78!important;
    }
    .pignose-calendar {
        max-width: 100%!important;
    }
    .pignose-calendar.pignose-calendar-dark .pignose-calendar-top {
        background-color:#1d5c78!important;
    }
    .pignose-calendar.pignose-calendar-dark .pignose-calendar-body .pignose-calendar-row .pignose-calendar-unit a {
        color: #ffffff!important;
    }
    .pignose-calendar.pignose-calendar-dark .pignose-calendar-body .pignose-calendar-row .pignose-calendar-unit.pignose-calendar-unit-sun a, .pignose-calendar.pignose-calendar-dark .pignose-calendar-body .pignose-calendar-row .pignose-calendar-unit.pignose-calendar-unit-sat a {
        color: #ff6060!important;
    }
    .grey-bg {  
        background-color: #F5F7FA;
    }
    .wrimagecard{	
        margin-top: 0;
        margin-bottom: 1.5rem;
        text-align: left;
        position: relative;
        background: #fff;
        box-shadow: 12px 15px 20px 0px rgba(46,61,73,0.15);
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    .wrimagecard .fad, .fab{
        position: relative;
        font-size: 70px;
    }
    .wrimagecard-topimage_header{
    padding: 20px;
    }
    a.wrimagecard:hover, .wrimagecard-topimage:hover {
        box-shadow: 2px 4px 8px 0px rgba(46,61,73,0.2);
    }
    .wrimagecard-topimage a {
        width: 100%;
        height: 100%;
        display: block;
    }
    .wrimagecard-topimage_title {
        padding: 20px 24px;
        height: 80px;
        padding-bottom: 0.75rem;
        position: relative;
    }
    .wrimagecard-topimage a {
        border-bottom: none;
        text-decoration: none;
        color: #525c65;
        transition: color 0.3s ease;
    }



/*     
    .button {
        display: inline-block;
        background: #4285f4;
        color: #fff;
        text-transform: uppercase;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0px 17px 10px -10px rgba(0, 0, 0, 0.4);
        cursor: pointer;
        -webkit-transition: all ease-in-out 300ms;
        transition: all ease-in-out 300ms;
    }
    .button:hover {
        box-shadow: 0px 37px 20px -20px rgba(0, 0, 0, 0.2);
        -webkit-transform: translate(0px, -10px) scale(1.2);
                transform: translate(0px, -10px) scale(1.2);
    } */


</style>
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <p class="md-txt" style="font-size: 1em">Panel Principal ƎFP Sales, <a href="javascript:void(0)" class="btn btn-xs bg-lightblue"  data-toggle="modal" data-target="#exampleModal"><i class="fad fa-tools "></i> Mis Herramientas</a></p>					
                </ol>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                    <li class="breadcrumb-item active">Tablero</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<section id="stats-subtitle">
    <div class="row">
        <div class="col-xl-6 col-md-12">
            <div class="card">
              <div class="card-content">
                <div class="card-body cleartfix">
                  <div class="media align-items-stretch">
                    <div class="align-self-center">
                      <h1 class="mr-2">17,000.00 €</h1>
                    </div>
                    <div class="media-body">
                      <h4>Ganancias Totales</h4>
                      <span>por ventas</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fad fa-coins fa-4x text-lightblue"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      <div class="col-xl-6 col-md-12">
        <h4><i class="fad fa-chart-line text-bold text-lightblue text-lg"></i> Matrículas Totales   <a class="text-xs float-right mt-3 " href="{{url('casecrud')}}"><i class="fad text-lightblue fa-sign-out-alt"></i>&nbsp;Administrar&nbsp; </a></h4>
        <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      </div>
    </div>
    <div class="row" style="margin-top: -150px;">
      <div class="col-xl-6 col-md-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body cleartfix">
              <div class="media align-items-stretch">
                <div class="align-self-center">
                  <h1 class="mr-2">3,600.00 €</h1>
                </div>
                <div class="media-body">
                  <h4>Ganancias Mensuales</h4>
                  <span>por ventas</span>
                </div>
                <div class="align-self-center">
                    <i class="fad fa-wallet fa-4x text-lightblue"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="leadsHeading" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">
      <div class="modal-content">
        <div class="modal-header bg-lightblue color-palette">  
            <h4 class="modal-title" id="eventModalHeading">Herramientas ƎFP Sales</h4>   
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
            </button>        
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3 col-sm-4 p-2">
                    <div class="wrimagecard wrimagecard-topimage">
                          <a href="#">
                          <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                            <center><i class="fad fa-tags text-warning"></i></center>
                          </div>
                          <div class="wrimagecard-topimage_title">
                            <h4>Precios Actualizados 2021
                            <div class="pull-right badge"></div></h4>
                          </div>
                        </a>
                      </div>
                    </div>
                <div class="col-md-3 col-sm-4 p-2">
                    <div class="wrimagecard wrimagecard-topimage">
                        <a href="#">
                        <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                        <center><i class = "fad fa-file-alt text-info"></i></center>
                        </div>
                        <div class="wrimagecard-topimage_title">
                        <h4>Documentos para Matricular
                        <div class="pull-right badge" id="WrControls"></div></h4>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 p-2">
                    <div class="wrimagecard wrimagecard-topimage">
                        <a href="https://grupoefp.com/" target="_blank">
                        <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                        <center><i class="fad fa-browser text-blue"> </i></center>
                        </div>
                        <div class="wrimagecard-topimage_title" >
                        <h4>Acceso a Página Web
                        <div class="pull-right badge" id="WrForms"></div>
                        </h4>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 p-2">
                    <div class="wrimagecard wrimagecard-topimage">
                        <a href="https://mail.ionos.es" target="_blank">
                        <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                            <center><i class="fad fa-envelope-open-text text-navy"> </i></center>
                        </div>
                        <div class="wrimagecard-topimage_title">
                        <h4>Acceso a E-Mail
                        <div class="pull-right badge" id="WrInformation"></div></h4>
                        </div>
                        
                    </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 p-2">
                    <div class="wrimagecard wrimagecard-topimage">
                        <a href="{{Auth::user()->profile_url()}}" target="_blank">
                        <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                            <center><img src="https://golinks.online/uploads/avatars/2aa5d71184f958c200bd2e45e3a25f8e.png" width="75px"alt=""></center>
                        </div>
                        <div class="wrimagecard-topimage_title">
                        <h4>Acceso a mi GoLinks®
                        <div class="pull-right badge" id="WrInformation"></div></h4>
                        </div>
                        
                    </a>
                    </div>
                </div>
                @if(Auth::user()->hasRole('superadmin'))
                    <div class="col-md-3 col-sm-4 p-2">
                        <div class="wrimagecard wrimagecard-topimage">
                            <a href="#">
                            <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                            <center><i class="fad fa-money-check-edit text-success"> </i></center> 
                            </div>
                            <div class="wrimagecard-topimage_title">
                            <h4>Acceso a Financiera
                            <div class="pull-right badge" id="WrNavigation"></div></h4>
                            </div>
                            
                        </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 p-2">
                        <div class="wrimagecard wrimagecard-topimage">
                            <a href="#">
                            <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                            <center><i class="fab fa-youtube text-red"></i></center>
                            </div>
                            <div class="wrimagecard-topimage_title">
                            <h4>Video Instructivo Paypal
                            <div class="pull-right badge" id="WrThemesIcons"></div></h4>
                            </div>
                        </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-lightblue" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

<br>
<div class="row"> 
    <div class="col-sm-6">
        <h4><i class="fad fa-users text-bold text-lightblue text-lg"></i> Últimos Leads Asignados <a class="text-xs float-right mt-3 " href="{{url('leads')}}"><i class="fad text-lightblue fa-sign-out-alt"></i>&nbsp;Administrar&nbsp; </a></h4>
        <div class="table-responsive">
            <table class="small-table table-sm table " cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                    <th style="width: 10px">#</th>
                    <th>Estudiante</th>
                    <th>Curso</th>
                    <th>Estado Contrato</th>
                    <th>Fecha Creación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(App\Models\Province::from('leads as le')
                                ->leftJoin('lead_status as lu', 'le.lead_status_id', '=', 'lu.id')
                                ->leftJoin('users as u', 'le.agent_id', '=', 'u.id')
                                ->leftJoin('courses as crs', 'le.course_id', '=', 'crs.id')
                                ->select('le.*','u.name as agent_name','crs.name as curso'
                                        ,'lu.name as lead_status','lu.color_class as color_class')
                                ->orderBy('le.created_at','desc')
                                ->take(9)->get() as $cData)
                    @php
                        Carbon\Carbon::setLocale('es');
                        $fecha = Carbon\Carbon::parse($cData->dt_reception);
                        $date = $fecha->locale(); 
                        $fecha_lead =$fecha->day.' de '. $fecha->monthName.' de '  .$fecha->year; 
                    @endphp
                    <tr></td>
                        <td>{{ $loop->index +1}}</td>
                        <td>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="text-black">{{$cData->student_first_name }} {{$cData->student_last_name }}</span>
                                    <span class="text-muted text-xs mt-1">{{$cData->student_email }}</span>
                                </div>
                            </div>
                        
                        <td>{{$cData->curso }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-column">
                                   <span><h6 class="mt-1 badge badge-pill  {{$cData->color_class}}">{{$cData->lead_status }}</h6></span>
                                   <span><p>Asignado a: {{$cData->agent_name }}</p></span>
                                </div>
                            </div>
                            
                        </td>
                        <td>{{$fecha_lead }}</td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>    
        <br>
        
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <h4><i class="fad fa-calendar-alt text-bold text-lightblue text-lg"></i> Tareas</h4>
        <div class="calendar-schedules"></div>
        <div id="display-calendar-alert"></div>
        <a href="javascript:void(0)" class="btn btn-sm bg-lightblue float-right">Ir al calendario</a>
    </div>
</div>
  
@stop
@push('js')
<script>
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
        @if (session('flash'))
            $.dialog({
                title: '',
                content: "<i class='fad fa-quote-left text-lightblue'></i><h5 class='text-lightblue'> {{ session('flash')->quote }}</h5>"+
                         "<br>" +  
                         "<p class='text-gray mt-n3'><b>-{{ session('flash')->author }}</b></p>" +  
                         "<p class='ml-2 mt-n2 text-lightblue'>{{ session('flash')->description }}</p>"   ,   
            });
        @endif
        
        let schedulesAux = [];
        $.when(
            $.get("{{url('eventmaster')}}" +'/', (x)=>{
                x.map(y=>{
                    schedulesAux.push({name : 'alert',
                        type : 'Observacion: '+y.title
                                +'\nFecha y Hora: '+ y.start
                                +'\nCliente: '+ y.first_name +' '+ y.last_name,
                        date:moment(y.start).format("YYYY-MM-DD")});
                });
            })
        ).then(function(){
            $('.calendar-schedules').pignoseCalendar({
                format: 'DD/MM/YYYY',
                theme: 'blue',
                lang: 'es',
                scheduleOptions: {
                    colors: {
                        alert: '#f90202',
                    }
                },
                schedules: schedulesAux,
                select: function (date, context) {
                    var message = `
                        <div class="callout callout-lightblue">
                            <h5>Citas para el día: ${(date[0] === null ? ' ' : date[0].format('DD/MM/YYYY'))}</h5>

                            <p><div class="schedules-date"></div></p>
                        </div>
                     `;
                    var $target = context.calendar.parent().next().show().html(message);

                    for (var idx in context.storage.schedules) {
                        var schedule = context.storage.schedules[idx];
                        if (typeof schedule !== 'object') {
                            continue;
                        }
                        $target.find('.schedules-date').append('<p">' + schedule.type.replace(/(?:\r\n|\r|\n)/g, '<br />') + '</p>');
                    }
                }
            });
        });
        var areaChartOptions = {
            responsive: true,
            title: {
                display: true,
                text: ''
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            maintainAspectRatio : false,
            responsive : true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                    display: true,
                    labelString: 'Mes'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                    display: true,
                    labelString: 'Matrículas'
                    }
                }]
            }
        };
        var areaChartData = {
            labels  : ['Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero'],
            datasets: [
                {
                    label               : '',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label               : '',
                    backgroundColor     : 'rgba(210, 214, 222, 1)',
                    borderColor         : 'rgba(210, 214, 222, 1)',
                    pointRadius         : false,
                    pointColor          : 'rgba(210, 214, 222, 1)',
                    pointStrokeColor    : '#c1c7d1',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data                : [65, 59, 80, 81, 56, 55, 40]
                },
            ]
        };


        var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
        var lineChartOptions = $.extend(true, {}, areaChartOptions)
        var lineChartData = $.extend(true, {}, areaChartData)
        lineChartData.datasets[0].fill = false;
        lineChartData.datasets[1].fill = false;
        lineChartOptions.datasetFill = false

        var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
        });

        var lineChartCanvas2 = $('#lineChart2').get(0).getContext('2d')
        var lineChartOptions2 = $.extend(true, {}, areaChartOptions)
        var lineChartData2 = $.extend(true, {}, areaChartData)
        lineChartData2.datasets[0].fill = false;
        lineChartData2.datasets[1].fill = false;
        lineChartOptions2.datasetFill = false

        var lineChart2 = new Chart(lineChartCanvas2, {
        type: 'line',
        data: lineChartData2,
        options: lineChartOptions2
        });
        
        
    });
	// @if (session('flash'))
        
    // @endif
   

</script>
@endpush
