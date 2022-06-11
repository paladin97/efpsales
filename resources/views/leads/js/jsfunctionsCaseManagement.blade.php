<script>
    // Modal de editar Lead
	$('body').on('click', '.enrollStudent', function () {
        $('#leadFormCase').trigger("reset");
        var case_lead_id = $(this).data('object');
        $('#case_lead_id').val(case_lead_id);
        //Siempre oculta la información del tutor
        $('#samepayer').hide();
        var fechaLoad = moment().format('YYYY-MM-DD');
        $('#contract_dt_creation').val(fechaLoad);
        
        $.get("{{route('leadcrud.index') }}" +'/' + case_lead_id +'/edit', function (data) {
            // console.log(data[0]);
            // var fechaSplit = data[0].creation_date.split(/[-]/);
            // var fechaLoad = new Date();
            // fechaLoad =  fechaSplit[0]+'-'+fechaSplit[1]+'-'+fechaSplit[2]+'T'+fechaSplit[3]+':'+fechaSplit[4];
            $('#modelHeadingCase').html("Crear Expediente");
            //se colocan los datos del alumno en el formulario de matriculación
            $("#case_first_name").val(data[0].student_first_name);
            $("#case_last_name").val(data[0].student_last_name);
            $("#case_dt_birth").val(data[0].student_dt_birth);
            $("#case_provinces_list").data('select2').trigger('select', {
                    data: {"id": data[0].province_id}
            });
            $('#case_countries_list').val('179').change();//España por defecto
            $('#enroll').val('2').change();//Matricula No por defecto
            $('#material_list').val('2').change();//Material No por defecto
            $('#validity').val('24').change();//2 años por defecto No por defecto
            $("#user_id_list").val(data[0].agent_id).change();
            $("#companies_list").val(data[0].company_id).change();
            $("#contract_courses_list").val(data[0].course_id).change();
            $("#case_email").val(data[0].student_email);
            $("#case_mobile").val(data[0].student_mobile);
            $('#caseModal').modal('show');
        });
    });

    //Traer información del curso
    $('#contract_courses_list').change(function () { 
        // console.log("cambio estado lead");
        var course_id = $(this).val();
        $.get("{{route('coursecrud.index') }}" +'/' + course_id +'/edit', function (data) {
            // console.log('datos: ',data.pvp);
            
            $("#course_info").html('<div class="mt-3 callout callout-info text-lightblue">'+
                        '<h5><i class="fad fa-info-circle"></i> Datos del curso</h5>'+
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
                var total_service = $("#total_service").val() ? $("#total_service").val() : 0;
                var total_discount = $("#discount_value").val() ? $("#discount_value").val() : 0;
                var total_increase = $("#case_increase_value").val() ? $("#case_increase_value").val() : 0;
                var total_discount = (parseFloat(total_service) - parseFloat(total_discount)); 
                var total_increase = (total_discount + parseFloat(total_increase)); 
                
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
            placeholder: 'Ingrese observaciones del contrato',
            tabsize: 2,
            height: 100,
        });

    //De acuerdo al importe de la cuota seleccionada y el descuento, calcula el valor de cada cuota
    $("#pp_fee_quantity").change(function () {
        console.log($("#pp_fee_quantity").val() );
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
        
    });
    
    //Cuando escoge que si lleva matrícula, le coloca un importe inicial de 300€
    // $("#enroll").change(function () {
    //     switch($(this).val()) {
    //         case '1':
    //             $("#pp_initial_payment").val(0);
    //             break;
    //         case '2':
    //             $("#pp_initial_payment").val(0);
    //             break;
    //         default:
    //             $("#pp_initial_payment").val(0);
    //     }
    // });

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

    $("#material_list").change(function () {  //by Robert
        
        if($("#material_list").val() == 1){

            $("#order_div").removeClass('d-none');

        }
        else{
            
            $("#order_div").addClass('d-none');
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