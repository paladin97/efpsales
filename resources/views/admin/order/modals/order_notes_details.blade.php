@extends('adminlte::page')

@section('title', $pageTitle)
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Editorial</a></li>
                    <li class="breadcrumb-item active">Notas</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    @include('admin.order.modals.order_notes_crud')

    <div class="row justify-content-md-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header text-center">
                    <h2><i class="fad fa-clipboard-list-check text-lightblue"></i>Notas del pedido {{ $code }}</h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover data-table" id="notes">
                        <thead>
                            <tr>
                                <th><input name="select_all" value="1" type="checkbox"></th>
                                <th>Notas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($notes as $note)
                                <tr>

                                    <th><input name="select{{ $note->id }}" id="order_id{{ $note->id }}"
                                            value="{{ $note->id }}" type="checkbox"></th>
                                    <td>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <p class="card-text">{{ $note->observation }}</p>
                                            </div>
                                    </td>
                                    <td>
                                        <div class="dropdown" align="center">
                                            <a href="#" data-toggle="dropdown" class="text-secondary "
                                                aria-expanded="false">
                                                <i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i></a>

                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                                style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a href="#" data-toggle="dropdown" class="text-secondary"></a>
                                                <a class="dropdown-item editNote" value="{{ $note->id }}"
                                                    href="javascript:void(0)" title="Editar"> <i
                                                        class="fad fa-pencil-alt text-lightblue"></i>Editar</a>
                                                <a class="dropdown-item addNote" value="{{ $note->id }}"
                                                    href="javascript:void(0)" title="Agregar Nota"> <i
                                                        class="fad fa-plus-square text-lightblue"></i>Agregar Nota</a>
                                                <form method="POST" action="{{ route('ordernote.destroy', $note->id) }}">
                                                    {!! method_field('DELETE') !!}
                                                    {!! csrf_field() !!}
                                                    <button type="submit" class="dropdown-item deleteNote" title="Eliminar">
                                                        <i class="fad fa-trash text-lightblue"></i>Eliminar</button>
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


@stop
@push('js')
    <script src="{{ asset('js/components/datatable.js') }}"></script>
    <script src="{{ asset('js/components/general-layout.js') }}"></script>

    {{-- Carga funciones js del módulo --}}
    @include('admin.order.js.jsfunctions')
    {{-- Fin carga --}}
@endpush
