@extends('adminlte::page')

@section('title', $pageTitle)
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Editorial</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    {{-- Carga los modal --}}
    @include('admin.order.modals.order_crud')
    @include('admin.order.modals.order_notes_crud')
    @include('admin.order.modals.order_note_history')
    {{-- Fin carga --}}
    <div class="card card-lightblue card-outline">


        <div class="card-header text-center">
            <h4><i class="fad fa-clipboard-list-check text-lightblue"></i>Pedidos de materiales</h4>
        </div>
        <div class="card-body">
            <div class="row" style="margin: auto;">
                <div class="col-sm-12">
                    <div class="table-responsive ">
                        <table
                            class="small-table table-sm table-bordered table-striped row-border cell-border order-column compact table data-table"
                            id="status" cellspacing="0" width="100%">
                            <thead class="text-center">
                                <tr>
                                    <th><input name="select_all" value="1" type="checkbox"></th>
                                    <th>Código del Pedido</th>
                                    <th>Contrato</th>
                                    <th>Imprenta</th>
                                    <th>Agencia de envío</th>
                                    <th>Pedido</th>
                                    <th>Estado</th>
                                    <th>Notas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($orders as $order)
                                    <tr>

                                        <th><input name="select{{ $order->id }}" id="order_id{{ $order->id }}"
                                                value="{{ $order->id }}" type="checkbox">
                                        </th>
                                        <td>{{ $order->code }}</td>
                                        <td>{{ $order->enrollment_number }}</td>
                                        <td>
                                            @if ($order->printing_provider != null)
                                                <div class="card contact card-lightblue" style="min-width: 265px">
                                                    <div class="card-body">
                                                        <h4 class="card-title">{{ $order->printname }}</h4>
                                                        <address class="card-text">{{ $order->printaddress }}
                                                        </address>
                                                        </p>
                                                        <a href="tel:{{ $order->printphone }}"
                                                            class="card-link fa fa-phone"></a>
                                                        <a href="mailto:{{ $order->printemail }}"
                                                            class="card-link fa fa-envelope"></a>
                                                    </div>
                                                </div>
                                            @else
                                                {!! 'Sin asignar' !!}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->shipping_agency != null)
                                                <div class="card contact card-lightblue" style="min-width: 265px">
                                                    <div class="card-body contact">
                                                        <p class="card-title">{{ $order->shipname }}</p>
                                                        <address class="card-text">{{ $order->shipaddress }}
                                                        </address>

                                                        <a style="display:inline" href="tel:{{ $order->shipphone }}"
                                                            class="card-link fa fa-phone"></a>
                                                        <a style="display:inline" href="mailto:{{ $order->shipemail }}"
                                                            class="card-link fa fa-envelope"></a>
                                                    </div>
                                                </div>
                                            @else
                                                {!! 'Sin asignar' !!}
                                            @endif
                                        </td>
                                        <td>{{ $order->order }}</td>
                                        <td>
                                            <h5><span
                                                    class="badge d-block text-xs bg-{{ $order->color }}">{{ $order->status }}</span>
                                            </h5>
                                        </td>
                                        <td>
                                            <div class="scrollable">

                                                @php
                                                    $notes = $order->orderNotesLimited()['notes'];
                                                    $count = $order->orderNotesLimited()['count'];
                                                @endphp
                                                @if ($count >= 1)
                                                    @foreach ($notes as $note)
                                                        <div class="card bg-light" style="min-width: 280px">
                                                            <div class="card-body">
                                                                <p class="card-text">{{ $note->observation }}</p>
                                                                <a href="#" value="{{ $note->id }}"
                                                                    class="btn btn-primary btn-xs editNote">Editar</a>
                                                                <form style="display:inline" method="POST"
                                                                    action="{{ route('ordernote.destroy', $note->id) }}">
                                                                    {!! method_field('DELETE') !!}
                                                                    {!! csrf_field() !!}
                                                                    <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta nota?')" class="btn btn-danger btn-xs"
                                                                        title="Eliminar">Eliminar</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @if ($count > 3)
                                                        <a href="javascript:void(0)" value="{{ $order->id }}"
                                                            class="btn btn-secondary moreNotes" style="padding: 0px 2px;">
                                                            <p style="display: inline; margin-right: 2px;">+</p>
                                                            <span class="badge badge-light"
                                                                style="padding: 2px;">{{ $count - 3 }}</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    {!! 'No hay notas que mostrar' !!}
                                                @endif
                                            </div>

                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" data-toggle="dropdown" class="text-secondary "
                                                    aria-expanded="false">
                                                    <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i></a>

                                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                                    style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a href="#" data-toggle="dropdown" class="text-secondary"></a>
                                                    <a class="dropdown-item editOrder" value="{{ $order->id }}"
                                                        href="javascript:void(0)" title="Editar"> <i
                                                            class="fad fa-pencil-alt text-lightblue"></i>Editar</a>
                                                    <a class="dropdown-item addNote" value="{{ $order->id }}"
                                                        href="javascript:void(0)" title="Agregar Nota"> <i
                                                            class="fad fa-plus-square text-lightblue"></i>Agregar Nota</a>
                                                    <form method="POST"
                                                        action="{{ route('order.destroy', $order->id) }}">
                                                        {!! method_field('DELETE') !!}
                                                        {!! csrf_field() !!}
                                                        <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta orden?')" class="dropdown-item deleteOrder"
                                                            title="Eliminar"> <i
                                                                class="fad fa-trash text-lightblue"></i>Eliminar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        div.scrollable {
            width: 100%;
            max-height: 250px;
            margin: 0;
            padding: 0;
            overflow: auto;
        }

        div.contact {
            max-width: 270px;
            max-height: 120px;

        }

        div.card>div {

            padding: 4px;
            margin: 3px;
        }

        .moreNotes {
            position: relative;
            left: 87%;
            bottom: 8px;
        }

    </style>

@stop
@push('js')
    <script src="{{ asset('js/components/datatable.js') }}"></script>
    <script src="{{ asset('js/components/general-layout.js') }}"></script>

    {{-- Carga funciones js del módulo --}}
    @include('admin.order.js.jsfunctions')
    {{-- Fin carga --}}
@endpush
