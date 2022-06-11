<script>
    
	
	var SITEURL = "{{url('/')}}";
	

	// Construye la datatable principal de LEADS
	const table = $('.data-table').DataTable({
		bAutoWidth: false,
		dom: 'lBfrtip',
		processing: true,
		serverSide: true,
		pagingType: 'full_numbers',
		StateSave: true,
		ajax: {
			url:  "{{ route('spreadsheet.index') }}",
			type: 'GET',
			data: function (data) {
			for (var i = 0, len = data.columns.length; i < len; i++) {
				if (! data.columns[i].search.value) delete data.columns[i].search;
				if (data.columns[i].searchable === true) delete data.columns[i].searchable;
				if (data.columns[i].orderable === true) delete data.columns[i].orderable;
				if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
			}
			data.year_filter = $("#year_filter").val();
			data.quarter_filter = $("#quarter_filter").val();
			data.agent_list_filter = $("#agent_list_filter").val();
			data.contract_method_type_list_filter = $("#contract_method_type_list_filter").val();
			data.contract_aucom_filter = $("#contract_aucom_filter").val();
			data.contract_province_filter = $("#contract_province_filter").val();
			data.course_area_filter = $("#course_area_filter").val();
			data.course_filter = $("#course_filter").val();
			delete data.search.regex;
			}
		},
		lengthMenu: [
			[ 25, 10, 50, 100,-1 ],
			[ '25 registros', '10 registros', '50 registros', '100 registros',  'Mostrar todo' ]
		],
		buttons: [
			{
				extend: 'excel',
				title: '',
				filename: 'Matrículas',
				text:  '<i class="fad fa-file-excel fa-2x" data-ask="2" data-toggle="tooltip"  title="Descargar Excel"></i> ',
				className: 'btn btn-secondary bg-lightblue hidden mr-2 p-1 rounded',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				},
				exportOptions: {
					// header: false,
					columns: [1,2,3,4,5,6,7]
				}
			},
			{
				extend: 'pdf',
				filename: 'Matrículas',
				orientation: 'landscape',
				text:  '<i class="fad fa-file-pdf p-0 fa-2x" data-ask="2"  data-toggle="tooltip"   title="Descargar PDF"></i> ',
				className: 'rounded-left btn btn-secondary bg-lightblue p-1  hidden',
				init: function(api, node, config) {
					$(node).removeClass('dt-button')
				},
				exportOptions: {
					columns: [1,2,3,4,5,6,7]
				}
			}
		],
		order: [[ 1, 'desc' ],[2, 'desc']],
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
		columns: [
			{ data: 'year', name: 'year'},
			{ data: 'year', name: 'year'},
			{ data: 'quarter', name: 'quarter'},
			{ data: 'area_name', name: 'crsearea.name'},
			{ data: 'course_name', name: 'crse.name'},
			{ data: 'n_ventas', name: 'n_ventas'},
			{ data: 'ventas', name: 'ventas',render: $.fn.dataTable.render.number( ',', '.', 0, '', ' €' )},
			{ data: 'commissions', name: 'commissions',render: $.fn.dataTable.render.number( ',', '.', 0, '', ' €' ), visible:false},
			],
		columnDefs: [
			{
				targets: [3,4,5,6],
				className: 'dt-body-center'
			},			
			{
				targets: [5],
				searchable: false
			},
				
		],
		footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 6 ).footer() ).html(
                pageTotal +' € ('+ total +' € total)'
            );
        }
	});
	
	table.on( 'draw.dt', function () {
		var PageInfo = table.page.info();
		table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
			cell.innerHTML = i + 1 + PageInfo.start;
		} );
	} );


	//Limpiar filtros

	$('#btnFiterSubmitSearch').click(function(){
		// console.log($("#dt_assignment_from").val());
		$('.data-table').DataTable().draw(true);
	});





	
	
</script>