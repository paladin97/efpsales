<script>
    $('body').on('click', '.caseEdit', function () {
        $("#contract_actions").val('edit');
        $("#EditEnrollError").html("");
        $('#editContractForm').trigger("reset");
        var contract_id = $(this).data('object');
        $.get("{{route('contractcrud.index') }}" +'/' + contract_id +'/edit', function (data) {
            // console.log(data);
            var fechaLoad = moment().format('YYYY-MM-DD');
            //Aparecer tutor si es menor de edad
            $('#samepayer').hide();
            $('#modalHeadingContract').html("Editar Contrato - Al editar el contrato debe rellenar la Forma de Pago");
            $('#saveBtn').val("edit-contract");
            $('#caseModal').modal('show');
            //Se colocan los campos desde la base de datos
            $('#contract_id').val(data[0].id);
            $('#contract_dt_creation').val(fechaLoad);
            $('#contract_enrollment_number').val(data[0].enrollment_number);
            $('#case_type_id').val(data[0].case_type_id);
            $('#case_lead_id').val(data['lead_id_encrypted']);
            //Cliente
            $('#case_first_name').val(data[0].name).change();
            $('#case_last_name').val(data[0].last_name).change();
            $('#case_dni').val(data[0].dni).change();
            $('#case_address').val(data[0].address);
            $('#case_town').val(data[0].town);
            $('#case_cp').val(data[0].postal_code);
            $('#case_studies').val(data[0].studies);
            $('#case_profession').val(data[0].profession);
            $('#case_email').val(data[0].mail);
            $('#case_mobile').val(data[0].mobile);
            $('#case_phone').val(data[0].phone);
            $('#case_dt_birth').val(data[0].dt_birth);
            $('#gender').val(data[0].gender);
            $('#has_work').val(data[0].has_work);
            
            //Pagador
            //si es igual pagador entonces pone el check
            if(data[0].person_id == data[0].payer_id){
                $("#same_payer_person").prop( "checked", true );
                $('#samepayer').hide();
            }
            else{
                $("#same_payer_person").prop( "checked", false );
                $('#samepayer').show();
            }
            
            $('#case_first_name_payer').val(data[0].name_t).change();
            $('#case_last_name_payer').val(data[0].last_name_t).change();
            $('#case_dni_payer').val(data[0].dni_t);
            $('#case_address_payer').val(data[0].address_t);
            $('#case_town_payer').val(data[0].town_t);
            $('#case_cp_payer').val(data[0].postal_code_t);
            $('#case_email_payer').val(data[0].mail_t);
            $('#case_mobile_payer').val(data[0].mobile_t);
            $('#case_phone_payer').val(data[0].phone_t);
            $('#case_dt_birth_payer').val(data[0].dt_birth_t);
            $('#case_gender_payer').val(data[0].gender_t);
            
            //Contrato
            $('#contract_id').val(data[0].id);
            $('#discount_value').val((data[0].discount_value)? data[0].discount_value : 0);
            $('#case_increase_value').val((data[0].increase_value)? data[0].increase_value : 0);
            $('#bonificado').val(data[0].discount_voucher);
            $('#fundae').val(data[0].fundae);
            $('#s_payment').val(data[0].s_payment);
            $('#pp_initial_payment').val(parseFloat(data[0].pp_initial_payment));
            $('#pp_fee_value').val(data[0].pp_fee_value);
            $('#pp_enroll_payment').val(data[0].pp_enroll_payment);
            $('#pp_dt_first_payment').val(data[0].pp_dt_first_payment);
            $('#pp_dt_final_payment').val(data[0].pp_dt_final_payment);
            $('#m_payment').val(parseFloat(data[0].m_payment));
            $('#card_holder_name').val(data[0].cc_name);
            $('#card_number').val(data[0].cc_number);
            $('#expiry_month').val(moment(data[0].cc_expiry, 'YYYY-MM-DD').format('MM'));
            $('#expiry_year').val(moment(data[0].cc_expiry, 'YYYY-MM-DD').format('YYYY'));
            $('#cvv').val(data[0].cc_secret_code);
            $('#dd_holder_name').val(data[0].dd_holder_name);
            $('#dd_holder_address').val(data[0].dd_holder_address);
            $('#dd_holder_dni').val(data[0].dd_holder_dni);
            $('#dd_iban').val(data[0].dd_iban);
            $('#dd_bank_name').val(data[0].dd_bank_name);
            $('#t_payment_concept').val(data[0].t_payment_concept);
            $('#t_iban').val(data[0].t_iban);
            $('#paypal_receipt').val(data[0].p_receipt);
            
            if(data[0].sinister_check){
                $("#sinister_check").prop("checked", true);
            }
            if(data[0].cash){
                $('#cash_payment').prop("checked", true);
            }
            $('#contract_observations').summernote('code', data[0].observations);
            $('#management_observations').val(data[0].management_observations);
            
            /*Se rellenan los campos de tipo select*/
            //Inicia expediente
            
            $("#case_provinces_list").val(data[0].client_province_id).change();
            $("#case_countries_list").val(data[0].client_country_id).change();
            //Pagador
            $("#case_provinces_list_payer").val(data[0].client_province_id_t).change();
            $("#case_countries_list_payer").val(data[0].client_country_id_t).change();
            $("#user_id_list").val(data[0].agent_id).change();
            $("#contract_courses_list").val(data[0].course_id).change();
            $("#material_list").val(data[0].material_id).change();
            $("#enroll").val(data[0].enroll).change();
            $('#enroll_postpone_start').val(data[0].enroll_postpone_start);
            $('#postpone_enroll_start').val(data[0].dt_postpone_enroll_start);
            $('#enroll_postpone_end').val(data[0].enroll_postpone_end);
            // $('#enroll_postpone_end').val(parseFloat(data[0].enroll_postpone_end)).change();
            $('#postpone_enroll_end').val(data[0].dt_postpone_enroll_end);
            //Para asignar la cantidad de cuotas pero no llamar el evento
            $("#pp_fee_quantity").val(data[0].pp_fee_quantity).trigger('change.select2');
            //Fin expediente
            //Forma de pago
            $("#contract_payment_type_id").val(data[0].contract_payment_type_id).change();

            switch (data[0].contract_payment_type_id) {
                case 1:
                    $("#single_payment").removeClass('d-none');
                    $("#postponed_payment").addClass('d-none');
                    $("#monthly_payment").addClass('d-none');
                    break;
                case 2:
                    $("#single_payment").addClass('d-none');
                    $("#postponed_payment").removeClass('d-none');
                    $("#monthly_payment").addClass('d-none');
                    break;
                case 3:
                    $("#single_payment").addClass('d-none');
                    $("#postponed_payment").addClass('d-none');
                    $("#monthly_payment").removeClass('d-none');
                    break;
                default:
                    $("#single_payment").addClass('d-none');
                    $("#postponed_payment").addClass('d-none');
                    $("#monthly_payment").addClass('hide');
            }
            //Fin forma de pago
            //Método de Pago
            $("#contract_method_type_id").val(data[0].contract_method_type_id).change();
            
            switch (data[0].contract_method_type_id) {
                case 1:
                    $('#card_number').mask('0000 0000 0000 0000');
                    $('#contract_method_type_1').removeClass('hide');
                    $('#contract_method_type_2').addClass('hide');
                    $('#contract_method_type_3').addClass('hide');
                    $('#contract_method_type_4').addClass('hide');
                    break;
                case 2:
                    $('#debit_iban').mask('SS00 0000 0000 0000 0000 0000');
                    $('#contract_method_type_2').removeClass('hide');
                    $('#contract_method_type_1').addClass('hide');
                    $('#contract_method_type_3').addClass('hide');
                    $('#contract_method_type_4').addClass('hide');
                    break;
                case 3:
                    $('#contract_method_type_3').removeClass('hide');
                    $('#contract_method_type_1').addClass('hide');
                    $('#contract_method_type_2').addClass('hide');
                    $('#contract_method_type_4').addClass('hide');
                    break;
                case 4:
                    $('#contract_method_type_4').removeClass('hide');
                    $('#contract_method_type_1').addClass('hide');
                    $('#contract_method_type_2').addClass('hide');
                    $('#contract_method_type_3').addClass('hide');
                    break;
                default:
                    $('#contract_method_type_4').addClass('hide');
                    $('#contract_method_type_1').addClass('hide');
                    $('#contract_method_type_2').addClass('hide');
                    $('#contract_method_type_3').addClass('hide');
            }

            //Fin Método de Pago
            $("#companies_list").data('select2').trigger('select', {
                    data: {"id": data[0].company_id}
            });

            /*Otros Factores*/
            $("#validity").data('select2').trigger('select', {
                    data: {"id": data[0].validity}
            });
            /*Fin Otros Factores*/
            saveBtnCase
            $("#saveBtnCase").html(`<i class="fad fa-download"></i> Guardar Cambios`);
            /*Fin relleno*/
   
        })
    });

    //Seleccionar la categoría con el servicio
    //Para los estados o provincias
    $('#case_service_category_list').on('select2:select', function (e) {  
        // console.log('change');  
        $("#service_info").html('');
        var category_id = [$(this).val()];
        $.get("{{url('api/service')}}",
            {option: category_id},  
            function(data) {    
                var case_service_list = $('#case_service_list');
                case_service_list.empty(); 
                case_service_list.append("<option value=''>Seleccione un asunto</option>");
                $.each(data, function(index, element) {
                    case_service_list.append("<option value='"+ element.id +"'>" + element.name + "</option>");
            });
        });
    });

    //Traer información del curso
    $('#contract_courses_list').change(function () { 
            var course_id = $(this).val();
            $.get("{{route('coursecrud.index') }}" +'/' + course_id +'/edit', function (data) {
                $("#course_info").html('<div class="mt-3 callout callout-info text-lightblue">'+
                            '<h5><i class="fad fa-info-circle"></i> Información del curso</h5>'+
                            '<div class="row text-lg">'+
                                '<input type="hidden" id="total_service" value="'+parseFloat(data.pvp)+'">'+
                                '<input type="hidden" id="service_group" value="'+parseFloat(data.area_id)+'">'+
                                '<input type="hidden" id="fee_tax">'+
                                '<div class="col-sm-12"><b>Curso: </b>'+data.name+' | <b>P.V.P: </b>'+(numeral(data.pvp).format('0,0.00')) +' €'+'</div>'+
                        '</div>');
                $("#discount").removeClass('d-none');
                $("#increase").removeClass('d-none');
                $.get("{{route('courseareacrud.index') }}" +'/' + data.area_id +'/edit', function (data) {
                    $("#fee_tax").val(data.fee_tax);
                });
            });
            $('#pp_fee_quantity').val([]).trigger("change"); //Reinicio el # de cuotas
            $('#pp_fee_value').val(0);                       //Reinicio el valor de la cuota
        });

    //reiniciar comentarios, esconder el descuento y metodos y formas de pago
    $('#caseModal').on('show.bs.modal', function (e) {
        $("#discount").addClass('d-none');
        $("#increase").addClass('d-none');
        $("#single_payment").addClass('d-none');
        $("#postponed_payment").addClass('d-none');
        $("#monthly_payment").addClass('d-none');
        $("#contract_method_type_1").addClass('d-none');
        $("#contract_method_type_2").addClass('d-none');
        $("#contract_method_type_3").addClass('d-none');
        $("#contract_method_type_4").addClass('d-none');
        $("#contract_method_type_5").addClass('d-none');
        $("#course_info").html('');
    });
    //Cuando cambia el tipo de pago
    $("#contract_payment_type_id").change(function () {
        // console.log($(this).val());
        switch($(this).val()) {
            case '1': //Contado
                $("#single_payment").removeClass('d-none');
                $("#postponed_payment").addClass('d-none');
                $("#monthly_payment").addClass('d-none');
                $("#pp_initial_payment").val(parseFloat(0));
                break;
            case '2': //Aplazado
                $("#single_payment").addClass('d-none');
                $("#postponed_payment").removeClass('d-none');
                $("#monthly_payment").addClass('d-none');
                break;
            case '3': //Especial
                $("#single_payment").addClass('d-none');
                $("#postponed_payment").addClass('d-none');
                $("#monthly_payment").removeClass('d-none');
                break;
            case '':
                $("#single_payment").addClass('d-none');
                $("#postponed_payment").addClass('d-none');
                $("#monthly_payment").addClass('d-none');
                break;
            default:
                // code block
        }
        
    });

    //Controla Fecha fin
    $("#pp_dt_first_payment").change(function () {
        initial = $(this).val();
        fQuantity = $("#pp_fee_quantity").val() ? $("#pp_fee_quantity").val() -1 : 0;
        $("#pp_dt_final_payment").val(moment(initial).add(fQuantity,'month').format('YYYY-MM-DD'));
    });

    //prepara el summernote
	$('#contract_observations').summernote({
            placeholder: 'Ingrese el Objeto del Encargo',
            tabsize: 2,
            height: 100
        });


    //De acuerdo al importe de la cuota seleccionada y el descuento, calcula el valor de cada cuota
    $("#pp_fee_quantity").change(function () {
        // console.log($("#pp_fee_quantity").val() );
        var total_service = $("#total_service").val() ? $("#total_service").val() : 0;
        var increase_tax = $("#increase_tax").val() ? $("#increase_tax").val() : 0;
        var fee_tax = 0;
        var enroll_value = 0;
        if ($('#contract_method_type_id').val() == 5){//Financiación por Financiera
            //De acuerdo al método de pago revisa si es financiera para ajustar las cuotas
            fee_tax = $('#fee_tax').val();
        }
        else{
            fee_tax = 0;
        }
        if ($('#enroll').val() == 1){//Lleva matricula
            //De acuerdo al método de pago revisa si es financiera para ajustar las cuotas
            //PENDIENTE los 300 hay que sacarlos de la base de datos
            enroll_value = 300;

        }
        else{
            enroll_value = 0;
        }
        var advance = $("#pp_initial_payment").val() ? parseFloat($("#pp_initial_payment").val()) : 0;
        var fee_quantity = $("#pp_fee_quantity").val() ? $("#pp_fee_quantity").val() : 0;
        var fee_value = ((((parseFloat(total_service) + parseFloat(0)) - parseFloat(advance) )/ parseFloat(fee_quantity)) + parseFloat(increase_tax)+ parseFloat(fee_tax) ) ; 
        $("#pp_fee_value").val(parseFloat(fee_value).toFixed(2));
    });

    //Si cambio el anticipo reinicio las cuotas y su valor
    $("#pp_initial_payment").change(function () {
        $('#pp_fee_quantity').val([]).trigger("change"); //Reinicio el # de cuotas
        $('#pp_fee_value').val(0);                       //Reinicio el valor de la cuota
    });

    //Si cambio si lleva matricula o no
    $("#enroll").change(function () {
        $('#pp_fee_quantity').val([]).trigger("change"); //Reinicio el # de cuotas
        $('#pp_fee_value').val(0);                       //Reinicio el valor de la cuota
        var total_service = $("#total_service").val() ? $("#total_service").val() : 0;
        if ($('#enroll').val() == 1){//Lleva matricula
            //se debe mostrar la fecha aplazada si es el caso
            $("#postpone_enroll_div").removeClass('d-none');

        }
        else{
            $("#postpone_enroll").val('');
            $("#postpone_enroll_div").addClass('d-none');
        }
    });


    //Para los medios de pago
    $("#contract_method_type_id").change(function () {
        // console.log($(this).val());
        switch($(this).val()) {
            case '1'://Tarjeta
                $('#pp_fee_quantity').val([]).trigger("change"); //Reinicio el # de cuotas
                $('#pp_fee_value').val(0);                       //Reinicio el valor de la cuota
                $('#card_number').mask('0000 0000 0000 0000');
                $('#contract_method_type_1').removeClass('d-none');
                $('#contract_method_type_2').addClass('d-none');
                $('#contract_method_type_3').addClass('d-none');
                $('#contract_method_type_4').addClass('d-none');
                $('#contract_method_type_5').addClass('d-none');
                break;
            case '2'://Domiciliación
                $('#pp_fee_quantity').val([]).trigger("change"); //Reinicio el # de cuotas
                $('#pp_fee_value').val(0);                       //Reinicio el valor de la cuota
                $('#debit_iban').mask('SS00 0000 0000 0000 0000 0000');
                $('#contract_method_type_2').removeClass('d-none');
                $('#contract_method_type_1').addClass('d-none');
                $('#contract_method_type_3').addClass('d-none');
                $('#contract_method_type_4').addClass('d-none');
                $('#contract_method_type_5').addClass('d-none');
                break;
            case '3'://transferencia
                $('#pp_fee_quantity').val([]).trigger("change"); //Reinicio el # de cuotas
                $('#pp_fee_value').val(0);                       //Reinicio el valor de la cuota
                $('#contract_method_type_3').removeClass('d-none');
                $('#contract_method_type_1').addClass('d-none');
                $('#contract_method_type_2').addClass('d-none');
                $('#contract_method_type_4').addClass('d-none');
                $('#contract_method_type_5').addClass('d-none');
                break;
            case '4'://Paypal
                $('#contract_method_type_4').removeClass('d-none');
                $('#contract_method_type_1').addClass('d-none');
                $('#contract_method_type_2').addClass('d-none');
                $('#contract_method_type_3').addClass('d-none');
                $('#contract_method_type_5').addClass('d-none');
                break;
            case '5'://Financiera
                $('#pp_fee_quantity').val([]).trigger("change"); //Reinicio el # de cuotas
                $('#pp_fee_value').val(0);                       //Reinicio el valor de la cuota
                $('#contract_method_type_5').addClass('d-none');
                $('#contract_method_type_1').addClass('d-none');
                $('#contract_method_type_2').addClass('d-none');
                $('#contract_method_type_3').addClass('d-none');
                $('#contract_method_type_4').addClass('d-none');
                break;
            case '':
                $('#contract_method_type_4').addClass('d-none');
                $('#contract_method_type_1').addClass('d-none');
                $('#contract_method_type_2').addClass('d-none');
                $('#contract_method_type_3').addClass('d-none');
                break;
            default:
                // code block
        }
        $('#paymentMethodInfo').html($('#contract_method_type_id option:selected').text());
    });

    //Cuando guarda los datos y crea el expediente
    $('#saveBtnCase').click(function (e) {
        var contract_actions = $("#contract_actions").val();
        if (contract_actions == 'edit') {//Solo funciona si esta rematriculando
            $("#leadCaseError").html("");
            e.preventDefault();
            $(this).html('Enviando..<i class="fad fa-spinner fa-pulse"></i>');
            $(this).attr("disabled", "disabled");
            var formData = new FormData($('#leadFormCase')[0]);
            $.ajax({ 
                data: formData,      
                url: "{{ route('contractcrud.store') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#leadFormCase').trigger("reset");
                        $(".select_list").val([]).trigger("change");
                        $('#caseModal').modal('hide');
                        $('#saveBtnCase').html('<i class="fad fa-download"></i> Guardar Cambios');
                        $('#saveBtnCase').removeAttr("disabled", "disabled");
                        table.draw(false);
                        toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                    }else{
                        toastr.error('No se puede enviar la información, revise los siguientes errores', '', {timeOut: 3000,positionClass: "toast-top-center"});
                        $.each( data.error, function( key, value ) {
                            $("#leadCaseError").append('<li><label class="text-red">'+value+'</label></li>');
                            // $("#"+key+"_error").text(value);
                        });
                        $('#saveBtnCase').html('<i class="fad fa-download"></i> Guardar Cambios');
                        $('#saveBtnCase').removeAttr("disabled", "disabled");
                    }
                }
            }); 
        } 
    });

    //Logíca para controlar el mismo pagador o diferente
    $('#same_payer_person').change(function() {
        if(this.checked != true){
            $('#samepayer').show();
        }
        else{
            $('#samepayer').hide();
        }
    });
</script>