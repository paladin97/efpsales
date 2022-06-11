<script>
    function updateDataTableSelectAllCtrl(table){
       var $table             = table.table().node();
       var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
       var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
       var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);
    
       // If none of the checkboxes are checked
       if($chkbox_checked.length === 0){
          chkbox_select_all.checked = false;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = false;
          }
    
       // If all of the checkboxes are checked
       } else if ($chkbox_checked.length === $chkbox_all.length){
          chkbox_select_all.checked = true;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = false;
          }
    
       // If some of the checkboxes are checked
       } else {
          chkbox_select_all.checked = true;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = true;
          }
       }
    }
    

    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
        });
        var rows_selected = [];
        var table = $('.data-table').DataTable({
            dom: 'lBfrtip',
            // dom: 'lfrtip',
            processing: true,
            serverSide: true,
            responsive: {
                details: {
                    type: 'column'
                }
            }, 
            order: [[ 5, 'desc' ]],
            ajax: {
                url: "{{ route('certificatecrud.index') }}",
                type: 'GET',
			    data: function (data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (! data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                data.cert_contract_filter = $("#cert_contract_filter").val();
                data.student_filter = $("#student_filter").val();
                data.course_filter = $("#course_filter").val();
                data.course_type_filter = $("#course_type_filter").val();
                data.course_area_filter = $("#course_area_filter").val();
                delete data.search.regex;
                }
            },
            language: {
                search: "<strong>Buscar:</strong>",
                    lengthMenu: "<strong>Mostrar _MENU_ Registros por página&nbsp;&nbsp;</strong>",
                    zeroRecords: "<strong>No se ha encontrado ningún registro</strong>",
                    info: "<strong>Mostrando la página _PAGE_ de _PAGES_</strong>",
                    infoEmpty: "<strong>No hay registros disponibles</strong>",
                    infoFiltered: "<strong>(filtrado de los registros totales de _MAX_)</strong>",
                    processing: "Procesando",
                    paginate: {
                        first: "<strong>Primero</strong>",
                        last: "<strong>Ultimo</strong>",
                        next: "<strong>Siguiente</strong>",
                        previous: "<strong>Anterior</strong>"
                    }
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            buttons: [
                {
                    text: '<i id="generateCertificate" class="fad fa-layer-plus" data-toggle="tooltip"  title="Generar Certificado"></i>',
                    className: 'btn btn-secondary bg-lightblue hidden mr-2 rounded',
                    action: function(e, dt, node, config) {
                        $(node).removeClass('dt-button');
                    }
                    
                }
            ],
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false}, 
                // { data: 'checkbox', name: 'checkbox'}, 
                { data: 'enrollment_number', name: 'c.enrollment_number'},
                { data: 'course_area', name: 'cra.name' },
                { data: 'course_name', name: 'crse.course_name' },
                { data: 'name', name: 'pi.name'},
                { data: 'dt_created', name: 'c.dt_created'},
                { data: 'validity', name: 'c.validity'},
                { data: 'cert_status', name: 'cert_status'},
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false}
            ],
            columnDefs: [
                {
                    targets: [1,2,3,4,5,6,7],
                    className: 'dt-body-center'
                },
                {
                    render: function ( data, type, row ) {
                        return row['name'] + ' ' +row['last_name'];
                    }, targets: [4]
                   
                },
                {
                    render: function ( data, type, row ) {
                        return moment(data).format('DD/MM/YYYY')
                    }, targets: [5]
                   
                },
                {
                    render: function ( data, type, row ) {
                        return data + ' meses';
                    }, targets: [6]
                   
                },
                {
                    render: function ( data, type, row ) {
                        var bg_color = (data == 'GENERADO') ? 'bg-success': 'bg-warning';
                        return '<span class="badge d-block text-sm '+bg_color+'">'+data+'</span>';
                    }, targets: [7]
                },
            ]
        });

        table.on( 'draw.dt', function () {
            var PageInfo = table.page.info();
            table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

        $('.bnk_checkbox').on('click', 'tr', function () {
            var id = this.id;
            var index = $.inArray(id, selected);
    
            if ( index === -1 ) {
                selected.push( id );
            } else {
                selected.splice( index, 1 );
            }
            $(this).toggleClass('selected');
        } );

        $('#generateCertificate').click(function () {
            $('#saveBtn').val("create-product");
            $('#cert_id').val('');
            $(".select_list").val([]).trigger("change");
            $('#certificateForm').trigger("reset");
            $('#modalHeadingCertificate').html("Generar Certificado");
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Enviando..'); 
            $.ajax({
                data: $('#certificateForm').serialize(),
                url: "{{ route('certificatecrud.store') }}",
                type: "POST", 
                dataType: 'json',
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#certificateForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Generar');
                        toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                        table.draw();
                    }else{
                        $.each( data.error, function( key, value ) {
                            $("#certificateCreateOrUpdateError").append('<li><label class="text-red">'+value+'</label></li>');
                        });
                      $('#saveBtn').html('Generar');
                  }
                }
            });
        });


        $('body').on('click', '.generateCertificate', function () {
            $.confirm({
                title: '¡Atención!',
                content: 'Certificado Generado Exitosamente',
                buttons: {
                    OK: {
                        text: 'OK',
                        btnClass: 'btn-blue',
                        keys: ['enter', 'shift'],
                        action: function(){
                            table.draw();
                        }
                    }
                }
            });
            
        });

        //Limpiar filtros
        $('#btnCleanLeadID').click(function(){
            $(".select_list_filter").val([]).trigger("change");
        });

        $('#btnFiterSubmitSearch').click(function(){
            // console.log($("#dt_assignment_from").val());
            $('.data-table').DataTable().draw(true);
        });
});
</script>