
<script>
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Modal Previo a Enviar a Sequra
        $('body').on('click', '.caseFinancial', function () {
            var contract_id = $(this).data('object');
            $.get("{{route('contractcrud.index') }}" +'/' + contract_id +'/edit', function (data) {
                $("#sequrainfo").html(`<ul class="list-unstyled">
                                        <li class="h3"><i class="fad fa-user text-lightblue mr-2"></i> &nbsp;`+data[0].name +' '+ data[0].last_name +`</li>
                                        <li><i class="fad fa-map-marked-alt text-lightblue mr-2"></i>&nbsp;`+data[0].client_province +`</li>
                                        <li><i class="fad fa-scroll text-lightblue mr-2"></i>&nbsp;`+data[0].course_name +`</li>
                                        <li><i class="fad fa-phone-volume text-lightblue mr-2"></i>&nbsp;`+data[0].mobile+`</li>
                                        <li><i class="fad fa-envelope text-lightblue mr-2"></i>&nbsp; `+data[0].mail+`</li>
                                        <li><i class="fad fa-euro-sign text-lightblue mr-2"></i>Total Matrícula:<b> `+data[0].total+`€ </b>
                                            | Nº Cuotas: <b>`+data[0].pp_fee_quantity+`</b>
                                            | Valor Cuota: <b>`+data[0].pp_fee_value+`€</b>
                                        </li>
                                    </ul>`);
                //Llenar datos de financiera
                var dateSequra = moment(data[0].dt_birth_t);
                var dateSequraYear = dateSequra.format('YYYY');
                var dateSequraMonth = dateSequra.format('M');
                var dateSequraDay = dateSequra.format('D');
                $('#order_order_ref_1').val(data[0].enrollment_number+'_'+data[0].id);
                $('#order_merchant_reference').val('SEND TEST');
                $('#order_nin').val(data[0].dni_t);
                $('#order_given_names').val(data[0].name_t);
                $('#order_surnames').val(data[0].last_name_t);
                $('#order_email_address').val(data[0].mail_t);
                $('#order_date_of_birth_day').val(dateSequraDay);
                $('#order_date_of_birth_month').val(dateSequraMonth);
                $('#order_date_of_birth_year').val(dateSequraYear);
                $('#order_address_street').val(data[0].address_t);
                $('#order_address_postal_code').val(data[0].postal_code_t);
                $('#order_address_city').val(data[0].town_t);
                $('#order_mobile_phone').val(data[0].mobile_t);
                //Order Info
                $('#order_service_reference').val(data[0].enrollment_number);
                $('#order_service_name').val(data[0].short_code);
                $('#order_service_price_with_tax').val(data[0].crse_pvp);
                // $('#order_service_quantity').val(data[0].pp_fee_quantity);
            });
            $('#financialservice').modal('show');
        });
        
        //Boton confirmación enviar a sequra
        //Guardar Cambios
        $("#formSequra").submit(function(event){
            event.preventDefault(); //prevent default action 
            // $('#sequraSave').html('Enviando...<i class="fad fa-redo-alt fa-spin"></i>');
            // $('#sequraSave').attr("disabled", "disabled");
            var post_url = $(this).attr("action"); //get form action url
            var request_method = $(this).attr("method"); //get form GET/POST method
            var form_data = $(this).serialize(); //Encode form elements for submission
            var total_request = post_url + '?' + form_data
            window.open(total_request);
            // $.ajax({
            //     url : post_url,
            //     type: request_method,
            //     data : form_data
            // }).done(function(response){ //
            //     $('#sequraSave').html('Financiar con SeQura');
            //     $('#sequraSave').removeAttr("disabled", "disabled");
            //     $('#financialservice').modal('hide');
            //     toastr.success('Respuesta del Servidor', '', {timeOut: 4000,positionClass: "toast-top-center"});
            // });
        });
    });
</script>