<script>
    function format ( d ) {
        var dt_birth = moment(d.dt_birth).isValid()? moment(d.dt_birth).format('DD/MM/YYYY') : ' ';
        let format;
        format = `
				<div class="row" style="margin:auto">
					<div class="col-sm-1" align="center">
						<i class="fad fa-level-up fa-rotate-90 fa-3x text-lightblue"></i>
					</div>
					<div class="card card-lightblue bg-transparent card-outline col-sm-11" style="background-color:#1e91ff42!important;">
						<div class="card-header pl-3 pr-3 pt-2 pb-0">
							<h4><i class="fad fa-info-circle text-bold text-lightblue text-lg"></i> Detalle del Registro</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="small-table table-sm table-bordered stripe row-border cell-border order-column compact table " cellspacing="0" width="100%">

											<tr class="bg-lightblue">
												<td><b>Genero: </b>`+ (d.gender ? d.gender :' ' )+`</td>
												<td><b>País: </b>`+ (d.country_name ? d.country_name :' ' ) +`</td>
												<td><b>Fecha de nacimiento: </b>`+ (d.dt_birth ? dt_birth  :' ' )+`</td>
												<td><b>Estudios: </b>`+ (d.studies ? d.studies  :' ' )+`</td>
												<td><b>Profesion: </b>`+ (d.profession ? d.profession  :' ' )+`</td>
											</tr>
                                            <tr class="bg-lightblue">
                                                <td colspan="5" align="left"><b>Domicilio: </b>`+ d.address_name +`</td>
                                            </tr> 
											<tr class="bg-lightblue">
												<td><b>Banco: </b>`+ (d.bank_name ? d.bank_name :' ' )+`</td>
												<td><b>IBAN: </b>`+ (d.bank_iban ? d.bank_iban  :' ' )+`</td>
												<td><b>Salario: </b>`+ (d.salary ? d.salary  :' ' )+`</td>
                                                <td colspan="2"><b>Modelo de liquidación: </b>`+ (d.liquidation_name ? d.liquidation_name : ' ')+`</td>
											</tr>
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
	
    function updateDataTableSelectAllCtrl(table){
       var $table             = table.table().node();
       var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
       var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
       var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);
    
       // If none of the checkboxes are checked
       if($chkbox_checked.length === 0){
          chkbox_select_all.checked = false;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = false;
          }
    
       // If all of the checkboxes are checked
       } else if ($chkbox_checked.length === $chkbox_all.length){
          chkbox_select_all.checked = true;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = false;
          }
    
       // If some of the checkboxes are checked
       } else {
          chkbox_select_all.checked = true;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = true;
          }
       }
    }
    
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#summernote').summernote({
            placeholder: 'Ingrese los términos y condiciones',
            tabsize: 2,
            height: 350
        });

        $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
        });
        var rows_selected = [];
        var table = $('.data-table').DataTable({
            bAutoWidth: false,
            dom: 'lBfrtip',
            processing: true,
            serverSide: true,
            pagingType: 'full_numbers',
            StateSave: true,
            ajax: {
                url: "{{ route('peopleinfcrud.index') }}",
                type: 'GET',
			    data: function (data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (! data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                data.company_filer          = $("#company_filer").val();
                data.person_type_filter     = $("#person_type_filter").val();
                data.province_filter        = $("#province_filter").val();
                delete data.search.regex;
                }
            },

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
            lengthMenu: [
                [ 10, 25, 50, 100,-1 ],
                [ '10 registros', '25 registros', '50 registros', '100 registros',  'Mostrar todo' ]
		    ],
            buttons: [
            ],
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false}, 
                { data: null, searchable: false, orderable: false,width: '1%',className: 'details-control dt-body-center',
                  defaultContent: '<i class = "fad text-lightblue fa-lg fa-plus" title="Ver Detalle"></i>'},
			    { data: null, searchable: false, orderable: false,width: '1%',className: 'details-control dt-body-center'},
                { data: 'company_name', name: 'com.name' },
                { data: 'person_type_name', name: 'pty.name' },
                { data: 'name', name: 'pi.name' },
                { data: 'dni', name: 'pi.dni' },
                { data: 'phone', name: 'pi.phone' },
                { data: 'mobile', name: 'pi.mobile' },
                { data: 'mail', name: 'pi.mail' },
                { data: 'province_name', name: 'pro.name' },
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
                    targets: [1,2,3,4,5],
                    className: 'dt-body-center'
                },
                {
                    render: function ( data, type, row ) {
                        return row['name'] + ' ' +row['last_name'];
                    }, targets: [5]
                   
                },
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
            }
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
            table_notes.responsive.recalc();
        });

        //Limpiar filtros
        $('#btnCleanLeadID').click(function(){
            $(".select_list_filter").val([]).trigger("change");
        });

        $('#btnFiterSubmitSearch').click(function(){
            // console.log($("#dt_assignment_from").val());
            $('.data-table').DataTable().draw(true);
        });

        var personByRole = [];
        var labelsByRole = [];
        var backgroundColor = [];
        var randomColor  = '#fff';
        $('#graphGroup').html('');
        @foreach($personByRole as $cData)
            randomColor = Colors.random()['rgb'];
            personByRole.push({{$cData['count']}});
            labelsByRole.push('{{$cData['name']}}');
            backgroundColor.push(randomColor);
            $('#graphGroup').append('<li><i class="far fa-circle" style="color:'+randomColor+'"></i> {{$cData['name']}} </li>');
        @endforeach
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData        = {
        labels: labelsByRole,
        datasets: [
            {
            data: personByRole,
            backgroundColor : backgroundColor,
            }
        ]
        }
        var pieOptions     = {
        legend: {
            display: false
        }
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var pieChart = new Chart(pieChartCanvas, {
        type: 'doughnut',
        data: pieData,
        options: pieOptions      
        })

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
        $('#createNewCourse').click(function () {
            $('#saveBtn').val("create-product");
            $('#course_id').val('');
            $('#peopleForm').trigger("reset");
            $('#summernote').summernote('code','');
            $('#modalHeadingPeople').html("Nuevo Registro");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editPerson', function () {
            $(".text-danger").text("");
            var course_id = $(this).data('id');
            $.get("{{route('peopleinfcrud.index') }}" +'/' + course_id +'/edit', function (data) {
                $('#modalHeadingPeople').html("Editar Información Personal");
                $('#saveBtn').val("edit-course");
                $('#ajaxModel').modal('show');
                $('#course_id').val(data.id);
                $('#summernote').summernote('code', data.program);
                $('#newcrse_name').val(data.name);
                $('#newcrse_duration').val(data.duration);
                $('#newcrse_pvp').val(data.pvp);
                // $("#modal_course_categories_list").data('select2').trigger('select', {
				// 	data: {"id": res[0].course_category_id}
				// });
                /*Se rellenan los campos de tipo select*/

                $("#newcrse_company").data('select2').trigger('select', {
                    data: {"id": data.company_id, 'text':data.course_company_name}
                });

                // $("#newcrse_company").val(data.company_id).trigger('change');
                $("#newcrse_tipo").val(data.type_id).trigger('change');
                $("#newcrse_area").val(data.area_id).trigger('change');
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Enviando..'); 
            var formData = new FormData($('#peopleForm')[0]);
            $.ajax({
                data: formData,
                url: "{{ route('peopleinfcrud.store') }}",
                type: "POST", 
                dataType: 'json',
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#peopleForm').trigger("reset");
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
                // $('.dt-checkboxes:checked').each(function(){
                //     id.push($(this).val());
                // });
                if(id.length > 0){
                    $.ajax({
                        url:"{{ route('course.cancel')}}",
                        method:"GET",
                        data:{course_id:id},
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

        // $('body').on('click', '.deletePerson', function () {
        //     var course_id = $(this).data("id");
        //     confirm("Esta seguro de borrar este Servicio?");
        //     $.ajax({
        //         type: "DELETE",
        //         url: "{{ route('peopleinfcrud.store') }}"+'/'+course_id,
        //         success: function (data) {
        //             table.draw();
        //             },
        //         error: function (data) {
        //             console.log('Error:', data);
        //         }
        //         }); 
        // });

        // Anular Contrato sin Aceptar
        $('body').on('click', '.deleteCustomer', function () {

            var Customer_id = $(this).data("id");
            confirm("Are You sure want to delete !");

            
        });

        $('body').on('click', '.deletePerson', function () {
            var cancel_course_id = $(this).data('id');
            $.confirm({
                title: '<i class="fa fa-exclamation text-red"></i> Eliminar Curso <i class="fa fa-exclamation text-red"></i> ',
                content: '¿Confirma la eliminación de este curso?',
                buttons: {
                    confirmar :{
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                type: "DELETE",
                                url: '/peopleinfcrud/'+cancel_course_id,
                                success: function (data) {
                                    table.draw();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
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

        // Handle click on checkbox
        $('.data-table tbody').on('click', 'input[type="checkbox"]', function(e){
            console.log("check");
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
});
</script>