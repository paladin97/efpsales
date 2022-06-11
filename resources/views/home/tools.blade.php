<div class="col-sm-6">
    <div class="card card-body elevation-3">
    <h4><i class="fad fa-tools text-bold text-lightblue text-lg"></i> Herramientas ƎFP Sales (<a class="zoom" href="https://grupoefp.com" target="_blank"><i class="fad fa-browser text-blue fa-1x"></i> Sitio Web</a>)</h4>
    <div class="row">
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage zoom">
                <a href="{{asset('storage/uploads/TARIFAS GRUPOEFP 2021.pdf')}}" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                <center><i class="fad fa-money-check-edit-alt text-green"></i></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Tarifas grupoEFP 2021
                <div class="pull-right badge" id="WrThemesIcons"></div></h4>
                </div>
            </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 p-2"> 
            <div class="wrimagecard wrimagecard-topimage zoom">
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
                <div class="wrimagecard wrimagecard-topimage zoom">
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
                <div class="wrimagecard wrimagecard-topimage zoom">
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
                <div class="wrimagecard wrimagecard-topimage zoom">
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
                <div class="wrimagecard wrimagecard-topimage zoom">
                    <a href="https://pay.sequra.es/session/new" target="_blank">
                    <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                        <center><img src="{{asset('storage/uploads/sequra.png')}}" width="75px"alt=""></center>
                    </div>
                    <div class="wrimagecard-topimage_title" align="center">
                    <h4>Pay Financiera
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
        @if (Auth::user()->hasRole('comercial') || Auth::user()->hasRole('teacher'))
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage zoom">
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
        @else
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage zoom">
                <a href="https://simba.sequra.es/session/new" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                    <center><img src="{{asset('storage/uploads/sequra.png')}}" width="85px"alt=""></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Simba Sequra
                <div class="pull-right badge" id="WrInformation"></div></h4>
                </div>
                </a>
            </div> 
        </div>
        @endif
        @if (Auth::user()->hasRole('comercial'))
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage zoom">
                <a href="{{asset('storage/uploads/Speach_grupoEFP_.pdf')}}" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                    <center><i class="fad fa-comment-alt-lines text-green"></i></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center"> 
                <h4>Speach de Venta
                <div class="pull-right badge" id="WrInformation"></div></h4>
                </div>
            </a>
            </div> 
        </div>
        @else
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage zoom">
                <a href="{{Auth::user()->profile_url()}}" target="_blank">
                <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                    <center><img src="{{asset('storage/uploads/golinks.png')}}" width="75px"alt=""></center>
                </div>
                <div class="wrimagecard-topimage_title" align="center">
                <h4>Acceso a mi GoLinks®
                <div class="pull-right badge" id="WrInformation"></div></h4>
                </div>
            </a>
            </div> 
        </div>
        @endif
        <div class="col-md-3 col-sm-4 p-2">
                <div class="wrimagecard wrimagecard-topimage zoom">
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
            <div class="wrimagecard wrimagecard-topimage zoom">
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
            <div class="wrimagecard wrimagecard-topimage zoom">
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
            <div class="wrimagecard wrimagecard-topimage zoom">
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
                <div class="wrimagecard wrimagecard-topimage zoom">
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
            <div class="wrimagecard wrimagecard-topimage zoom">
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
            <div class="wrimagecard wrimagecard-topimage zoom">
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
        @elseif (Auth::user()->hasRole('comercial'))
        <div class="col-md-3 col-sm-4 p-2">
            <div class="wrimagecard wrimagecard-topimage zoom">
                    <a class="viewTutorial" href="javascript:void(0)" data-toggle="modal" id="viewTutorial">
                    {{-- <a href="{{asset('storage/uploads/FORMACION ASESORES.mp4')}}" target="_blank"> --}}
                        <div class="wrimagecard-topimage_header" style="background-color:#54c3dc1a">
                            <center><i class="fad fa-video text-blue"></i></center>
                        </div>
                        <div class="wrimagecard-topimage_title" align="center">
                            <h4>Formación Comerciales 
                            <div class="pull-right badge" id="WrThemesIcons"></div></h4> 
                        </div>
                    </a>
            </div>
        </div>
        @endif
    </div>
</div>
</div>