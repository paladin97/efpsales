<div class="modal modal-wide fade" id="contractPaymentsModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-96" role="document">
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modelHeadingFeeCase">Cobros</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>        
            </div>
            <div class="modal-body">
                <div class="mt-3 callout callout-info text-lightblue p-1" style="font-size: 0.9em!important;">
                    <h5><i class="icon fad fa-info-circle"></i> Información de la cartera del Estudiante</h5>
                    <div class="row">
                        <div class="col-sm-12" id="contract_fee_information"></div>
                    </div>
                </div>
                <div class="row"  style="margin:auto;">
                    <div class="col-sm-12">
                        <div class="table-responsive"> 
                            <table id="datatable" class="small-table table-sm stripe row-border order-column compact table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Cuota</th>
                                        <th>Valor</th>
                                        <th>Valor Pagado</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Fecha Pagado</th>
                                        <th>Estado</th>
                                        <th>Razón impagado/rechazado</th>
                                        <th>Fecha impagado/rechazado</th>
                                        <th>Comprobante</th>
                                        <th>Comprobante impagado/rechazado</th>
                                        <th data-priority="1">Acciones</th>
                                        <th>ID</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="editPaymentFormDisplay" style="display: none">
                    <div class="card bg-lightblue">
                        <h4 align="center" id="feePaymentsHeading"></h4>
                        <form id="feePaymentsForm" name="feePaymentsForm" class="form-horizontal" enctype="multipart/form-data">
                            <input type="hidden" name="contract_id_fee" id="contract_id_fee">
                            <input type="hidden" name="enrollment_number" id="enrollment_number">
                            <div class="row" style="margin:auto;">
                                <div class="col-sm-3">
                                    <div class="row" style="margin:auto;">
                                        <div class="col-sm-12" align="center">
                                            <div class="form-group">
                                            @if(Auth::user()->hasRole('superadmin'))	
                                                <label for="name" class="mb-n1" style="text-align: center">Estado <label class="text-red">(*)</label></label>
                                                <select class="form-control form-control-sm select_list_filter" id="fee_payment_status_admin" name="fee_payment_status">
                                                    <option value="">Seleccione</option>
                                                    @foreach(App\Models\ContractFeeStatus::whereNotIn('short_name',['Z','PP'])->orderBy('name','ASC')->get()  as $cData)
                                                        <option value="{{$cData->short_name}}">{{$cData->name}}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <label for="name" class="mb-n1" style="text-align: center">Estado <label class="text-red">(*)</label></label>
                                                <div class="col-sm-12">
                                                    <input type="checkbox" class="custom-control-input" id="sinister_check" name="sinister_check">
                                                    <input type="checkbox" data-width="100px" data-onstyle="green btn-sm" data-offstyle="danger btn-sm" data-toggle="toggle" id="fee_payment_status" name="fee_payment_status[]" value="P" data-on="Pagado" data-off="Impagado">
                                                </div>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="row" id="fee_paid_row" style="display: none">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="name" class="mb-n1">Fecha Pagado <label class="text-red">(*)</label></label>
                                                <input type="date" class="form-control form-control-sm" id="fee_payment_dt_paid" name="fee_payment_dt_paid"/>
                                            </div>
                                        </div><div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="name" class="mb-n1">Valor Pagado <label class="text-red">(*)</label></label>
                                                <input type="text" class="form-control  form-control-sm" id="fee_paid_value" name="fee_paid_value" placeholder="Introduce el importe" pattern="[0-9]{1,}"> 
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="name" class="mb-n1">Comprobante de Pago <label class="text-red">&nbsp;</label></label>
                                                <input type="file"  class="form-control  form-control-sm" id="fee_path" name="fee_path" placeholder="" value="" maxlength="250">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="name" class="mb-n1">Observaciones <label class="text-red">&nbsp;</label></label>
                                                <textarea  class="form-control  form-control-sm" id="fee_observations" name="fee_observations" placeholder="Ingrese una observación." value="" maxlength="250"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="fee_unpaid_row" style="display: none">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="mb-n1">Fecha Cambio de Estado<label class="text-red">(*)</label></label>
                                                <input type="date" class="form-control form-control-sm" id="fee_payment_dt_unpaid" name="fee_payment_dt_unpaid"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="mb-n1">Comprobante o Soporte <label class="text-red">&nbsp;</label></label>
                                                <input type="file"  class="form-control form-control-sm" id="fee_unpaid_path" name="fee_unpaid_path" placeholder="" value="" maxlength="250">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="mb-n1">Razón Cambio de Estado <label class="text-red">(*)</label></label>
                                                <textarea id="fee_unpaid_reason" name = "fee_unpaid_reason" class="form-control form-control-sm" style="height:auto;" rows="2" maxlength="250" placeholder="Ingrese una observación."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-sm-12" align="center" style="margin-top: -2%">
                                    <div class="form-group">
                                        <label for="name" class="mb-n1">&nbsp;</label>
                                        <button class="btn bg-white" id="saveBtnFeePayments" value="lead-notes"></button>                            
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                <small><ol id="ContractFeeError"></ol></small>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
