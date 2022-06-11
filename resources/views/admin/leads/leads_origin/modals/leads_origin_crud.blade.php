<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-25" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette"> 
                <h4 class="modal-title" id="modalHeadingOrigin">Editar Empresa</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>      
            </div>    
            <div class="modal-body">    
                <form id="originForm" name="originForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="leadorigin_id" id="leadorigin_id">
                    <div class="row mt-n2">
                        <label for="name" class="mb-n1">Nombre <label class="text-red">(*)</label></label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Ingrese el nombre" value="" maxlength="250" required="">
                        <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                    </div>
                </form>
                {{-- @include('partials.admin.information') --}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-lightblue" id="saveBtn" value="create-product">Guardar Cambios</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <p>
                        <div class="alert alert-dismissible" style="display: none; background-color: green;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <div id="alert_message_lead" align="center" style="color:white;"></div>
                        </div>
            </div>
        </div>
    </div>
</div>