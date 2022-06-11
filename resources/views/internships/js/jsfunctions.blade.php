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
							<h4><i class="fad fa-lightblue-circle text-bold text-white text-lg"></i> Detalle de la matrícula</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table " cellspacing="0" width="100%">
											<tr class="bg-lightblue">
												<td><b>E-Mail: </b>`+ d.mail+`</td>
												<td><b>Fecha Aprobación: </b>`+  (d.dt_approved ? d.dt_approved : '')+`</td>
												<td><b>Móvil: </b>`+ d.mobile +`</td>
												<td><b>Método de Pago: </b>`+ d.payment_method +`</td>
												<td><b>Domicilio: </b>`+ d.student_address +`</td>
											</tr>
										</tabl>
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
            $("#dt_assignment_from").val(start.format('YYYY-MM-DD HH:mm:ss'));
            $("#dt_assignment_to").val(end.format('YYYY-MM-DD HH:mm:ss'));
            // console.log("A new date selection was made: " + start.format('YYYY-MM-DD HH:mm:ss') + ' to ' + end.format('YYYY-MM-DD HH:mm:ss'));
        }); 

	// Construye la datatable principal de LEADS
	const rows_selected = [];
	const table = $('.data-table').DataTable({
		processing: true,
            serverSide: true,
            // scrollX: true,
            autoWidth : false,
            StateSave: true,
            pagingType: "full_numbers",
		bAutoWidth: false,
		dom: 'lBfrtip',
		processing: true,
		serverSide: true,
		pagingType: 'full_numbers',
		StateSave: true,
		ajax: {
			url:  "{{ route('contractintership.index') }}",
			type: 'GET',
			data: function (data) {
			for (var i = 0, len = data.columns.length; i < len; i++) {
				if (! data.columns[i].search.value) delete data.columns[i].search;
				if (data.columns[i].searchable === true) delete data.columns[i].searchable;
				if (data.columns[i].orderable === true) delete data.columns[i].orderable;
				if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
			}
			data.dt_contract_from = $("#dt_contract_from").val();
			data.dt_contract_to = $("#dt_contract_to").val();
			data.agent_list_filter = $("#agent_list_filter").val();
			data.contract_status_filter = $("#contract_status_filter").val();
			data.services_list_filter = $("#services_list_filter").val();
			data.contract_payment_type_list_filter = $("#contract_payment_type_list_filter").val();
			data.contract_method_type_list_filter = $("#contract_method_type_list_filter").val();
			data.contract_type_filter = $("#contract_type_filter").val();
			delete data.search.regex;
			}
		},
		lengthMenu: [
			[ 10, 25, 50, 100,-1 ],
			[ '10 registros', '25 registros', '50 registros', '100 registros',  'Mostrar todo' ]
		],
		buttons: [
			{
				title: '',
				text:  '<i class="fad fa-layer-plus fa-2x" onClick="addContract()" data-ask="2" data-toggle="tooltip"  title="Crear Contrato de Prácticas"></i> ',
				className: 'btn btn-secondary bg-lightblue hidden mr-2 p-1 rounded-right',
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
					// header: false,
					columns: [2,3,4,5,6,7,8,9,10]
				}
			},
			{
				extend: 'pdf',
				filename: 'Leads',
				orientation: 'landscape',
				text:  '<i class="fad fa-file-pdf p-0 fa-2x" data-ask="2"  data-toggle="tooltip"   title="Descargar PDF"></i> ',
				className: 'rounded-left btn btn-secondary bg-lightblue p-1  hidden',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				},
				exportOptions: {
					columns: [2,3,4,5,6,7,8,9,10]
				}
			}
		],
		order: [[ 11, 'desc' ]],
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
                { data: 'id', name: 'c.id'}, 
                { data: null, searchable: false, orderable: false,width: '1%',className: 'details-control dt-body-center',
                  defaultContent: '<i class = "fa fa-plus-square" title="Ver Detalle"></i>'},
                { data: null, searchable: false, orderable: false,width: '1px',className: 'details-control dt-body-center'},
                { data: 'course_name', name: 'crse.name', width: '300px'},
                { data: 'internship_status', name: 'ist.name'},
                { data: 'ecenter', name: 'ecen.name'},
                { data: 'prov_req', name: 'prov_req.name'},
                { data: 'contract_type', name: 'ct.name'},
                { data: 'company_name', name: 'com.name'},
                { data: 'dt_course_completion', name: 'c.dt_course_completion'},
                { data: 'enrollment_number', name: 'c.enrollment_number'},
                { data: 'contract_status', name: 'cs.name'},
                { data: 'duration', name: 'c.duration'},
                { data: 'student_name', name: 'pi.name'},
                { data: 'student_last_name', name: 'pi.last_name'},
                { data: 'mobile', name: 'pi.mobile'},
                { data: 'mail', name: 'pi.mail'},
                { searchable: false,width: '250px',data: 'total', name: 'total',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
                { searchable: false,data: 'total_pending', name: 'total_pending',render: $.fn.dataTable.render.number( ',', '.', 2, '', ' €' )},
                { data: 'dt_created', name: 'c.dt_created'},
                { data: 'dt_approved', name: 'c.dt_approved'},
                // { data: 'dt_approved', name: 'c.dt_approved'},
                { data: 'agent_name', name: 'us.name'},
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false},             
            ],
            columnDefs: [
                {
                    targets: [2,5,6,7,8,10,11,12,13,14,15,16,19,20,21], 
                    className: 'dt-body-center'
                },
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    width: '5px',
                    className: 'dt-body-center',
                    render: function (data, type, full, meta){
                        return '<input type="checkbox">';
                    }
                },
                {
                    targets: 2,
                    searchable: false,
                    orderable: false,
                    width: '1px',
                },
                {
                    render: function ( data, type, row ) {
                        return moment(data).isValid()? moment(data).format("DD/MM/YYYY") : ' ';
                    }, targets: [9,19]
                },
                {
                    render: function ( data, type, row ) {
                        return '<span class="badge '+row['bg_color']+'">'+data+'</span>';
                    }, targets: [11]
                },
                {
                    render: function ( data, type, row ) {
                        return moment(data).isValid()? moment(data).format("DD/MM/YYYY HH:mm:ss") : ' ';
                    }, targets: [20]
                },
                { targets: [7,8,15,16,18,21],visible:false},   
            ],
            rowCallback: function(row, data, dataIndex){
                // Get row ID
                var rowId = data['id'];
                // console.log(data['id']);
                // If row ID is in the list of selected row IDs
                if($.inArray(rowId, rows_selected) !== -1){
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            },
            drawCallback: function( settings ) {
                var column4 = table.column(14);
                @if(Auth::user()->hasRole('comercial'))
                    column4.visible(true);
                @endif

                var column5 = table.column(15);
                @if(Auth::user()->hasRole('comercial'))
                    column5.visible(true);
                @endif
                // table.columns.adjust();
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
	});

	//Limpiar filtros
	$('#btnCleanLeadID').click(function(){
		$(".select_list_filter").val([]).trigger("change");
		$("#dt_contract_filter").val('');
		$("#dt_contract_from").val('');
		$("#dt_contract_to").val('');
	});

	$('#btnFiterSubmitSearch').click(function(){
		// console.log($("#dt_assignment_from").val());
		$('.data-table').DataTable().draw(true);
	});

    

	//Funciones del crud
	//Modal de crear Lead
	function addContract(){
		$('#saveBtn').val("create-product");
		$('#contract_id').val('');
		$('#editContractForm').trigger("reset");
		var fechaLoad = moment().format('YYYY-MM-DD');
		$('#contract_dt_creation').val(fechaLoad);
		$('#contract_type_id').val('2');
		$(".select_list").val([]).trigger("change");
		$('#modelHeading').html("Crear nuevo Lead");
		$('#editContractModal').modal('show');
		$("#course_info").empty();
	}   

	// Modal de editar Lead
	$('body').on('click', '.editContract', function () {
		$("#internShipFormError").html("");
		$('#editContractForm').trigger("reset");
		var contract_id = $(this).data('object');
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