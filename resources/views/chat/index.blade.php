@extends('adminlte::page')

<style>
    /* .chat {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .chat li {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dotted #B3A9A9;
    }

    .chat li.left .chat-body {
        margin-left: 60px;
    }

    .chat li.right .chat-body {
        margin-right: 60px;
    }


    .chat li .chat-body p {
        margin: 0;
        color: #777777;
    }

    .panel .slidedown .glyphicon,
    .chat .glyphicon {
        margin-right: 5px;
    }

    .panel-body {
        overflow-y: scroll;
        height: 400px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar-thumb {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #555;
    }


    .chats-panel-footer {
        padding: 10px 15px;
        background-color: #f5f5f5;

        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    }



    .panel-head {
        overflow-x: auto;
        overflow-x: hidden;
        height: 400px;
        padding: 0 !important;
    }

    .panel-head li {
        margin: 2px;

        border-radius: 5px;
        border: solid lightgrey;
        padding: 10px;
    }

    .panel-head ul {
        display: contents;
        list-style: none;
    }

    .pull-right {
        float: right !important;
    }

    .pull-left {
        float: left !important;
    }

    li.left {
        background-color: #54c3dc1a;
    }

    li.selected {
        border-color: #3c8dbc !important
    }

    .panel-head ul li:hover {

        border-color: #3c8dbc !important
    }

    .chat-notify {
        float: right;
    } */



    #not-read {
        border-radius: 40px;
        text-align: center;
        background: aquamarine;
        padding: 5px;
        margin: 10px;
    }

    @-webkit-keyframes animar {
        0% {
            -webkit-transform: translate(0px, 0px);
        }

        10% {
            -webkit-transform: translate(20px, 0px);
        }

        20% {
            -webkit-transform: translate(0px, 0px);
        }

        30% {
            -webkit-transform: translate(20px, 0px);
        }

        40% {
            -webkit-transform: translate(0px, 0px);
        }

        50% {
            -webkit-transform: translate(20px, 0px);
        }

        60% {
            -webkit-transform: translate(0px, 0px);
        }

        70% {
            -webkit-transform: translate(20px, 0px);
        }

        80% {
            -webkit-transform: translate(0px, 0px);
        }

        90% {
            -webkit-transform: translate(20px, 0px);
        }

        100% {
            -webkit-transform: translate(0px, 0px);
        }
    }

    .temblor {
        -webkit-animation: animar .5s 2;
    }

</style>

<link href="{{ asset('css/chat.css') }}" rel="stylesheet">

