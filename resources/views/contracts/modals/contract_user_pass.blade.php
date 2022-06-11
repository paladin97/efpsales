{{-- Modal de Usuario de Aula Virtual --}}
<div class="modal modal-wide fade" id="editUserClassRoomModal" data-backdrop="static"  aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-25 width-mobile" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="managementNotesHeading">Agregar Usuario en Aula Virtual</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times  text-white"></i></span>
                </button>        
            </div>    
            <div class="modal-body">   
                <form id="editUserClassRoom" name="editUserClassRoom" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="user_class_case_id" id="user_class_case_id">
                    <div class="row d-flex justify-content-center" style="margin:auto;">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="name" class="col-sm-12 control-label">Usuario de Aula Virtual</label>
                                    <input id="user_classroom" name ="user_classroom" class="form-control form-control-sm" style="height:auto;" rows="3" maxlength="250" placeholder="Inserte nombre de usuario">
                            </div>
                        </div>
                    </div>        
                </form>
            </div>
            <div class="modal-footer">
                <div align="right">
                    <button type="submit" href="javascript:void(0)" class="btn bg-lightblue" id="savedBtnUclass" value="upload-sepa">Guardar Cambios</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
