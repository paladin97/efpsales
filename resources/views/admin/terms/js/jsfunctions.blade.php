<script>
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
        //prepara el summernote
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
            dom: 'lBfrtip',
            processing: true,
            serverSide: true,
            responsive: {
                details: {
                    type: 'column'
                }
            },
            order: [[ 3, 'desc' ]],
            ajax: "{{ route('termscrud.index') }}",
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
                        last: "<strong>Ultimo</strong>",
                        next: "<strong>Siguiente</strong>",
                        previous: "<strong>Anterior</strong>"
                    }
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            buttons: [
                {
                    text: '<i id="createTerm" class="fad fa-layer-plus" data-toggle="tooltip"  title="Agregar un nuevo registro"></i>',
                    className: 'btn btn-secondary bg-lightblue hidden mr-2 rounded',
                    action: function(e, dt, node, config) {
                        $(node).removeClass('dt-button');
                    }
                    
                }
            ],
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false}, 
                { data: 'term_company_name', name: 'com.name'},
                { data: 'contract_type', name: 'ct.name'},
                { data: 'created_at', name: 'term.created_at' },
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
				render: function ( data, type, row ) {
                        return data ? moment(data).format("DD/MM/YYYY HH:mm") : " ";
                    }, targets: [3]
                },
                {
                    targets: [1,2,3,4],
                    className: 'dt-body-center'
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
        $('#createTerm').click(function () {
            $('#saveBtn').val("create-product");
            $('#term_id').val('');
            $('#termForm').trigger("reset");
            $(".select_list").val([]).trigger("change");
            $('#summernote').summernote('code','');
            $('#modalHeadingTerm').html("Nuevos Términos");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editTerm', function () {
            $(".text-danger").text("");
            var term_id = $(this).data('id');
            $.get("{{route('termscrud.index') }}" +'/' + term_id +'/edit', function (data) {
                // console.log(data);
                $('#modalHeadingTerm').html("Editar Términos");
                $('#saveBtn').val("edit-service");
                $('#ajaxModel').modal('show');
                $('#term_id').val(data.id);
                $('#summernote').summernote('code', data.terms);
                /*Se rellenan los campos de tipo select*/
                $("#modal_term_company").val(data.company_id).trigger('change');
                $("#modal_term_ctype").val(data.contract_type_id).trigger('change');
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Enviando..');
            $.ajax({
                data: $('#termForm').serialize(),
                url: "{{ route('termscrud.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#termForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Guardar Cambios');
                        toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                        table.draw();
                    }else{
                        $.each( data.error, function( key, value ) {
                            checkedNew = key.replace(/\./g, "");
                            // console.log("#"+checkedNew+"_error");
                            $("#"+checkedNew+"_error").text(value);
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
                        url:"{{ route('termscrud.cancel')}}",
                        method:"GET",
                        data:{term_id:id},
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

        // $('body').on('click', '.deleteService', function () {
        //     var term_id = $(this).data("id");
        //     confirm("Esta seguro de borrar este Servicio?");
        //     $.ajax({
        //         type: "DELETE",
        //         url: "{{ route('termscrud.store') }}"+'/'+term_id,
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

        $('body').on('click', '.deleteService', function () {
            var cancel_term_id = $(this).data('id');
            $.confirm({
                title: '<i class="fa fa-exclamation text-red"></i> Eliminar Procedimiento <i class="fa fa-exclamation text-red"></i> ',
                content: '¿Confirma la eliminación de este procedimiento?',
                buttons: {
                    confirmar :{
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                type: "DELETE",
                                url: '/service/'+cancel_term_id,
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