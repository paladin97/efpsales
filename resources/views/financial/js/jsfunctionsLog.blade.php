<script>
    
	// Construye la datatable principal de las liquidaciones
	const rows_selected = [];
	const table = $('.data-table').DataTable({
		processing: true,
        serverSide: true,
        // scrollX: true,
        autoWidth : false,
        StateSave: true,
        pagingType: "full_numbers",
		bAutoWidth: false,
		dom: 'rt',
		processing: true,
		serverSide: true,
		pagingType: 'full_numbers',
		StateSave: true,
		ajax: {
			url:  "{{ route('liquidationcrudlog.index') }}",
			type: 'GET',
			data: function (data) {
			for (var i = 0, len = data.columns.length; i < len; i++) {
				if (! data.columns[i].search.value) delete data.columns[i].search;
				if (data.columns[i].searchable === true) delete data.columns[i].searchable;
				if (data.columns[i].orderable === true) delete data.columns[i].orderable;
				if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
			}
			data.dt_reception_filter = $("#dt_reception_filter").val();
			data.agent_list_filter = $("#agent_list_filter").val();
			delete data.search.regex;
			}
		},
		lengthMenu: [
			[ 10, 25, 50, 100,-1 ],
			[ '10 registros', '25 registros', '50 registros', '100 registros',  'Mostrar todo' ]
		],
		order: [[ 2, 'desc' ]],
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
			{ data: 'agent_name', name: 'agent_name'},
			{ data: 'agent_type', name: 'agent_type'},
			{ data: 'period_liq', name: 'period_liq'},
			{ data: 'status', name: 'status'},
			{ data: 'leads', name: 'leads'},
			{ data: 'personal_sales_count', name: 'personal_sales_count'},
			{ data: 'conversion', name: 'conversion',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' %' )},
			{ data: 'personal_sales', name: 'personal_sales',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
			{ data: 'personal_commission', name: 'personal_commission',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
			{ data: 'delegation_sales_count', name: 'delegation_sales_count'},
			{ data: 'delegation_sales', name: 'delegation_sales',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
			{ data: 'delegation_commission', name: 'delegation_commission',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
			{ data: 'positive_value', name: 'positive_value',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
			{ data: 'negative_value', name: 'negative_value',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
			{ data: 'grand_total', name: 'grand_total',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
			{ data: 'action_button', name: 'action_button'},
			],
		columnDefs: [
			{
				render: function ( data, type, row ) {
					if (data == 'A') {
						return '<span class="badge d-block text-xs bg-success"> FIRMADA</span>';
					} else {
						return '<span class="badge d-block text-xs bg-red">PENDIENTE</span>';	
					}
				}, targets: [3]
			},
		],
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
</script>