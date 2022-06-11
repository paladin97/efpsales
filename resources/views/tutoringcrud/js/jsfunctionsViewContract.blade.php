<script>
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".wrapper").show(); 
    });
    
    // Modal de editar Lead
	$('body').on('click', '.manageLead', function () {
    	$('#leadForm').trigger("reset")
        $('#leadNotesModal').modal('show');
        $("#lead_id_notes").val($(this).data('object'));
        $("#leadNoteError").html("");
        var lead_id = $(this).data('object');
        $.get("{{route('leadcrud.index') }}" +'/' + lead_id +'/edit', function (data) {
            $("#leads_status_list").val(data[0].lead_status_id).trigger('change.select2');
            $("#leadInfo").html('<div class="callout callout-lightblue">'+
                        '<h3><i class="fad fa-user text-lightblue"></i> '+data[0].first_name +' '+ data[0].last_name + '</h3>' +
                        '<h5><i class="fad fa-scroll text-lightblue"></i> '+data[0].service_name +
                        '<h5><i class="fad fa-phone-volume text-lightblue"></i> '+data[0].mobile +
                        '<br><i class="fad fa-envelope text-lightblue"></i> '+data[0].email +
                    '</h5></div>');
        });
		const table_notes = $('.data-table-notes').DataTable({
            destroy:true,
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            ajax:  "{{ url('leads/notes/') }}"+"/"+lead_id,
            dom: 'lfrtip',
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
                    paginate: {
                        first: "Primero",
                        last: "Ultimo",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
            },
            columns: [
                { data: 'created_at', name: 'created_at'},
                { data: 'created_at', name: 'created_at'},
                { data: 'sent_method', name: 'sent_method'},
                { data: 'status', name: 'status'},
                { data: 'observation', name: 'observation'},
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
                        return moment(data).format("HH:mm:ss");
                    }, "targets": 1
                },
                { targets: 5, visible: false},
                {
                    targets: 6,
                    visible:false,
                    searchable:false
                }
            ],
            drawCallback: function( settings ) {
                var column = table_notes.column(5);
                @if(Auth::user()->hasRole('superadmin'))
                    column.visible(true);
                @endif
            },
        }).responsive.recalc();
        table_notes.responsive.recalc();

    });

    //Guardar Cambios
    $('#leadFormNotes').submit(function(e) {
        $("#leadNoteError").html("");
        e.preventDefault();
        $('#saveBtnLeadNotes').html('Enviando...<i class="fad fa-redo-alt fa-spin"></i>');
        $('#saveBtnLeadNotes').attr("disabled", "disabled");
        var formData = new FormData($('#leadFormNotes')[0]);
        $.ajax({ 
            data: formData,      
            url: "{{ route('leadnotecrud.store') }}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                $(".text-danger").text("");
                if($.isEmptyObject(data.error)){
                    $('#leadFormNotes').trigger("reset");
                    $(".select_list").val([]).trigger("change");
                    $('#saveBtnLeadNotes').html('Crear Nota');
                    $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
                    $('.data-table-notes').DataTable().draw(false);
                    $('.data-table').DataTable().draw(false);
                    toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                }else{
                    $.each( data.error, function( key, value ) {
                        $("#leadNoteError").append('<li><label class="text-red">'+value+'</label></li>');
                        // $("#"+key+"_error").text(value);
                    });
                    $('#saveBtnLeadNotes').html('Crear Nota');
                    $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
                }
            },
            error: function (data){
                console.log('Error:', data);
                $('#saveBtnLeadNotes').html('Guardar Cambios');
                $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
            }
        });
    });
</script>