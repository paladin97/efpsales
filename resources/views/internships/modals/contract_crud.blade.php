<div class="modal modal-wide fade" id="editContractModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-96" role="document">
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modalHeadingContract">Crear Contrato de Prácticas</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>        
            </div>
            <div class="modal-body">
				<form id="leadFormCase" name="editContractForm" class="form-horizontal" enctype="multipart/form-data">
					<input type="hidden" name="contract_lead_id" id="contract_lead_id">
					<input type="hidden" name="contract_id" id="contract_id">
					<input type="hidden" name="contract_type_id" id="contract_type_id">
					<input type="hidden" name="contract_dt_creation" id="contract_dt_creation">
					<input type="hidden" name="contract_enroll_nmber" id="contract_enroll_nmber">
					<div class="row">
						<div class="col-sm-3">
						  <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
							<a class="nav-link active" id="vert-tabs-personal-tab" data-toggle="pill" href="#vert-tabs-personal" role="tab" aria-controls="vert-tabs-personal" aria-selected="true"><span class="fa-stack"><span class="fad fa-circle text-lightblue fa-stack-2x"></span><strong class="fa-stack-1x text-white">1</strong></span> Información del Estudiante 	</a>
							<a class="nav-link" id="vert-tabs-payer-tab" data-toggle="pill" href="#vert-tabs-payer" role="tab" aria-controls="vert-tabs-payer" aria-selected="false"><span class="fa-stack"><span class="fad fa-circle text-lightblue fa-stack-2x"></span><strong class="fa-stack-1x text-white">2</strong></span> Información del Pagador</a>
							<a class="nav-link" id="vert-tabs-case-tab" data-toggle="pill" href="#vert-tabs-case" role="tab" aria-controls="vert-tabs-case" aria-selected="false"><span class="fa-stack"><span class="fad fa-circle text-lightblue fa-stack-2x"></span><strong class="fa-stack-1x text-white">3</strong></span> Información del Contrato</a>
							<a class="nav-link" id="vert-tabs-payment_type-tab" data-toggle="pill" href="#vert-tabs-payment_type" role="tab" aria-controls="vert-tabs-payment_type" aria-selected="false"><span class="fa-stack"><span class="fad fa-circle text-lightblue fa-stack-2x"></span><strong class="fa-stack-1x text-white">4</strong></span> Forma de pago</a>
							<a class="nav-link" id="vert-tabs-payment_method-tab" data-toggle="pill" href="#vert-tabs-payment_method" role="tab" aria-controls="vert-tabs-payment_method" aria-selected="false"><span class="fa-stack"><span class="fad fa-circle text-lightblue fa-stack-2x"></span><strong class="fa-stack-1x text-white">5</strong></span> Medios de pago</a>
							<a class="nav-link" id="vert-tabs-others-tab" data-toggle="pill" href="#vert-tabs-others" role="tab" aria-controls="vert-tabs-others" aria-selected="false"><span class="fa-stack"><span class="fad fa-circle text-lightblue fa-stack-2x"></span><strong class="fa-stack-1x text-white">6</strong></span> Otros Factores</a>
						</div>
						</div>
						<div class="col-sm-9">
						  <div class="tab-content" id="vert-tabs-tabContent">
							<div class="tab-pane text-left fade active show" id="vert-tabs-personal" role="tabpanel" aria-labelledby="vert-tabs-personal-tab">
								<div class="row mt-n2">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Nombre(s)  <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_first_name" name="contract_first_name" placeholder="Ingrese los nombres" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Apellido(s)  <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_last_name" name="contract_last_name" placeholder="Ingrese los apellidos" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">NIE/DNI/PASS <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_identifier" name="contract_identifier" placeholder="Ingrese el documento" value="" maxlength="250">
										</div>
									</div>
								</div>
								<div class="row mt-n2">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Domilicio  <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_address" name="contract_address" placeholder="Ingrese el domicilio" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="mb-n1">Población <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_town" name="contract_town" placeholder="Ingrese la provincia" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="mb-n1">Código postal <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_cp" name="contract_cp" placeholder="Ingrese el código postal" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Provincia  <label class="text-red">(*)</label></label>
											<select style="width:100%;" class="form-control form-control-sm select_list" id="contract_provinces_list" name="contract_provinces_list[]">
												<option value="">Seleccione..</option>
												@foreach(App\Models\Province::all()->sortBy('name') as $cData)
												<option value="{{$cData->id}}">{{$cData->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="row mt-n2">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Email  <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_email" name="contract_email" placeholder="Ingrese el email"  value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="mb-n1">Móvil  <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_mobile" name="contract_mobile" placeholder="Ingrese el móvil" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="name" class="mb-n1">Télefono  <label class="text-red">&nbsp;</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_phone" name="contract_phone" placeholder="Ingrese el télefono" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Fecha nacimiento <label class="text-red">(*)</label></label>
											<input type="date" class="form-control form-control-sm" id="contract_dt_birth" name="contract_dt_birth" placeholder="Ingrese una fecha" value="" maxlength="250">
										</div>
									</div>
								</div>
								<div class="row mt-n2">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Nacionalidad  <label class="text-red">(*)</label></label>
											<select style="width:100%;" class="form-control form-control-sm select_list" id="contract_countries_list" name="contract_countries_list[]">
												<option value="71">España</option>
												@foreach(App\Models\Country::all()->sortBy('name') as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Estudios  <label class="text-red">(*)</label></label>
											<input type="input" class="form-control form-control-sm" id="contract_studies" name="contract_studies" placeholder="Ingrese los estudios" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Trabaja  <label class="text-red">(*)</label></label>
											<select style="width:100%;" class="form-control form-control-sm select_list"  id="has_work" name="has_work">
												<option value = '0'  selected="selected">No se aporta</option>
												<option value = '1'>Si</option>
												<option value = '2'>No</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row mt-n2 d-none">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Género <label class="text-red">(*)</label></label>
											<select style="width:100%;" class="form-control form-control-sm select_list" id="gender" name="gender">
												<option value = 'M'>Masculino</option>
												<option value = 'F'>Femenino</option>
												<option value = 'P'>Pangénero</option>
											</select>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name" class="mb-n1">Estado civil  <label class="text-red">(*)</label></label>
											<select style="width:100%;" class="form-control form-control-sm select_list"  id="marital_status" name="marital_status">
												<option value = 'NSA' selected="selected">No se aporta</option>
												<option value = 'SOL'>Soltero/a</option>
												<option value = 'CAS'>Casado/a</option>
												<option value = 'DIV'>Divorciado/a</option>
												<option value = 'VIU'>Viudo/a</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="vert-tabs-payer" role="tabpanel" aria-labelledby="vert-tabs-payer-tab">
								<div class="row mt-n2 d-flex justify-content-center">
									<div class="form-group">
										<label for="name" class="mb-n1">El pago lo hará el estudiante  </label>
										<input type="checkbox" id="same_payer_person" name="same_payer_person[]" value="1" checked>
									</div>
								</div>
								<div id="samepayer" style="display:none">
									<div class="row mt-n2">
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Nombres/ Razón Social <label class="text-red">(*)</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_first_name_t" name="contract_first_name_t" placeholder="Ingrese los nombres" value="" maxlength="250">
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Apellidos/ Razón Social <label class="text-red">(*)</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_last_name_t" name="contract_last_name_t" placeholder="Ingrese los apellidos" value="" maxlength="250">
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">NIE/DNI/PASS/CIF <label class="text-red">(*)</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_identifier_t" name="contract_identifier_t" placeholder="Ingrese el documento" value="" maxlength="250">
											</div>
										</div>
									</div>
									<div class="row mt-n2">
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Domilicio  <label class="text-red">(*)</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_address_t" name="contract_address_t" placeholder="Ingrese el domicilio" value="" maxlength="250">
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="name" class="mb-n1">Población <label class="text-red">(*)</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_town_t" name="contract_town_t" placeholder="Ingrese la provincia" value="" maxlength="250">
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="name" class="mb-n1">Código postal <label class="text-red">(*)</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_cp_t" name="contract_cp_t" placeholder="Ingrese el código postal" value="" maxlength="250">
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Provincia  <label class="text-red">(*)</label></label>
												<select style="width:100%;" class="form-control form-control-sm select_list" id="contract_provinces_list_t" name="contract_provinces_list_t[]_payer">
													<option value="">Seleccione..</option>
													@foreach(App\Models\Province::all()->sortBy('name') as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="row mt-n2">
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Email  <label class="text-red">(*)</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_email_t" name="contract_email_t" placeholder="Ingrese el email"  value="" maxlength="250">
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="name" class="mb-n1">Móvil  <label class="text-red">(*)</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_mobile_t" name="contract_mobile_t" placeholder="Ingrese el móvil" value="" maxlength="250">
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="name" class="mb-n1">Télefono  <label class="text-red">&nbsp;</label></label>
												<input type="text" class="form-control form-control-sm" id="contract_phone_t" name="contract_phone_t" placeholder="Ingrese el télefono" value="" maxlength="250">
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Fecha nacimiento <label class="text-red">(*)</label></label>
												<input type="date" class="form-control form-control-sm" id="contract_dt_birth_t" name="contract_dt_birth_t" placeholder="Ingrese una fecha" value="" maxlength="250">
											</div>
										</div>
									</div>
									<div class="row mt-n2">
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Nacionalidad  <label class="text-red">(*)</label></label>
												<select style="width:100%;" class="form-control form-control-sm select_list_payer" id="contract_countries_list_t" name="contract_countries_list_t[]">
													<option value="71">España</option>
													@foreach(App\Models\Country::all()->sortBy('name') as $cData)
														<option value="{{$cData->id}}">{{$cData->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="row mt-n2 d-none">
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Género <label class="text-red">(*)</label></label>
												<select style="width:100%;" class="form-control form-control-sm select_list" id="gender_t" name="gender_t">
													<option value = 'M'>Masculino</option>
													<option value = 'F'>Femenino</option>
													<option value = 'P'>Pangénero</option>
												</select>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="name" class="mb-n1">Estado civil  <label class="text-red">(*)</label></label>
												<select style="width:100%;" class="form-control form-control-sm select_list"  id="marital_status_t" name="marital_status_t">
													<option value = 'NSA' selected="selected">No se aporta</option>
													<option value = 'SOL'>Soltero/a</option>
													<option value = 'CAS'>Casado/a</option>
													<option value = 'DIV'>Divorciado/a</option>
													<option value = 'VIU'>Viudo/a</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="vert-tabs-case" role="tabpanel" aria-labelledby="vert-tabs-case-tab">
								<div class="row mt-n2">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="mb-n1">Curso Realizado <label class="text-red">(*)</label></label>
											<select class="form-control form-control-sm select_list" id="contract_courses_list" name="contract_courses_list[]">
												<option value="">Seleccione un curso</option>
												@foreach(App\Models\Course::all()->sortBy('name') as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="mb-n1">Centro de Estudios <label class="text-red">(*)</label></label>
											<select style="width:100%;"  class="form-control-sm select_list" id="contract_ecenter_list" name="contract_ecenter_list[]" maxlength="250">
												<option value="">Seleccione un centro</option>
												@foreach(App\Models\EducationalCenter::all() as $cData)
													<option value="{{$cData->id}}">{{$cData->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="mb-n1">Estado del Curso <label class="text-red">(*)</label></label>
											<select style="width:100%;"  class="form-control form-control-sm select_list" id="internship_studies_list" name="internship_studies_list[]" maxlength="250">
												<option value="">Seleccione un estado</option>
												@foreach(App\Models\InternshipStatus::all() as $cData)
												<option value="{{$cData->id}}">{{$cData->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="mb-n1">Provincia Prácticas <label class="text-red">(*)</label></label>
											<select style="width:100%;"  class="form-control form-control-sm select_list" id="contract_intrnship_province_id" name="contract_intrnship_province_id[]" maxlength="250">
												<option value="">Seleccione una provincia</option>
												@foreach(App\Models\Province::all() as $cData)
												<option value="{{$cData->id}}">{{$cData->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="row mt-n2">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="mb-n1">Fecha finalización curso <label class="text-red">(*)</label></label>
											<input type="date" class="form-control form-control-sm" id="contract_intership_dt_completion" name="contract_intership_dt_completion" placeholder="Ingrese una fecha" value="" maxlength="250">
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="mb-n1">Total horas de prácticas <label class="text-red">(*)</label></label>
											<input type="text" class="form-control form-control-sm" id="contract_intersh_total_h" name="contract_intersh_total_h" placeholder='Introduce la cantidad'  pattern='[0-9]{1,}'>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="name" class="mb-n1">Fecha matriculación <label class="text-red">(*)</label></label>
											<input type="date" class="form-control form-control-sm" id="contract_internship_dt_contract" name="contract_internship_dt_contract" placeholder="Ingrese una fecha" value="" maxlength="250">
										</div>
									</div>
								</div>
								<div class="row mt-n2">
									<div class="col-sm-12">
										<div id="course_info">
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="vert-tabs-payment_type" role="tabpanel" aria-labelledby="vert-tabs-payment_type-tab">
								<div class="row  mt-n2">
									<div class="col-sm-12">
										<div class="form-group">
											<label for="name" class="mb-n1">Forma de pago <label class="text-red">(*)</label></label>
											<select style="width:100%;"  class="form-control form-control-sm select_list" id="contract_payment_type_id" name="contract_payment_type_id">
												<option value="">Seleccione</option>
												@foreach(App\Models\ContractPaymentType::all()->sortBy('name') as $cData)
												<option value="{{$cData->id}}">{{$cData->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="row border-top">
									<div class="col-sm-12">
										<div class="row d-none mt-1" id="single_payment">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="name" class="mb-n1">Un pago por importe <label class="text-red">(*)</label></label>
													<input type="text" class="form-control form-control-sm" id="s_payment" name="s_payment" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
												</div>
											</div>
										</div>
										<div class="row d-none mt-1" id="postponed_payment">
											<div class="col-sm-12">
												<div class="row">
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="mb-n1">Importe inicial <label class="text-red">&nbsp;</label></label>
															<input type="text" readonly class="form-control form-control-sm" id="pp_initial_payment" name="pp_initial_payment" value="0" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="mb-n1">Nº Cuotas <label class="text-red">(*)</label></label>
															<select style="width:100%;"  class="form-control form-control-sm select_list" id="pp_fee_quantity" name="pp_fee_quantity">
																<option value="">Seleccione</option>
																<option value="2">Semi-Contado</option>
																<option value="3">3</option>
																<option value="6">6</option>
																<option value="12">12</option>
																<option value="18">18</option>
																<option value="24">24</option>
															</select>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="mb-n1">Importe cuotas <label class="text-red">(*)</label></label>
															<input type="text" readonly class="form-control form-control-sm" id="pp_fee_value" name="pp_fee_value" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
														</div>
													</div>
												</div>
												<div class="row mt-n2">
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="mb-n1">Pagar desde <label class="text-red">(*)</label></label>
															<input type="date" class="form-control form-control-sm" id="pp_dt_first_payment" name="pp_dt_first_payment">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="name" class="mb-n1">Pagar hasta <label class="text-red">&nbsp;</label></label>
															<input readonly type="date" class="form-control form-control-sm" id="pp_dt_final_payment" name="pp_dt_final_payment">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row d-none  mt-1" id="monthly_payment">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="name" class="mb-n1">Importe mensual <label class="text-red">(*)</label></label>
													<input type="text" class="form-control form-control-sm" id="m_payment" name="m_payment" placeholder='Introduce el importe'  pattern='[0-9]{1,}'>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="vert-tabs-payment_method" role="tabpanel" aria-labelledby="vert-tabs-payment_method-tab">
								<div class="row mt-n2">
									<div class="col-sm-12">
										<div class="form-group">
											<label for="name" class="mb-n1">Método de pago <label class="text-red">(*)</label></label>
											<select style="width:100%;"  class="form-control form-control-sm select_list" id="contract_method_type_id" name="contract_method_type_id">
												<option value="">Seleccione</option>
												@foreach(App\Models\ContractPaymentMethod::all()->sortBy('name') as $cData)
												<option value="{{$cData->id}}">{{$cData->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="border-top">
									<div class="row d-none" id="contract_method_type_1">
										<div class="col-sm-12">
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
														<label for="name" class="mb-n1">Nombre <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" id="card_holder_name" name="card_holder_name" placeholder='Nombre en la tarjeta'>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label for="name" class="mb-n1">Nº de la tarjeta <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" id="card_number" name="card_number" placeholder='____ ____ ____ ____'>
													</div>
												</div>
												<div class="col-sm-4">
													<label for="name" class="mb-n1">Fecha expiración <label class="text-red">(*)</label></label>
													<div class="row">
														<div class="col-sm-6">
															<select class="form-control form-control-sm select_list" name="expiry_month" id="expiry_month">
																@for ($i = 1; $i <= 12; $i++)
																	<option value="{{sprintf("%02d", $i)}}">{{sprintf("%02d", $i)}}</option>
																@endfor
															</select>
														</div>
														<div class="col-sm-6">
															<select class="form-control form-control-sm select_list" name="expiry_year" id="expiry_year">
																	<option value="{{Carbon\Carbon::now()->year}}">{{Carbon\Carbon::now()->year}}</option>
																@for ($i = 1; $i < 10; $i++)
																	<option value="{{Carbon\Carbon::now()->addYear($i)->year}}">{{Carbon\Carbon::now()->addYear($i)->year}}</option>
																@endfor
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="row mt-n2">
												<div class="col-sm-4">
													<div class="form-group">
														<label for="name" class="mb-n1">CVV <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" name="cvv" id="cvv" placeholder="Código de seguridad">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label for="name" class="mb-n1">Tipo Tarjeta <label class="text-red">(*)</label></label>
														<select style="width:100%;" class="form-control form-control-sm  select_list" id="cc_type" name="cc_type[]">
															<option value="Visa">Visa</option>
															<option value="Mastercard">Mastercard</option>
															<option value="AMEX">AMEX</option>
															<option value="Discover">Discover</option>
															<option value="Diners">Diners</option>
														</select>
													</div>
												</div>
												<div class="col-sm-4">
													<img src="{{asset('images/paymentgateway.png')}}" width="200" alt="">
												</div>
											</div>
										</div>
									</div>
									<div class="row d-none" id="contract_method_type_2">
										<div class="col-sm-12">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="mb-n1">Titular de la cuenta  <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" id="dd_holder_name" name="dd_holder_name" placeholder='Ingrese nombres y apellidos'>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="mb-n1">DNI/NIE/PASS <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" id="dd_holder_dni" name="dd_holder_dni" placeholder='Ingrese el DNI/NIE/PASS'>
													</div>
												</div>
											</div>
											<div class="row mt-n2">
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="mb-n1">IBAN <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" id="dd_iban" name="dd_iban" placeholder="____ ____ ____ ____ ____ __">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="mb-n1">Banco <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" id="dd_bank_name" name="dd_bank_name" placeholder='Nombre del banco'>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row d-none" id="contract_method_type_3">
										<div class="col-sm-12">
											<div class="row ">
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="mb-n1">Datos de ingreso <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" id="t_payment_concept" name="t_payment_concept">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label for="name" class="mb-n1">Cuenta de ingreso <label class="text-red">&nbsp;</label></label>
														<input readonly type="text" class="form-control form-control-sm" id="t_iban" name="t_iban" value="EL IBAN ESTA ASOCIADO A LA EMPRESA QUE ESCOJA PARA ESTE CONTRATO">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row d-none" id="contract_method_type_4">
										<div class="col-sm-12">
											<div class="row ">
												<div class="col-sm-12">
													<div class="form-group">
														<label for="name" class="mb-n1">Pago en efectivo <label class="text-red">(*)</label></label>
														<input type="checkbox"  id="cash_payment" name="cash_payment[]" value="1">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row d-none" id="contract_method_type_5">
										<div class="col-sm-12">
											<div class="row ">
												<div class="col-sm-12">
													<div class="form-group">
														<label for="name" class="mb-n1">Pago por PayPal <label class="text-red">(*)</label></label>
														<input type="text" class="form-control form-control-sm" id="paypal_receipt" name="paypal_receipt" placeholder="Ingrese el número del recibo de paypal">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="vert-tabs-others" role="tabpanel" aria-labelledby="vert-tabs-others-tab">
								<div class="row">
									<div class="col-sm-12"> 
										<label for="name" class="mb-n1">Observaciones <label class="text-red">(*)</label></label>
										<div class="col-sm-12">
											<textarea id="contract_observations" name="contract_observations"> - </textarea>
										</div>                                                 
									</div>
                                </div>
								<div class="row">
									<div class="col-sm-4"> 
										<label for="name" class="mb-n1">Vigencia del contrato <label class="text-red">(*)</label></label>
										<div class="col-sm-12">
											<select class="form-control form-control-sm select_list" id="validity" name="validity">
												<option value="24">2 años</option>
												<option value="12">1 año</option>
												<option value="10">10 meses</option>
												<option value="6">6 meses</option>
												<option value="3">3 meses</option>
												<option value="1">1 mes</option>
											</select>
										</div>                                                 
                                    </div>
                                    <div class="col-sm-4"> 
										<label for="name" class="mb-n1">Empresa <label class="text-red">(*)</label></label>
										<div class="col-sm-12">
											<select class="form-control form-control-sm  select_list_filter" id="companies_list" name="companies_list[]">
                                                <option value="">Seleccione un curso</option>
                                                @foreach(App\Models\Company::from('companies as com')
                                                            ->leftJoin('access_companies as ascom','com.id','ascom.company_id')
                                                            ->where('ascom.user_id','=',Auth::user()->id)
                                                            ->select('com.*')
                                                            ->orderBy('name','ASC')->get() as $cData)
                                                    <option value="{{$cData->id}}">{{$cData->name}}</option>
                                                @endforeach
                                            </select>
										</div>                                                 
									</div>
								</div>
								<hr>
								<button type="submit" class="btn bg-lightblue float-right" id="saveBtn" value="create-product"><i class="fad fa-download"></i> Matricular</button>
							</div>
						  </div>
						</div>
					  </div>
				</form>
			</div>
			<p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
			<small><ol id="internShipFormError"></ol></small>
			
        </div>
    </div>
</div>