<script>
    function format ( d ) {
        //Organizamos las 4 fechas
    // `d` is the original data object for the row
        let format;
		format = `
				<div class="row" style="margin:auto;">
					<div class="col-sm-1" align="center">
						<i class="fad fa-level-up fa-rotate-90 fa-3x text-lightblue"></i>
					</div>
					<div class="card card-lightblue bg-transparent card-outline col-sm-11" style="background-color:#1e91ff42!important;">
						<div class="card-header pl-3 pr-3 pt-2 pb-0">
							<h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Detalle de la Matrícula</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table " cellspacing="0" width="100%">
											<tr class="bg-lightblue">
												<td><b>Material: </b>`+ ((d.material_name == null)? ' ' : d.material_name)+`</td>
												<td><b>E-Mail: </b>`+ d.mail+`</td>
												<td><b>Móvil: </b><a style="color:white;" href="tel:`+ d.mobile +`">`+ d.mobile +`</a></td>
												<td><b>Método de Pago: </b>`+ d.payment_method +`</td>
												<td><b>Domicilio: </b>`+ d.student_address +`</td>
											</tr>
											<tr class="bg-lightblue">
												<td><b>Fecha Visualización Estudiante: </b>`+  (d.dt_opening ? moment(d.dt_opening).format('DD/MM/YYYY HH:mm'): '')+`</td>
												<td><b>Fecha Visualización Pagador: </b>`+  (d.dt_opening_payer ? moment(d.dt_opening_payer).format('DD/MM/YYYY HH:mm') : '')+`</td>
												<td><b>Fecha Firma Estudiante: </b>`+  (d.dt_approved ? moment(d.dt_approved).format('DD/MM/YYYY HH:mm') : '')+`</td>
												<td><b>Fecha Firma Pagador: </b>`+  (d.dt_approved_payer ? moment(d.dt_approved_payer).format('DD/MM/YYYY HH:mm'): '')+`</td>
												<td></td>
											</tr>
										</table>
									</div>
									<hr class="bg-white" style="border-top: 4px solid #ffffff;">
									<div class="row">
										<div class="col-sm-12">
											<h4><i class="fad fa-th-list text-bold text-lightblue text-lg"></i> Gestión de la Matrícula</h4>
										</div>
									</div>
									<div class="row" style="margin:auto;">
										<div class="col-sm-12">
											<a class="btn bg-lightblue btn-xs managementNote" data-container="body" data-object="`+d.crypt_case_id+`" data-id="`+d.id+`"><i class="fad fa-plus fa-xs"></i> Añadir Nota de gestión</a>
											<div class="table-responsive">
												<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table data-table_`+d.id+`" cellspacing="0" width="100%">
													<thead>
														<tr class="bg-lightblue">
															<th>Fecha Gestión</th>
                                                			<th>Fecha Actuación</th>
															<th>Medio de contacto</th>
															<th>Tipo</th>
															<th>Categoría</th>
															<th>Observaciones</th>
															<th>Usuario</th>
															<th>Soporte</th>
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
	
	var SITEURL = "{{url('/')}}";
	
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
            $("#dt_contract_from").val(start.format('YYYY-MM-DD HH:mm:ss'));
            $("#dt_contract_to").val(end.format('YYYY-MM-DD HH:mm:ss'));
            // console.log("A new date selection was made: " + start.format('YYYY-MM-DD HH:mm:ss') + ' to ' + end.format('YYYY-MM-DD HH:mm:ss'));
        }); 

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
			url:  "{{ route('contractcrud.index') }}",
			type: 'GET',
			data: function (data) {
			for (var i = 0, len = data.columns.length; i < len; i++) {
				if (! data.columns[i].search.value) delete data.columns[i].search;
				if (data.columns[i].searchable === true) delete data.columns[i].searchable;
				if (data.columns[i].orderable === true) delete data.columns[i].orderable;
				if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
			}
			data.filter_contract_id = $("#filter_contract_id").val();
			data.dt_contract_from = $("#dt_contract_from").val();
			data.dt_contract_to = $("#dt_contract_to").val();
			data.agent_list_filter = $("#agent_list_filter").val();
			data.contract_status_filter = $("#contract_status_filter").val();
			data.courses_list_filter = $("#courses_list_filter").val();
			data.contract_payment_type_list_filter = $("#contract_payment_type_list_filter").val();
			data.contract_method_type_list_filter = $("#contract_method_type_list_filter").val();
			data.contract_type_filter = $("#contract_type_filter").val();
			data.contract_province_filter = $("#contract_province_filter").val();
			delete data.search.regex;
			}
		},
		lengthMenu: [
			[ 25, 10, 50, 100,-1 ],
			[ '25 registros', '10 registros', '50 registros', '100 registros',  'Mostrar todo' ]
		],
		buttons: [
			{
				extend: 'excel',
				title: '',
				filename: 'Matrículas',
				text:  '<i class="fad fa-file-excel fa-2x" data-ask="2" data-toggle="tooltip"  title="Descargar Excel"></i> ',
				className: 'btn btn-secondary bg-lightblue hidden mr-2 p-1 rounded',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				},
				exportOptions: {
					// header: false,
					columns: [3,4,5,6,7,8,9,10,11,12,13,14,15]
				}
			},
			{
				extend: 'pdf',
				filename: 'Matrículas',
				orientation: 'landscape',
				text:  '<i class="fad fa-file-pdf p-0 fa-2x" data-ask="2"  data-toggle="tooltip"   title="Descargar PDF"></i> ',
				className: 'rounded-left btn btn-secondary bg-lightblue p-1  hidden',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				},
				exportOptions: {
					columns: [3,4,5,6,7,8,9,10,11,12,13,14,15]
				}
			}
		],
		order: [[ 13, 'desc' ]],
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
			{ data: 'enrollment_number', name: 'c.enrollment_number'},
			{ data: 'course_name', name: 'crse.name'},
			{ data: 'full_name', name: 'full_name', searchable:false},
			{ data: 'user_classroom', name: 'c.user_classroom'},
			{ data: 'pass_classroom', name: 'c.pass_classroom'},
			{ data: 'teacher_name', name: 'teacher.name'},
			// { data: 'contract_type', name: 'ct.name'},
			{ data: 'payment_type', name: 'cs.name'},
			{ data: 'contract_status', name: 'cpm.name'},
			{ data: 'total', name: 'total',render: $.fn.dataTable.render.number( ',', '.', 0, '', ' €' )},
			{ data: 'total_pending', name: 'total_pending',render: $.fn.dataTable.render.number( ',', '.', 0, '', ' €' )},
			{ data: 'created_at', name: 'c.created_at'},
			{ data: 'dt_assignment', name: 'le.dt_assignment'},
			{ data: 'agent_name', name: 'us.name'},
			{ data: 'comunication', name: 'comunication'},
			{ data: 'acciones', name: 'acciones', orderable: false, searchable: false} ,  
			{ data: 'name', name: 'pi.name',visible:false, searchable:true},    
			{ data: 'last_name', name: 'pi.last_name',visible:false, searchable:true},
			{ data: 'created_at', name: 'c.created_at', orderable: false, searchable: true, visible:false},     
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
				targets: [3,4,5,6,7,8,9,10,11,12,13,14,15,16],
				className: 'dt-body-center'
			},			
			{
				targets: [5],
				searchable: false
			},
			{
				render: function ( data, type, row ) {
					return data ? moment(data).format("DD/MM/YYYY") : " ";
				}, targets: [13]
			},
			{
				render: function ( data, type, row ) {
					return data ? moment(data).format("DD/MM/YYYY") : " ";
				}, targets: [14]
			},
			{
				render: function ( data, type, row ) {
					return '<span class="badge badge-pill text-sm '+row['bg_color']+'">'+data+'</span>';
				}, targets: [10]
			},
			{
				render: function ( data, type, row ) {
					return '<i class="fad text-lightblue fa-lg fa-id-badge"></i> '+data;
				}, targets: [15]
			},
			{
                    render: function ( data, type, row ) {
                        return '<a href="javascript:void(0)" style = "cursor: pointer; " class="editUserRoom"><i class="fad text-lightblue fa-md fa-user"></i> <span class="text-sm" style="color:black!important">'+ (data==null?'...':data)+' </span></a>';
                    }, targets: [6]
            },
			{
                    render: function ( data, type, row ) {
                        return '<a href="javascript:void(0)" style = "cursor: pointer; " class="editPassRoom"><i class="fad text-lightblue fa-md fa-key"></i> <span class="text-sm" style="color:black!important">'+(data==null?'...':data)+' </span></a>';
                    }, targets: [7]
            },
			{
                    render: function ( data, type, row ) {
                        return '<a href="javascript:void(0)" style = "cursor: pointer; " class="editTeacher"><i class="fad text-lightblue fa-md fa-chalkboard-teacher"></i> <span class="text-sm" style="color:black!important">'+(data==null?'...':data)+' </span></a>';
                    }, targets: [8]
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
				$(row).find('td:eq(6)').addClass('printable');
				$(row).find('td:eq(7)').addClass('printable');
				$(row).find('td:eq(8)').addClass('printable');
			@endif
		},
		createdRow: function( row, data, dataIndex ) {
			if(data['contract_status_id'] == 1){
				$(row).addClass('bg-yellow');
			}
		},
		drawCallback: function( settings ) {
			var column6 = table.column(6);
			var column7 = table.column(7);
			var column8 = table.column(8);
			@if(Auth::user()->hasRole('comercial'))
				column6.visible(false);
				column7.visible(false);
				column8.visible(false);
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
		var data = table.row($row).data()['id'];
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
			row.child.hide("slow");
			tr.removeClass('shown');
			i.removeClass('fa-minus');
			i.addClass('fa-plus');
		}
		else {
			// Open this row
			row.child( format(row.data()) ).show("slow");
			tr.addClass('shown');
			i.removeClass('fa-plus');
			i.addClass('fa-minus');
		}
		const contract_id_to_note = row.data().crypt_case_id;
		const table_managment = $(".data-table_"+row.data().id).DataTable({                
			destroy:true,
			processing: true,
			serverSide: true,
			ajax:  "{{ url('contracts/managementnotes/') }}"+"/"+contract_id_to_note,
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
                { data: 'created_at', name: 'created_at'},
                { data: 'dt_reminder', name: 'management_dt_reminder'},
                { data: 'contact_method', name: 'contact_method'},
                { data: 'contact_type', name: 'contact_type'},
                { data: 'category', name: 'category'},
                { data: 'observations', name: 'observations'},
                { data: 'user_note_name', name: 'user_note_name'},
                { data: 'pathnote', name: 'pathnote'},
                { data: 'id', name: 'id'},
                ],
            columnDefs: [   
                {
                    "render": function ( data, type, row ) {
                        return moment(data).format("DD/MM/YYYY - HH:mm");
                    },"targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return moment(data).format("DD/MM/YYYY - HH:mm");
                    }, "targets": 1
                },
                {
				render: function ( data, type, row ) {
					return '<span class="badge d-block text-xs '+row['color']+'">'+data+'</span>';
				}, targets: [4]
			    },
                { targets: [6,8], visible: false},
                { targets: [0,1,2,3,4,5,6], className: 'dt-body-center '}
            ],
            drawCallback: function( settings ) {
                var column = table_notes.column(6);
                @if(Auth::user()->hasRole('superadmin'))
                    column.visible(true);
                @endif
            },
            drawCallback: function( settings ) {
                var column = table_managment.column(6);
                @if(Auth::user()->hasRole('superadmin'))
                    column.visible(true);
                    
                @endif
            },
        }).responsive.recalc();
        table_managment.responsive.recalc();
	});

	//Limpiar filtros
	$('#btnCleanLeadID').click(function(){
		$(".select_list_filter").val([]).trigger("change");
		$("#dt_contract_filter").val('');
		$("#dt_contract_from").val('');
		$("#dt_contract_to").val('');
		$('#filter_contract_id').val("");
	});

	$('#btnFiterSubmitSearch').click(function(){
		// console.log($("#dt_assignment_from").val());
		$('.data-table').DataTable().draw(true);
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
		$('#lead_agent_id_hidden').val({{Auth::user()->id}});
		//Siempre es BBDD para el comercial
		//El estado es 1 cuando es nuevo
		$('#lead_status_id_hidden').val(1);
		$('#leadForm').trigger("reset");
		$('#leadsHeading').html("Crear Lead");
		$('#leadsModal').modal('show');
		$('#lead_id').val('');
		$(".select_list").val([]).trigger("change");
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
			url: "{{route('contractcrud.index') }}" +'/' + lead_id +'/edit',
			dataType: 'json',
			success: function(res){
				// console.log(res[0])
				$('#leadsHeading').html("Editar LEAD");
				$('#leadsModal').modal('show');
				$("#modal_services_list").data('select2').trigger('select', {
					data: {"id": res[0].service_id}
				});
				$("#modal_provinces_list").data('select2').trigger('select', {
					data: {"id": res[0].province_id}
				});
				$("#countries_list").data('select2').trigger('select', {
					data: {"id": res[0].country_id}
				});
				$("#first_name").val(res[0].first_name);
				$("#last_name").val(res[0].last_name);
				$("#email").val(res[0].email);
				$("#mobile").val(res[0].mobile);
				$("#dt_birth").val(res[0].dt_birth);
				$("#observations").val(res[0].observations);
				$("#lead_id").val(lead_id);
				$("#lead_dt_reception_hidden").val(moment(res[0].dt_reception).format('YYYY-MM-DDTHH:mm:ss'));
				$("#lead_dt_assignment_hidden").val(res[0].observations);
				$('#lead_agent_id_hidden').val(res[0].agent_id);
				$("#lead_status_id_hidden").val(res[0].lead_status_id);
				$("#lead_origin_id_hidden").val(res[0].leads_origin_id);
				// Si es administrador
				@if(Auth::user()->hasRole('superadmin'))
					// $('#dt_reception').attr('max', maxDate);
					$("#dt_reception_edit").val(moment(res[0].dt_reception).format('YYYY-MM-DDTHH:mm:ss'));
					$("#dt_assignment_edit").val(moment(res[0].dt_assignment).format('YYYY-MM-DDTHH:mm:ss'));
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
			url: "{{ route('contractcrud.store') }}",
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
						// $("#"+key+"_error").text(value);
					});
					$('#saveLeadBtn').html('Guardar Cambios');
					$('#saveLeadBtn').removeClass("disabled");
				}
			}
		});
	});

	//Guardar Usuario Aula Virtual
	$('#savedBtnUclass').click(function (e) {
		e.preventDefault();
		$('#savedBtnUclass').addClass("disabled");
		var formData = new FormData($('#editUserClassRoom')[0]);
		$.ajax({ 
			data: formData,      
			url: "{{ route('contract.updateuserroom') }}",
			type: "POST",
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function (data) {
				$(".text-danger").text("");
				if($.isEmptyObject(data.error)){
					$('#editUserClassRoom').trigger("reset");
					$('#editUserClassRoomModal').modal('hide');
					$('#savedBtnUclass').html('Guardar Cambios');
					$('#savedBtnUclass').removeClass("disabled");
					toastr.success('Usuario Actualizado Correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
					table.draw(false);
				}else{
					$.each( data.error, function( key, value ) {
						$("#EditEnrollError").append('<li><label class="text-red">'+value+'</label></li>');
						// $("#"+key+"_error").text(value);
					});
					$('#savedBtnUclass').html('Guardar Cambios');
					$('#savedBtnUclass').removeClass("disabled");
				}
			}
		});
	});

	//Guardar Contraseña Usuario
	$('#savedBtnPclass').click(function (e) {
		e.preventDefault();
		// $(this).attr("disabled", "disabled");
		$('#savedBtnPclass').html('<span aria-hidden="true" role="status" class="spinner-border spinner-border-sm"></span>  Enviando');
		$('#savedBtnPclass').addClass("disabled");
		var formData = new FormData($('#editPassClassRoom')[0]);
		$.ajax({ 
			data: formData,      
			url: "{{ route('contract.updatepassroom') }}",
			type: "POST",
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function (data) {
				$(".text-danger").text("");
				if($.isEmptyObject(data.error)){
					$('#editPassClassRoom').trigger("reset");
					$('#editPassClassRoomModal').modal('hide');
					$('#savedBtnPclass').html('Guardar Cambios');
					$('#savedBtnPclass').removeClass("disabled");
					toastr.success('Contraseña Actualizada Correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
					table.draw(false);
				}else{
					$.each( data.error, function( key, value ) {
						$("#EditEnrollError").append('<li><label class="text-red">'+value+'</label></li>');
					});
					$('#savedBtnPclass').html('Guardar Cambios');
					$('#savedBtnPclass').removeClass("disabled");
				}
			}
		});
	});

	//Guardar Profesor
	$('#saveBtnTeacher').click(function (e) {
		e.preventDefault();
		$('#savedBtnUclass').addClass("disabled");
		var formData = new FormData($('#editTeacher')[0]);
		$.ajax({ 
			data: formData,      
			url: "{{ route('contract.updateteacher') }}",
			type: "POST",
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function (data) {
				$(".text-danger").text("");
				if($.isEmptyObject(data.error)){
					$('#editTeacher').trigger("reset");
					$('#editTeacherModal').modal('hide');
					$('#saveBtnTeacher').html('Guardar Cambios');
					$('#saveBtnTeacher').removeClass("disabled");
					toastr.success('Tutor Actualizado Correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
					table.draw(false);
				}else{
					$.each( data.error, function( key, value ) {
						$("#TeacherAssignError").append('<li><label class="text-red">'+value+'</label></li>');
						// $("#"+key+"_error").text(value);
					});
					$('#saveBtnTeacher').html('Guardar Cambios');
					$('#saveBtnTeacher').removeClass("disabled");
				}
			}
		});
	});

	// Editar Usuario Aula Virtual
         $('body').on('click', '.editUserRoom', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var i = $(this).find('i');
            var contract_id =row.data().crypt_case_id;
            $.get("{{route('contractcrud.index') }}" +'/' + contract_id +'/edit', function (data) {
                $('#user_class_case_id').val(data[0].id);
                // $('#f_obs_enrollment_number').val(data[0].enrollment_number);
                $('#user_classroom').val(data[0].user_classroom);

            });
            $('#editUserClassRoomModal').modal('show');
        });

	// Editar Contraseña Aula Virtual
	$('body').on('click', '.editPassRoom', function () {
		var tr = $(this).closest('tr');
		var row = table.row( tr );
		var i = $(this).find('i');
		var contract_id =row.data().crypt_case_id;
		$.get("{{route('contractcrud.index') }}" +'/' + contract_id +'/edit', function (data) {
			$('#pass_class_case_id').val(data[0].id);
			// $('#f_obs_enrollment_number').val(data[0].enrollment_number);
			$('#pass_classroom').val(data[0].pass_classroom);

		});
		$('#editPassClassRoomModal').modal('show');
	});

	// Editar Profesor
	$('body').on('click', '.editTeacher', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var i = $(this).find('i');
            var contract_id =row.data().crypt_case_id;
            $.get("{{route('contractcrud.index') }}" +'/' + contract_id +'/edit', function (data) {
                $('#teacher_id_case_id').val(data[0].id);
                // $('#f_obs_enrollment_number').val(data[0].enrollment_number);
				$("#teacher_id").data('select2').trigger('select', {
					data: {"id": data[0].teacher_id}
				});
            });
            $('#editTeacherModal').modal('show');
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

	//Eventos de contratos, tutorias y paralización
	let schedulesAux = [];
	let schedulesNew= [];
    $.when(
        $.get("{{url('eventmastercontract')}}" +'/', (x)=>{
            x.map(y=>{
                schedulesAux.push({name : 'alert',
                    type : '\nObservación:  <span class="text-xs badge badge-pill '+y.status_color+'">'+y.status_name+'</span><b>'+y.title+'</b>'
                            +'\nFecha y Hora: <b>'+ y.start+'</b>'
                            +'\nCliente: <b>'+ y.student_first_name +' '+ y.student_last_name+'</b>'
                            +'\nAsesor: <b>'+ y.agent_name+'</b>',
                    date:moment(y.start).format("YYYY-MM-DD")
				});
				schedulesNew.push({
					id : y.id,
					name: y.student_first_name +' '+ y.student_last_name,
                    description : '\n<span class="text-xs badge badge-pill '+y.status_color+'">'+y.status_name+'</span>'
                            + '<br>'+y.title
							+'<br>Fecha y Hora: <b>'+ y.start+'</b>'
                            +'<br>Asesor: <b>'+ y.agent_name+'</b>'
							+'<br><a style="background-color:#f36197; color:white!important" class="text-xs badge badge-pill text-white" href="{{url('contractcrud')}}?lead_id_request='+y.lead_id_contact+'" target="_blank">Ir a la Matrícula</a>',
					date:	moment(y.start),
					type: 'event',
					color: '#f36197'
				});
            });
        })
    ).then(function(){
        $('.calendar-schedules').pignoseCalendar({
            format: 'DD/MM/YYYY',
            theme: 'blue',
            lang: 'es',
            scheduleOptions: {
                colors: {
                    alert: '#f90202',
                }
            },
            schedules: schedulesAux,
            select: function (date, context) {
                var message = `
                    <div class="callout callout-lightblue">
                        <h5>Eventos para el día: ${(date[0] === null ? ' ' : date[0].format('DD/MM/YYYY'))}</h5>

                        <p><div class="schedules-date"></div></p>
                    </div>
                    `;
                var $target = context.calendar.parent().next().show().html(message);

                for (var idx in context.storage.schedules) {
                    var schedule = context.storage.schedules[idx];
                    if (typeof schedule !== 'object') {
                        continue;
                    }
                    $target.find('.schedules-date').append('<p">' + schedule.type.replace(/(?:\r\n|\r|\n)/g, '<br />') + '</p>');
                }
            }
        });
		$('#calendar').evoCalendar({
			'language': 'es',
			'format': 'dd MM, yyyy',
			'firstDayOfWeek': 1,
			'todayHighlight': true,
			'theme': 'Royal Navy',
			'calendarEvents': schedulesNew,
			'sidebarDisplayDefault': true
		});
    });

	$('body').on('click', '.viewContractCalendar', function () {
		// $('#contract_calendar_modal').modal('show');
		$('#miniCalendarHeader').html('Calendario de Tutorías o Paralización de Aula');
		$('#calendarModal').modal('show');
	});

	//Al cerrar el modal volver a cargar los eventos
	$('#managementNotesModal').on('hidden.bs.modal', function(){
		let schedulesAux = [];
		let schedulesNew= [];
		$.when(
			$.get("{{url('eventmastercontract')}}" +'/', (x)=>{
				x.map(y=>{
					schedulesAux.push({name : 'alert',
						type : '\nObservación:  <span class="text-xs badge badge-pill '+y.status_color+'">'+y.status_name+'</span><b>'+y.title+'</b>'
								+'\nFecha y Hora: <b>'+ y.start+'</b>'
								+'\nCliente: <b>'+ y.student_first_name +' '+ y.student_last_name+'</b>'
								+'\nAsesor: <b>'+ y.agent_name+'</b>',
						date:moment(y.start).format("YYYY-MM-DD")
					});
					schedulesNew.push({
						id : y.id,
						name: y.student_first_name +' '+ y.student_last_name,
						description : '\n<span class="text-xs badge badge-pill '+y.status_color+'">'+y.status_name+'</span>'
								+ '<br>'+y.title
								+'<br>Fecha y Hora: <b>'+ y.start+'</b>'
								+'<br>Asesor: <b>'+ y.agent_name+'</b>',
						date:	moment(y.start),
						type: 'event',
						color: '#f36197'
					});
				});
			})
		).then(function(){
			$('.calendar-schedules').pignoseCalendar({
				format: 'DD/MM/YYYY',
				theme: 'blue',
				lang: 'es',
				scheduleOptions: {
					colors: {
						alert: '#f90202',
					}
				},
				schedules: schedulesAux,
				select: function (date, context) {
					var message = `
						<div class="callout callout-lightblue">
							<h5>Eventos para el día: ${(date[0] === null ? ' ' : date[0].format('DD/MM/YYYY'))}</h5>

							<p><div class="schedules-date"></div></p>
						</div>
						`;
					var $target = context.calendar.parent().next().show().html(message);

					for (var idx in context.storage.schedules) {
						var schedule = context.storage.schedules[idx];
						if (typeof schedule !== 'object') {
							continue;
						}
						$target.find('.schedules-date').append('<p">' + schedule.type.replace(/(?:\r\n|\r|\n)/g, '<br />') + '</p>');
					}
				}
			});
			$('#calendar').evoCalendar({
				'language': 'es',
				'format': 'dd MM, yyyy',
				'firstDayOfWeek': 1,
				'todayHighlight': true,
				'theme': 'Royal Navy',
				'calendarEvents': schedulesNew,
				'sidebarDisplayDefault': false
			});
		});
	});
	
</script>