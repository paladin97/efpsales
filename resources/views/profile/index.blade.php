@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Perfil</li>
        </ol>
    </div>
    </div>
</div>
@stop

@section('content')
<div class="row" style="margin:auto;">
  <div class="col-sm-12">
    <div class="card" style="margin:auto;">
        <div class="card-header text-center">
          <h3><i class="fad fa-user text-lightblue"></i> Perfil</h3>
        </div>
        <div class="card-body">
          <form id="profileForm" name="profileForm" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
            <input type="hidden" name="user_role_id" id="user_role_id" value="{{$user_role->id}}">
            <input type="hidden" name="person_id" id="person_id" value="{{$user->person_id}}">
            <div class="row">
              <div class="col-md-3" align="center">
                <img class="profile-user-img img-responsive img-circle bg-blue bg-profile" id="user_avatar" style="width: 150px;" src="{{asset('storage/uploads/users/'.$user->avatar)}}" alt="User profile picture">
                <h2 class="profile-username text-center" id="name" name="name"  style="font-size: 1.3em;">{{$user->name}} {{$user->last_name}}</h2>
                <input type="file"  class="text-center center-block file-upload" style="margin-top: 10px;" id="avatar" name="avatar" placeholder="Sube una foto" value="" maxlength="250">
              </div>
              <div class="col-md-9">
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" style="margin-left: -22px;margin-top: -14px;" id="myTabPlayer-list" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="configuracion-tab" data-toggle="tab" href="#configuracion" role="tab" aria-controls="infopersonal" style="border-top-color:#117a8b; border-top: 5px solid #117a8b;" aria-selected="true">Configuración</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <div class="tab-content" id="myTabPlayerContent" style="background: #ffffffd1;">
                      <div class="tab-pane fade show active" id="configuracion" role="tabpanel" aria-labelledby="configuracion-tab">
                        <div class="row mt-n2">
                            <div class="col-sm-4">
                                <div class="form-group">
                                <label for="name" class="mb-n1">Nombre</label>
                                <input type="text" class="form-control form-control-sm" id="user_name" name="user_name" placeholder="Nombre" value="{{$user->name}}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                <label for="name" class="mb-n1">Apellidos</label>
                                <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" placeholder="Apellidos" value="{{$user->last_name}}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                <label for="name" class="mb-n1">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-sm" id="mail" name="mail" placeholder="Email" value="{{$user->email}}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-n2">
                          <div class="col-sm-4">
                              <div class="form-group">
                                <label for="name" class="mb-n1">Enlace GoLinks®</label>
                                <input type="email" class="form-control form-control-sm" id="profile_url" name="profile_url" placeholder="Golinks" value="{{$user->profile_url}}">
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="name" class="mb-n1">Password</label>
                              <input type="password" class="form-control form-control-sm" id="password" name="password">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>                  
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer text-muted">
          <button type="submit" id="btnSave" name="btnSave" class="btn btn-primary bg-lightblue"><span>Actualizar</span></button>
        </div>
    </div>
  </div>
</div>
      
    
@stop
@push('js')
<script src="{{ asset('js/components/datatable.js')}}"></script>
<script src="{{ asset('js/components/general-layout.js')}}"></script>

@include('profile.js.jsfuntions')


@endpush