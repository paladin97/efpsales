<script>
    
    $('body').on('click', '.massiveAssign', function () {
        var id = [];
        $("#leadassignagent").empty();
        if(rows_selected.length > 0){
            var bar = new Promise((resolve, reject) => {
                rows_selected.forEach((value, index, array) => {
                    $.get("{{route('leadcrud.index') }}" +'/' + value +'/edit', function (data) {
                        // console.log(data[0].course_id);
                        $("#leadinfoassign").html(`<div class="form-group">
                                                <label for="name" class="mb-n1">Comercial<label class="text-red">(*)</label></label>
                                                <select style="width:100%;" class="form-control assign_list" id="leads_agent_assign_list" name="leads_agent_assign_list[]">
                                                    <option value="7" selected>Sin asignar</option>`+
                                                    @foreach (App\Models\User::whereIn('status',[1])->get() as $cData)
                                                        @if ($cData->hasRole('comercial'))
                                                            `<option value="{{$cData->id}}">{{$cData->name}}</option>`+
                                                        @endif
                                                    @endforeach
                                                `</select>
                                                <div class="form-group">
                                                    <label for="name" class="mb-n1">Fecha de asignación  <label class="text-red">(*)</label></label>
                                                    <input type="datetime-local" class="form-control form-control-sm" id="dt_assignment_massive" name="dt_assignment_massive" placeholder="Ingrese una fecha" value="" maxlength="250">
                                                </div>
                                            </div>
                                        </div>`
                        );
                        $("#leadassignagent").append(
                                    `<li>`+data[0].student_first_name  +  ' '  +
                                                                        data[0].student_last_name   + ' | ' +
                                                                        data[0].student_mobile      + ' | ' +
                                                                        '<b>'+data[0].course_name   + ' | ' +
                                                                        data[0].province_name +`</b>
                                                                        </li>`);
                        $('.assign_list').select2();
                    });  
                    if (index === array.length -1) resolve();
                });
            });
            bar.then(() => {
                console.log('All Loaded!');
                $('.assign_list').select2({
                    placeholder: 'Seleccione',
                    language: {
                        // You can find all of the options in the language files provided in the
                        // build. They all must be functions that return the string that should be
                        // displayed.
                        inputTooShort: function () {
                            return "Debe introducir dos o más carácteres...";
                            },
                        inputTooLong: function(args) {
                        // args.maximum is the maximum allowed length
                        // args.input is the user-typed text
                        return "Ha ingresado muchos carácteres...";
                        },
                        errorLoading: function() {
                        return "Error cargando resultados";
                        },
                        loadingMore: function() {
                        return "Cargando más resultados";
                        },
                        noResults: function() {
                        return "No se ha encontrado ningún registro";
                        },
                        searching: function() {
                        return "Buscando...";
                        },
                        maximumSelected: function(args) {
                        // args.maximum is the maximum number of items the user may select
                        return "Error cargando resultados";
                        }
                    }
                });
            });
            $("#lead_id_massive_assign").val(rows_selected);
            $('#leadMassiveAssignModal').modal('show');
        }else{
            $.alert('Por favor seleccione por lo menos una casilla');
        }
    });

    $("#leadMassiveAssignModal").on('hidden.bs.modal', function(){
        $("#leadassignagent").empty();
    });

    $('#saveAssignLeadBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Enviando..<i class="fad fa-refresh fa-pulse"></i>');
        $(this).attr("disabled", "disabled");
        var formData = new FormData($('#leadAssignForm')[0]);
        $.ajax({ 
            data: formData,      
            url: "{{ route('leadcrud.massassign') }}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                $('#leadAssignForm').trigger("reset");
                $(".select_list").val([]).trigger("change");
                $('#saveAssignLeadBtn').html('Guardar Cambios');
                $('#saveAssignLeadBtn').removeAttr("disabled", "disabled");
                $('#leadMassiveAssignModal').modal('hide');
                $("#leadsToModify").html('');
                rows_selected.length = 0;
                table.draw(false);
                },
            error: function (data) {
                // console.log('Error:', data);
                // $(".alert").removeClass('hide');
                // $(".alert").addClass('show');
                // $(".alert").slideDown(300).delay(3000).slideUp(300);
                $('#saveAssignLeadBtn').html('Guardar Cambios');
                $('#saveAssignLeadBtn').removeAttr("disabled", "disabled");
                }
        });
    });
</script>