<script>
    // Modal de editar Lead
	$('body').on('click', '.storyLead', function () {
    	$('#leadFormStory').trigger("reset");
        $(".select_list").val([]).trigger("change");
        $('#div_leads_sub_status_list').hide();
        $('#leadStoryModal').modal('show');
        $("#lead_id_notes_story").val($(this).data('object'));
        $("#id_note_story").val('');
        $("#leadNoteError").html("");
        var lead_id_story = $(this).data('object');
        $.get("{{route('leadcrud.index') }}" +'/' + lead_id_story +'/edit', function (data) {
            // console.log(data[0]);
            if(data[0].lead_status_id==5){
                $("#dt_call_reminder").val(moment(data[0].dt_call_reminder).format("YYYY-MM-DDTHH:mm"));
                $("#dt_call_reminder_element").show();  
            }
            else{
                $("#dt_call_reminder_element").hide();
            }
            // $("#leads_status_list").val(data[0].lead_status_id).trigger('change.select2');

            $("#leadInfoStory").html(`<ul class="list-unstyled">
                                    <li class="h3"><i class="fad fa-user text-lightblue"></i> &nbsp;`+data[0].student_first_name +' '+ data[0].student_last_name +`</li>
                                    <li><i class="fad fa-map-marked-alt text-lightblue"></i>&nbsp;`+data[0].province_name +`</li>
                                    <li><i class="fad fa-scroll text-lightblue"></i>&nbsp;`+data[0].course_name +`</li>
                                    <li><i class="fad fa-phone-volume text-lightblue"></i>&nbsp;`+data[0].student_mobile+`</li>
                                    <li><i class="fad fa-envelope text-lightblue"></i>&nbsp; `+data[0].student_email+`</li>
                                </ul>`);
        });

        $('#leads_status_list').on('select2:select', function (e) {    
            var status_id = [$(this).val()];
            if(status_id.includes('4')){
                $('#div_leads_sub_status_list').show();
            }else{
                $('#div_leads_sub_status_list').hide();
                $("#leads_sub_status_list").empty().trigger("change");
            }
            // $("#leads_sub_status_list").empty().trigger("change");
            // if($(this).select2('val') == 13 || $(this).select2('val') == 5 || $(this).select2('val') == 3 || $(this).select2('val') == 8 || $(this).select2('val') == 7 || $(this).select2('val') == 2){
            //     $.get("{{url('api/substatus')}}",
            //     {option: status_id},  
            //     function(data) {    
            //         var lead_sub_status_filter = $('#leads_sub_status_list');
            //         lead_sub_status_filter.empty();
            //         $.each(data, function(index, element) {
            //             lead_sub_status_filter.append("<option value='"+ element.id +"'>" + element.name + "</option>");
            //         });
            //     });
            // }
        });
		const table_notes = $('.data-table-notes').DataTable({
            destroy:true,
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            ajax:  "{{ url('leads/notes/') }}"+"/"+lead_id_story,
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
    $('#leadFormStory').submit(function(e) {
        $("#leadNoteError").html("");
        e.preventDefault();
        $('#saveBtnLeadNotesStory').html('Enviando...<i class="fad fa-redo-alt fa-spin"></i>');
        $('#saveBtnLeadNotesStory').attr("disabled", "disabled");
        var formData = new FormData($('#leadFormStory')[0]);
        $.ajax({ 
            data: formData,      
            url: "{{ route('leadnotes.story') }}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                $(".text-danger").text("");
                if($.isEmptyObject(data.error)){
                    $('#leadFormStory').trigger("reset");
                    $(".select_list").val([]).trigger("change");
                    $('#saveBtnLeadNotesStory').html('Crear Nota');
                    $('#saveBtnLeadNotesStory').removeAttr("disabled", "disabled");
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
                    $('#saveBtnLeadNotesStory').html('Crear Nota');
                    $('#saveBtnLeadNotesStory').removeAttr("disabled", "disabled");
                }
            },
            error: function (data){
                // console.log('Error:', data);
                $('#saveBtnLeadNotesStory').html('Crear Nota');
                $('#saveBtnLeadNotesStory').removeAttr("disabled", "disabled");
            }
        });
    });

    // Editar Nota Gestión
	$('body').on('click', '.editNote', function () {
        var note_id = $(this).data('object') // Extract info from data-* attributes
        $("#leadNoteError").html("")
        $('#leadFormStory').trigger("reset")
		$.ajax({
			type:"GET",
			url: "/leads/notes/edit/" + note_id,
			dataType: 'json',
			success: function(res){
				console.log(res)
				$("#lead_notes_via_story").data('select2').trigger('select', {
					data: {"id": res.sent_method}
				});
				$("#leads_sub_status_list").data('select2').trigger('select', {
					data: {"id": res.lead_sub_status_id }
				});
				$("#leads_status_list").data('select2').trigger('select', {
					data: {"id": res.lead_status_id }
				});
                $("#dt_call_reminder").val(moment(res.dt_call_reminder).format("YYYY-MM-DDTHH:mm"));
				$('#lead_notes_story_observation').val(res.observation);
				$('#id_note_story').val(res.id);
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