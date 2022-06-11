<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="companyHeading" id="ajaxModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">  
        <div class="modal-content">     
            <div class="modal-header color-palette bg-lightblue">    
                <h4 class="modal-title" id="leadsHeading">Editar Empresa</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>           
            </div>      
            <div class="modal-body">    
                <form id="companyForm" name="companyForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="company_id" id="company_id">
                    <div class="row mt-2">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Nombre Empresa <label class="text-danger">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Ingrese el nombre" value="" maxlength="250" required="">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">CIF <label class="text-danger">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="cif" name="cif" placeholder="Ingrese CIF de la empresa" value="" maxlength="250" required="">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Representante Legal <label class="text-danger">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="leg_rep_full_name" name="leg_rep_full_name" placeholder="Ingrese nombre representante" value="" maxlength="250" required="">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">DNI/NIE Representante <label class="text-danger">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="leg_rep_nif" name="leg_rep_nif" placeholder="Ingrese nombre representante" value="" maxlength="250" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Dirección</label>
                                <input type="text" class="form-control form-control-sm" id="address" name="address" placeholder="Ingrese la dirección" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Localidad</label>
                                <input type="text" class="form-control form-control-sm" id="town" name="town" placeholder="Ingrese la localidad" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Provincia</label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="company_province" name="company_province">
                                    <option value="">Provincia</option>
                                    @foreach(App\Models\Province::all()->sortBy('name') as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Código Postal</label>
                                <input type="text" class="form-control form-control-sm" id="postal_code" name="postal_code" placeholder="Ingrese la código postal" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Correo Electrónico</label>
                                <input type="text" class="form-control form-control-sm" id="mail" name="mail" placeholder="Ingrese el email" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Teléfono</label>
                                <input type="text" class="form-control form-control-sm" id="phone" name="phone" placeholder="Ingrese un teléfono" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Banco</label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="company_bank" name="company_bank">
                                    <option value="">Seleccione un Banco</option>
                                    @foreach(App\Models\Bank::all()->sortBy('name') as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Switf</label>
                                <input type="text" class="form-control form-control-sm" id="switf" name="switf" placeholder="Ingrese switf" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Cuenta Bancaria</label>
                                <input type="text" class="form-control form-control-sm" id="bank_account" name="bank_account" placeholder="____ ____ ____ ____ ____ __" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Descripción</label>
                                <div class="col-sm-12">
                                    <textarea id="description" name = "description" class="form-control form-control-sm" style="height:auto;" rows="3" maxlength="250" placeholder="Ingrese una observación"> </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-lightblue">Enlaces de Redes Sociales y Sitio Web</h5>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="mb-n1"><i class="fab fa-facebook fa-1x text-lightblue"></i> Enlace de Facebook <label class="text-danger">&nbsp;</label></label>
                                <input type="text" class="form-control form-control-sm" id="url_facebook" name="url_facebook" placeholder="Ej. https://www.facebook.com/nombre" value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="mb-n1"><i class="fab fa-instagram fa-1x text-lightblue"></i> Enlace de Instagram <label class="text-danger">&nbsp;</label></label>
                                <input type="text" class="form-control form-control-sm" id="url_instagram" name="url_instagram" placeholder="Ej. https://www.instagram.com/nombre/" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="mb-n1"><i class="fab fa-google fa-1x text-lightblue"></i> Enlace de Google Business <label class="text-danger">&nbsp;</label></label>
                                <input type="text" class="form-control form-control-sm" id="url_business" name="url_business" placeholder="Ej. https://g.page/..." value="" maxlength="250">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="mb-n1"><i class="fad fa-browser fa-1x text-lightblue"></i> Enlace de Sitio Web <label class="text-danger">&nbsp;</label></label>
                                <input type="text" class="form-control form-control-sm" id="url_website" name="url_website" placeholder="Ej. https://nombredominio" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
            <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                    <small>
                        <ol id="leadCreateOrUpdateError">

                        </ol>
                    </small>
            <div class="modal-footer">
                <button type="submit" class="btn bg-lightblue" id="saveBtn" value="create-user"><span>Guardar Cambios</span></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span>Cancelar</span></button>
                <p>
                    <div class="alert alert-dismissible" style="display: none; background-color: green;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <div id="alert_message_lead" align="center" style="color:white;"></div>
                    </div>
            </div>
        </div>
    </div>
</div>