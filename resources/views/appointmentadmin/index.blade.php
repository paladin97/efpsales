@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Calendario Administrativo</li>
            </ol>
        </div>
        </div>
    </div>
@stop
@section('content')
{{-- Carga los modal --}}
@include('appointmentadmin.modals.appointment_crud')
{{-- Fin carga --}}
    <div class="card-deck">
        <div class="card card-lightblue card-outline col-sm-2">
            <div class="card-header pl-3 pr-3 pt-2 pb-0">
                <h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Instrucciones</h4>
            </div>
            <div class="card-body">
                <ol>
                    <li>Para crear un evento presione sobre cualquier día del calendario.</li>
                    <li>Puede crear un evento de varios días seleccionando varios días con el mouse.</li>
                    <li>Para editar un evento, solo presione sobre su nombre.</li>
                </ol>
                <hr>
                <h4 align="center" class="text-lightblue text-bold"> Leyenda Etiquetas </h4>
                <ul class="list-unstyled">
                @foreach (App\Models\LeadStatus::whereNotIn('id',[1,2,4,6,7,9,10,11,12])->orderBy('name','ASC')->get() as $cData)
                    
                    <li class="m-1"><span class="badge d-block text-xs {{$cData->color_class}}">{{$cData->name}}</span></li>
                
                @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-3 d-none">
            <div class="sticky-top mb-3">
                <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Eventos Disponibles</h4>
                </div>
                <div class="card-body">
                    <!-- the events -->
                    <div id="external-events">
                    <div class="external-event bg-success">Reunión</div>
                    <div class="external-event bg-warning">Llamar a cliente</div>
                    <div class="external-event bg-lightblue">Cita Personal</div>
                    <div class="external-event bg-primary">Gestionar Expediente</div>
                    <div class="external-event bg-danger">Evento Importante</div>
                    <div class="checkbox">
                        <label for="drop-remove">
                        <input type="checkbox" id="drop-remove">
                        eliminar despues de arrastrar
                        </label>
                    </div>
                    </div>
                </div>
                </div>
                <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Crear Evento</h3>
                </div>
                <div class="card-body">
                    <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                    <ul class="fc-color-picker" id="color-chooser">
                        <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                        <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                        <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                        <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                        <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                    </ul>
                    </div>
                    <div class="input-group">
                    <input id="new-event" type="text" class="form-control" placeholder="Título del evento">

                    <div class="input-group-append">
                        <button id="add-new-event" type="button" class="btn btn-primary">Añadir</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="card card-lightblue card-outline col-sm-10" align="center">
            <div class="card-header pl-3 pr-3 pt-2 pb-0 text-center">
                <h4><i class="fas fa-calendar-alt text-bold text-lightblue text-lg"></i> Calendario Administrativo GrupoeFP</h4>
            </div>
            <div class="card-body p-0">
            <!-- THE CALENDAR -->
            <div class="response alert alert-success mt-2" style="display: none;"></div>
                <div id="calendarcontainer" style="width: 100%;">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="dialog">
        <div id="dialog-body">
            
        </div>

    </div>
@stop
@push('js')
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
@include('appointmentadmin.js.jsfunctions')
{{-- Fin carga --}}
@endpush