<div class="modal fade" style="border-radius: 25px" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="whatsAppTemplateModal" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-25" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modalHeadingWhatsapp">Plantillas de Whatsapp</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>           
            </div>    
            <div class="modal-body">    
                <form id="whatsAppSendForm" name="whatsAppSendForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="whatsapp_message_id" id="whatsapp_message_id">
                    <input type="hidden" name="whatsapp_message_phone" id="whatsapp_message_phone">
                    <div class="row mt-n2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Escoja una Plantilla <label class="text-red">&nbsp;</label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="whatsapp_template_type_id" name="whatsapp_template_type_id">
                                    <option value="">Seleccione una tipo</option>
                               
                                </select>
                                <small style="font-size: 70%" id="whatsapp_template_type_id_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Plantilla <label class="text-red">(*)</label></label>
                                <textarea class="form-control" rows="5" id="summernoteWhatsapp" name ="summernoteWhatsapp" placeholder="Mensaje"></textarea>
                                <small style="font-size: 70%" id="whatsapp_template_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="mb-n1">&nbsp; <label class="text-red">&nbsp;</label></label>
                                <p id="counter"  class="text-blue" style="margin:0px;"><span>0</span> /1000 caracteres permitidos</p>
                                <small style="font-size: 70%" id="whatsapp_template_type_id_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- @include('partials.admin.information') --}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-lightblue float-right" id="saveBtnWhatsapp" value="create-product"><i class="fad fa-paper-plane text-white"></i> Enviar</button>
                <p>
                <div class="alert alert-dismissible" style="display: none; background-color: green;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <div id="alert_message_lead" align="center" style="color:white;"></div>
                </div>
            </div>
        </div>
    </div>
</div>