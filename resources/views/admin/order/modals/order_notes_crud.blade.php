<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modelOrder"
    data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-50" role="document">
        <div class="modal-content">
            <div class="modal-header bg-lightblue color-palette">
                <h4 class="modal-title" id="modalHeadingStatus">Notas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="orderNoteForm" method="POST" action="" name="orderNoteForm" class="form-horizontal"
                    enctype="multipart/form-data">
                    <input type="hidden" value="" name="order_id" id="order_note_id">
                    <input type="hidden" name="" value="" id="note_method">
                    {{-- {!! method_field('PUT')!!} --}}
                    {!! csrf_field() !!}
                    <div class="row mt-n2">
                        <div class="col-sm-6">
                            <label for="order_note_text" class="mb-n1">Agregar Nota<label
                                    class="text-red">&nbsp;</label></label>
                            <textarea class="form-control form-control-sm" rows="5" id="order_note_text" name="order_note_text"
                                placeholder='Introduce una nota'></textarea>
                        </div>

                        <div class="col-sm-3" id="order_status_div">
                            <div class="form-group">
                                <label for="order_status" class="mb-n1">Estado</label>
                                <select class="form-control form-control-sm select_list_filter" id="order_status2"
                                    name="order_status">
                                    @foreach (App\Models\OrderStatus::all() as $cData)
                                        <option value="{{ $cData->id }}">{{ $cData->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-lightblue" id="saveBtn" value="create-product">Guardar
                            Cambios</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <p>
                        <div class="alert alert-dismissible" style="display: none; background-color: green;">
                            <button type="button" class="close" data-dismiss="alert"
                                aria-hidden="true">×</button>
                            <div id="alert_message_lead" align="center" style="color:white;"></div>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
