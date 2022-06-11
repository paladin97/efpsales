<script>

    /* initialize the external events
     -----------------------------------------------------------------*/
	function ini_events(ele) {
		ele.each(function () {

		// create an Event Object (https://fullcalendar.io/docs/event-object)
		// it doesn't need to have a start or end
		var eventObject = {
			title: $.trim($(this).text()) // use the element's text as the event title
		}

		// store the Event Object in the DOM element so we can get to it later
		$(this).data('eventObject', eventObject)

		// make the event draggable using jQuery UI
		$(this).draggable({
			zIndex        : 1070,
			revert        : true, // will cause the event to go back to its
			revertDuration: 0  //  original position after the drag
		})

		})
	}

	ini_events($('#external-events div.external-event'))

	/* initialize the calendar
		-----------------------------------------------------------------*/
	//Date for the calendar events (dummy data)
	var date = new Date()
	var d    = date.getDate(),
		m    = date.getMonth(),
		y    = date.getFullYear()

	var Calendar = FullCalendar.Calendar;
	var Draggable = FullCalendar.Draggable;

	var containerEl = document.getElementById('external-events');
	var checkbox = document.getElementById('drop-remove');
	var calendarEl = document.getElementById('calendar');

	// initialize the external events
	// -----------------------------------------------------------------

	new Draggable(containerEl, {
		itemSelector: '.external-event',
		eventData: function(eventEl) {
		return {
			title: eventEl.innerText,
			backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
			borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
			textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
		};
		}
	});

	var calendar = new Calendar(calendarEl, {
		headerToolbar: {
			left  : 'prev,next today',
			center: 'title',
			right : 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
		},
		selectable: true,
		themeSystem: 'bootstrap',
		locale: 'es',
		//Random default events
		events: "{{url('eventmaster')}}",  
		editable: true,
		droppable: true, // this allows things to be dropped onto the calendar !!!
		dateClick: function(info){ 
			// console.log(moment(date).format("YYYY-MM-DDTHH:mm:ss"));
			$('#start').val(moment(info['dateStr']).format("YYYY-MM-DDTHH:mm:ss"));
			$('#eventModal').modal('show');
			$("#call_id").attr('href','');
			$("#call_id").html('');
			$("#agent_name").html('');
			$("#btnViewDetail").addClass('d-none');
			$("#client_list").val([]).trigger("change");
		},
		select: function(info){
			$('#start').val(moment(info.startStr).format("YYYY-MM-DDTHH:mm:ss"));
			$('#end').val(moment(info.endStr).format("YYYY-MM-DDTHH:mm:ss"));
			$('#eventModal').modal('show');
			$("#call_id").attr('href','');
			$("#call_id").html('');
			$("#agent_name").html('');
			$("#btnViewDetail").addClass('d-none');
			$("#client_list").val([]).trigger("change");
			
		},
		drop: function(info) {
			// is the "remove after drop" checkbox checked?
			if (checkbox.checked) {
				// if so, remove the element from the "Draggable Events" list
				info.draggedEl.parentNode.removeChild(info.draggedEl);
			}
		},
		eventClick: function(info) {
			// console.log(info.event);
			$("#eventModalHeading").html('Editar evento');
			$("#title").val(info.event.title);
			$("#color").val(info.event.backgroundColor);
			$("#text_color").val(info.event.extendedProps.text_color);
			$("#call_id").attr('href','tel:'+info.event.extendedProps.student_mobile);
			$("#call_id").html(info.event.extendedProps.student_mobile);
			$("#agent_name").html('Asesor Comercial: <b>'+info.event.extendedProps.agent_name+'</b>');
			$("#btnViewDetail").removeClass('d-none');
			$("#client_list").data('select2').trigger('select', {
					data: {"id": info.event.extendedProps.lead_id}
			});
			$('#start').val(moment(info.event.start).format("YYYY-MM-DDTHH:mm:ss"));
			$('#end').val(moment(info.event.end).format("YYYY-MM-DDTHH:mm:ss"));
			$('#allDay').prop('checked', info.event.allDay);
			$('#event_id').val(info.event.id);
			//Añadir valores al boton de info adicional
			$("#btnViewDetail").data('object',info.event.extendedProps.lead_id);
			$("#btnViewDetail").data('full_name',info.event.extendedProps.student_first_name + ' ' +info.event.extendedProps.student_last_name );
			$("#btnViewDetail").data('province_name',info.event.extendedProps.province_name);
			$("#btnViewDetail").data('course_name',info.event.extendedProps.course_name);
			$("#btnViewDetail").data('student_mobile',info.event.extendedProps.student_mobile);
			$("#btnViewDetail").data('email',info.event.extendedProps.email);
			$('#eventModal').modal('show');
			
		},
		eventDrop: function(info) {
			$("#eventModalHeading").html('Editar evento');
			$("#title").val(info.event.title);
			$("#color").val(info.event.backgroundColor);
			$("#text_color").val(info.event.extendedProps.text_color);
			$("#client_list").data('select2').trigger('select', {
					data: {"id": info.event.extendedProps.lead_id}
			});
			$('#start').val(moment(info.event.start).format("YYYY-MM-DDTHH:mm:ss"));
			$('#end').val(moment(info.event.end).format("YYYY-MM-DDTHH:mm:ss"));
			$('#allDay').prop('checked', info.event.allDay);
			$('#event_id').val(info.event.id);
			$('#eventModal').modal('show');
		},
		eventDidMount: function(info) {
			// if($('#lead_status_filter').val() == 0 && $('#agent_list_filter').val() == 0){
			// 	info.event.setProp('display', 'auto');
			// }
			// else{
			// 	if ((Number(info.event.extendedProps.status_id) + Number(info.event.extendedProps.agent_id) ) 
			// 		== (Number($('#lead_status_filter').val()) + Number($('#agent_list_filter').val()))) {
			// 		info.event.setProp('display', 'auto');
			// 	}
			// 	else {
			// 		info.event.setProp('display','none');
			// 	}
			// }
			
			if($('#lead_status_filter').val() == 0 && $('#agent_list_filter').val() == 0){//TODOS ESTADOS, TODOS COMERCIALES
				info.event.setProp('display', 'auto');
			}
			else if($('#lead_status_filter').val() == 0 && $('#agent_list_filter').val() != 0){//TODOS ESTADOS, ALGUN COMERCIAL
				if (info.event.extendedProps.agent_id == $('#agent_list_filter').val()) {
					info.event.setProp('display', 'auto');
				}
				else {
					info.event.setProp('display','none');
				}
			}
			else if($('#lead_status_filter').val() != 0 && $('#agent_list_filter').val() == 0){//ALGUN ESTADO, TODOS COMERCIALES
				if (info.event.extendedProps.hexa_color == $('#lead_status_filter').val()) {
					info.event.setProp('display', 'auto');
				}
				else {
					info.event.setProp('display','none');
				}
			}
			else if($('#lead_status_filter').val() != 0 && $('#agent_list_filter').val() != 0){//ALGUN ESTADO, ALGUN COMERCIAL
				if ((info.event.extendedProps.hexa_color == $('#lead_status_filter').val() ) 
					&& (info.event.extendedProps.agent_id== $('#agent_list_filter').val())){
						info.event.setProp('display', 'auto');
				}
				else {
					info.event.setProp('display','none');
				}
			}
			else{
				info.event.setProp('display','none');
			}
			
		}
	});

	calendar.render();
	// $('#calendar').fullCalendar()

	/* ADDING EVENTS */
	var currColor = '#3c8dbc' //Red by default
	// Color chooser button
	$('#color-chooser > li > a').click(function (e) {
		e.preventDefault()
		// Save color
		currColor = $(this).css('color')
		// Add color effect to button
		$('#add-new-event').css({
		'background-color': currColor,
		'border-color'    : currColor
		})
	});

	$('#add-new-event').click(function (e) {
		e.preventDefault()
		// Get value and make sure it is not null
		var val = $('#new-event').val()
		if (val.length == 0) {
		return
		}

		// Create events
		var event = $('<div />')
		event.css({
		'background-color': currColor,
		'border-color'    : currColor,
		'color'           : '#fff'
		}).addClass('external-event')
		event.text(val)
		$('#external-events').prepend(event)

		// Add draggable funtionality
		ini_events(event)

		// Remove event from text input
		$('#new-event').val('')
	});

	//Limpiar filtros
	$('#btnCleanLeadID').click(function(){
		$(".select_list_filter").val([]).trigger("change");
	});

	$('#btnFiterSubmitSearch').click(function(){
		// console.log($("#dt_assignment_from").val());
		calendar.refetchEvents();
	});

	/*
	Filtros de los eventos
	*/
	$('#lead_status_filter').on('change',function(){
		calendar.refetchEvents();
	})
	$('#agent_list_filter').on('change',function(){
		console.log('cambio de agente');
		calendar.refetchEvents();
	})

	//Guardar Cambios
	$('#dayClick').submit(function(e) {
		$("#eventError").html('');
		e.preventDefault();
		$('#saveBtnEvent').html('<span aria-hidden="true" role="status" class="spinner-border spinner-border-sm"></span>  Enviando');
		$('#saveBtnEvent').addClass("disabled");
		var formData = new FormData($('#dayClick')[0]);
		$.ajax({
			type:'POST',
			url: "{{route('eventmaster.create')}}",
			data: formData, 
			dataType: 'json',
			cache:false,
			contentType: false,
			processData: false,
			success: function (data) {
				$(".text-danger").text("");
				if($.isEmptyObject(data.error)){
					$('#dayClick').trigger("reset");
					$('#eventModal').modal('hide');
					$('#saveBtnEvent').html('Guardar Cambios');
					$('#saveBtnEvent').removeClass("disabled");
					toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
					calendar.refetchEvents();
				}else{
					$.each( data.error, function( key, value ) {
						$("#eventError").append('<li><label class="text-red">'+value+'</label></li>');
						// $("#"+key+"_error").text(value);
					});
					$('#saveBtnEvent').html('Guardar Cambios');
					$('#saveBtnEvent').removeClass("disabled");
				}
			}
		});
	});

	//Refrescar el formulario cuando se cierra modal
	$("#eventModal").on('hidden.bs.modal', function(){
		$('#dayClick').trigger("reset");
	});

	//Para que elimine el evento
	$('body').on('click', '#DeleteBtnEvent', function () {
		var event_id = $("#event_id").val();
		$.confirm({
			title: '<i class="fa fa-exclamation text-red fa-rotate-180"></i> Eliminar Evento <i class="fa fa-exclamation text-red"></i> ',
			content: '¿Confirma la eliminación de este evento?',
			buttons: {
				confirmar :{
					btnClass: 'btn-blue',
					action: function () {
						$.ajax({
							type: "DELETE",
							url: '/eventmaster/delete/'+event_id,
							dataType: "JSON",
							data: {
								"id": event_id // method and token not needed in data
							},
							success: function (data) {
								toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
								$('#eventModal').modal('hide');
								calendar.refetchEvents();
							},
							error: function (data) {
								// console.log('Error:', data);
								$.alert({
									title: '<i class="fas fa-exclamation-triangle text-red"></i> Error al eliminar el Evento',
									content: 'No se puede eliminar el evento, por favor contacte con el soporte del sitio',
								});
							}
						});    
					},
				},
				Cancelar: {
					text: 'Cancelar', // With spaces and symbols
					action: function () {
						$.alert('Operación Cancelada');
					}
				}
			}
		});
	});

	//Para que muestre el datella del Lead
	$('body').on('click', '#btnViewDetail', function () {
		$.confirm({
			columnClass: 'xlarge',
			icon: 'fad fa-tasks-alt',
			type: 'blue',
			title: '<div style="text-align:center;"><b class="text-lightblue">Notas LEADS</b></div>',
			content: '' +
			'<form action="" class="formName">' +
			'<div class="card">'+
				'<div class="card-body text-left">'+
					'<div class="row">'+
						'<div class="card card-lightblue card-outline col-sm-12" id="leadInfo"><ul class="list-unstyled">'+
                                    '<li class="h3"><i class="fad fa-user text-lightblue"></i> &nbsp;'+$("#btnViewDetail").data('full_name')+'</li>'+
                                    '<li><i class="fad fa-map-marked-alt text-lightblue"></i>&nbsp;'+$("#btnViewDetail").data('province_name')+'</li>'+
                                    '<li><i class="fad fa-scroll text-lightblue"></i>&nbsp;'+$("#btnViewDetail").data('course_name') +'</li>'+
                                    '<li><i class="fad fa-phone-volume text-lightblue"></i>&nbsp;'+$("#btnViewDetail").data('student_mobile')+'</li>'+
                                    '<li><i class="fad fa-envelope text-lightblue"></i>&nbsp; '+$("#btnViewDetail").data('email')+'</li>'+
                                '</ul>'+
						'</div>'+
					'</div>'+
					'<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table datatable-notes-appointment" cellspacing="0" width="100%">'+
						'<thead>'+
							'<tr class="bg-lightblue">'+
								'<th>Fecha Gestión</th>'+
								'<th>Hora</th>'+
								'<th>Medio de contacto</th>'+
								'<th>Estado</th>'+
								'<th>Sub Estado</th>'+
								'<th>Observaciones</th>'+
								'<th>Usuario</th>'+
								'<th>ID</th>'+
							'</tr>'+
						'</thead>'+
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
				var lead_id = $("#btnViewDetail").data('object');
				console.log(lead_id);
				const table_notes_appointment = $('.datatable-notes-appointment').DataTable({            
					destroy:true,
					processing: true,
					serverSide: true,
					ajax:  "{{ url('leads/notes/') }}"+"/"+lead_id,
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
						{ data: 'created_at', name: 'le.created_at'},
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
								return moment(data).format("DD/MM/YYYY");
							},"targets": 0
						},
						{
							"render": function ( data, type, row ) {
								// var text = parseInt(data)  ? data : " ";
								// console.log(data);
								return moment(data).format("HH:mm");
							}, "targets": 1
						},
						{ targets: 6, visible: false},
						{
							targets: 7,
							visible:false,
							searchable:false
						}
					],
					drawCallback: function( settings ) {
						var column = table_notes_appointment.column(5);
						@if(Auth::user()->hasRole('superadmin'))
							column.visible(true);
						@endif
					},
					initComplete: function (settings, json) {  
						$(".datatable-notes-appointment").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");            
					},
				}).responsive.recalc();
				table_notes_appointment.responsive.recalc();
			},
		});
		
		
	});

</script>