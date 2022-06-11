<script>
    
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
							<h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Datos de la empresa</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table " cellspacing="0" width="100%">
											<tr class="bg-lightblue">
												<td><b>Dirección: </b>`+ d.address+`</td>
												<td><b>Localidad: </b>`+ d.town +`</td>
												<td><b>Provincia: </b>`+ d.province_name +`</td>
												<td><b>Código Postal: </b>`+ d.postal_code +`</td>
											</tr>`;
                                            
										format +=  `
										</table>
                                        <table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table " cellspacing="0" width="100%">
											<tr class="bg-lightblue">
												<td><b>Banco: </b>`+ d.bank_name+`</td>
												<td><b>Cuenta Bancaria: </b>`+ d.bank_account +`</td>
												<td><b>Swift: </b>`+ d.switf +`</td>
												<td><b>Descripción: </b>`+ d.description +`</td>
											</tr>`;
                                            
										format +=  `
										</table>
									</div>
								</div>
							</div>
						</div>
                        <div class="card-header pl-3 pr-3 pt-2 pb-0">
							<h4><i class="fad fa-rss text-bold text-lightblue text-lg"></i> Redes Sociales y Web</h4>
						</div>
                        <div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table " cellspacing="0" width="100%">
											<tr class="bg-lightblue">
												<td><b>Facebook: </b><a target="_blank" style="color:white;" href="`+ d.url_facebook +`"><u>`+ d.url_facebook +`</u></a></td>
												<td><b>Instagram: </b><a target="_blank" style="color:white;" href="`+ d.url_instagram +`"><u>`+ d.url_instagram +`</u></a></td>
                                                <td><b>Reseñas de Google: </b><a target="_blank" style="color:white;" href="`+ d.url_business +`"><u>`+ d.url_business +`</u></a></td>
												<td><b>Sitio Web: </b><a target="_blank" style="color:white;" href="`+ d.url_website +`"><u>`+ d.url_website +`</u></a></td>
											</tr>`;
										format +=  `
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				`;
        return format;
	}
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var rows_selected = [];
        var table = $('.data-table').DataTable({
            autowith: false,
            dom: "<'toolbar'>"+
                "<'row'<'col-md-6'l>>"+
                "<'row'<'col-md-6 mt-2'B><'col-md-6 mt-2'f>>" +
                "<'row' <'col-md-2'<'toolbar_sub'>> >"+
                    "<'row'<'col-md-12'tr>><'row'<'col-md-12'ip>>",
            processing: true,
            serverSide: true,
            pagingType: 'full_numbers',
		    StateSave: true,
            ajax: {
                url:  "{{ route('company.index') }}",
                type: 'GET',
                data: function (data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (! data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                }
            },
            lengthMenu: [
                [ 25,10, 50, 100, -1],
                [ '25 registros','10 registros','50 registros', '100 registros',  'Mostrar Todo' ]
            ],
            buttons: [
                {
                    text: '<i id="createNewCompany" class="fad fa-warehouse" data-toggle="tooltip"  title="Crear Empresa"></i>',
                    className: 'btn btn-secondary bg-lightblue hidden mr-2 rounded',
                    init: function(api, node, config) {
                        $(node).removeClass('dt-button')
                    }
                    
                }
            ],
            order: [[ 3, 'asc' ]],
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
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columns: [
                { data: 'id', name: 'company_id'},
                { data: null, searchable: false, orderable: false,width: '1%',className: 'details-control dt-body-center',
                    defaultContent: '<i class = "fad text-lightblue fa-lg fa-plus" title="Ver Detalle"></i>'},
                { data: null, searchable: false, orderable: false,width: '1%',className: 'details-control dt-body-center'},
                { data: 'name', name: 'name' },
                { data: 'cif', name: 'cif' },
                { data: 'leg_rep_full_name', name: 'leg_rep_full_name' },
                { data: 'leg_rep_nif', name: 'leg_rep_nif' },
                { data: 'phone', name: 'phone' },
                { data: 'mail', name: 'mail' },
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false}
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
				targets: [3,4,5,6,7,8],
                    className: 'dt-body-center printable'
                },
            ],
            rowCallback: function(row, data, dataIndex){
                var rowId = data['id'];
                if($.inArray(rowId, rows_selected) !== -1){
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            },
        });

        $('.data-table thead th').css('white-space','pre');

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

        $('#createNewCompany').click(function () {
            $('#saveBtn').val("create-product");
            $('#company_id').val('');
            $('#companyForm').trigger("reset");
            $('.modal-title').html("Nueva Empresa");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editCompany', function () {
            $(".text-danger").text("");
            var company_id = $(this).data('id');
            $.get("{{route('company.index') }}" +'/' + company_id +'/edit', function (data) {
                $('.modal-title').html("Editar Empresa");
                $('#saveBtn').val("edit-company");
                $('#ajaxModel').modal('show');
                $('#company_id').val(data.id);
                $('#name').val(data.name);
                $('#mail').val(data.mail);
                $('#phone').val(data.phone);
                $('#cif').val(data.cif);
                $('#address').val(data.address);
                $('#description').val(data.description);
                $('#town').val(data.town);
                $('#postal_code').val(data.postal_code);
                $('#leg_rep_nif').val(data.leg_rep_nif);
                $('#leg_rep_full_name').val(data.leg_rep_full_name);
                $('#logo_path').val(data.logo_path);
                $('#bank_account').val(data.bank_account);
                $('#switf').val(data.switf);
                $('#url_facebook').val(data.url_facebook);
                $('#url_instagram').val(data.url_instagram);
                $('#url_business').val(data.url_business);
                $('#url_website').val(data.url_website);
                
                /*Se rellenan los campos de tipo select*/
                $("#company_province").val(data.province_id).trigger('change');
                $("#company_bank").val(data.bank_id).trigger('change');
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Enviando..');
            $("#ErrorField").empty();
            var formData = new FormData($('#companyForm')[0]);
            $.ajax({
                data:formData,
                url: "{{ route('company.store') }}",
                type: "POST",
                dataType: 'json',
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#companyForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Guardar Cambios');
                        toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                        table.draw();
                    }else{
                        $.each( data.error, function( key, value ) {
                        $("#"+key+"_error").text(value);
                        });
                        $('#saveBtn').html('Guardar Cambios');
                  }
                }
            });
        });

        $('#bulk_delete').click(function(){
            var id = [];
            if(confirm("Esta seguro de realizar esta acción?")){
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    id.push(rowId);
                });
                if(id.length > 0){
                    $.ajax({
                        url:"{{ route('company.cancel')}}",
                        method:"GET",
                        data:{company_id:id},
                        success:function(data){
                            // alert(data);
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
                else
                {
                    alert("Por favor seleccione por lo menos una casilla");
                }
            }
        });

        $('body').on('click', '.deleteCompany', function () {
            var cancel_company_id = $(this).data('id');
            $.confirm({
                title: '<i class="fa fa-exclamation text-danger fa-rotate-180"></i> Eliminar Compañía <i class="fa fa-exclamation text-danger"></i> ',
                content: '¿Confirma la eliminación de esta compañía?',
                buttons: {
                    confirmar :{
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                type: "DELETE",
                                url: '/company/'+cancel_company_id,
                                success: function (data) {
                                    table.draw();
                                },
                                error: function (data) {
                                    $.alert({
                                        title: '<i class="fas fa-exclamation-triangle text-danger"></i> Error al eliminar la compañía',
                                        content: 'No se puede eliminar la compañía, por favor contacte con el soporte del sitio',
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
                $('[data-bs-toggle="tooltip"]').tooltip()
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
	});    

});
</script>