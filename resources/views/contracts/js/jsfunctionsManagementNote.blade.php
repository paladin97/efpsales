<script>
    // Modal de editar Lead
	$('body').on('click', '.managementNote', function () {
    	var contract_id = $(this).data('object');
        $("#managementNoteError").html("");
        $('#managementFormNotes').trigger("reset");
        $('#managementNotesModal').modal('show');
        $.get("{{route('contractcrud.index') }}" +'/' + contract_id +'/edit', function (data) {
            $("#managementContractInfo").html(`<ul class="list-unstyled">
                                    <li class="h3"><i class="fad fa-user text-lightblue"></i> &nbsp;`+data[0].name +' '+ data[0].last_name +`</li>
                                    @if(Auth::user()->hasRole('superadmin'))
                                        <li><i class="fad fa-envelope-open-dollar text-green"></i> Cobrado: <b class="text-green">`+(data.total_charged==null ? '0.00' : data.total_charged)+` €</b></li>
                                        <li><i class="fad fa-envelope-open-dollar text-red"></i> Pendiente: <b class="text-red">`+(data.total_pending==null ? '0.00' : data.total_pending)+` €</b></li>
                                    @endif
                                    <li><i class="fad fa-scroll text-lightblue"></i>&nbsp;`+data[0].course_name +`</li>
                                    <li><i class="fad fa-file-contract text-lightblue"></i>&nbsp;`+data[0].enrollment_number +`</li>
                                    <li><i class="fad fa-user-tie text-lightblue"></i>&nbsp;`+data[0].agent_name+`</li>
                                </ul>`);
            $("div.toolbar_sub_cf").html('');  
            $("#contract_id_notes").val(data[0].id);    
            $("#contract_enrollment_notes").val(data[0].enrollment_number);  
        });
		const table_management_notes = $('#datatable_management').DataTable({
            destroy:true,
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            ajax:  "{{ url('contracts/managementnotes/') }}"+"/"+contract_id,
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
                { data: 'created_at', name: 'created_at'},
                { data: 'dt_reminder', name: 'management_dt_reminder'},
                { data: 'contact_method', name: 'contact_method'},
                { data: 'contact_type', name: 'contact_type'},
                { data: 'category', name: 'category'},
                { data: 'observations', name: 'observations'},
                { data: 'user_note_name', name: 'user_note_name'},
                { data: 'pathnote', name: 'pathnote'},
                { data: 'acciones', name: 'acciones'},
                { data: 'id', name: 'id'},
                ],
            columnDefs: [   
                {
                    "render": function ( data, type, row ) {
                        return moment(data).format("DD/MM/YYYY - HH:mm");
                    },"targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return moment(data).format("DD/MM/YYYY - HH:mm");
                    },"targets": 1
                },
                // {
                //     "render": function ( data, type, row ) {
                //         return moment(data).format("HH:mm:ss");
                //     }, "targets": 2
                // },
                {
				render: function ( data, type, row ) {
					return '<span class="badge d-block text-xs '+row['color']+'">'+data+'</span>';
				}, targets: [4]
			    },
                { targets: [6,8,9], visible: false},
                { targets: [0,1,2,3,4,5,6,7], className: 'dt-body-center '}
            ],
            drawCallback: function( settings ) {
                var column = table_notes.column(5);
                @if(Auth::user()->hasRole('superadmin'))
                    column.visible(true);
                @endif
            },
            drawCallback: function( settings ) {
                var column = table_management_notes.column(6);
                @if(Auth::user()->hasRole('superadmin'))
                    column.visible(true);
                    
                @endif
                var column2 = table_management_notes.column(8);
                @if(Auth::user()->hasRole('superadmin'))
                    column2.visible(true);
                @endif
            },
        }).responsive.recalc();
        table_management_notes.responsive.recalc();

    });

    //Guardar Cambios
    $('#managementFormNotes').submit(function(e) {
        $("#managementNoteError").html("");
        e.preventDefault();
        $('#saveBtnManagementNotes').html('Enviando...<i class="fad fa-redo-alt fa-spin"></i>');
        $('#saveBtnManagementNotes').attr("disabled", "disabled");
        var formData = new FormData($('#managementFormNotes')[0]);
        $.ajax({ 
            data: formData,      
            url: "{{ route('managementnotecrud.store') }}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                $(".text-danger").text("");
                if($.isEmptyObject(data.error)){
                    $('#managementFormNotes').trigger("reset");
                    $(".select_list").val([]).trigger("change");
                    $('#saveBtnManagementNotes').html('Crear Nota');
                    $('#saveBtnManagementNotes').removeAttr("disabled", "disabled");
                    $('.data-table-notes').DataTable().draw(false);
                    $('.data-table').DataTable().draw(false);
                    toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                    $('#datatable_management').DataTable().draw(false);
                }else{
                    $.each( data.error, function( key, value ) {
                        $("#managementNoteError").append('<li><label class="text-red">'+value+'</label></li>');
                        // $("#"+key+"_error").text(value);
                    });
                    $('#saveBtnManagementNotes').html('Crear Nota');
                    $('#saveBtnManagementNotes').removeAttr("disabled", "disabled");
                }
            },
            error: function (data){
                console.log('Error:', data);
                $('#saveBtnManagementNotes').html('Crear Nota');
                $('#saveBtnManagementNotes').removeAttr("disabled", "disabled");
            }
        });
    });

    // Editar Nota Gestión
	$('body').on('click', '.editManagementNote', function () {
        var note_id = $(this).data('id') // Extract info from data-* attributes
        $("#leadNoteError").html("")
        $('#leadFormNotes').trigger("reset")
		$.ajax({
			type:"GET",
			url: "/contracts/managementnotes/edit/" + note_id,
			dataType: 'json',
			success: function(res){
				// console.log(res);
				$("#management_notes_method").data('select2').trigger('select', {
					data: {"id": res.contact_method}
				});
				$("#management_notes_type").data('select2').trigger('select', {
					data: {"id": res.contact_type }
				});
				$("#management_category").data('select2').trigger('select', {
					data: {"id": res.management_note_category_id }
				});
                $("#management_dt_reminder").val(moment(res.dt_reminder).format("YYYY-MM-DDTHH:mm"));
				$('#management_notes_observation').val(res.observations);
				$('#id_note').val(res.id);
				// Si es administrador
			}
		});

    });

    //Eliminar Notas de Gestión
    $('body').on('click', '.deleteManagementNote', function () {
        var management_note_id = $(this).data("id");
        $.confirm({
            // theme: 'dark',
            title: '<i class="fad text-lightblue fa-exclamation-triangle"></i> <span class="text-lightblue">Atención</span>',
            content: '¿Esta seguro de realizar esta acción?',
            buttons: {
                confirmar :{
                    btnClass: 'btn-blue',
                    action: function () {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('managementnotecrud.store') }}"+'/'+management_note_id,
                            success: function (data) {
                                $("#datatable_management").DataTable().draw();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    },
                }
            }
        });
    });
    //Botones de uploadfile
    $("#management_file").filestyle({
        size: 'sm',
        btnClass : 'btn-success',
        text : '&nbsp; Escoger'
    });
    $(".file-green").filestyle({
        size: 'sm',
        buttonName : 'btn-success',
        buttonText : '&nbsp; Escoger'
    });
</script>