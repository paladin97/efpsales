@extends('adminlte::master')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
    <link rel="stylesheet" href="{{ asset('css/logincss.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animsition/4.0.2/css/animsition.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"/>
@stop

@section('classes_body')@stop

@section('body')
    
<div class="container-login100">
    <div class="wrap-login100">
        <form class="login100-form validate-form" action="{{ $login_url }}" method="post">
            {{-- Logo --}}
            <div class="{{ $auth_type ?? 'login' }}-logo">
                <a href="{{ $dashboard_url }}">
                    <img src="{{ asset(config('adminlte.logo_img')) }}" height="150">
                    {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                </a>
            </div>
            <span class="login100-form-title p-b-43">
                @hasSection('auth_header')
                    {{ config('adminlte.classes_auth_header', '') }}
                    @yield('auth_header')
                @endif            
            </span>
            @yield('auth_body')
            
            {{-- <div class="text-center p-t-46 p-b-20">
            <span class="txt2">
            or sign up using
            </span>
            </div>
            <div class="login100-form-social flex-c-m">
            <a href="#" class="login100-form-social-item flex-c-m bg1 m-r-5">
            <i class="fa fa-facebook-f" aria-hidden="true"></i>
            </a>
            <a href="#" class="login100-form-social-item flex-c-m bg2 m-r-5">
            <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            </div> --}}
        </form>
            <div class="login100-more"></div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script>
        (function($){"use strict";$('.input100').each(function(){$(this).on('blur',function(){if($(this).val().trim()!=""){$(this).addClass('has-val');}
    else{$(this).removeClass('has-val');}})})
    var input=$('.validate-input .input100');$('.validate-form').on('submit',function(){var check=true;for(var i=0;i<input.length;i++){if(validate(input[i])==false){showValidate(input[i]);check=false;}}
    return check;});$('.validate-form .input100').each(function(){$(this).focus(function(){hideValidate(this);});});function validate(input){if($(input).attr('type')=='email'||$(input).attr('name')=='email'){if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/)==null){return false;}}
    else{if($(input).val().trim()==''){return false;}}}
    function showValidate(input){var thisAlert=$(input).parent();$(thisAlert).addClass('alert-validate');}
    function hideValidate(input){var thisAlert=$(input).parent();$(thisAlert).removeClass('alert-validate');}})(jQuery);
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animsition/4.0.2/js/animsition.js"></script>
    @stack('js')
    @yield('js')
@stop
