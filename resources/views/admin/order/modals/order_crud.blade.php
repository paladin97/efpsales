<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-50" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">   
                <h4 class="modal-title" id="modalHeadingStatus">Pedidos de materiales</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>       
            </div>    
            <div class="modal-body">    
                <form id="orderForm" method="POST" action="" name="orderForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="order_id" id="order_id">
                    {!! method_field('PUT')!!}
                    {!!  csrf_field() !!}
                    <div class="row mt-n2">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="order_code" class="mb-n1">Código del Pedido<label class="text-red">&nbsp;</label></label>
                                <input type="text" class="form-control form-control-sm" id="order_code" name="order_code" placeholder='Introduce el código del Pedido'>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="order_text" class="mb-n1">Pedido de Material<label class="text-red">&nbsp;</label></label>
                            <textarea class="form-control form-control-sm" rows="4" id="order_text" name="order_text" placeholder='Introduce el pedido'></textarea>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="order_status" class="mb-n1">Estado</label>
                                <select class="form-control form-control-sm select_list_filter" id="order_status" name="order_status">
                                    @foreach(App\Models\OrderStatus::all() as $cData)
                                        <option value="{{$cData->id}}">{{$cData->status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Agencias de envío</label>
                                <select style="width:100%;"  class="form-control form-control-sm select_list " id="shipping_list" name="shipping_list">
                                    <option value="">Seleccione</option>
                                    @foreach(App\Models\ProviderType::find(2)->providers as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Imprentas</label>
                                <select style="width:100%;"  class="form-control form-control-sm select_list " id="printing_list" name="printing_list">
                                    <option value="">Seleccione</option>
                                    @foreach(App\Models\ProviderType::find(1)->providers as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                       
                       
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
                </form>
                {{-- @include('partials.admin.information') --}}
            </div>
            
        </div>
    </div>
</div>