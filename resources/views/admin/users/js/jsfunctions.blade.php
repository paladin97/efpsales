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
    // $(function () {   
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.list_select').select2({
            language: {
                inputTooShort: function () {
                    return "Debe introducir dos o más carácteres...";
                    },
                inputTooLong: function(args) {
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

        
        $('.list_select_filter').select2({
            language: {
                inputTooShort: function () {
                    return "Debe introducir dos o más carácteres...";
                    },
                inputTooLong: function(args) {
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
                return "Error cargando resultados";
                }
            }
        });
        //Fin de Autocompletados
        
        var rows_selected = [];
        const table = $('.data-table').DataTable({
            bAutoWidth: false,
            dom: 'lBfrtip',
            processing: true,
            serverSide: true,
            order:  [1],
            responsive: {
                    details: {
                        renderer: function ( api, rowIdx, columns ) {
                            var aux = '<div class="row">';
                            var data = $.map(columns, function(col, i) {
                                if (col.hidden) {
                                    if (col.data) {
                                        aux = aux + '<div class="col p-1">';
                                        aux = aux + '<div class="card"><div class="card-header bg-navy">'+
                                                    '<b>'+col.title+'</b>'+
                                                    '</div><div class="card-body">'+col.data+
                                                    '</div></div>';
                                        aux = aux + '</div>'
                                        }
                                }
                                
                            }).join('');
                            data = aux + data +'</div>'
                            return data ? $('<table/>').append(data) : false;
                        }
                    }
            },
            ajax: {
                url: "{{ route('user.index') }}",
                data: function (data) {
                    for (var i = 0, len = data.columns.length; i < len; i++) {
                        if (! data.columns[i].search.value) delete data.columns[i].search;
                        if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                        if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                        if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                    }
                delete data.search.regex;
                }
            },
            order: [[ 2, 'asc' ]],
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
            buttons: [
                {
                    text: '<i id="createNewUser" class="fad fa-user-plus" data-toggle="tooltip"  title="Crear Usuario"></i>',
                    className: 'btn btn-secondary bg-lightblue hidden mr-2 rounded',
                    init: function(api, node, config) {
                        $(node).removeClass('dt-button')
                    }
                    
                }
                // ,
                // {
                //     text: '<a style="color: white!important;" class="isDisabled" href="javascript:void(0)" name="bulk_delete" id="bulk_delete"  ><i class="fas fa-trash"></i><strong>&nbsp;<span>Eliminar masivo</span></strong></a>',
                //     className: 'btn  btn-danger',
                //     init: function(api, node, config) {
                //         $(node).removeClass('dt-button')
                //     }
                // }
            ],
            columns: [
                { data: 'id', name: 'id'},
                { data: 'pathimage', name: 'pathimage'}, 
                { data: 'created_at', name: 'created_at'},
                { data: 'full_name', name: 'full_name'},
                { data: 'email', name: 'email' }, 
                { data: 'profile_url', name: 'profile_url'},
                { data: 'company', name: 'company' }, 
                { data: 'role_slug', name: 'role_slug' },
                { data: 'status', name: 'status' },                  
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false},             
                ],
            columnDefs: [
                {
                    targets: [6,2,7],
                    className: 'dt-body-center'
                },
                {
                    "render": function ( data, type, row ) {
                        return moment(data).format("DD/MM/YYYY");
                    },"targets": 2
                },
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
                    render: function ( data, type, row ) {
                        return '<a href="'+data+'" target="_blank"><span class="badge bg-lightblue">Visitar</span><img src="{{asset('storage/uploads/golinks.png')}}" width="25px"alt=""></a>';
                    }, targets: [5]
                },
                {
                    targets: [8],
                    className: 'dt-body-center',
                    render: function (data, type, row){
                        // return (today >= row['dt_incident'] && today <= row['dt_recover']) ? '<span class="text-red"><i class="fas fa-user-injured"></i> Baja</span>' : '<span class="text-green"><i class="fas fa-heartbeat"></i> Alta</span>';
                        if(row['status']=='1'){
                            return '<span class="text-green"><i class="fad fa-user-check fa-2x"></i> </span>';
                        }
                        else{
                            return '<span class="text-red"><i class="fad fa-user-times fa-2x"></i> </span>';
                        }

                    }
                },
            ],
            rowCallback: function(row, data, dataIndex){
                // Get row ID
                var rowId = data['id'];
                if($.inArray(rowId, rows_selected) !== -1){
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            }
        });

        //Limpiar filtros 
        $('body').on('click', '#btnCleanLeadID', function () {
            $(".list_select_filter").val([]).trigger("change");
        });
        
        $('body').on('click', '#btnFiterSubmitSearch', function () {
            // console.log($("#dt_assignment_from").val());
            $('.data-table').DataTable().draw(true);
        });

        $('.data-table').css("width","100%")
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
        $('#createNewUser').click(function () {
            $('#saveBtn').val("create-product");
            $('#user_id').val('');
            $('#person_id').val('');
            $('#role_id').val('');
            $('#userForm').trigger("reset");
            //reset los campos select2
            $(".select2-hidden-accessible").val([]).trigger("change");
            //fin reset
            $('#leadsHeading').html('<i class="fad fa-user-plus"></i> Nuevo Usuario');
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editUser', function () {
            $(".text-danger").text("");
            var user_id = $(this).data('id');
            $.get("{{route('user.index') }}" +'/' + user_id +'/edit', function (dataux) {
                let data = dataux[0];
                // console.log(data);
                $('#leadsHeading').html('<i class="fad fa-user-edit"></i> Editor Usuario');
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#user_id').val(data.id);
                $('#user_role_id').val(data.user_role_id);
                $('#person_id').val(data.person_id);
                $('#name').val(data.name);
                $('#last_name').val(data.last_name);
                $('#email').val(data.email);
                $('#profile_url').val(data.profile_url);
                $('#status').val(data.status);
                $('#company').val(data.company);
                $('#password').val(data.password);

                /*Se rellenan los campos de tipo select*/
                $("#user_role").data('select2').trigger('select', {
                    data: {"id": data.role_id, 'text':data.role_name}
                });

                $("#newuser_person_type").data('select2').trigger('select', {
                            data: {"id": data.person_type_id, 'text':data.person_type}
                });
                
                $("#user_company").data('select2').trigger('select', {
                    data: {"id": data.company_id, 'text':data.company}
                });
                /*Fin relleno*/

                /*Checkbox Estado*/
                if(data.status == 1){
                    $("#user_status").prop("checked", true);
                }
                else{
                    $("#user_status").prop("checked", false);
                }
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Enviando..');
            var formData = new FormData($('#userForm')[0]);
            $.ajax({ 
                data: formData,      
           url: "{{ route('user.store') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".text-danger").text("");
                    if($.isEmptyObject(data.error)){
                        $('#userForm').trigger("reset");
                        $("#provinces_list").val(null).trigger('change');
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Guardar Cambios');
                        toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                        table.draw();
                    }else{
                        $.each( data.error, function( key, value ) {
                        $("#"+key+"_error").text(value);
                        });
                        $('#saveBtn').html('Guardar Cambios');
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
                        url:"{{ route('user.cancel')}}",
                        method:"GET",
                        data:{user_id:id},
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

        $('body').on('click', '.deleteUser', function () {
            var user_id = $(this).data('id');
            $.confirm({
                title: '<i class="fa fa-exclamation text-red fa-rotate-180"></i> Eliminar el Usuario <i class="fa fa-exclamation text-red"></i> ',
                content: '¿Confirma la eliminación de este usuario?',
                buttons: {
                    confirmar :{
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                type: "DELETE",
                                url: '/user/'+user_id,
                                success: function (data) {
                                    table.draw();
                                },
                                error: function (data) {
                                    // console.log('Error:', data);
                                    $.alert({
                                        title: '<i class="fas fa-exclamation-triangle text-red"></i> Error al eliminar el Usuario',
                                        content: 'No se puede eliminar el usuario, por favor contacte con el soporte del sitio',
                                    });
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
        });
    });
</script>