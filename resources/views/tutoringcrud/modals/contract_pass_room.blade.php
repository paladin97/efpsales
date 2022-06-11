{{-- Modal de Contraseña de Aula Virtual --}}
<div class="modal modal-wide fade" id="editPassClassRoomModal" data-backdrop="static"  aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-25" role="document">   
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="managementPassHeading">Agregar Contraseña en Aula Virtual</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times  text-white"></i></span>
                </button>        
            </div>    
            <div class="modal-body">   
                <form id="editPassClassRoom" name="editPassClassRoom" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="pass_class_case_id" id="pass_class_case_id">
                    <div class="row d-flex justify-content-center" style="margin:auto;">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="name" class="col-sm-12 control-label">Contraseña de Aula Virtual</label>
                                    <input id="pass_classroom" name ="pass_classroom" class="form-control form-control-sm" style="height:auto;" rows="3" maxlength="250" placeholder="Inserte contraseña de usuario">
                            </div>
                        </div>
                    </div>        
                </form>
            </div>
            <div class="modal-footer">
                <div align="right">
                    <button type="submit" href="javascript:void(0)" class="btn bg-lightblue" id="savedBtnPclass" value="upload-sepa">Guardar Cambios</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>