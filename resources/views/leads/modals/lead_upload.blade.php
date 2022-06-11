<div class="modal modal-wide fade" id="leadUploadModal" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">     
        <div class="modal-content">     
            <div class="modal-header" align="center">  
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="glyphicon glyphicon-remove-circle fa-2x"></span>
                </button>     
                <h3 align="center" class="modal-title" id="leadUploadHeading"><b>Subir Clientes en EXCEL </b>  </h3>
            </div>    
            <div class="modal-body">    
                <div class="row" style="margin:auto;">
                    <div class="col-sm-4">
                        <a href="{{ asset('storage/documents/IonaSalesPlantilla.xls') }}" target="_blank"><button style="background-color:green; color:white;" class="btn btn-sm"><i class="fa fa-download fa-lg"></i> </i>&nbsp; Bajar Plantilla</button></a>
                    </div>
                </div>
                <form id="leadImportForm" name="leadImportForm" class="form-horizontal" enctype="multipart/form-data">
                    <div class="row" style="margin:auto;">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="col-sm-12 control-label">Archivo</label>
                                <div class="col-sm-12">
                                    <input type="file"  class="form-control" id="fileupload" name="fileupload" placeholder="" value="" maxlength="250">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="col-sm-12 control-label">&nbsp;</label>
                                <div class="col-sm-12">
                                    <button type="button" href="javascript:void(0)" class="btn btn-primary btn-sm" id="sendBtnUpload" value="lead-notes"><i class="fa fa-cloud-upload"></i>&nbsp; Subir</button>   
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="alert alert-dismissible successUploadDiv" style="display: none;background-color: green;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <div id="alertMessageUploadLeads" align="center" style="color:white;"></div>
                            </div>
                            <div class="alert alert-dismissible alert-warning warningUploadDiv" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <div id="alertMessageUploadLeadsWarning" align="center" style="color:white;"></div>
                            </div>
                            <div class="alert alert-dismissible bg-red errorUploadDiv" style="display: none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <div id="alertMessageUploadLeadsError" align="center" style="color:white;"></div>
                            </div>
                        </div>
                    </div>        
                </form>
            </div>
            <div class="modal-footer">
                
                <p>
                {{-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> --}}
                
            </div>
        </div>
    </div>
</div>