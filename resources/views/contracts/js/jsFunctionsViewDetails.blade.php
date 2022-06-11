<script>

//Ficha del alumno
$('body').on('click', '.clientDetails', function () {
            var client_contract_id = $(this).data('object');
            var url = "{{ url('contractdetail/') }}/"+client_contract_id+"/detail";
            $('#client_file_bill').html('');
            $.get(url, function (data) {
                console.log(data);
                // console.log(data);
                var client_fee_c_id =data[0].id;
                var lead_id_to_note_client = data[0].lead_note_id_crypt;
                var contract_id_management = data[0].id_crypt;
                //Se colocan los campos desde la base de datos
                $('#client_name').html(data[0].name);
                $('#client_last_name').html(data[0].last_name);
                $('#client_dni').html(data[0].dni);
                $('#client_address').html(data[0].address);
                $('#client_town').html(data[0].town);
                $('#client_province').html(data[0].student_province);
                $('#client_cp').html(data[0].postal_code);
                $('#client_email').html(data[0].mail);
                $('#client_mobile').html(data[0].mobile);
                $('#client_dt_birth').html(moment(data[0].dt_birth).format('DD/MM/YYYY'));
                $('#client_country').html(data[0].student_country);
                $('#client_studies').html(data[0].studies);
                $('#client_gender').html(data[0].gender);
                $('#client_course').html(data[0].course_name);
                $('#client_material').html(data[0].material_name);
                $('#client_complementary_course').html(data[0].aux_course_name);
                $('#client_modality').html(data[0].modality);
                $('#client_payment_type').html(data[0].payment_type);
                $('#client_s_payment').html('€ '+data.total_pending);
                $('#client_pp_initial_payment').html('€ '+data[0].pp_initial_payment);
                $('#client_pp_fee_quantity').html(data[0].pp_fee_quantity);
                $('#client_pp_fee_value').html('€ '+data[0].pp_fee_value);
                $('#client_pp_enroll_payment').html('€ '+data[0].pp_enroll_payment);
                $('#client_pp_dt_first_payment').html(data[0].pp_dt_first_payment);
                $('#client_pp_dt_final_payment').html(data[0].pp_dt_final_payment);
                $('#client_m_payment').html('€ '+data[0].m_payment)
                $('#client_method_type_id').html(data[0].payment_method)
                $('#client_card_holder').html(data[0].cc_name);
                $('#client_card_number').html(data[0].cc_number);
                $('#client_card_expiry').html('0' + moment(data[0].cc_expiry, 'YYYY-MM-DD').format('M') + moment(data[0].cc_expiry, 'YYYY-MM-DD').format('YYYY'));
                $('#client_card_cvv').html(data[0].cc_secret_code);
                $('#client_card_type').html(data[0].cc_type);
                $('#client_dd_holdername').html(data[0].dd_holder_name);
                $('#client_dd_dni').html(data[0].dd_holder_dni);
                $('#client_dd_iban').html(data[0].dd_iban);
                $('#client_dd_bank').html(data[0].dd_bank_name);
                $('#client_t_concept').html(data[0].t_payment_concept);
                $('#client_t_iban').html(data[0].t_iban);
                $('#client_cash_payment').html(data[0].cash);
                $('#client_observations').html(data[0].observations);
                $('#client_management_observations').html(data[0].financials_observations);
				$('#client_validty').html(data[0].validity+' Meses');
                $('#client_company').html(data[0].company_name);
                $('#client_enrollment_number').html(data[0].enrollment_number);
                $('#client_dt_created').html(data[0].dt_created);
                $('#client_comercial_name').html(data[0].agent_name);
                $('#client_management_comercial_name').html(data[0].agent_name);
                $('#client_charges').html('Total Cobrado: <b>€ '+(data.total_charged==null ? '0.00' : data.total_charged)+'</b> | Total Pendiente: <b>€ '+(data.total_pending==null ? '0.00' : data.total_pending)+'</b>'); 
                //Bonificado
                switch (data[0].discount_voucher) {
                    case 0:
                        $('#client_discount').html('No');
                        break;
                    case 1:
                        $('#client_discount').html('Si');
                        break;
                    default:
                        $('#client_discount').html('No se aporta');

                } 
                //Abona Matricula
                switch (data[0].enroll) {
                    case 1:
                        $('#client_enroll').html('Si');
                        break;
                    case 2:
                        $('#client_enroll').html('No');
                        break;
                    default:
                        $('#client_enroll').html('No se aporta');

                } 
                
                switch (data[0].contract_payment_type_id) {
                    case 1:
                        $("#client_single_payment").removeClass('d-none');
                        $("#client_postponed_payment").addClass('d-none');
                        $("#client_monthly_payment").addClass('d-none');
                        break;
                    case 2:
                        $("#client_single_payment").addClass('d-none');
                        $("#client_postponed_payment").removeClass('d-none');
                        $("#client_monthly_payment").addClass('d-none');
                        break;
                    case 3:
                        $("#client_single_payment").addClass('d-none');
                        $("#client_postponed_payment").addClass('d-none');
                        $("#client_monthly_payment").removeClass('d-none');
                        break;
                    default:
                        $("#client_single_payment").addClass('d-none');
                        $("#client_postponed_payment").addClass('d-none');
                        $("#client_monthly_payment").addClass('d-none');
                        
                }

                switch (data[0].contract_method_type_id) {
                    case 1:
                        $('#client_contract_method_type_1').removeClass('d-none');
                        $('#client_contract_method_type_2').addClass('d-none');
                        $('#client_contract_method_type_3').addClass('d-none');
                        $('#client_contract_method_type_4').addClass('d-none');
                        break;
                    case 2:
                        if (data[0].sepa_path == null) {
                            $('#client_dd_sepa').html('<a href="#" disabled target="_blank" class="btn btn-danger btn-sm" style="padding:1px 2px;"  ><i class="fa fa-file"></i>&nbsp; SEPA no disponible</a>');
                        }
                        else{
                            $('#client_dd_sepa').html('<a href="{{asset('storage/sepaproof/')}}/'+data[0].enrollment_number+'/'+data[0].sepa_path+'" target="_blank" class="btn btn-primary btn-sm" style="padding:1px 2px;"  ><i class="fa fa-file"></i>&nbsp; Ver SEPA</a>');
                        }   
                        $('#client_contract_method_type_2').removeClass('d-none');
                        $('#client_contract_method_type_1').addClass('d-none');
                        $('#client_contract_method_type_3').addClass('d-none');
                        $('#client_contract_method_type_4').addClass('d-none');
                        break;
                    case 3:
                        $('#client_contract_method_type_3').removeClass('d-none');
                        $('#client_contract_method_type_1').addClass('d-none');
                        $('#client_contract_method_type_2').addClass('d-none');
                        $('#client_contract_method_type_4').addClass('d-none');
                        break;
                    case 4:
                        $('#client_contract_method_type_4').removeClass('d-none');
                        $('#client_contract_method_type_1').addClass('d-none');
                        $('#client_contract_method_type_2').addClass('d-none');
                        $('#client_contract_method_type_3').addClass('d-none');
                        break;
                    case 5:
                        $('#client_contract_method_type_4').addClass('d-none');
                        $('#client_contract_method_type_1').addClass('d-none');
                        $('#client_contract_method_type_2').addClass('d-none');
                        $('#client_contract_method_type_3').addClass('d-none');
                        break;
                    default:
                        $('#contract_method_type_4').addClass('d-none');
                        $('#contract_method_type_1').addClass('d-none');
                        $('#contract_method_type_2').addClass('d-none');
                        $('#contract_method_type_3').addClass('d-none');
                }
                $.when(
                    $('#viewDetailModal').modal('show')
                ).then(function(){
                    var table_fee_client = $('#datatable-clientefees').DataTable({
                        destroy:true,
                        processing: true,
                        serverSide: true,
                        ajax:  "{{ url('contracts/fee/') }}"+"/"+client_fee_c_id,
                        dom: 'rtip',
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
                                processing: 'Procesando',
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
                            { data: 'id', name: 'id'},
                            ],
                        columnDefs: [
                            {
                                "render": function ( data, type, row ) {
                                    return moment(data).isValid()? moment(data).format("DD/MM/YYYY") : ' ';
                                },"targets": [3,4,7]
                            }, 
                            {
                                "render": function ( data, type, row ) {
                                    if(row['status']=='P'){
                                        return '<span class="badge bg-newgreen">Pagado</span>';
                                    }
                                    else if(row['status']=='Z'){
                                        return '<span class="badge bg-orange">Pagado (PV)</span>'
                                    }
                                    else if(row['status']=='PP'){
                                        return '<span class="badge bg-gray">Pendiente de Cobro</span>'
                                    }
                                    else if(row['status']=='AN'){
                                        return '<span class="badge bg-red">Cobro Rechazado</span>'
                                    }
                                    else{
                                        return '<span class="badge bg-red">Impagado</span>';
                                    }
                                },"targets": [5]
                            },                       
                            {
                                targets: 10,
                                visible:false,
                                searchable:false
                            },
                            {
                                targets: [0,5],
                                className: 'dt-body-center',
                            }
                        ],
                        initComplete: function (settings, json) {  
                            $("#datatable-clientefees").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");            
                        },
                    }).responsive.recalc();
                    table_fee_client.responsive.recalc(); 

                    var table_notes_client = $('#datatable-clientnotes').DataTable({
                        destroy:true,
                        processing: true,
                        serverSide: true,
                        pagingType: "full_numbers",
                        // scrollX: true,
                        ajax:  "{{ url('leads/notes/') }}"+"/"+lead_id_to_note_client,
                        dom: 'rtip',
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
                                processing: "Procesando",
                                paginate: {
                                    first: "Primero",
                                    last: "Último",
                                    next: "Siguiente",
                                    previous: "Anterior"
                                }
                        },
                        columns: [
                            { data: 'created_at', name: 'created_at'},
                            { data: 'created_at', name: 'created_at'},
                            { data: 'sent_method', name: 'sent_method'},
                            { data: 'status', name: 'status'},
                            { data: 'sub_status', name: 'sub_status'},
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
                            { targets: 6, visible: false},
                            {
                                targets: 7,
                                visible:false,
                                searchable:false
                            },
                            { targets: [0,1,2,3,4,5,6], className: 'dt-body-center '}
                        ],
                        drawCallback: function( settings ) {
                            var column = table_notes_client.column(6);
                            @if(Auth::user()->hasRole('superadmin'))
                                column.visible(true);
                            @endif
                        }
                    }).responsive.recalc();
                    table_notes_client.responsive.recalc();  

                    var table_notes_managenment_client = $('#datatable-management-clientnotes').DataTable({
                        destroy:true,
                        processing: true,
                        serverSide: true,
                        pagingType: "full_numbers",
                        // scrollX: true,
                        ajax:  "{{ url('contracts/managementnotes/') }}"+"/"+contract_id_management,
                        dom: 'rtip',
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
                                processing: "Procesando",
                                paginate: {
                                    first: "Primero",
                                    last: "Último",
                                    next: "Siguiente",
                                    previous: "Anterior"
                                }
                        },
                        columns: [
                            { data: 'created_at', name: 'created_at'},
                            { data: 'created_at', name: 'created_at'},
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
                            { targets: 6, visible: false},
                            {
                                targets: [8,9],
                                visible:false,
                                searchable:false
                            },
                            { targets: [0,1,2,3,4,5,6], className: 'dt-body-center '}
                        ],
                        drawCallback: function( settings ) {
                            var column = table_notes_managenment_client.column(6);
                            @if(Auth::user()->hasRole('superadmin'))
                                column.visible(true);
                            @endif
                        }
                    }).responsive.recalc();
                    table_notes_managenment_client.responsive.recalc();  
                    var view_documents = '<div><table class="small-table table-sm table-border cell-border order-column compact table data-table">'
                        +'<thead><tr><th>Documentos del Contrato</th></tr></thead>';
                    data.documents.forEach( function(valor, indice, array) {
                        view_documents = view_documents +'<td><a target="_blank" href="https://efpsales.com/'+data.documents[indice].path+'" class="btn btn-lightblue text-sm p-0"><i class="fad fa-file-alt text-blue"> </i> '+data.documents[indice].name+'</a></td></tr>'
                        
                    });
                    view_documents = view_documents + '</div></table>';
                    console.log(view_documents);
                    $("#client_documments").html(view_documents);
                });  
            });
        });


</script>