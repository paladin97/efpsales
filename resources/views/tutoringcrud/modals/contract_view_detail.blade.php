<div class="modal modal-wide fade" id="viewDetailModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-96" role="document">
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modelHeadingDetail"><i class="fad fa-lg fa-book-user "></i> Ficha del Alumno</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>        
            </div>
            <div class="modal-body">
				<div class="row">
                    <div class="col-sm-3">
                        <div class="nav flex-column nav-tabs h-100" id="detail-tabs-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="detail-tabs-personal-tab" data-toggle="pill" href="#detail-tabs-personal" role="tab" aria-controls="detail-tabs-personal" aria-selected="true"></span> Información del Cliente <i class="fad fa-chevron-double-right text-lightblue"></i></a>
                            <a class="nav-link" id="detail-tabs-payer-tab" data-toggle="pill" href="#detail-tabs-payer" role="tab" aria-controls="detail-tabs-payer" aria-selected="false"></span> Información del Curso <i class="fad fa-chevron-double-right text-lightblue"></i></a>
                            <a class="nav-link" id="detail-tabs-case-tab" data-toggle="pill" href="#detail-tabs-case" role="tab" aria-controls="detail-tabs-case" aria-selected="false"></span> Información del Contrato <i class="fad fa-chevron-double-right text-lightblue"></i></a>
                            <a class="nav-link" id="detail-tabs-payment_type-tab" data-toggle="pill" href="#detail-tabs-payment_type" role="tab" aria-controls="detail-tabs-payment_type" aria-selected="false"></span> Gestiones del LEAD <i class="fad fa-chevron-double-right text-lightblue"></i></a>
                            <a class="nav-link" id="detail-tabs-payment_method-tab" data-toggle="pill" href="#detail-tabs-payment_method" role="tab" aria-controls="detail-tabs-payment_method" aria-selected="false"></span> Gestiones del Contrato <i class="fad fa-chevron-double-right text-lightblue"></i></a>
                            <a class="nav-link" id="detail-tabs-documents-tab" data-toggle="pill" href="#detail-tabs-documents" role="tab" aria-controls="detail-tabs-documents" aria-selected="false"></span> Documentos <i class="fad fa-chevron-double-right text-lightblue"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="tab-content" id="detail-tabs-tabContent">
                            <div class="tab-pane text-left fade active show" id="detail-tabs-personal" role="tabpanel" aria-labelledby="detail-tabs-personal-tab">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Nombres</label>
                                            <div class="col-sm-12">
                                                <p id="client_name"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Apellidos</label>
                                            <div class="col-sm-12">
                                                <p id="client_last_name"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">NIE/DNI/PASS</label>
                                            <div class="col-sm-12">
                                                <p id="client_dni"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Móvil </label>
                                            <div class="col-sm-12">
                                                <p id="client_mobile"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Domilicio </label>
                                            <div class="col-sm-12">
                                                <p id="client_address"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Población </label>
                                            <div class="col-sm-12">
                                                <p id="client_town"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Provincia </label>
                                            <div class="col-sm-12">
                                                <p id="client_province"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Código postal </label>
                                            <div class="col-sm-12">
                                                <p id="client_cp"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Email </label>
                                            <div class="col-sm-12">
                                                <p id="client_email"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Fecha nacimiento </label>
                                            <div class="col-sm-12">
                                                <p id="client_dt_birth"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Nacionalidad </label>
                                            <div class="col-sm-12">
                                                <p id="client_country"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Estudios </label>
                                            <div class="col-sm-12">
                                                <p id="client_studies"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Género </label>
                                            <div class="col-sm-12">
                                                <p id="client_gender"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Trabajo </label>
                                            <div class="col-sm-12">
                                                <p id="client_work"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="detail-tabs-payer" role="tabpanel" aria-labelledby="detail-tabs-payer-tab">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Curso</label>
                                            <div class="col-sm-12">
                                                <p id="client_course"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Material </label>
                                            <div class="col-sm-12">
                                                <p id="client_material"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">P.V.P </label>
                                            <div class="col-sm-12">
                                                <p id="client_complementary_course"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="tab-pane fade" id="detail-tabs-case" role="tabpanel" aria-labelledby="detail-tabs-case-tab">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Forma de pago </label>
                                            <div class="col-sm-12">
                                                <p id="client_payment_type"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="row d-none" id="client_single_payment">
                                            <div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name" class="col-sm-12 control-label">Un pago por importe </label>
                                                        <div class="col-sm-12">
                                                            <p id="client_s_payment"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none" id="client_postponed_payment">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="name" class="col-sm-12 control-label">Importe inicial </label>
                                                            <div class="col-sm-12">
                                                                <p id="client_pp_initial_payment"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="name" class="col-sm-12 control-label">Nº Cuotas </label>
                                                            <div class="col-sm-12">
                                                                <p id="client_pp_fee_quantity"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="name" class="col-sm-12 control-label">Importe cuotas </label>
                                                            <div class="col-sm-12">
                                                                <p id="client_pp_fee_value"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="name" class="col-sm-12 control-label">Matrícula</label>
                                                            <div class="col-sm-12">
                                                                <p id="client_pp_enroll_payment"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="name" class="col-sm-12 control-label">Pagar desde </label>
                                                            <div class="col-sm-12">
                                                                <p id="client_pp_dt_first_payment"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="name" class="col-sm-12 control-label">Pagar hasta </label>
                                                            <div class="col-sm-12">
                                                                <p id="client_pp_dt_final_payment"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none" id="client_monthly_payment">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Importe mensual </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_m_payment"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Método de pago </label>
                                            <div class="col-sm-12">
                                                <p id="client_method_type_id"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="hide" id="client_contract_method_type_1">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Nombre <small style="font-size:65%;">(Como aparece en la tarjeta)</small> </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_card_holder"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Nº de la tarjeta </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_card_number"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Fecha expiración</label>
                                                    <div class="col-sm-12">
                                                        <p id="client_card_expiry"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">CVV </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_card_cvv"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Tipo </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_card_type"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="client_contract_method_type_2" class="hide">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Titular de la cuenta</label>
                                                    <div class="col-sm-12">
                                                        <p id="client_dd_holdername"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">DNI/NIE/PASS </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_dd_dni"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">IBAN </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_dd_iban"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Banco </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_dd_bank"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">SEPA </label>
                                                    <div class="col-sm-12" id="client_dd_sepa">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hide" id="client_contract_method_type_3">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Datos de ingreso </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_t_concept"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Cuenta de ingreso </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_t_iban"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hide" id="client_contract_method_type_4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-12 control-label">Pago en efectivo </label>
                                                    <div class="col-sm-12">
                                                        <p id="client_cash_payment"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12"> 
                                        <label for="name" class="col-sm-12 control-label">Observaciones </label>
                                        <div class="col-sm-12">
                                            <p id="client_observations"></p>
                                        </div>                                                 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"> 
                                        <label for="name" class="col-sm-12 control-label">Vigencia del contrato </label>
                                        <div class="col-sm-12">
                                            <p id="client_validty"></p>
                                        </div>                                                 
                                    </div>
                                    <div class="col-sm-3"> 
                                        <label for="name" class="col-sm-12 control-label">Empresa </label>
                                        <div class="col-sm-12">
                                            <p id="client_company"></p>
                                        </div>                                                 
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Contrato </label>
                                            <div class="col-sm-12">
                                                <p id="client_enrollment_number"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-12 control-label">Fecha Matrícula  </label>
                                            <div class="col-sm-12">
                                                <p id="client_dt_created"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <hr>
                                        <h3 align="center" class="text-lightblue"><b>COBROS</b></h3>
                                        <p id="client_charges"></p>
                                        <div class="row"  style="margin:auto;">
                                            <div class="table-responsive col-sm-12">
                                                <table id="datatable-clientefees" class="small-table stripe row-border order-column compact table table-striped" cellspacing="0" width="100%">
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
                                                            <th>ID</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>                                              
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="detail-tabs-payment_type" role="tabpanel" aria-labelledby="detail-tabs-payment_type-tab">
                                <div class="row">
                                    <div class="col-sm-12"> 
                                        <h3 class="text-lightblue">Notas de Gestión del LEAD</h3>
                                        <div class="row">
                                            <div class="table-responsive col-sm-12">  
                                                <label for="name" class="col-sm-12 control-label">Asesor Pedagógico </label>
                                                <div class="col-sm-12">
                                                    <p id="client_comercial_name"></p>
                                                </div>                                                 
                                            </div>
                                        </div>
                                        <div class="row"  style="margin:auto;">
                                            <div class="col-sm-12">
                                                <table id="datatable-clientnotes" class="small-table stripe row-border order-column compact table table-striped" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha Gestión</th>
                                                            <th>Hora</th>
                                                            <th>Medio de contacto</th>
                                                            <th>Estado</th>
                                                            <th>Sub Estado</th>
                                                            <th>Observaciones</th>
                                                            <th>Usuario</th>
                                                            <th>ID</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>                                               
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="detail-tabs-payment_method" role="tabpanel" aria-labelledby="detail-tabs-payment_method-tab">
                                <div class="row">
                                    <div class="col-sm-12"> 
                                        <h3 class="text-lightblue">Notas de Gestión del Contrato</h3>
                                        <div class="row">
                                            <div class="col-sm-12">  
                                                <label for="name" class="col-sm-12 control-label">Asesor Pedagógico </label>
                                                <div class="col-sm-12">
                                                    <p id="client_management_comercial_name"></p>
                                                </div>                                                 
                                            </div>
                                        </div>
                                        <div class="row"  style="margin:auto;">
                                            <div class="col-sm-12">
                                                <table id="datatable-management-clientnotes" class="small-table stripe row-border order-column compact table table-striped" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha Gestión</th>
                                                            <th>Hora</th>
                                                            <th>Medio de contacto</th>
                                                            <th>Tipo</th>
                                                            <th>Categoría</th>
                                                            <th>Observaciones</th>
                                                            <th>Usuario</th>
                                                            <th>Soporte</th>
                                                            <th>Acciones</th>
                                                            <th>ID</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>                                               
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="detail-tabs-documents" role="tabpanel" aria-labelledby="detail-tabs-documents-tab">
                                <div class="scroll" id="client_documments"></div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
        </div>
    </div>
</div>