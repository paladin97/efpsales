<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modalHeadingLiquidationModel">Editar</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>           
            </div>    
            <div class="modal-body">    
                <form id="liquidationmodelForm" name="liquidationmodelForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="liquidationmodel_id" id="liquidationmodel_id">
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Nombre de Modelo <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="newlqmod_name" name="newlqmod_name" placeholder="Ingrese el nombre" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Empresa <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="newlqmod_company" name="newlqmod_company" required="">
                                    <option value="">Seleccione empresa</option>
                                    @foreach(App\Models\Company::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Salario Base <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="newlqmod_basesalary" name="newlqmod_basesalary" placeholder="Ingrese el salario base" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Comisión por Matrícula <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="newlqmod_enrollcommission" name="newlqmod_enrollcommission" placeholder="Ingrese el precio" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Bonus por Matrícula <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="newlqmod_enrollbonuscommission" name="newlqmod_enrollbonuscommission" placeholder="Ingrese bonus por matrícula" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Comisión por Venta Delegación <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="newlqmod_enrolldelegation" name="newlqmod_enrolldelegation" placeholder="Ingrese comisión por venta de delegación" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Descripción <label class="text-red">(*)</label></label>
                                <textarea id="summernote" name="editordata"></textarea>
                                <small style="font-size: 70%" id="editordata_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- @include('partials.admin.information') --}}
            </div>
            <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
            <small>
                <ol id="leadCreateOrUpdateError">

                </ol>
            </small> 
            <div class="modal-footer">
                <button type="submit" class="btn btn-info" id="saveBtn" value="create-product">Guardar Cambios</button>
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