@section('content')


    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" id="current_user" value="{{ Auth::user()->id }}" />
    <input type="hidden" id="pusher_app_key" value="{{ env('PUSHER_APP_KEY') }}" />
    <input type="hidden" id="pusher_cluster" value="{{ env('PUSHER_APP_CLUSTER') }}" />

    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card card-body elevation-3">
                <h4><i class="fad fa-chart-line text-bold text-lightblue text-lg"></i>Chast </h4>
                <div class="container">
                    <div class="row">
                        @if (Auth::user()->hasRole('superadmin'))
                            <div class="col-md-8">
                            @else
                                <div class="col-md-12">
                        @endif

                        <div class="panel panel-primary">
                            <div class="">
                                <div id="panelChats" class="panel-body">
                                    <ul class="chat">
                                        @php
                                            $x = true;
                                        @endphp
                                        @isset($menssages)

                                            @foreach ($menssages as $menssage)
                                                @if ($x == true && $menssage->user_send_id != Auth::user()->id && $menssage->status_id == '1')
                                                    <li>
                                                        <div id="not-read">
                                                            <h6> mensajes no leidos </h6>
                                                        </div>
                                                    </li>
                                                    {{ $x = false }}
                                                @endif

                                                @if (Auth::user()->id == $menssage->user_send_id)
                                                    <li class="left clearfix">
                                                        <span class="chat-img pull-left">
                                                            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt=""
                                                                class="img-circle">


                                                            <div class="chat-body clearfix">
                                                                <div class="header">
                                                                    <strong
                                                                        class="primary-font">{{ Auth::user()->name }}</strong>
                                                                    <small class="pull-right text">
                                                                        <span class="glyphicon glyphicon-time"></span></small>
                                                                </div>
                                                                <p>
                                                                    {{ $menssage->contens }}
                                                                </p>
                                                            </div>
                                                        </span>
                                                    </li>
                                                @else
                                                    <li class="right clearfix">
                                                        <span class="chat-img pull-right">
                                                            <img src="http://placehold.it/50/FA6F57/fff&text=ME" alt=""
                                                                class="img-circle">

                                                            <div class="chat-body clearfix">
                                                                <div class="header">
                                                                    @if (Auth::user()->hasRole('superadmin'))
                                                                        <strong
                                                                            class="primary-font">{{ $list_chats[0]->name }}</strong>
                                                                    @else
                                                                        <strong class="primary-font">Administrador</strong>
                                                                    @endif
                                                                    <small class=" text"><span
                                                                            class="glyphicon glyphicon-time"></span></small>

                                                                </div>
                                                                <p>
                                                                    {{ $menssage->contens }}
                                                                </p>
                                                            </div>
                                                        </span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endisset



                                    </ul>
                                </div>
                                <div class="panel-footer chats-panel-footer">
                                    <div class="input-group">
                                        <input id="btn-input" type="text" class="form-control input-sm"
                                            placeholder="Escriba su mensaje..." />
                                        <span class="input-group-btn">

                                            <button
                                                onclick="sendMenssages({{ Auth::user()->id }},'{{ Auth::user()->name }}')"
                                                class="btn bg-lightblue" id="btn-chat">
                                                Enviar</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->hasRole('superadmin'))
                        <div class="col-md-4">
                            <div class="panel-head chats-panel-footer">
                                <ul id="contact-comercial">
                                    @php
                                        $x = true;
                                    @endphp
                                    @foreach ($list_chats as $chats)
                                        @if ($x)
                                            <li id='chast-li-{{ $chats->id }}' class=" h selected"
                                                onclick="getChat({{ Auth::user()->id }},{{ $chats->id }},'{{ Auth::user()->name }}','{{ $chats->name }}')">
                                                {{ $chats->name }}
                                                <span value={{ $chats->count_notify }}
                                                    class="chat-notify badge badge-pill bg-lightblue">{{ $chats->count_notify }}</span>

                                            </li>
                                            {{ $x = false }}
                                        @else
                                            <li class="h " id='chast-li-{{ $chats->id }}'
                                                onclick="getChat({{ Auth::user()->id }},{{ $chats->id }},'{{ Auth::user()->name }}','{{ $chats->name }}')">
                                                {{ $chats->name }}
                                                <span value={{ $chats->count_notify }}
                                                    class=" chat-notify badge badge-pill bg-lightblue">{{ $chats->count_notify }}</span>

                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}


    <div class="container-chat">
        <div class="row no-gutters">

            @if (Auth::user()->hasRole('superadmin'))
                <div id="contact" class="col-md-4 border-right">
                    <div class="settings-tray">
                        <img class="profile-image"
                            src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/filip.jpg" alt="Profile img">
                        <div id="name-user" class="text">
                            <h4>{{ Auth::user()->name }}</h4>
                        </div>
                        {{-- <span class="settings-tray--right">
                        <i class="material-icons">cached</i>
                        <i class="material-icons">message</i>
                        <i class="material-icons">menu</i>
                    </span> --}}
                    </div>

                        <div class="search-box">
                            <div class="input-wrapper">
                                <i class="fas fa-search"> buscar </i>
                                <input placeholder="Buscar aqui" type="text">
                            </div>
                        </div>

                    <ul id="contact-comercial">
                        @php
                            $x = true;
                        @endphp
                        @foreach ($list_chats as $chats)
                            @if ($x)
                                <li id='chast-li-{{ $chats->id }}' class=" h selected"
                                    onclick="getChat({{ Auth::user()->id }},{{ $chats->id }},'{{ Auth::user()->name }}','{{ $chats->name }}')">
                                    <div class="selected friend-drawer friend-drawer--onhover">
                                        <img class="profile-image"
                                            src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg"
                                            alt="">
                                        <div class="text">
                                            <h6>{{ $chats->name }}</h6>
                                            @if ($chats->updated_at != null)
                                                <p class="text-muted">{{ $chats->updated_at }}</p>
                                            @else
                                                <p class="text-muted">no se ha conectado</p>
                                            @endif
                                        </div>
                                        <span class="span-notify time text-muted small">{{ $chats->count_notify }}</span>
                                    </div>
                                    <hr>
                                </li>

                                {{ $x = false }}
                            @else
                                <li id='chast-li-{{ $chats->id }}' class="h "
                                    onclick="getChat({{ Auth::user()->id }},{{ $chats->id }},'{{ Auth::user()->name }}','{{ $chats->name }}')">
                                    <div class="friend-drawer friend-drawer--onhover">
                                        <img class="profile-image"
                                            src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg"
                                            alt="">
                                        <div class="text">
                                            <h6>{{ $chats->name }}</h6>
                                            @if ($chats->updated_at != null)
                                                <p class="text-muted">{{ $chats->updated_at }}</p>
                                            @else
                                                <p class="text-muted">no se ha conectado</p>
                                            @endif
                                        </div>
                                        <span class="span-notify time text-muted small">{{ $chats->count_notify }}</span>
                                    </div>
                                    <hr>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif


            @if (Auth::user()->hasRole('superadmin'))
                <div id="conversation" class="col-md-8">
                @else
                    <div id="conversation" class="col-md-12">
            @endif

            <div class="settings-tray">
                <div class="friend-drawer no-gutters friend-drawer--grey">
                    <img class="profile-image"
                        src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg" alt="">
                    <div class="text">
                        @if (Auth::user()->hasRole('superadmin'))
                            <h4 id="name-user-receive">{{ $list_chats[0]->name }}</h4>
                        @else
                            <h4 id="name-user-receive">Administrador</h4>
                        @endif

                        {{-- <p class="text-muted">Layin' down the law since like before Christ...</p> --}}
                    </div>
                    {{-- <span class="settings-tray--right">
                            <i class="material-icons">cached</i>
                            <i class="material-icons">message</i>
                            <i class="material-icons">menu</i>
                        </span> --}}
                </div>
            </div>

            <div class="chat-panel">

                <ul class="chat">
                    @php
                        $x = true;
                    @endphp
                    @isset($menssages)

                        @foreach ($menssages as $menssage)
                            @if ($x == true && $menssage->user_send_id != Auth::user()->id && $menssage->status_id == '1')
                                <li id="li-not-read">
                                    <div id="not-read">
                                        <h6> mensajes no leidos </h6>
                                    </div>
                                </li>
                                {{ $x = false }}
                            @endif

                            @if (Auth::user()->id != $menssage->user_send_id)
                                <li>
                                    <div class="row no-gutters">
                                        <div class="col-md-3">
                                            <div class="chat-bubble chat-bubble--left">
                                                {{ $menssage->contens }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @else
                                <li>
                                    <div class="row no-gutters">
                                        <div class="col-md-3 offset-md-9">
                                            <div class="chat-bubble chat-bubble--right">
                                                {{ $menssage->contens }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    @endisset
                </ul>



                <div class="row">
                    <div class="col-12">
                        <div class="chat-box-tray">
                            {{-- <i class="material-icons">sentiment_very_satisfied</i> --}}
                            <input id="btn-input" type="text" placeholder="Escribir mensaje aqui...">
                            {{-- <i class="material-icons">send</i> --}}
                            <button onclick="sendMenssages({{ Auth::user()->id }},'{{ Auth::user()->name }}')"
                                class="btn bg-lightblue" id="btn-chat">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



@stop

@push('js')
    <script src="{{ asset('js/components/general-layout.js') }}"></script>
    <script src="{{ asset('js/components/jquery.min.js') }}"></script>
    <script src="{{ asset('js/components/jspusher.js') }}"></script>
    @include('chat.js.jsfunctionschats')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endpush
