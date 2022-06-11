<script>
    function updateDataTableSelectAllCtrl(table) {
        var $table = table.table().node();
        var $chkbox_all = $('tbody input[type="checkbox"]', $table);
        var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

        // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all.checked = false;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If some of the checkboxes are checked
        } else {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = true;
            }
        }
    }

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        //var rows_selected = [];
        /* var table = $('.data-table').DataTable({
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
            ajax: "{{ route('contractstatus.index') }}",
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
                { data: 'id', name: 'ct.id', orderable: false, searchable: false},
                { data: 'contract_type', name: 'cty.name' }, 
                { data: 'name', name: 'ct.name' }, 
                { data: 'short_name', name: 'ct.short_name' }, 
                { data: 'description', name: 'ct.description' },
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
                {   width: "40%", "targets": 4 },
                {
                    render: function ( data, type, row ) {
                        return '<span class="badge '+row['color_class']+'">'+data+'</span>';
                    }, targets: [2]
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
        }); */



        $('.bnk_checkbox').on('click', 'tr', function() {
            var id = this.id;
            var index = $.inArray(id, selected);

            if (index === -1) {
                selected.push(id);
            } else {
                selected.splice(index, 1);
            }
            $(this).toggleClass('selected');
        });

        /*   $('#createNewStatus').click(function () {
              $('#saveBtn').val("create-product");
              $('#contractsatus_id').val('');
              $('#statusForm').trigger("reset");
              $('#modalHeadingStatus').html("Nuevo Estado de Contrato");
              $('#ajaxModel').modal('show');
          }); */

        $('.editOrder').click(function() {
            // $('#saveBtn').val("create-product");
            // $('#order_id').val('');

            var value = $(this).attr('value');
            var url = "{{ route('order.find2') }}";
            url = url + "/" + value;

            $.ajax({

                url: url,
                type: "GET",
                dataType: 'json',
                success: function(data) {


                    $('#orderForm').trigger("reset");

                    var url_update = "{{ route('order.update2') }}"
                    url_update = url_update + "/" + value;

                    $('#orderForm').attr('action', url_update);
                    $('#order_text').val(data['order']);
                    $('#order_status').val(data['order_status_id']).change();
                    $('#printing_list').val(data['printing_provider_id']).change();
                    $('#shipping_list').val(data['shipping_agency_provider_id']).change();
                    $('#order_code').val(data['order_code']);

                }
            });

            $('#ajaxModel').modal('show');
        });



        function editNote() {

            $('.editNote').on("click", function() {

                $('#historyNotesOrderModal').modal('hide');

                $("#order_status_div").addClass('d-none').change();
                $('[for="order_note_text"]').text('Editar Nota');
                $('#note_method').attr('value', 'PUT');
                $('#note_method').attr('name', '_method');

                var value = $(this).attr('value');
                var url = "{{ route('ordernotefind.find2') }}";
                url = url + "/" + value;

                $.ajax({

                    url: url,
                    type: "GET",
                    dataType: 'json',
                    success: function(data) {


                        $('#orderNoteForm').trigger("reset");

                        var url_update = "{{ route('ordernote.update2') }}"
                        url_update = url_update + "/" + value;

                        $('#orderNoteForm').attr('action', url_update);
                        $('#order_note_text').val(data['observation']);


                    }
                });

                $('#modelOrder').modal('show');
            });
        }

        editNote()

        function deleteNote() {

            $('.deleteNote').on("click", function() {

                var value = $(this).attr('value');

                $.confirm({

                    title: 'Confirmación',

                    content: '¿Está seguro que desea eliminar esta nota?',

                    buttons: {

                        confirmar: function() {

                            
                            var url = "{{ route('ordernote.destroy2') }}";
                            url = url + "/" + value;

                            $.ajax({

                                url: url,
                                type: "DELETE",
                                dataType: 'json',
                                success: function(data) {

                                    $('#tr' + value).hide('slow', function() {
                                        $('#tr' + value).remove();
                                    });

                                }
                            });
                        },
                        cancel: function() {

                        }
                    }

                });







            });

        }




        function addNote() {

            $('.addNote').on("click", function() {

                $('#historyNotesOrderModal').modal('hide');

                var value = $(this).attr('value');
                var url = "{{ route('order.find2') }}";
                url = url + "/" + value;

                $.ajax({

                    url: url,
                    type: "GET",
                    dataType: 'json',
                    success: function(data) {

                        $('#orderNoteForm').trigger("reset");
                        $('#order_status2').val(data['order_status_id']).change();


                    }
                });


                $('[for="order_note_text"]').text('Agregar Nota');
                $('#order_note_id').attr('value', value);
                $('#orderNoteForm').attr('action', "{{ route('ordernote.store') }}");
                $("#order_status_div").removeClass('d-none').change();
                $('#note_method').attr('value', '');
                $('#note_method').attr('name', '');
                $('#modelOrder').modal('show');

                // alert("Entro al evento click")

            });
        }

        addNote()

        $('.moreNotes').click(function() {

            //$('#note_method').attr('value', 'PUT');
            //$('#note_method').attr('name', '_method');

            $('#notes_table tr').remove();

            var value = $(this).attr('value');
            var url = "{{ route('order.notes2') }}";
            url = url + "/" + value;

            $.ajax({

                url: url,
                type: "GET",
                dataType: 'json',
                success: function(data) {


                    $('#modalHeadingHistoryNotes').text("Notas del pedido " + data['code']);
                    var notes = data['notes'];

                    $.each(notes, function(index, note) {

                        $('#notes_table > tbody').append('<tr id="tr' + note['id'] +
                            '">' +

                            '<th><input name="select' + note['id'] +
                            '" id="order_id' + note['id'] + '"' +
                            'value="' + note['id'] + '" type="checkbox"></th>' +
                            '<td>' +
                            '<div class="card bg-light">' +
                            ' <div class="card-body">' +
                            ' <p class="card-text">' + note['observation'] +
                            '</p>' +
                            '</div>' +
                            '</td>' +
                            '<td>' +
                            '<div class="dropdown" align="center">' +
                            '<a href="#" data-toggle="dropdown" class="text-secondary "' +
                            'aria-expanded="false">' +
                            '<i class="fad fa-fw fa-lg fa-ellipsis-v mr-1 text-info"></i></a>' +

                            '<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"' +
                            ' style="position: absolute; transform: translate3d(-144px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">' +
                            '<a href="#" data-toggle="dropdown" class="text-secondary"></a>' +
                            '<a class="dropdown-item editNote" value="' + note[
                                'id'] + '" title="Editar"> ' +
                            '<i class="fad fa-pencil-alt text-lightblue"></i>Editar</a>' +
                            '<a class="dropdown-item addNote" value="' + note[
                                'id'] + '"' +
                            ' href="javascript:void(0)" title="Agregar Nota"> ' +
                            '<i class="fad fa-plus-square text-lightblue"></i>Agregar Nota</a>' +


                            '<a  class="dropdown-item deleteNote" title="Eliminar" value="' +
                            note[
                                'id'] + '">' +
                            '<i class="fad fa-trash text-lightblue"></i>Eliminar</a>' +

                            '</div>' +
                            ' </div>' +
                            '</td>' +
                            '</tr>'

                        ).change();

                    });

                    addNote()
                    editNote()
                    deleteNote()
                }
            });


            $('#historyNotesOrderModal').modal('show');




        });


        /* $('body').on('click', '.editContractStatus', function () {
            $(".text-danger").text("");
            var contractsatus_id = $(this).data('id');
            $.get("{{ route('contractstatus.index') }}" +'/' + contractsatus_id +'/edit', function (data) {
                $('#modalHeadingStatus').html("Editar Estado de Contrato");
                $('#saveBtn').val("edit-origin");
                $('#ajaxModel').modal('show');
                $('#contractsatus_id').val(data.id);
                $('#name').val(data.name);
                $('#short_name').val(data.short_name);
                $('#description').val(data.description);
                $('#color_class').addClass(data.color_class);
                $('#color_class').val(data.color_class);
                $("#contract_type").data('select2').trigger('select', {
					data: {"id": data.contract_type_id}
				});
            })
        }); */

        /* $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Enviando..');
            $.ajax({
                data: $('#statusForm').serialize(),
                url: "{{ route('contractstatus.store') }}",
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
        }); */

        /*   $('body').on('click', '.deleteLeadOrigin', function () {
              var bank_id = $(this).data("id");
              confirm("Esta seguro de borrar este Estado del Lead?");
              $.ajax({
                  type: "DELETE",
                  url: "{{ route('contractstatus.store') }}"+'/'+contractsatus_id,
                  success: function (data) {
                      table.draw();
                      },
                  error: function (data) {
                      console.log('Error:', data);
                  }
                  }); 
          }); */
        // Handle click on checkbox
        /*  $('.data-table tbody').on('click', 'input[type="checkbox"]', function(e){
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
         }); */

        // Handle click on table cells with checkboxes
        /* $('.data-table').on('click', 'td:first-child, th:first-child', function(e){
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        }); */

        // Handle click on "Select all" control
        /*  $('thead input[name="select_all"]', table.table().container()).on('click', function(e){
             if(this.checked){
                 $('.data-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
             } else {
                 $('.data-table tbody input[type="checkbox"]:checked').trigger('click');
             }

             // Prevent click event from propagating to parent
             e.stopPropagation();
         }); */

        // Handle table draw event
        /*  table.on('draw', function(){
             // Update state of "Select all" control
             updateDataTableSelectAllCtrl(table);
             $(function () {
                     $('[data-toggle="tooltip"]').tooltip()
                 });
         }); */
        // });

        //Para controlar el color seleccionado
        /* ADDING EVENTS */
        /* var currColor = '#3c8dbc' //Red by default
        $('#color-chooser > li > a').click(function (e) {
            e.preventDefault();
            //Save color
            currColorName = $(this).data('bg-color');
            $("#color_class").removeClass();
            $("#color_class").addClass('form-control form-control-sm');
            $("#color_class").addClass(currColorName);
            $("#color_class").val(currColorName);
        }) */
    });
</script>
