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
            dom: 'Blfrtip',
            processing: true,
            serverSide: true,
            destroy: true,
            order:  2,
            responsive: {
                details: {
                    type: 'column'
                }
            },
            ajax: "{{ route('status.index') }}",
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
                // {
                //     text: '<a href="javascript:void(0)"  style = "color:white!important;" id="createNewStatus"><strong><i class="far fa-plus-square"></i> &nbsp;<span>Nuevo Estado</span></strong></a>&nbsp;',
                //     className: 'btn btn-success mr-1 mb-2',
                //     action: function(e, dt, node, config) {
                //         $(node).removeClass('dt-button');
                //     }
                    
                // },
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
                { data: 'description', name: 'description' },
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
                    targets: [3],
                    className: 'dt-body-center',
                    width: '20%'
                    
                },
                {
                    render: function ( data, type, row ) {
                        return '<span class="badge '+row['color_class']+'">'+data+'</span>';
                    }, targets: [1]
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
        $('#createNewStatus').click(function () {
            $('#saveBtn').val("create-product");
            $('#leadstatus_id').val('');
            $('#statusForm').trigger("reset");
            $('#modalHeadingStatus').html("Nuevo Estado del Lead");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editLeadStatus', function () {
            $(".text-danger").text("");
            var leadstatus_id = $(this).data('id');
            $.get("{{route('status.index') }}" +'/' + leadstatus_id +'/edit', function (data) {
                $('#modalHeadingStatus').html('<i class="fad fa-clipboard-list-check"></i> Editar Estado');
                $('#saveBtn').val("edit-origin");
                $('#ajaxModel').modal('show');
                $('#leadstatus_id').val(data.id);
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#color_class').addClass(data.color_class);
                $('#color_class').val(data.color_class);
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Enviando..');
            $.ajax({
                data: $('#statusForm').serialize(),
                url: "{{ route('status.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#statusForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Guardar Cambios');
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

        // $('#bulk_delete').click(function(){
        //     var id = [];
        //     if(confirm("Esta seguro de realizar esta acción?")){
        //         $.each(rows_selected, function(index, rowId){
        //             // Create a hidden element
        //             id.push(rowId);
        //         });
        //         // $('.dt-checkboxes:checked').each(function(){
        //         //     id.push($(this).val());
        //         // });
        //         if(id.length > 0){
        //             $.ajax({
        //                 url:"{{ route('status.massremove')}}",
        //                 method:"GET",
        //                 data:{bank_id:id},
        //                 success:function(data){
        //                     // alert(data);
        //                     table.draw();
        //                 },
        //                 error: function (data) {
        //                     console.log('Error:', data);
        //                 }
        //             });
        //         }
        //         else
        //         {
        //             alert("Por favor seleccione por lo menos una casilla");
        //         }
        //     }
        // });

        $('body').on('click', '.deleteLeadOrigin', function () {
            var bank_id = $(this).data("id");
            confirm("Esta seguro de borrar este Estado del Lead?");
            $.ajax({
                type: "DELETE",
                url: "{{ route('status.store') }}"+'/'+leadstatus_id,
                success: function (data) {
                    table.draw();
                    },
                error: function (data) {
                    console.log('Error:', data);
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

    //Para controlar el color seleccionado
    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    $('#color-chooser > li > a').click(function (e) {
        e.preventDefault();
        //Save color
        currColorName = $(this).data('bg-color');
        $("#color_class").removeClass();
        $("#color_class").addClass('form-control form-control-sm');
        $("#color_class").addClass(currColorName);
        $("#color_class").val(currColorName);
    })
});
</script>