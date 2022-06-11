<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-50" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">   
                <h4 class="modal-title" id="modalHeadingStatus">Editar Empresa</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>      
            </div>    
            <div class="modal-body">    
                <form id="statusForm" name="statusForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="leadstatus_id" id="leadstatus_id">
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <label for="name" class="mb-n1">Nombre <label class="text-red">(*)</label></label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Ingrese el nombre" value="" maxlength="250" required="">
                            <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                        </div>
                        <div class="col-sm-4">
                            <label for="name" class="mb-n1">Descripción<label>&nbsp;</label></label>
                            <input type="text" class="form-control form-control-sm" id="description" name="description" placeholder="Ingrese una descripción" value="" maxlength="250">
                        </div>
                        <div class="col-sm-4">
                            <label for="name" class="mb-n1">Color<label>&nbsp;</label></label>
                            <input type="text" id="color_class" name="color_class" value ='bg-red' class="form-control form-control-sm"  readonly >
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-sm-12">
                            <label for="name" class="mb-n1">Elija un color representativo<label>&nbsp;</label></label>
                            <ul class="fc-color-picker" id="color-chooser">
                                <li><a class="text-red" data-bg-color="bg-red" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-yellow" data-bg-color="bg-yellow" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-blue" data-bg-color="bg-blue" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-lightblue" data-bg-color="bg-lightblue" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-green" data-bg-color="bg-green" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-navy" data-bg-color="bg-navy" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-teal" data-bg-color="bg-teal" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-olive" data-bg-color="bg-olive" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-lime" data-bg-color="bg-lime" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-orange" data-bg-color="bg-orange" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-fuchsia" data-bg-color="bg-fuchsia" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-purple" data-bg-color="bg-purple" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-maroon" data-bg-color="bg-maroon" href="javascript:void(0)"><i class="fas fa-square"></i></a></li>
                            </ul>
                        </div>
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