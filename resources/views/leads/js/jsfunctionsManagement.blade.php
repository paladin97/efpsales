<script>
    // Modal de editar Lead
	$('body').on('click', '.manageLead', function () {
    	$('#leadFormNotes').trigger("reset");
        $(".select_list").val([]).trigger("change");
        $('#div_leads_sub_status_list').hide();
        $('#leadNotesModal').modal('show');
        $("#lead_id_notes").val($(this).data('object'));
        $("#id_note").val('');
        $("#leadNoteError").html("");
        var lead_id = $(this).data('object');
        $.get("{{route('leadcrud.index') }}" +'/' + lead_id +'/edit', function (data) {
            // console.log(data[0]);
            if(data[0].lead_status_id==5){
            //  || (data[0].lead_status_id==3) || (data[0].lead_status_id==8) || (data[0].lead_status_id==7)
            // || (data[0].lead_status_id==13) || (data[0].lead_status_id==10) || (data[0].lead_status_id==9))
                $("#dt_call_reminder").val(moment(data[0].dt_call_reminder).format("YYYY-MM-DDTHH:mm"));
                $("#dt_call_reminder_element").show();  
            }
            else{
                $("#dt_call_reminder_element").hide();
            }
            // $("#leads_status_list").val(data[0].lead_status_id).trigger('change.select2');

            $("#leadInfo").html(`<ul class="list-unstyled">
                                    <li class="h3"><i class="fad fa-user text-lightblue"></i> &nbsp;`+data[0].student_first_name +' '+ data[0].student_last_name +`</li>
                                    <li><i class="fad fa-map-marked-alt text-lightblue"></i>&nbsp;`+data[0].province_name +`</li>
                                    <li><i class="fad fa-scroll text-lightblue"></i>&nbsp;`+data[0].course_name +`</li>
                                    <li><i class="fad fa-phone-volume text-lightblue"></i>&nbsp;`+data[0].student_mobile+`</li>
                                    <li><i class="fad fa-envelope text-lightblue"></i>&nbsp; `+data[0].student_email+`</li>
                                </ul>`);
        });

        $('#leads_status_list').on('select2:select', function (e) {    
            var status_id = [$(this).val()];
            if(status_id.includes('13') || status_id.includes('5') || status_id.includes('3') || status_id.includes('8') || status_id.includes('7') || status_id.includes('2')){
                $('#div_leads_sub_status_list').show();
            }else{
                $('#div_leads_sub_status_list').hide();
                $("#leads_sub_status_list").empty().trigger("change");
            }
            $("#leads_sub_status_list").empty().trigger("change");
            if($(this).select2('val') == 13 || $(this).select2('val') == 5 || $(this).select2('val') == 3 || $(this).select2('val') == 8 || $(this).select2('val') == 7 || $(this).select2('val') == 2){
                $.get("{{url('api/substatus')}}",
                // {option: "Sin Seleccionar"},
                {option: status_id},  
                function(data) {    
                    var lead_sub_status_filter = $('#leads_sub_status_list');
                    lead_sub_status_filter.empty();
                    $.each(data, function(index, element) {
                        lead_sub_status_filter.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                    });
                });
            }
        });
		const table_notes = $('.data-table-notes').DataTable({
            destroy:true,
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            ajax:  "{{ url('leads/notes/') }}"+"/"+lead_id,
            dom: 'lrtp',
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
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
            },
            columns: [
                { data: 'created_at', name: 'le.created_at'},
				// { data: 'created_at', name: 'le.created_at'},
                // { data: 'dt_call_reminder', name: 'le.dt_call_reminder'},
                { data: 'dt_call_reminder', name: 'le.dt_call_reminder'},
				{ data: 'sent_method', name: 'le.sent_method'},
				{ data: 'status', name: 'status'},
				{ data: 'sub_status', name: 'sub_status'},
				{ data: 'observation', name: 'le.observation'},
				{ data: 'user_note_name', name: 'user_note_name'},
                { data: 'acciones', name: 'acciones'},
                { data: 'id', name: 'id'},
                ],
            columnDefs: [
                {
                    "render": function ( data, type, row ) {
                        // var text = parseInt(data)  ? data : " ";
                        // console.log(row);
                        return moment(data).format("DD/MM/YYYY HH:mm");
                    },"targets": [0,1]
                },
                // {
                //     "render": function ( data, type, row ) {
                //         // var text = parseInt(data)  ? data : " ";
                //         // console.log(data);
                //         return moment(data).format("HH:mm");
                //     }, "targets": [1,3]
                // },
                { targets: 6, visible: false},
                {
                    targets: 8,
                    visible:false,
                    searchable:false
                }
            ],
            drawCallback: function( settings ) {
                var column = table_notes.column(6);
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
                    $('.data-table-notes').DataTable().draw(false);
                }else{
                    toastr.error('No se puede enviar la información, revise los siguientes errores', '', {timeOut: 3000,positionClass: "toast-top-center"});
                    $.each( data.error, function( key, value ) {
                        $("#leadNoteError").append('<li><label class="text-red">'+value+'</label></li>');
                        // $("#"+key+"_error").text(value);
                    });
                    $('#saveBtnLeadNotes').html('Crear Nota');
                    $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
                }
            },
            error: function (data){
                // console.log('Error:', data);
                $('#saveBtnLeadNotes').html('Crear Nota');
                $('#saveBtnLeadNotes').removeAttr("disabled", "disabled");
            }
        });
    });

    // Editar Nota Gestión
	$('body').on('click', '.editNote', function () {
        var note_id = $(this).data('object') // Extract info from data-* attributes
        $("#leadNoteError").html("")
        $('#leadFormNotes').trigger("reset")
		$.ajax({
			type:"GET",
			url: "/leads/notes/edit/" + note_id,
			dataType: 'json',
			success: function(res){
				console.log(res)
				$("#lead_notes_via").data('select2').trigger('select', {
					data: {"id": res.sent_method}
				});
				$("#leads_sub_status_list").data('select2').trigger('select', {
					data: {"id": res.lead_sub_status_id }
				});
				$("#leads_status_list").data('select2').trigger('select', {
					data: {"id": res.lead_status_id }
				});
                $("#dt_call_reminder").val(moment(res.dt_call_reminder).format("YYYY-MM-DDTHH:mm"));
				$('#lead_notes_observation').val(res.observation);
				$('#id_note').val(res.id);
				// Si es administrador
			}
		});

    });
    //Cuando es Volver a llamar debe mostrar la alerta
    // $('#leads_status_list').change(function () {     
        // console.log("cambio estado lead");
        // var status_id = [$(this).val()];
        // console.log(status_id);
        
        // if (['5'].includes(status_id[0])){
            // $("#dt_call_reminder_element").show();
            // $("#dt_call_reminder").val(new Date().toJSON().slice(0,19));
        // }
        // else{
        //     $("#dt_call_reminder_element").hide();
        // }
        
    // });
</script>