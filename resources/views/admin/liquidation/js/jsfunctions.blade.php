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

        $('#summernote').summernote({
            placeholder: 'Ingrese la descripción del modelo de liquidación',
            tabsize: 2,
            height: 150
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
            ajax: "{{ route('liquidationmodel.index') }}",
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
            order: [[ 2, 'desc' ]],
            buttons: [
                {
                    text: '<i id="createNewLiquidationModel" class="fad fa-layer-plus" data-toggle="tooltip"  title="Crear Modelo de Liquidación"></i>',
                    className: 'btn btn-secondary bg-lightblue hidden mr-2 rounded',
                    action: function(e, dt, node, config) {
                        $(node).removeClass('dt-button');
                    }
                    
                }
                // ,
                // {
                //     text: '<a style="color: white!important;"  href="javascript:void(0)" name="bulk_delete" id="bulk_delete"  ><i class="fas fa-trash"></i><strong>&nbsp;<span>Eliminar Masivo</span></strong></a>',
                //     className: 'btn btn-danger',
                //     action: function(e, dt, node, config) {
                //         $(node).removeClass('dt-button');
                //     }
                // }
            ],
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false}, 
                // { data: 'checkbox', name: 'checkbox'}, 
                { data: 'name', name: 'name' },
                { data: 'liquidacion_company_name', name: 'com.name'},
                { data: 'base_salary', name: 'base_salary' ,render: $.fn.dataTable.render.number( ',', '.', 2,'',' €' )}, 
                { data: 'enroll_commission', name: 'enroll_commission' ,render: $.fn.dataTable.render.number( ',', '.', 2,'',' €' )},  
                { data: 'enroll_bonus_commission', name: 'enroll_bonus_commission' ,render: $.fn.dataTable.render.number( ',', '.', 2,'',' €' )}, 
                { data: 'enroll_delegation', name: 'enroll_delegation' ,render: $.fn.dataTable.render.number( ',', '.', 2,'',' €' )},
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
                // {
                //     render: function ( data, type, row ) {
                //         return data *100 + '%';
                //     }, targets: [4]
                   
                // },
                // {
                //     render: function ( data, type, row ) {
                //         const result = (row['pvp'] * (1+(row['tax_amount']*1)));
                //         return (numeral(result).format('0,0.00')) +' €';
                //     }, targets: [5]
                   
                // },
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
        $('#createNewLiquidationModel').click(function () {
            $('#saveBtn').val("create-product");
            $('#liquidationmodel_id').val('');
            $('#liquidationmodelForm').trigger("reset");
            $('#summernote').summernote('code','');
            $('#modalHeadingLiquidationModel').html("Nuevo Modelo de Liquidación");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editLiquidationModel', function () {
            $("#leadCreateOrUpdateError").html("")
            var liquidationmodel_id = $(this).data('id');
            $.get("{{route('liquidationmodel.index') }}" +'/' + liquidationmodel_id +'/edit', function (data) {
                $('#modalHeadingLiquidationModel').html("Editar Modelo de Liquidación");
                $('#saveBtn').val("edit-liquidationmodel");
                $('#ajaxModel').modal('show');
                $('#liquidationmodel_id').val(data.id);
                $('#summernote').summernote('code', data.description);
                $('#newlqmod_name').val(data.name);
                $('#newlqmod_basesalary').val(data.base_salary);
                $('#newlqmod_enrollcommission').val(data.enroll_commission);
                $('#newlqmod_enrollbonuscommission').val(data.enroll_bonus_commission);
                $('#newlqmod_enrolldelegation').val(data.enroll_delegation);
                $("#newlqmod_company").val(data.company_id).trigger('change');
            })
        });

        $('#saveBtn').click(function (e) {
            $("#leadCreateOrUpdateError").html("");
            e.preventDefault();
            // $(this).html('Enviando..');
            var formData = new FormData($('#liquidationmodelForm')[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('liquidationmodel.store') }}",
                data: formData,
                dataType: 'json',
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#liquidationmodelForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Guardar Cambios');
                        toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                        table.draw();
                    }else{
                        $.each( data.error, function( key, value ) {
                            $("#leadCreateOrUpdateError").append('<li><label class="text-red">'+value+'</label></li>');
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
                        url:"{{ route('liquidationmodel.cancel')}}",
                        method:"GET",
                        data:{liquidationmodel_id:id},
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

        // Anular Contrato sin Aceptar
        $('body').on('click', '.deleteCustomer', function () {

            var Customer_id = $(this).data("id");
            confirm("Are You sure want to delete !");

            
        });

        $('body').on('click', '.deleteLiquidationmodel', function () {
            var cancel_liquidationmodel_id = $(this).data('id');
            $.confirm({
                title: '<i class="fa fa-exclamation text-red"></i> Eliminar Modelo de Liquidación <i class="fa fa-exclamation text-red"></i> ',
                content: '¿Confirma la eliminación de este procedimiento?',
                buttons: {
                    confirmar :{
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                type: "DELETE",
                                url: '/liquidationmodel/'+cancel_liquidationmodel_id,
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