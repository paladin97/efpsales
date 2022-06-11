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
        $('#summernote').summernote({
            placeholder: 'Ingrese el contenido del curso',
            tabsize: 2,
            height: 350
        });

        $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
        });
        var rows_selected = [];
        var table = $('.data-table').DataTable({
            dom: 'lBfrtip',
            processing: true,
            serverSide: true,
            responsive: {
                details: {
                    type: 'column'
                }
            }, 
            ajax: {
                url: "{{ route('coursecrud.index') }}",
                type: 'GET',
			    data: function (data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (! data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                data.course_company_filter = $("#course_company_filter").val();
                data.course_area_filter = $("#course_area_filter").val();
                data.course_type_filter = $("#course_type_filter").val();
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
                        last: "<strong>Último</strong>",
                        next: "<strong>Siguiente</strong>",
                        previous: "<strong>Anterior</strong>"
                    }
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            lengthMenu: [
                [ 10, 25, 50, 100,-1 ],
                [ '10 registros', '25 registros', '50 registros', '100 registros',  'Mostrar todo' ]
		    ],
            buttons: [
                {
                    text: '<i id="createNewCourse" class="fad fa-layer-plus" data-toggle="tooltip"  title="Agregar Nuevo Curso"></i>',
                    className: 'btn btn-secondary bg-lightblue hidden mr-2 rounded',
                    action: function(e, dt, node, config) {
                        $(node).removeClass('dt-button');
                    }
                    
                }
                // ,
                // {
                //     text: '<a style="color: white!important;"  href="javascript:void(0)" name="bulk_delete" id="bulk_delete"  ><i class="fas fa-trash"></i><strong>&nbsp;<span>Eliminar Masivo</span></strong></a>',
                //     className: 'btn btn-danger',
                //     action: function(e, dt, node, config) {
                //         $(node).removeClass('dt-button');
                //     }
                // }
            ],
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false}, 
                // { data: 'checkbox', name: 'checkbox'}, 
                { data: 'course_company_name', name: 'com.name' },
                { data: 'name', name: 'crse.name' },
                { data: 'course_type_name', name: 'ty.name'},
                { data: 'course_area_name', name: 'ar.name'},
                { data: 'duration', name: 'crse.duration'},
                { data: 'pvp', name: 'crse.pvp' ,render: $.fn.dataTable.render.number( ',', '.', 2,'',' €' )}, 
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false}
            ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    width: '1%',
                    className: 'dt-body-center',
                    render: function (data, type, full, meta){
                        return '<input type="checkbox">';
                    }
                },
                {
                    targets: [6],
                    width: '10%',
                },
                {
                    targets: [1,2,3,4,5],
                    className: 'dt-body-center'
                },
                {
                    render: function ( data, type, row ) {
                        return data + ' horas';
                    }, targets: [5]
                   
                },
                // {
                //     render: function ( data, type, row ) {
                //         const result = (row['pvp'] * (1+(row['tax_amount']*1)));
                //         return (numeral(result).format('0,0.00')) +' €';
                //     }, targets: [5]
                   
                // },
            ],

            rowCallback: function(row, data, dataIndex){
                // Get row ID
                var rowId = data['id'];
                // console.log(data['id']);
                // If row ID is in the list of selected row IDs
                if($.inArray(rowId, rows_selected) !== -1){
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            }
        });

        //Limpiar filtros
        $('#btnCleanLeadID').click(function(){
            $(".select_list_filter").val([]).trigger("change");
        });

        $('#btnFiterSubmitSearch').click(function(){
            // console.log($("#dt_assignment_from").val());
            $('.data-table').DataTable().draw(true);
        });
        var coursesByArea = [];
        var labelsByArea = [];
        var backgroundColor = [];
        var randomColor  = '#fff';
        $('#graphGroup').html('');
        @foreach($courseByArea as $cData)
            randomColor = Colors.random()['rgb'];
            coursesByArea.push({{$cData['count']}});
            labelsByArea.push('{{$cData['name']}}');
            backgroundColor.push(randomColor);
            $('#graphGroup').append('<li><i class="far fa-circle" style="color:'+randomColor+'"></i> {{$cData['name']}} </li>');
        @endforeach
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData        = {
        labels: labelsByArea,
        datasets: [
            {
            data: coursesByArea,
            backgroundColor : backgroundColor,
            }
        ]
        }
        var pieOptions     = {
        legend: {
            display: false
        }
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var pieChart = new Chart(pieChartCanvas, {
        type: 'doughnut',
        data: pieData,
        options: pieOptions      
        })

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
        $('#createNewCourse').click(function () {
            $("#dossier_url").attr("href","");
            $("#dossier_download").addClass('d-none');
            $('#saveBtn').val("create-product");
            $('#course_id').val('');
            $('#courseForm').trigger("reset");
            $('#summernote').summernote('code','');
            $('#modalHeadingCourse').html('<i class="fad fa-books-medical"></i> Nuevo Curso');
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editCourse', function () {
            $("#dossier_url").attr("href","");
            $("#dossier_download").addClass('d-none');
            $(".text-danger").text("");
            var course_id = $(this).data('id');
            $.get("{{route('coursecrud.index') }}" +'/' + course_id +'/edit', function (data) {
                $('#modalHeadingCourse').html('<i class="fad fa-books"></i> Editar Curso');
                $('#saveBtn').val("edit-course");
                $('#ajaxModel').modal('show');
                $('#course_id').val(data.id);
                $('#summernote').summernote('code', data.program);
                $('#newcrse_name').val(data.name);
                $('#newcrse_duration').val(data.duration);
                $('#newcrse_pvp').val(data.pvp);
                // $("#modal_course_categories_list").data('select2').trigger('select', {
				// 	data: {"id": res[0].course_category_id}
				// });
                /*Se rellenan los campos de tipo select*/

                $("#newcrse_company").data('select2').trigger('select', {
                    data: {"id": data.company_id, 'text':data.course_company_name}
                });

                // $("#newcrse_company").val(data.company_id).trigger('change');
                $("#newcrse_tipo").val(data.type_id).trigger('change');
                $("#newcrse_area").val(data.area_id).trigger('change');

                //Ver el dossier
                if(data.dossier_path){
                    $("#dossier_url").attr("href","{{ asset('storage/dossiers/')}}"+'/'+data.area_id+'/'+data.dossier_path);
                    $("#dossier_download").removeClass('d-none');
                }
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('<span aria-hidden="true" role="status" class="spinner-border spinner-border-sm"></span>  Enviando');
		    $(this).addClass("disabled");
            var formData = new FormData($('#courseForm')[0]);
            $.ajax({
                data: formData,
                url: "{{ route('coursecrud.store') }}",
                type: "POST", 
                dataType: 'json',
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#courseForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Guardar Cambios');
                        $('#saveBtn').removeClass("disabled");
                        toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                        table.draw();
                    }else{
                        toastr.error('No se puede enviar la información, revise los siguientes errores', '', {timeOut: 3000,positionClass: "toast-top-center"});
                        $.each( data.error, function( key, value ) {
                            $("#"+key+"_error").text(value);
                        });
                      $('#saveBtn').html('Guardar Cambios');
                      $('#saveBtn').removeClass("disabled");
                  }
                }
            });
        });

        $('#bulk_delete').click(function(){
            var id = [];
            if(confirm("Esta seguro de realizar esta acción?")){
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    id.push(rowId);
                });
                // $('.dt-checkboxes:checked').each(function(){
                //     id.push($(this).val());
                // });
                if(id.length > 0){
                    $.ajax({
                        url:"{{ route('course.cancel')}}",
                        method:"GET",
                        data:{course_id:id},
                        success:function(data){
                            // alert(data);
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
                else
                {
                    alert("Por favor seleccione por lo menos una casilla");
                }
            }
        });

        // $('body').on('click', '.deleteCourse', function () {
        //     var course_id = $(this).data("id");
        //     confirm("Esta seguro de borrar este Servicio?");
        //     $.ajax({
        //         type: "DELETE",
        //         url: "{{ route('coursecrud.store') }}"+'/'+course_id,
        //         success: function (data) {
        //             table.draw();
        //             },
        //         error: function (data) {
        //             console.log('Error:', data);
        //         }
        //         }); 
        // });

        // Anular Contrato sin Aceptar
        $('body').on('click', '.deleteCustomer', function () {

            var Customer_id = $(this).data("id");
            confirm("Are You sure want to delete !");

            
        });

        $('body').on('click', '.deleteCourse', function () {
            var cancel_course_id = $(this).data('id');
            $.confirm({
                title: '<i class="fa fa-exclamation text-red"></i> Eliminar Curso <i class="fa fa-exclamation text-red"></i> ',
                content: '¿Confirma la eliminación de este curso?',
                buttons: {
                    confirmar :{
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                type: "DELETE",
                                url: '/coursecrud/'+cancel_course_id,
                                success: function (data) {
                                    table.draw();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });    
                        },
                    },
                    Cancelar: {
                        text: 'Cancelar', // With spaces and symbols
                        action: function () {
                            $.alert('Operación Cancelada');
                        }
                    }
                }
            });
        });

        // Handle click on checkbox
        $('.data-table tbody').on('click', 'input[type="checkbox"]', function(e){
            console.log("check");
            var $row = $(this).closest('tr');
            // Get row data
            var data = table.row($row).data()['id'];
            // Get row ID
            var rowId = data;
            // console.log(data);
            // Determine whether row ID is in the list of selected row IDs
            var index = $.inArray(rowId, rows_selected);
            // If checkbox is checked and row ID is not in list of selected row IDs
            if(this.checked && index === -1){
                rows_selected.push(rowId);
            // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1){
                rows_selected.splice(index, 1);
            }
            if(this.checked){
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on table cells with checkboxes
        $('.data-table').on('click', 'td:first-child, th:first-child', function(e){
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', table.table().container()).on('click', function(e){
            if(this.checked){
                $('.data-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('.data-table tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        table.on('draw', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
            $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
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
});
</script>