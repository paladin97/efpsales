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

