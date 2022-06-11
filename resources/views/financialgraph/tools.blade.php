<div class="col-sm-6">
    <div class="card card-body elevation-3">
    <h4><i class="fad fa-tools text-bold text-lightblue text-lg"></i> Herramientas ƎFP Sales</h4>
    <div class="row">
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="https://grupoefp.com/" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                <center><img src="{{asset('storage/uploads/website.png')}}" width="75px"alt=""></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>grupoefp.com
                <div class="pull-right badge" id="WrForms"></div>
                </h4>
                </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 p-2"> 
            <div class="wrimagecard wrimagecard-topimage">
                <a href="https://mail.ionos.es" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#7bcadb1a">
                    <center><i class="fad fa-envelope-open-text text-blue"> </i></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Acceso a E-Mail
                <div class="pull-right badge" id="WrInformation"></div></h4>
                </div> 
            </a>
            </div>
        </div>
        @if (Auth::user()->hasRole('teacher'))
            <div class="col-md-3 col-sm-4 p-2">
                <div class="wrimagecard wrimagecard-topimage">
                    <a href="https://elearning.campusyformacion.com/login/index.php" target="_blank">
                    <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                        <center><i class="fad fa-chalkboard-teacher text-blue"></i></center>
                    </div>
                    <div class="wrimagecard-topimage_title" align="center">
                    <h4>Aula Virtual
                    <div class="pull-right badge" id="WrInformation"></div></h4>
                    </div>
                </a>
                </div> 
            </div>
            <div class="col-md-3 col-sm-4 p-2">
                <div class="wrimagecard wrimagecard-topimage">
                    <a href="https://cursos.campusyformacion.com/login/index.php" target="_blank">
                    <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                        <center><i class="fad fa-chalkboard-teacher text-red fa-flip-horizontal"></i></center>
                    </div>
                    <div class="wrimagecard-topimage_title" align="center">
                    <h4>Aula Virtual
                    <div class="pull-right badge"></div></h4>
                    </div>
                </a>
                </div>
            </div>
        @else
            <div class="col-md-3 col-sm-4 p-2">
                <div class="wrimagecard wrimagecard-topimage">
                    <a href="javascript:void(0)" data-toggle="modal" id="viewPrice">
                    <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                    <center><i class="fad fa-tags text-warning "></i></center>
                    </div>
                    <div class="wrimagecard-topimage_title" align="center">
                    <h4>Precios y Dossieres
                    <div class="pull-right badge"></div></h4>
                    </div>
                </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 p-2">
                <div class="wrimagecard wrimagecard-topimage">
                    <a href="https://pay.sequra.es/session/new" target="_blank">
                    <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                        <center><img src="{{asset('storage/uploads/sequra.png')}}" width="75px"alt=""></center>
                    </div>
                    <div class="wrimagecard-topimage_title" align="center">
                    <h4>Financiera
                    <div class="pull-right badge" id="WrInformation"></div></h4>
                    </div>
                </a>
                </div> 
            </div>
        @endif
        {{-- <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="javascript:void(0)" data-toggle="modal" id="viewDossiers">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                <center><img src="{{asset('storage/uploads/dossieres.png')}}" width="75px"alt=""></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Dossieres
                <div class="pull-right badge" id="WrControls"></div></h4>
                </div>
            </a>
            </div>
        </div> --}}
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="{{Auth::user()->profile_url()}}" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                    <center><img src="https://golinks.online/uploads/avatars/2aa5d71184f958c200bd2e45e3a25f8e.png" width="75px"alt=""></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Acceso a mi GoLinks®
                <div class="pull-right badge" id="WrInformation"></div></h4>
                </div>
            </a>
            </div> 
        </div>
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="https://calendly.com/login?lang=es" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                <center><img src="{{asset('storage/uploads/Calendly.png')}}" width="75px"alt=""></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Acceso a Calendly®
                <div class="pull-right badge" id="WrThemesIcons"></div></h4>
                </div>
            </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 p-2">
                <div class="wrimagecard wrimagecard-topimage">
                    <a href="https://www.facebook.com/cursosgrupoefp" target="_blank">
                    <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                    <center><i class="fab fa-facebook-f text-blue"></i></center>
                    </div>
                    <div class="wrimagecard-topimage_title pt-4 pl-0 pr-0" align="center">
                    <h4>Facebook
                    <div class="pull-right badge" id="WrThemesIcons"></div></h4>
                    </div>
                </a>
                </div>
        </div>
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="https://www.instagram.com/grupoefp/" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                <center><i class="fab fa-instagram text-grey"></i></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Instagram
                <div class="pull-right badge" id="WrThemesIcons"></div></h4>
                </div>
            </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="javascript:void(0)" data-toggle="modal" id="viewBoletin">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                <center><img src="{{asset('storage/uploads/gobierno.png')}}" width="75px"alt=""></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Boletín Informativo
                <div class="pull-right badge" id="WrControls"></div></h4>
                </div>
            </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="{{asset('storage/uploads/GUÍA_DE_INICIACIÓN__PLATAFORMA-1.pdf')}}" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                <center><i class="fad fa-file-pdf text-red"></i></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Guía Plataforma
                <div class="pull-right badge" id="WrThemesIcons"></div></h4>
                </div>
            </a>
            </div>
        </div>
        @if (!(Auth::user()->hasRole('teacher')))
            <div class="col-md-3 col-sm-4 p-2">
                <div class="wrimagecard wrimagecard-topimage">
                    <a href="javascript:void(0)" data-toggle="modal" id="viewCuenta">
                    <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                    <center><img src="{{asset('storage/uploads/santander.png')}}" width="75px"alt=""></center>
                    </div>
                    <div class="wrimagecard-topimage_title" align="center">
                    <h4>Cuenta Bancaria
                    <div class="pull-right badge" id="WrControls"></div></h4>
                    </div>
                </a>
                </div>
            </div>
        @endif
        @if (Auth::user()->hasRole('superadmin'))
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="{{url('appointmentsadmin')}}" target="_self">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                <center><i class="fad fa-calendar-alt text-green"></i></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Mi Calendario
                <div class="pull-right badge" id="WrThemesIcons"></div></h4>
                </div>
            </a>
            </div>
        </div>
        @elseif (Auth::user()->hasRole('teacher'))
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="https://zoom.us/signin" target="_blank">
                    <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                        <center><img src="{{asset('storage/uploads/Zoom-Logo.png')}}" width="130px"alt=""></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Programar Tutorías
                <div class="pull-right badge" id="WrThemesIcons"></div></h4>
                </div>
            </a>
            </div>
        </div>
        @endif
    </div>
</div>
</div>