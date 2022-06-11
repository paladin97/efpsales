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
    // $(function () {   
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
        });
        var rows_selected = [];
        var table = $('.data-table').DataTable({
            dom: 'lBfrtip',
            processing: true,
            serverSide: true,
            destroy: true,
            order:  2,
            responsive: {
                details: {
                    type: 'column'
                }
            },
            ajax: "{{ route('origin.index') }}",
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
            buttons: [
                {
                    text: '<i id="createNewOrigin" class="fad fa-layer-plus" data-toggle="tooltip"  title="Crear Origen"></i> ',
                    className: 'btn btn-secondary bg-lightblue hidden mr-2 rounded',
                    action: function(e, dt, node, config) {
                        $(node).removeClass('dt-button');
                    }
                    
                }
                // ,
                // {
                //     text: '<a style="color: white!important;"  href="javascript:void(0)" name="bulk_delete" id="bulk_delete"  ><i class="fas fa-trash"></i><strong>&nbsp;<span>Eliminar Masivo</span></strong></a>',
                //     className: 'btn btn-danger mb-2',
                //     action: function(e, dt, node, config) {
                //         $(node).removeClass('dt-button');
                        
                //     }
                // }
            ],
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false},
                { data: 'name', name: 'name' }, 
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
                    targets: [1],
                    className: 'dt-body-center'
                },
                {
                    targets: [2],
                    className: 'dt-body-center',
                    width: '20%'
                    
                }
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
        $('#createNewOrigin').click(function () {
            $('#saveBtn').val("create-product");
            $('#leadorigin_id').val('');
            $('#originForm').trigger("reset");
            $('#modalHeadingOrigin').html('<i class="fad fa-globe-americas"></i> Nuevo Origen');
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editLeadOrigin', function () {
            $(".text-danger").text("");
            var leadorigin_id = $(this).data('id');
            $.get("{{route('origin.index') }}" +'/' + leadorigin_id +'/edit', function (data) {
                $('#modalHeadingOrigin').html('<i class="fad fa-globe-americas"></i> Editar Origen');
                $('#saveBtn').val("edit-origin");
                $('#ajaxModel').modal('show');
                $('#leadorigin_id').val(data.id);
                $('#name').val(data.name);
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Enviando..');
            $.ajax({
                data: $('#originForm').serialize(),
                url: "{{ route('origin.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#originForm').trigger("reset");
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
                        url:"{{ route('origin.cancel')}}",
                        method:"GET",
                        data:{bank_id:id},
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

        $('body').on('click', '.deleteLeadOrigin', function () {
            var cancel_origin_id = $(this).data('id');
            $.confirm({
                title: '<i class="fa fa-exclamation text-red fa-rotate-180"></i> Eliminar Origen de Lead <i class="fa fa-exclamation text-red"></i> ',
                content: '¿Confirma la eliminación de este Origen del Lead?',
                buttons: {
                    confirmar :{
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                type: "DELETE",
                                url: '/origin/'+cancel_origin_id,
                                success: function (data) {
                                    table.draw();
                                },
                                error: function (data) {
                                    // console.log('Error:', data);
                                    $.alert({
                                        title: '<i class="fas fa-exclamation-triangle text-red"></i> Error al eliminar el Origen del lead',
                                        content: 'No se puede eliminar el origen del lead, por favor contacte con el soporte del sitio',
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
    // });
});
</script>