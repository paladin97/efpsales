<script>
	function callReminders (){
		//Las alertas de volver a llamar
		@if(empty($call_reminder_leads))
			// whatever you need to do here
		@else 
			if(window.screen.width >= 768){
				$.confirm({
					columnClass: 'xlarge',
					icon: 'fad fa-bell-exclamation',
					type: 'blue',
					title: '<div style="text-align:center;"><b class="text-lightblue">Recordatorio LEADS</b></div>',
					content: '' +
					'<form action="" class="formName">' +
					'<div class="card">'+
						'<div class="card-header with-border">'+
							'<h4 class="card-title">Los siguientes alumnos estan señalados para seguimiento</h4>'+
						'</div>'+
						'<div class="card-body text-left">'+
							'<table id="reminder_call" class="small-table table-sm table-border cell-border order-column compact table-reminder" style="width:100%">'+
								'<thead>'+
									'<tr>'+
										'<th>#</th>'+
										'<th>Estado</th>'+
										'<th>Sub Estado</th>'+
										'<th>Alumno</th>'+
										'<th>Curso</th>'+
										'<th>Télefono</th>'+
										'<th>Recordatorio llamada</th>'+
										'<th>Acciones</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody>'+
									@foreach($call_reminder_leads as $call_reminder_lead)
										'<tr>'+
											'<td>{{(int)$loop->index + 1}}</td>'+
											'<td><span class="badge text-sm {{$call_reminder_lead['bg_color_class']}}">{{$call_reminder_lead['lead_status_name']}}</span></td>'+
											'<td><span class="badge text-sm {{$call_reminder_lead['sub_status_color']}}">{{$call_reminder_lead['lead_sub_status_name']}}</span></td>'+
											'<td>{{$call_reminder_lead['student_first_name']}} {{$call_reminder_lead['student_last_name']}}</td>'+
											'<td>{{$call_reminder_lead['course_name']}}</td>'+
											'<td><a href="tel:{{$call_reminder_lead['student_mobile']}}">{{$call_reminder_lead['student_mobile']}}</a></td>'+
											'<td>{{$call_reminder_lead['reminder_date']}} - {{$call_reminder_lead['reminder_hour']}} </td>'+
											'<td><a href="{{url('leadcrud').'?lead_id_request='.$call_reminder_lead['lead_id']}}" class="btn bg-lightblue btn-xs"><i class="fad fa-edit"></i> Gestionar</a></li></td>'+
										'</tr>'+
									@endforeach
								'</tbody>'+
							'</table>'+
						'</div>'+
					'</div>'+
					'</form>',
					buttons: {
						heyThere: {
							text: 'Cerrar', // text for button
							btnClass: 'bg-lightblue ', // class for the button
							action: function(heyThereButton){
							
							}
						},
					},
					onContentReady: function () {
						const table = $('.table-reminder').DataTable({
							dom: 'frtip',
							language: {
								search: "<strong>Buscar:</strong>",
									lengthMenu: "<strong>Mostrar _MENU_ Registros por página&nbsp;&nbsp;</strong>",
									zeroRecords: "<strong>No se ha encontrado ningún registro</strong>",
									info: "<strong>Mostrando la página _PAGE_ de _PAGES_</strong>",
									infoEmpty: "<strong>No hay registros disponibles</strong>",
									infoFiltered: "<strong>(filtrado de los registros totales de _MAX_)</strong>",
									processing: "Procesando",
									paginate: {
										first: "<strong>Primero</strong>",
										last: "<strong>Último</strong>",
										next: "<strong>Siguiente</strong>",
										previous: "<strong>Anterior</strong>"
									}
							},
						});
					},
				});
			}
		@endif
		//Las alertas de volver a llamar y respuesta aplazada antiguas
		@if(empty($call_reminder_leads_old))
			// whatever you need to do here
		@else 
			if(window.screen.width >= 768){
				$.confirm({
					columnClass: 'xlarge',
					icon: 'fad fa-bell-exclamation',
					type: 'blue',
					title: '<div style="text-align:center;"><b class="text-lightblue">Recordatorio LEADS</b></div>',
					content: '' +
					'<form action="" class="formName">' +
						'<div class="card">'+
							'<div class="card-header with-border">'+
								'<h4 class="card-title text-red"><i class="icon fa fa-warning fa-lg"></i> Los siguientes alumnos no han sido contactados en la fecha de actuación marcada, por favor gestionar</h4>'+
							'</div>'+
							'<div class="card-body text-left">'+
								'<div class="scroll">'+
									'<table  id="reminder_call_old" class="small-table stripe row-border cell-border order-column compact table table-stripe table-reminder-old" style="width:100%">'+
										'<thead>'+
											'<tr>'+
												'<th>#</th>'+
												'<th>Estado</th>'+
												'<th>Sub Estado</th>'+
												'<th>Alumno</th>'+
												'<th>Curso</th>'+
												'<th>Télefono</th>'+
												'<th>Email</th>'+
												'<th>Recordatorio llamada</th>'+
												'<th>Acciones</th>'+
											'</tr>'+
										'</thead>'+
										'<tbody>'+
											@foreach($call_reminder_leads_old as $call_reminder_lead_old)
												'<tr>'+
													'<td>{{(int)$loop->index + 1}}</td>'+
													'<td><span class="badge text-sm {{$call_reminder_lead_old['bg_color_class']}}">{{$call_reminder_lead_old['lead_status_name']}}</span></td>'+
													'<td><span class="badge text-sm {{$call_reminder_lead_old['sub_status_color']}}">{{$call_reminder_lead_old['lead_sub_status_name']}}</span></td>'+
													'<td>{{$call_reminder_lead_old['student_first_name']}} {{$call_reminder_lead_old['student_last_name']}}</td>'+
													'<td>{{$call_reminder_lead_old['course_name']}}</td>'+
													'<td><a href="tel:{{$call_reminder_lead_old['student_mobile']}}">{{$call_reminder_lead_old['student_mobile']}}</a></td>'+
													'<td><a href="mailto:{{$call_reminder_lead_old['student_email']}}">{{$call_reminder_lead_old['student_email']}}</a></td>'+
													'<td>{{$call_reminder_lead_old['reminder_date']}} - {{$call_reminder_lead_old['reminder_hour']}} </td>'+
													'<td><a href="{{url('leadcrud')}}?lead_id_request={{$call_reminder_lead_old['lead_id']}}" class="btn bg-lightblue btn-xs"><i class="fad fa-edit"></i> Gestionar</a></li></td>'+
												'</tr>'+
											@endforeach
										'</tbody>'+
									'</table>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</form>',
					buttons: {
						heyThere: {
							text: 'Cerrar', // text for button
							btnClass: 'bg-lightblue ', // class for the button
							action: function(heyThereButton){
							
							}
						},
					},
					onContentReady: function () {
						const table_old = $('.table-reminder-old').DataTable({
							dom: 'frtip',
							language: {
								search: "<strong>Buscar:</strong>",
									lengthMenu: "<strong>Mostrar _MENU_ Registros por página&nbsp;&nbsp;</strong>",
									zeroRecords: "<strong>No se ha encontrado ningún registro</strong>",
									info: "<strong>Mostrando la página _PAGE_ de _PAGES_</strong>",
									infoEmpty: "<strong>No hay registros disponibles</strong>",
									infoFiltered: "<strong>(filtrado de los registros totales de _MAX_)</strong>",
									processing: "Procesando",
									paginate: {
										first: "<strong>Primero</strong>",
										last: "<strong>Último</strong>",
										next: "<strong>Siguiente</strong>",
										previous: "<strong>Anterior</strong>"
									}
							},
						});
					},
				});
			}
			
			//Se quita la restricción mientras se define la directriz 20/10/2020
			// $("#lead_status_filter").val([10,26]).trigger('change');
			// $('.data-table').DataTable().draw(true);
			// $("#dt_reminder_from").val('2019-01-01 00:00:00');
			// $("#dt_reminder_to").val(moment().subtract(1, 'months').format('YYYY-MM-DD HH:mm:ss'));
			// var dt_ini_reminder = moment($("#dt_reminder_from").val()).format('DD/MM/YYYY');
			// var dt_end_reminder = moment($("#dt_reminder_to").val()).format('DD/MM/YYYY');
			// $('#dt_reminder_filter').daterangepicker();
			// $('#dt_reminder_filter').data('daterangepicker').setStartDate(dt_ini_reminder);
			// $('#dt_reminder_filter').data('daterangepicker').setEndDate(dt_end_reminder);

		@endif
	}
    function format ( d ) {
        var dt_reception = moment(d.dt_reception).isValid()? moment(d.dt_reception).format('DD/MM/YYYY') : ' ';
        var hr_reception = moment(d.dt_reception).isValid()? moment(d.dt_reception).format('HH:mm:ss') : ' ';
        var dt_assignment = moment(d.dt_assignment).isValid()? moment(d.dt_assignment).format('DD/MM/YYYY') : ' ';
        var hr_assignment = moment(d.dt_assignment).isValid()? moment(d.dt_assignment).format('HH:mm:ss') : ' ';
        var dt_activation = moment(d.dt_activation).isValid()? moment(d.dt_activation).format('DD/MM/YYYY') : ' ';
        var hr_activation = moment(d.dt_activation).isValid()? moment(d.dt_activation).format('HH:mm:ss') : ' ';
        var dt_enrollment = moment(d.dt_enrollment).isValid()? moment(d.dt_enrollment).format('DD/MM/YYYY') : ' ';
        var hr_enrollment = moment(d.dt_enrollment).isValid()? moment(d.dt_enrollment).format('HH:mm:ss') : ' ';
        var dt_payment = moment(d.dt_payment).isValid()? moment(d.dt_payment).format('DD/MM/YYYY') : ' ';
        var dt_last_update = moment(d.dt_last_update).isValid()? moment(d.dt_last_update).format('DD/MM/YYYY HH:mm:ss') : ' ';
        var hr_payment = moment(d.dt_payment).isValid()? moment(d.dt_payment).format('HH:mm:ss') : ' ';
        let prev_agent_name = (d.prev_agent_name ==null) ? ' ' : d.prev_agent_name;
        let observations = (d.observations ==null) ? ' ' : d.observations;
		let int_observ = (d.int_observ ==null) ? ' ' : d.int_observ;
        let format;
        format = `
				<div class="row" style="margin:auto">
					<div class="col-sm-1" align="center">
						<i class="fad fa-level-up fa-rotate-90 fa-3x text-lightblue"></i>
					</div>
					<div class="card card-lightblue bg-transparent card-outline col-sm-11" style="background-color:#1e91ff42!important;">
						<div class="card-header pl-3 pr-3 pt-2 pb-0">
							<h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Detalle del Lead</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table " cellspacing="0" width="100%">
											<tr class="bg-lightblue">
												<td><b>E-Mail: </b>`+ d.student_email+`</td>
												<td><b>País: </b>`+ d.country_name +`</td>
												<td><b>Fecha de última modificación: </b>`+ dt_last_update +`</td>
												<td><b>Fecha de contrato: </b>`+ dt_enrollment +`</td>
												<td><b>Fecha primer pago: </b>`+ dt_payment +`</td>
											</tr>
											<tr>
															<td colspan="5" align="center"><b>OBSERVACIONES INTERESANTES</b></td>
														</tr> 
														<tr>
															<td colspan="5">`+ int_observ +`</td>
														</tr> `;
											@if(Auth::user()->hasRole('superadmin'))
												format +=  `<tr>
																<td colspan="2"><b>Fecha recepción: </b>`+ dt_reception +`</td>
																<td colspan="2"><b>Hora recepción: </b>`+ hr_reception +`</td>
																<td><b>Origen: </b>`+ d.lead_origins_name +`</td>
															</tr>
															<tr>
																<td colspan="5" align="center"><b>OBSERVACIONES</b></td>
															</tr> 
															<tr>
																<td colspan="5">`+ observations +`</td>
															</tr> `;
											@endif
										format +=  `
										</table>
									</div>
									<hr class="bg-white" style="border-top: 4px solid #ffffff;">
									<div class="row">
										<div class="col-sm-12">
											<h4><i class="fad fa-th-list text-bold text-lightblue text-lg"></i> Gestión del LEAD</h4>
										</div>
									</div>
									<div class="row" style="margin:auto;">
										<div class="col-sm-12">
											<a class="btn bg-lightblue btn-xs manageLead" data-container="body" data-object="`+d.crypt_lead_id+`" data-id="`+d.id+`"><i class="fad fa-plus fa-xs"></i> Añadir Nota de gestión</a>
											<div class="table-responsive">
												<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table data-table_`+d.id+`" cellspacing="0" width="100%">
													<thead>
														<tr class="bg-lightblue">
															<th>Fecha Gestión</th>
															<th>Fecha Actuación</th>
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
						</div>
					</div>
				</div>
				`;
        return format;
	}
	
	//Para mostrar el filtro de sub estado
	$('#lead_status_filter').change(function(){
            var status_id = ($(this).val());
            // console.log(status_id.includes(4));
            if(status_id.includes('13') || status_id.includes('5') || status_id.includes('3') || status_id.includes('8') || status_id.includes('7') || status_id.includes('2')){
                $('#div_lead_sub_status_filter').show();
            }else{
                $('#div_lead_sub_status_filter').hide();
            }
            $.get("{{url('api/substatus')}}", 
            {option: $(this).val()}, 
            function(data) {    
                var lead_sub_status_filter = $('#lead_sub_status_filter');
                lead_sub_status_filter.empty();
                // console.log(data);
                $.each(data, function(index, element) {
                    lead_sub_status_filter.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                });
                
            });
        });

	var SITEURL = "{{url('/')}}";
	
	// Construye la datatable principal de LEADS
	const rows_selected = [];
	const table = $('.data-table').DataTable({
		bAutoWidth: false,
		dom: 'lBfrtip',
		processing: true,
		serverSide: true,
		pagingType: 'full_numbers',
		StateSave: true,
		ajax: {
			url:  "{{ route('leadcrud.index') }}",
			type: 'GET',
			data: function (data) {
			for (var i = 0, len = data.columns.length; i < len; i++) {
				if (! data.columns[i].search.value) delete data.columns[i].search;
				if (data.columns[i].searchable === true) delete data.columns[i].searchable;
				if (data.columns[i].orderable === true) delete data.columns[i].orderable;
				if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
			}
			data.filter_lead_id = $("#filter_lead_id").val();
			data.dt_assignment_from = $("#dt_assignment_from").val();
			data.dt_assignment_to = $("#dt_assignment_to").val();
			data.agent_list_filter = $("#agent_list_filter").val();
			data.provinces_list_filter = $("#provinces_list_filter").val();
			data.prev_agent_list_filter = $("#prev_agent_list_filter").val();
			data.lead_type_list_filter = $("#lead_type_list_filter").val();
			data.lead_sub_status_filter = $("#lead_sub_status_filter").val();
			data.lead_origin_list_filter = $("#lead_origin_filter").val();
			data.lead_status_filter = $("#lead_status_filter").val();
			data.courses_list_filter = $("#courses_list_filter").val();
			delete data.search.regex;
			}
		},
		lengthMenu: [
			[ 25,10, 50, 100, 1000],
			[ '25 registros','10 registros','50 registros', '100 registros',  '1000 registros' ]
		],
		buttons: [
			{
				title: '',
				text:  '<i class="fad fa-user-plus fa-2x" onClick="addLead()" data-ask="2" data-toggle="tooltip"  title="Crear Lead"></i> ',
				className: 'btn btn-secondary bg-lightblue hidden mr-2 p-1 rounded-right',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				}
			},
			@if (Auth::user()->hasRole('superadmin'))
				{
					title: '',
					text:  '<i class="fad fa-poll-people fa-2x" data-ask="2" data-toggle="tooltip"  title="Asignación de Leads"></i> ',
					className: 'btn btn-secondary massiveAssign bg-lightblue p-1 hidden mr-2 rounded-right rounded-left',
					init: function(api, node, config) {
						$(node).removeClass('dt-button')
					}
				},
			@endif
			
			{
				title: '',
				text:  '<i class="fad fa-bell-exclamation fa-2x" onClick="callReminders()" data-ask="2" data-toggle="tooltip"  title="Recordatorios"></i> ',
				className: 'btn btn-secondary bg-lightblue p-1 hidden mr-2 rounded-right rounded-left',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				}
			},
			{
				extend: 'excel',
				title: '',
				filename: 'Leads',
				text:  '<i class="fad fa-file-excel fa-2x" data-ask="2" data-toggle="tooltip"  title="Descargar Excel"></i> ',
				className: 'btn btn-secondary bg-lightblue hidden mr-2 p-1 rounded',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				},
				exportOptions: {
					// header: false,.
					columns: '.printable'
				}
			},
			{
				extend: 'pdf',
				filename: 'Leads',
				orientation: 'landscape',
				text:  '<i class="fad fa-file-pdf fa-2x " data-ask="2"  data-toggle="tooltip"   title="Descargar PDF"></i> ',
				className: 'rounded-left btn btn-secondary bg-lightblue  p-1 hidden',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				},
				exportOptions: {
					columns: '.printable'
				}
			}
		],
		order: [[ 9, 'desc' ]],
		language: {
			search: "<strong>Buscar:</strong>",
				lengthMenu: "<strong>Mostrar _MENU_ Registros por página&nbsp;&nbsp;</strong>",
				zeroRecords: "<strong>No se ha encontrado ningún registro</strong>",
				info: "<strong>Mostrando la página _PAGE_ de _PAGES_</strong>",
				infoEmpty: "<strong>No hay registros disponibles</strong>",
				infoFiltered: "<strong>(filtrado de los registros totales de _MAX_)</strong>",
				processing: "Procesando",
				paginate: {
					first: "<strong>Primero</strong>",
					last: "<strong>Último</strong>",
					next: "<strong>Siguiente</strong>",
					previous: "<strong>Anterior</strong>"
				}
		},
		select: {
			style: 'multi',
			selector: 'td:first-child'
		},
		columns: [
			{ data: 'id', name: 'le.id'},
			{ data: null, searchable: false, orderable: false,width: '1%',className: 'details-control dt-body-center',
                  defaultContent: '<i class = "fad text-lightblue fa-lg fa-plus" title="Ver Detalle"></i>'},
			{ data: null, searchable: false, orderable: false,width: '1%',className: 'details-control dt-body-center'},
			{ data: 'course_name', name: 'crse.name'},
			{ data: 'lead_origins_name', name: 'lo.name'},
			{ data: 'full_name', name: 'full_name'},
			{ data: 'student_email', name: 'le.student_email'},
			{ data: 'student_mobile', name: 'le.student_mobile'},
			{ data: 'province_name', name: 'prov.name'},
			{ data: 'dt_assignment', name: 'le.dt_assignment'},
			{ data: 'dt_last_update', name: 'le.dt_last_update'},
			{ data: 'lead_status_name', name: 'ls.name'},
			{ data: 'lead_sub_status_name', name: 'lss.name'},
			{ data: 'agent_name', name: 'us.name'},
			{ data: 'acciones', name: 'acciones', orderable: false, searchable: false},  
			{ data: 'student_first_name', name: 'le.student_first_name',visible:false, searchable:true},    
			{ data: 'student_last_name', name: 'le.student_last_name',visible:false, searchable:true}
			],
		columnDefs: [
			{
				targets: 0,
				searchable: false,
				orderable: false,
				width: '1%',
				className: 'dt-body-center',
				render: function (data, type, full, meta){
					return '<input type="checkbox">';
				}
			},
			{
				targets: [5],
				searchable: false
			},
			{
				targets: [3,5,6,7,8,9,10,11,12],
				className: 'dt-body-center printable'
			},
			{
				render: function ( data, type, row ) {
					return data ? moment(data).format("DD/MM/YYYY HH:mm") : " ";
				}, targets: [9,10]
			},
			{
				render: function ( data, type, row ) {
					return '<a href="tel:'+data+'">'+data+'</a>' ;
				}, targets: [7]
			},
			// {
			// 	render: function ( data, type, row ) {
			// 		return '<span class="badge badge-pill text-sm '+row['bg_color']+'">'+data+'</span>';
			// 	}, targets: [11]
			// },
			{
				render: function ( data, type, row ) {
					return '<span class="text-center d-block "><i class="fad text-lightblue fa-lg fa-id-badge"></i> '+data+'</span>';
				}, targets: [13]
			},
			{
				render: function ( data, type, row ) {
					// console.log(row);
					if(row['lead_status_id']==3|| row['lead_status_id']==5
					|| row['lead_status_id']==6|| row['lead_status_id']==8
					|| row['lead_status_id']==13 || row['lead_status_id']==14){
						return '<span class="badge d-block text-xs text-center '+row['bg_color']+'"><b>'+data+'</b>'
								+'<br> Día: ' + moment(row['dt_call_reminder']).format("DD/MM/YYYY")
								+'<br> Hora: ' + moment(row['dt_call_reminder']).format("HH:mm:ss")
								+'</span>';
					}
					return '<span class="badge d-block text-xs '+row['bg_color']+'">'+data+'</span>';
				}, targets: [11]
			},
			{
				render: function ( data, type, row ) {
					if(row['lead_sub_status_id'] == null || row['lead_sub_status_id'] == 9 || row['lead_sub_status_id'] == 10
					|| row['lead_sub_status_id'] == 11 || row['lead_sub_status_id'] == 12 || row['lead_sub_status_id'] == 13
					|| row['lead_sub_status_id'] == 14){
						return '';
					}
					return '<span class="badge d-block text-xs '+row['bg_color_sub']+'">'+data+'</span>';
				}, targets: [12]
			},
				
		],
		rowCallback: function(row, data, dataIndex){
			// Get row ID
			var rowId = data['id'];
			if($.inArray(rowId, rows_selected) !== -1){
				$(row).find('input[type="checkbox"]').prop('checked', true);
				$(row).addClass('selected');
			}
		},
		createdRow: function( row, data, dataIndex ) {
			@if(Auth::user()->hasRole('superadmin'))
				$(row).find('td:eq(4)').addClass('printable');
			@endif
		},
		drawCallback: function( settings ) {
			var column4 = table.column(4);
			@if(Auth::user()->hasRole('comercial'))
				column4.visible(false);
			@endif
		},
	});

	table.on( 'draw.dt', function () {
		var PageInfo = table.page.info();
		table.column(2, { page: 'current' }).nodes().each( function (cell, i) {
			cell.innerHTML = i + 1 + PageInfo.start;
		} );
	} );
	
	// Handle click on checkbox
	$('.data-table tbody').on('click', 'input[type="checkbox"]', function(e){
		// console.log("check");
		var $row = $(this).closest('tr');
		// Get row data
		var data = table.row($row).data()['crypt_lead_id'];
		// Get row ID
		var rowId = data;
		// console.log(data);
		// Determine whether row ID is in the list of selected row IDs
		var index = $.inArray(rowId, rows_selected);
		// If checkbox is checked and row ID is not in list of selected row IDs
		if(this.checked && index === -1){
			rows_selected.push(rowId);
		// Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
		} else if (!this.checked && index !== -1){
			rows_selected.splice(index, 1);
		}
		if(this.checked){
			$row.addClass('selected');
		} else {
			$row.removeClass('selected');
		}
		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(table);
		// Prevent click event from propagating to parent
		e.stopPropagation();
	});

	// Handle click on table cells with checkboxes
	$('.data-table').on('click', 'td:first-child, th:first-child', function(e){
		$(this).parent().find('input[type="checkbox"]').trigger('click');
	});

	// Handle click on "Select all" control
	$('thead input[name="select_all"]', table.table().container()).on('click', function(e){
		if(this.checked){
			$('.data-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
		} else {
			$('.data-table tbody input[type="checkbox"]:checked').trigger('click');
		}

		// Prevent click event from propagating to parent
		e.stopPropagation();
	});

	// Handle table draw event
	table.on('draw', function(){
		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(table);
		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		});
	});

	//Detalle
	$('.data-table tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = table.row( tr );
		var i = $(this).find('i');
		
		if ( row.child.isShown() ) {
			// This row is already open - close it
			row.child.hide();
			tr.removeClass('shown');
			i.removeClass('fa-minus');
			i.addClass('fa-plus');
		}
		else {
			// Open this row
			row.child( format(row.data()) ).show();
			tr.addClass('shown');
			i.removeClass('fa-plus');
			i.addClass('fa-minus');
		}
		const lead_id_to_note = row.data().crypt_lead_id;
		const table_notes = $(".data-table_"+row.data().id).DataTable({                
			destroy:true,
			processing: true,
			serverSide: true,
			ajax:  "{{ url('leads/notes/') }}"+"/"+lead_id_to_note,
			dom: 'lrtip',
			lengthMenu: [
				[ 10, 25, 50, -1 ],
				[ '10 registros', '25 registros', '50 registros', 'Mostrar todo' ]
			],
			order: [[ 0, 'desc' ]],
			language: {
				search: "Buscar:",
					lengthMenu: "Mostrar _MENU_ registros por página",
					zeroRecords: "No se ha encontrado ningún registro",
					info: "Mostrando la página _PAGE_ de _PAGES_",
					infoEmpty: "No hay registros disponibles",
					infoFiltered: "(filtrado de los registros totales de _MAX_)",
					processing: "Procesando",
					paginate: {
						first: "Primero",
						last: "Último",
						next: "Siguiente",
						previous: "Anterior"
					}
			},
			columns: [
				{ data: 'created_at', name: 'le.created_at'},
				{ data: 'dt_call_reminder', name: 'le.dt_call_reminder'},
				{ data: 'sent_method', name: 'le.sent_method'},
				{ data: 'status', name: 'status'},
				{ data: 'sub_status', name: 'sub_status'},
				{ data: 'observation', name: 'le.observation'},
				{ data: 'user_note_name', name: 'user_note_name'},
				{ data: 'id', name: 'id'},
				],
			columnDefs: [
				{
					"render": function ( data, type, row ) {
						// var text = parseInt(data)  ? data : " ";
						// console.log(data);
						return moment(data).format("DD/MM/YYYY HH:mm");
					},"targets": [0,1]
				},
				// {
				// 	"render": function ( data, type, row ) {
				// 		// var text = parseInt(data)  ? data : " ";
				// 		// console.log(data);
				// 		return moment(data).format("HH:mm");
				// 	}, "targets": 1
				// },
				{ targets: 6, visible: false},
				{
					targets: 7,
					visible:false,
					searchable:false
				}
			],
			drawCallback: function( settings ) {
				var column = table_notes.column(6);
				@if(Auth::user()->hasRole('superadmin'))
					column.visible(true);
				@endif
			},
			initComplete: function (settings, json) {  
				$(".data-table_"+row.data().id).wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");            
			},
		}).responsive.recalc();
		table_notes.responsive.recalc();
	});

	//Acciones Filtros
	$('input[name="ranges"]').daterangepicker({
		opens: 'left',
		timePicker: true,
		timePicker24Hour: true,
		locale: {
			"format": "DD/MM/YYYY HH:mm",  
			"timeFormat":'H:i',
			"separator": " - ",
			"applyLabel": "Aplicar",
			"cancelLabel": "Limpiar",
			"fromLabel": "From",
			"toLabel": "To",
			"customRangeLabel": "Personalizado",
			"daysOfWeek": [ "Do","Lu", "Ma","Mi", "Ju","Vi","Sa"],
			"monthNames": ["Enero", "Febrero", "Marzo","Abril","Mayo", "Junio", "Julio","Agosto","Septiembre", "Octubre","Noviembre", "Diciembre"],
			// "firstDay": 1
		}  
	}, function(start, end, label) {
		$("#dt_assignment_from").val(start.format('YYYY-MM-DD HH:mm:ss'));
		$("#dt_assignment_to").val(end.format('YYYY-MM-DD HH:mm:ss'));
		// console.log("A new date selection was made: " + start.format('YYYY-MM-DD HH:mm:ss') + ' to ' + end.format('YYYY-MM-DD HH:mm:ss'));
	}); 
	
	//Limpiar filtros
	$('#btnCleanLeadID').click(function(){
		$(".select_list_filter").val([]).trigger("change");
		$("#dt_reception_filter").val('');
		$("#dt_assignment_from").val('');
		$("#dt_assignment_to").val('');
		$('#filter_lead_id').val("");
	});

	$('#btnFiterSubmitSearch').click(function(){
		// console.log($("#dt_assignment_from").val());
		$('.data-table').DataTable().draw(true);
	});

	//Refrescar Datatable
	$('#btnFiterRefresh').click(function(){
		$('#btnFiterRefresh').html('<span aria-hidden="true" role="status" class="spinner-border spinner-border-sm"></span>  Enviando');
		$('#btnFiterRefresh').addClass("disabled");
		$.when(
			$(".select_list_filter").val([]).trigger("change"),
			$("#dt_reception_filter").val(''),
			$("#dt_assignment_from").val(''),
			$("#dt_assignment_to").val(''),
			$('#filter_lead_id').val("")
		).done(function(){
			$('.data-table').DataTable().draw(true);
			$('#btnFiterRefresh').html('<i class="fad fa-sync"></i> Refrescar Info');
			$('#btnFiterRefresh').removeClass("disabled");
		});	
		
	});


    //se ejecuta cada minuto los leads refresco automatico
	// 2021-03-17 se comenta hasta nueva orden
	// setInterval(function(){ 
	// 	$('.data-table').DataTable().draw(true);//this code runs every second 
	// }, 60000);


	//Funciones del crud
	//Modal de crear Lead
	function addLead(){
		const fecha_asignacion = moment().format('YYYY-MM-DDTHH:mm:ss');
		$('#lead_dt_assignment_hidden').val(fecha_asignacion);
		$('#lead_dt_reception_hidden').val(fecha_asignacion);
		//Siempre es referido para el comercial
		$('#lead_origin_id_hidden').val(3);
		$('#lead_type_id_hidden').val(1);
		$('#lead_agent_id_hidden').val({{Auth::user()->id}});
		//Siempre es BBDD para el comercial
		//El estado es 4 cuando es nuevo
		$('#lead_status_id_hidden').val(1);
		$('#lead_status_id_hidden').val(null);
		$('#leadForm').trigger("reset");
		$('#leadsHeading').html("Crear Lead");
		$('#lead_id').val('');
		$(".select_list").val([]).trigger("change");
		$('#leadsModal').modal('show');
	}   

	

	// Modal de editar Lead
	$('body').on('click', '.editLead', function () {
    // $('#leadsModal').on('show.bs.modal', function (event) {
        // var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient =$(this).data('title') // Extract info from data-* attributes
        var lead_id = $(this).data('object') // Extract info from data-* attributes
        // var modal = $(this)
        // modal.find('.modal-title').text(recipient)
		$('#leadsHeading').html(recipient);
        $("#leadCreateOrUpdateError").html("")
        $('#leadForm').trigger("reset")
		$.ajax({
			type:"GET",
			url: "{{route('leadcrud.index') }}" +'/' + lead_id +'/edit',
			dataType: 'json',
			success: function(res){
				// console.log(res[0])
				$('#leadsHeading').html("Editar LEAD");
				$('#leadsModal').modal('show');
				$("#modal_courses_list").data('select2').trigger('select', {
					data: {"id": res[0].course_id}
				});
				$("#modal_provinces_list").data('select2').trigger('select', {
					data: {"id": res[0].province_id}
				});
				$("#countries_list").data('select2').trigger('select', {
					data: {"id": res[0].country_id}
				});
				$("#first_name").val(res[0].student_first_name);
				$("#last_name").val(res[0].student_last_name);
				$("#email").val(res[0].student_email);
				$("#mobile").val(res[0].student_mobile);
				$("#dt_birth").val(res[0].dt_birth);
				$("#observations").val(res[0].observations);
				$("#int_observ").val(res[0].int_observ);
				$("#lead_id").val(lead_id);
				$("#lead_dt_reception_hidden").val(moment(res[0].dt_reception).format('YYYY-MM-DDTHH:mm:ss'));
				$("#lead_dt_assignment_hidden").val(moment(res[0].dt_assignment).format('YYYY-MM-DDTHH:mm:ss'));
				$('#lead_agent_id_hidden').val(res[0].agent_id);
				$("#lead_status_id_hidden").val(res[0].lead_status_id);
				$("#lead_sub_status_id_hidden").val(res[0].lead_sub_status_id);
				$("#lead_origin_id_hidden").val(res[0].leads_origin_id);
				$("#lead_type_id_hidden").val(res[0].lead_type_id);
				// Si es administrador
				@if(Auth::user()->hasRole('superadmin'))
					// $('#dt_reception').attr('max', maxDate);
					$("#dt_reception_edit").val(moment(res[0].dt_reception).format('YYYY-MM-DDTHH:mm'));
					$("#dt_assignment_edit").val(moment(res[0].dt_assignment).format('YYYY-MM-DDTHH:mm'));
					$("#leads_origins_list").data('select2').trigger('select', {
						data: {"id": res[0].leads_origin_id}
					});
					$("#agents_list").data('select2').trigger('select', {
							data: {"id": res[0].agent_id}
					});
				@endif
			}
		});

    });

	//Eliminar Lead
	function deleteFunc(id){
	if (confirm("Delete Record?") == true) {
		var id = id;
		// ajax
		$.ajax({
		type:"POST",
			url: "{{ url('delete-company') }}",
			data: { id: id },
			dataType: 'json',
			success: function(res){
				var oTable = $('#ajax-crud-datatable').dataTable();
				oTable.fnDraw(false);
				}
			});
		}
	}

	//Guardar Cambios
	$('#leadForm').submit(function(e) {
		$("#leadCreateOrUpdateError").html("");
		e.preventDefault();
		$('#saveLeadBtn').html('<span aria-hidden="true" role="status" class="spinner-border spinner-border-sm"></span>  Enviando');
		$('#saveLeadBtn').addClass("disabled");
		var formData = new FormData($('#leadForm')[0]);
		$.ajax({
			type:'POST',
			url: "{{ route('leadcrud.store') }}",
			data: formData, 
			dataType: 'json',
			cache:false,
			contentType: false,
			processData: false,
			success: function (data) {
				$(".text-danger").text("");
				if($.isEmptyObject(data.error)){
					$('#leadForm').trigger("reset");
					$(".select_list").val([]).trigger("change");
					$('#leadsModal').modal('hide');
					$('#saveLeadBtn').html('Guardar Cambios');
					$('#saveLeadBtn').removeClass("disabled");
					table.draw(false);
				}else{
					$.each( data.error, function( key, value ) {
						$("#leadCreateOrUpdateError").append('<li><label class="text-red">'+value+'</label></li>');
					});
					$('#saveLeadBtn').html('Guardar Cambios');
					$('#saveLeadBtn').removeClass("disabled");
				}
			}
		});
	});
	//Funciones del crud

    //Funciones para notas de lead
	
	//Funciones para el calendar
	
	//Fin funciones Calendar

    $('.bnk_checkbox').on('click', 'tr', function () {
        var id = this.id;
        var index = $.inArray(id, selected);

        if ( index === -1 ) {
            selected.push( id );
        } else {
            selected.splice( index, 1 );
        }
        $(this).toggleClass('selected');
    } );

	//Las alertas de volver a llamar
	@if(empty($call_reminder_leads))
		// whatever you need to do here
	@else 
		@if(!isset($_GET['lead_id_request']))
			if(window.screen.width >= 768){
				// document.getElementById("my_audio").play();
				$.confirm({
					columnClass: 'xlarge',
					icon: 'fad fa-bell-exclamation',
					type: 'blue',
					title: '<div style="text-align:center;"><b class="text-lightblue">Recordatorio LEADS</b></div>',
					content: '' +
					'<form action="" class="formName">' +
					'<div class="box box-solid">'+
						'<div class="box-header with-border">'+
							'<h4 class="box-title">Los siguientes alumnos están señalados para seguimiento</h4>'+
						'</div>'+
						'<div class="box-body text-left">'+
							'<table id="reminder_call" class="small-table table-sm table-border cell-border order-column compact table-reminder" style="width:100%">'+
								'<thead>'+
									'<tr>'+
										'<th>#</th>'+
										'<th>Estado</th>'+
										'<th>Sub Estado</th>'+
										'<th>Alumno</th>'+
										'<th>Curso</th>'+
										'<th>Télefono</th>'+
										'<th>Recordatorio llamada</th>'+
										'<th>Acciones</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody>'+
									@foreach($call_reminder_leads as $call_reminder_lead)
										'<tr>'+
											'<td>{{(int)$loop->index + 1}}</td>'+
											'<td><span class="badge text-sm {{$call_reminder_lead['bg_color_class']}}">{{$call_reminder_lead['lead_status_name']}}</span></td>'+
											'<td><span class="badge text-sm {{$call_reminder_lead['sub_status_color']}}">{{$call_reminder_lead['lead_sub_status_name']}}</span></td>'+
											'<td>{{$call_reminder_lead['student_first_name']}} {{$call_reminder_lead['student_last_name']}}</td>'+
											'<td>{{$call_reminder_lead['course_name']}}</td>'+
											'<td><a href="tel:{{$call_reminder_lead['student_mobile']}}">{{$call_reminder_lead['student_mobile']}}</a></td>'+
											'<td>{{$call_reminder_lead['reminder_date']}} - {{$call_reminder_lead['reminder_hour']}} </td>'+
											'<td><a href="{{url('leadcrud').'?lead_id_request='.$call_reminder_lead['lead_id']}}" class="btn bg-lightblue btn-xs"><i class="fad fa-edit"></i> Gestionar</a></li></td>'+
										'</tr>'+
									@endforeach
								'</tbody>'+
							'</table>'+
						'</div>'+
					'</div>'+
					'</form>',
					buttons: {
						heyThere: {
							text: 'Cerrar', // text for button
							btnClass: 'bg-lightblue ', // class for the button
							action: function(heyThereButton){
							
							}
						},
					},
					onContentReady: function () {
						const table = $('.table-reminder').DataTable({
							dom: 'frtip',
							language: {
								search: "<strong>Buscar:</strong>",
									lengthMenu: "<strong>Mostrar _MENU_ Registros por página&nbsp;&nbsp;</strong>",
									zeroRecords: "<strong>No se ha encontrado ningún registro</strong>",
									info: "<strong>Mostrando la página _PAGE_ de _PAGES_</strong>",
									infoEmpty: "<strong>No hay registros disponibles</strong>",
									infoFiltered: "<strong>(filtrado de los registros totales de _MAX_)</strong>",
									processing: "Procesando",
									paginate: {
										first: "<strong>Primero</strong>",
										last: "<strong>Último</strong>",
										next: "<strong>Siguiente</strong>",
										previous: "<strong>Anterior</strong>"
									}
							},
						});
					},
				});
			}
		@endif
	@endif
	//Las alertas de volver a llamar y respuesta aplazada antiguas
	@if(empty($call_reminder_leads_old))
		// whatever you need to do here
	@else 
		@if(!isset($_GET['lead_id_request']))
			if(window.screen.width >= 768){
				// var audioElement = document.createElement('audio');
				// audioElement.setAttribute('src', "{{asset('storage/uploads/notification.mp3')}}");				
				// audioElement.addEventListener('ended', function() {
				// 	this.play();
				// }, false);
				// audioElement.autoplay;
				$.confirm({
					columnClass: 'xlarge',
					icon: 'fad fa-bell-exclamation',
					type: 'blue',
					title: '<div style="text-align:center;"><b class="text-lightblue">Recordatorio LEADS</b></div>',
					content: '' +
					'<form action="" class="formName">' +
						'<div class="box box-solid">'+
							'<div class="box-header with-border">'+
								'<h4 class="box-title text-red"><i class="icon fa fa-warning fa-lg"></i> Los siguientes alumnos no han sido contactados en la fecha de actuación marcada, por favor gestionar</h4>'+
							'</div>'+
							'<div class="box-body text-left">'+
								'<div class="scroll">'+
									'<table  id="reminder_call_old" class="small-table stripe row-border cell-border order-column compact table table-stripe table-reminder-old" style="width:100%">'+
										'<thead>'+
											'<tr>'+
												'<th>#</th>'+
												'<th>Estado</th>'+
												'<th>Sub Estado</th>'+
												'<th>Alumno</th>'+
												'<th>Curso</th>'+
												'<th>Télefono</th>'+
												'<th>Email</th>'+
												'<th>Recordatorio llamada</th>'+
												'<th>Acciones</th>'+
											'</tr>'+
										'</thead>'+
										'<tbody>'+
											@foreach($call_reminder_leads_old as $call_reminder_lead_old)
												'<tr>'+
													'<td>{{(int)$loop->index + 1}}</td>'+
													'<td><span class="badge text-sm {{$call_reminder_lead_old['bg_color_class']}}">{{$call_reminder_lead_old['lead_status_name']}}</span></td>'+
													'<td><span class="badge text-sm {{$call_reminder_lead_old['sub_status_color']}}">{{$call_reminder_lead_old['lead_sub_status_name']}}</span></td>'+
													'<td>{{$call_reminder_lead_old['student_first_name']}} {{$call_reminder_lead_old['student_last_name']}}</td>'+
													'<td>{{$call_reminder_lead_old['course_name']}}</td>'+
													'<td><a href="tel:{{$call_reminder_lead_old['student_mobile']}}">{{$call_reminder_lead_old['student_mobile']}}</a></td>'+
													'<td><a href="mailto:{{$call_reminder_lead_old['student_email']}}">{{$call_reminder_lead_old['student_email']}}</a></td>'+
													'<td>{{$call_reminder_lead_old['reminder_date']}} - {{$call_reminder_lead_old['reminder_hour']}} </td>'+
													'<td><a href="{{url('leadcrud')}}?lead_id_request={{$call_reminder_lead_old['lead_id']}}" class="btn bg-lightblue btn-xs"><i class="fad fa-edit"></i> Gestionar</a></li></td>'+
												'</tr>'+
											@endforeach
										'</tbody>'+
									'</table>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</form>',
					buttons: {
						heyThere: {
							text: 'Cerrar', // text for button
							btnClass: 'bg-lightblue ', // class for the button
							action: function(heyThereButton){
							
							}
						},
					},
					onContentReady: function () {
						const table_old = $('.table-reminder-old').DataTable({
							dom: 'frtip',
							language: {
								search: "<strong>Buscar:</strong>",
									lengthMenu: "<strong>Mostrar _MENU_ Registros por página&nbsp;&nbsp;</strong>",
									zeroRecords: "<strong>No se ha encontrado ningún registro</strong>",
									info: "<strong>Mostrando la página _PAGE_ de _PAGES_</strong>",
									infoEmpty: "<strong>No hay registros disponibles</strong>",
									infoFiltered: "<strong>(filtrado de los registros totales de _MAX_)</strong>",
									processing: "Procesando",
									paginate: {
										first: "<strong>Primero</strong>",
										last: "<strong>Último</strong>",
										next: "<strong>Siguiente</strong>",
										previous: "<strong>Anterior</strong>"
									}
							},
						});
					},
				});
			}
		@endif
		
		
		//Se quita la restricción mientras se define la directriz 20/10/2020
		// $("#lead_status_filter").val([10,26]).trigger('change');
		// $('.data-table').DataTable().draw(true);
		// $("#dt_reminder_from").val('2019-01-01 00:00:00');
		// $("#dt_reminder_to").val(moment().subtract(1, 'months').format('YYYY-MM-DD HH:mm:ss'));
		// var dt_ini_reminder = moment($("#dt_reminder_from").val()).format('DD/MM/YYYY');
		// var dt_end_reminder = moment($("#dt_reminder_to").val()).format('DD/MM/YYYY');
		// $('#dt_reminder_filter').daterangepicker();
		// $('#dt_reminder_filter').data('daterangepicker').setStartDate(dt_ini_reminder);
		// $('#dt_reminder_filter').data('daterangepicker').setEndDate(dt_end_reminder);

	@endif
	
</script>>