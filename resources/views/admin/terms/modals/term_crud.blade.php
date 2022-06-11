<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modalHeadingTerm">Editar Términos</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>           
            </div>    
            <div class="modal-body">    
                <form id="termForm" name="termForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="term_id" id="term_id">
                    <div class="row mt-n2">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Empresa <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="modal_term_company" name="modal_term_company">
                                    <option value="">Seleccione una empresa</option>
                                    @foreach(App\Models\Company::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach 
                                </select>
                                <small style="font-size: 70%" id="modal_term_company_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Tipo Contrato <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="modal_term_ctype" name="modal_term_ctype">
                                    <option value="">Seleccione una tipo</option>
                                    @foreach(App\Models\ContractType::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach 
                                </select>
                                <small style="font-size: 70%" id="modal_term_ctype_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Términos y condiciones <label class="text-red">(*)</label></label>
                                <textarea id="summernote" name="editordata"></textarea>
                                <small style="font-size: 70%" id="editordata_error" class="text-danger"></small>
                            </div>
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