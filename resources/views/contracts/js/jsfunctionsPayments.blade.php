<script>
    //Para cambiar a responsive la tabla en el modal
    $("#contractPaymentsModal").on('shown', function(){
        alert("I want this to appear after the modal has opened!");
        $("#datatable").DataTable().responsive.recalc();
    }); 
    //Para gestionar los cobros
    $('body').on('click', '.caseCharges', function () { 
        $("#ContractFeeError").html("");
        $('#feePaymentsForm').trigger("reset");
        $("#contract_id_payments").val($(this).data('id'));
        var contract_id = $(this).data('object');
        $.get("{{route('contractcrud.index') }}" +'/' + contract_id +'/edit', function (data) {
            var contract_id_payment = data[0].id;
            // console.log(contract_id_payment);
            $('#contract_fee_information').html('<div class="col-sm-3"><p>Numero Matrícula: </b>'+ data[0].enrollment_number+'</p></div>'+
                                            '<div class="col-sm-3"><p><b></b>Cliente: </b>'+ data[0].name+' '+ data[0].last_name+'</p>'
                                            );
            $.when(
                $('#contractPaymentsModal').modal('show')
            ).then(function(){
                const table_notes = $('#datatable').DataTable({
                    destroy:true,
                    processing: true,
                    serverSide: true,
                    ajax:  "{{ url('contracts/fee/') }}"+"/"+contract_id_payment,
                    dom: "<'toolbar_cf'><'row'<'col-md-6'l>>"+
                                "<'row' <'col-md-12'<'toolbar_sub_cf'>> >"+
                                    "<'row'<'col-md-12'tr>><'row'<'col-md-12'ip>>",
                    lengthMenu: [
                        [ 20, 40, 50, -1 ],
                        [ '20 registros', '40 registros', '50 registros', 'Mostrar todo' ]
                    ],
                    order: [[ 0, 'asc' ]],
                    language: {
                        search: "Buscar:",
                            lengthMenu: "Mostrar _MENU_ registros por página",
                            zeroRecords: "No se ha encontrado ningún registro",
                            info: "Mostrando la página _PAGE_ de _PAGES_",
                            infoEmpty: "No hay registros disponibles",
                            infoFiltered: "(filtrado de los registros totales de _MAX_)",
                            processing: "Procesando",
                            paginate: {
                                first: "Primero",
                                last: "Último",
                                next: "Siguiente",
                                previous: "Anterior"
                            }
                    },
                    columns: [
                        { data: 'fee_number', name: 'fee_number'},
                        { data: 'fee_value', name: 'fee_value',render: $.fn.dataTable.render.number( ',', '.', 2,'',' €' )},
                        { data: 'fee_paid', name: 'fee_paid',render: $.fn.dataTable.render.number( ',', '.', 2,'',' €' )},
                        { data: 'dt_payment', name: 'dt_payment'},
                        { data: 'dt_paid', name: 'dt_paid'},
                        { data: 'status', name: 'status'},
                        { data: 'reason_unpaid', name: 'reason_unpaid'},
                        { data: 'dt_unpaid', name: 'dt_unpaid'},
                        { data: 'pathimage', name: 'pathimage'},
                        { data: 'pathunpaid', name: 'pathunpaid'},
                        { data: 'acciones', name: 'acciones'},
                        { data: 'id', name: 'id'},
                        ],
                    columnDefs: [
                        {
                            "render": function ( data, type, row ) {
                                // var text = parseInt(data)  ? data : " ";
                                // console.log(data);
                                return moment(data).isValid()? moment(data).format("DD/MM/YYYY") : ' ';
                            },"targets": [3,4,7]
                        }, 
                        {
                            "render": function ( data, type, row ) {
                                return '<span class="badge badge-pill text-sm '+row['color_class']+'">'+row['status_name']+'</span>';
                            },"targets": [5]
                        },                       
                        {
                            targets: 11,
                            visible:false,
                            searchable:false
                        },
                        {
                            targets: [0,5],
                            className: 'dt-body-center',
                        }
                    ]
                }).responsive.recalc();
                table_notes.responsive.recalc();
                $("div.toolbar_sub_cf").html('<div class="mt-3 callout callout-lightblue text-lightblue p-1" style="font-size: 0.9em!important;">'+
                                    '<h5><i class="icon fad fa-lightblue"></i> Leyenda</h5>'+
                                    '<ul class="list-inline">'
                                        @foreach(App\Models\ContractFeeStatus::all()->sortBy('id') as $cData)
                                            +'<li class="list-inline-item" style="padding-bottom:2px; padding-top:2px;"><span class="badge {{$cData->color_class}}">{{$cData->name}}: {{$cData->description}}</span></li>'
                                        @endforeach
                                        +'</ul>'+
                                    '</div>'
                );
            });            
        })
    });

    $('body').on('click', '.editPayment:not(.disabled)', function () {
        $('#feePaymentsForm').trigger("reset");
        var fee_id = $(this).data('id');
        var fee_number = $(this).data('feenumber');
        $.get("{{route('contractfeecrud.index') }}" +'/' + fee_id +'/edit', function (data) {
            // console.log(data.status);
            var fechaLoad = moment().format('YYYY-MM-DD');
            $('#contract_id_fee').val(data.id);
            $('#enrollment_number').val(data.enrollment_number);
            $('#fee_payment_dt_paid').val(data.dt_paid);
            $('#fee_paid_value').val(data.fee_value);
            $('#fee_payment_dt_unpaid').val(data.dt_unpaid);
            $('#fee_unpaid_reason').val(data.reason_unpaid);
            $('#fee_observations').val(data.observations);
            $('#fee_payment_status_admin').val(data.status).trigger("change");
            // $('#fee_payment_status_admin').val(data.status);
            if(data.status=='P' || data.status=='Z'){
                $('#fee_payment_status').prop("checked",true);
                $('#fee_payment_status').bootstrapToggle('on')
            }
            else{
                $('#fee_payment_status').bootstrapToggle('off')
            }

        });
        $("#feePaymentsHeading").html("<i class='fad fa-file-invoice'></i> Cuota Nº: <b style='font-size:1.2em;'>"+fee_number+"</b>");
        $("#saveBtnFeePayments").html('<i class="fad fa-download"></i>&nbsp;&nbsp;Actualizar');
        
        $("#editPaymentFormDisplay").show();
    });
    $("#fee_payment_status").change(function () {
        if(this.checked) {
            $("#fee_paid_row").show();
            $("#fee_unpaid_row").hide();
            $("#ContractFeeError").html('');
        }
        else{
            $("#fee_paid_row").hide();
            $("#fee_unpaid_row").show();
            $("#ContractFeeError").html('');
        }            
    });

    $("#fee_payment_status_admin").change(function () {
        switch($(this).val()) {
            case 'P':
                $("#fee_paid_row").show();
                $("#fee_unpaid_row").hide();
                $("#ContractFeeError").html('');
                break;
            case 'E':
                $("#fee_paid_row").hide();
                $("#fee_unpaid_row").show();
                $("#ContractFeeError").html('');;
                break;
            case 'AN':
                $("#fee_paid_row").hide();
                $("#fee_unpaid_row").show();
                $("#ContractFeeError").html('');
                break;
            case 'RB':
                $("#fee_paid_row").hide();
                $("#fee_unpaid_row").show();
                $("#ContractFeeError").html('');
                break;
            case 'VE':
                $("#fee_paid_row").hide();
                $("#fee_unpaid_row").show();
                $("#ContractFeeError").html('');
                break;
            default:
                // code block
        }       
    });

    $('#saveBtnFeePayments').click(function (e) {
        $("#ContractFeeError").html("");
        e.preventDefault();
        $(this).html('<div class="overlay">Enviando..<i class="fa fa-refresh fa-spin"></i></div>');
        $(this).attr("disabled", "disabled");
        var formData = new FormData($('#feePaymentsForm')[0]);
        $.ajax({ 
            data: formData,      
            url: "{{ route('contractfeecrud.store') }}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                $(".text-danger").text("");
                if($.isEmptyObject(data.error)){
                    $('#feePaymentsForm').trigger("reset");
                    $("#saveBtnFeePayments").html('<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;Actualizar');
                    $('#saveBtnFeePayments').removeAttr("disabled", "disabled");
                    $("#alert_message_note_fee").html(data['success']);                
                    $(".alert").show(200).delay(4000).hide(200);    
                    $('#datatable').DataTable().draw(false);
                    $("#editPaymentFormDisplay").hide();
                    table.draw(false);
                }else{
                    $.each( data.error, function( key, value ) {
                        $("#ContractFeeError").append('<li><label class="text-red">'+value+'</label></li>');
                    });
                    $("#saveBtnFeePayments").html('<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;Actualizar');
                    $('#saveBtnFeePayments').removeAttr("disabled", "disabled");
                }
            },
            error: function (data){
                console.log('Error:', data);
                $("#saveBtnFeePayments").html('<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;Actualizar');
            }
        });
    });

    //Botones de uploadfile
    $("#fee_path").filestyle({
        size: 'sm',
        btnClass : 'btn-success',
        text : '&nbsp; Escoger'
    });
    $("#fee_unpaid_path").filestyle({
        size: 'sm',
        btnClass : 'btn-danger',
        text : '&nbsp; Escoger'
    });
    $(".file-green").filestyle({
        size: 'sm',
        buttonName : 'btn-success',
        buttonText : '&nbsp; Escoger'
    });
</script>