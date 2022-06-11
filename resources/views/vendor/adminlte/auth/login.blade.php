@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_header', __('adminlte::adminlte.login_message'))

@section('auth_body')
        {{ csrf_field() }}
        {{-- Email field --}}
        <div class="input-group">
            <div class="wrap-input100 validate-input" data-validate="email valido requerido: ex@abc.xyz">
                <input class="input100 form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                    value="{{ old('email') }}" type="email" name="email" autofocus>
                <span class="focus-input100"></span>
                <span class="label-input100"> {{ __('adminlte::adminlte.email') }} </span>
            </div>
            @if($errors->has('email'))
                <div class="text-red">
                    <strong>! {{ $errors->first('email') }}</strong>
                </div>
            @endif
        </div>
        
        {{-- Password field --}}
        <div class="input-group">
            <div class="wrap-input100 validate-input" data-validate="La contraseña es requerida">
                <input class="input100 form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                    type="password" name="password">
                <span class="focus-input100"></span>
                <span class="label-input100">{{ __('adminlte::adminlte.password') }}</span>
            </div>
            @if($errors->has('password'))
                <div class="text-red">
                    <strong>! {{ $errors->first('password') }}</strong>
                </div>
            @endif
        </div>

        {{-- Login field --}}
        <div class="flex-sb-m w-full p-t-3 p-b-32">
            <div class="contact100-form-checkbox">
                <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember" id="remember">
                <label class="label-checkbox100" for="ckb1">
                    {{ __('adminlte::adminlte.remember_me') }}
                </label>
            </div>
            {{-- <div>
                <a href="#" class="txt1">
                Forgot Password?
                </a>
            </div> --}}
        </div>
        
        <div class="container-login100-form-btn">
            <button class="login100-form-btn {{ config('adminlte.classes_auth_btn', '') }}">
                {{ __('adminlte::adminlte.sign_in') }}
            </button>
        </div>
        <blockquote>
            <p>Construimos el futuro de las nuevas generaciones </p>
        </blockquote>

@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($password_reset_url)
        <p class="my-0">
            <a href="{{ $password_reset_url }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    @if($register_url)
        <p class="my-0">
            <a href="{{ $register_url }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif
@stop
