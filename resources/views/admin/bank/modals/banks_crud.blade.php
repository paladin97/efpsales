<div class="modal fade bd-example-modal-xl " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">  
        <div class="modal-content">
            <div class="modal-header bg-lightblue color-palette"> 
                <h4 class="modal-title" id="modalHeadingBank">Crear Banco</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>  
            </div>    
            <div class="modal-body">    
                <form id="bankForm" name="bankForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="bank_id" id="bank_id">
                    <div class="row mt-n2">
                        <label for="name" class="mb-n1">Nombre (*)</label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Ingrese el nombre" value="" maxlength="250" required="">
                        <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                    </div>
                </form>
                {{-- @include('partials.admin.information') --}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-lightblue" id="saveBtn" value="create-club"><span>Guardar Cambios</span></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span>Cancelar</span></button>
            </div>
        </div>
    </div>
</div>