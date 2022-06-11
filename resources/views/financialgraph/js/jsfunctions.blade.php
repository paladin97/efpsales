<script>
	function seleccionaTexto(element){ //el rango es calculado y devolvemos el elemento seleccionado. 
        var doc = document,
        text = doc.getElementById(element),
        range,
        selection;
        if(doc.body.createTextRange){ //ms
            range = doc.body.createTextRange();
            range.moveToElementText(text);
            range.select();
        }else if(window.getSelection){ //all others
            selection = window.getSelection();
            range = doc.createRange();
            range.selectNodeContents(text);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    }
// (function(){
// 	var actualizarHora = function(){
// 		// Obtenemos la fecha actual, incluyendo las horas, minutos, segundos, dia de la semana, dia del mes, mes y año;
// 		var fecha = new Date(),
// 				horas = fecha.getHours(),
// 				ampm,
// 				minutos = fecha.getMinutes(),
// 				diaSemana = fecha.getDay(),
// 				segundos = fecha.getSeconds(),
// 				dia = fecha.getDate(),
// 				mes = fecha.getMonth(),
// 				year = fecha.getFullYear();

// 			// Accedemos a los elementos del DOM para agregar mas adelante sus correspondientes valores
// 		var phoras = document.getElementById('horas');
// 		var pminutos = document.getElementById('minutos');
// 		var psegundos = document.getElementById('segundos');
// 		var pampm = document.getElementById('ampm');
// 		var pdiaSemana = document.getElementById('diaSemana');
// 		var pdia = document.getElementById('dia');
// 		var pmes = document.getElementById('mes');
// 		var pyear = document.getElementById('year');

// 		// Obtenemos el dia se la semana y lo mostramos
// 		var semana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
// 			pdiaSemana.textContent = semana[diaSemana];

// 		// Obtenemos el dia del mes
// 		pdia.textContent = dia;
// 			// Obtenemos el Mes y año y lo mostramos
// 		var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
// 		pmes.textContent = meses[mes];
// 		pyear.textContent = year;
// 	};
//     actualizarHora();
//   	var intervalo = setInterval(actualizarHora, 1000);
//   }())
$(document).ready( function () {
	//Fecha en español
	moment.locale('es');
	// $("#fechaespa").html(moment(Date.now()).format('LLLL'));
	// $("#fechaespa").html(moment(Date.now()).format('dddd D [de] MMMM [de] YYYY'));
	$("#diade").html(moment(Date.now()).format('dddd'));
	$("#numerodia").html(moment(Date.now()).format('D'));
	$("#mesde").html(moment(Date.now()).format('MMMM'));
	$("#aniode").html(moment(Date.now()).format('YYYY'));
});
$('body').on('click', '#viewPrice', function () { 
	$.alert({
		columnClass: 'large',
		icon: 'fad fa-book-reader',
		type: 'blue',
		title: '<b>Precios y Dossieres de Cursos</b>',
		content: '' +
		'<div><table class="small-table table-sm table-border cell-border order-column compact table data-table">'
			+'<thead><tr><th>Grupo</th><th>Curso</th><th>P.V.P</th><th>Duración</th><th>Dossier</th></tr></thead>' 
		@foreach(App\Models\Course::from('courses as cou')
				->leftJoin('course_areas as carea','carea.id','cou.area_id')
				->select('cou.*','carea.name as group_course','carea.id as careaid')
				->orderBy('carea.name','ASC')->get() as $cData)
					+'<tr><td><b><i class="fad fa-dot-circle text-blue"></i> ({{$cData->group_course}})</b></td>' 
                    +'<td>{{$cData->name}}</td>'
                    +'<td><b>{{$cData->pvp}} €</b></td>'
					+'<td><b>{{$cData->duration}} horas</b></td>'
				@if($cData->dossier_path)
					+'<td><a target="_blank" href="https://efpsales.com/storage/dossiers/{{$cData->careaid}}/{{$cData->dossier_path}}" class="btn btn-lightblue text-sm p-0"><i class="fad fa-file-alt text-blue"> </i> Descargar</a></td></tr>'
				@else
					+'<td><a href="javascript:void(0)" class="btn btn-lightblue disabled text-sm p-0"><i class="fad fa-file-alt text-red"> </i> No Disponible</a></td></tr>'
				@endif
                    +'</tr>'
		@endforeach
		+ '</div></table>',
		buttons: {
			heyThere: {
				text: 'Cerrar', // text for button
				btnClass: 'bg-lightblue ', // class for the button
				action: function(heyThereButton){
				
				}
			},
		},
		onContentReady: function () {
			const table = $('.data-table').DataTable({
				dom: 'frtip',
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
			});
		},
	});
});


$('body').on('click', '#viewBoletin', function () { 
	$.alert({
		columnClass: 'short',
		icon: 'fad fa-info-circle',
		type: 'blue',
		title: '<b>Boletín Informativo de grupo eFP</b>',
		content: '' +
		'<div><table>'
		+'<tr><td><i class="fad fa-dot-circle text-blue"> </i><a target="_blank" href="https://www.todofp.es/sobre-fp/actualidad/pruebas-libres/pruebas-libres-1921.html#ancla01-1" class="btn btn-lightblue">Fechas de las pruebas libres para la obtención de títulos de FP</a></td></tr>'
		+'<tr><td><i class="fad fa-dot-circle text-blue"> </i><a target="_blank" href="https://www.todofp.es/que-como-y-donde-estudiar/que-estudiar/ciclos.html" class="btn btn-lightblue">Estudios por niveles</a></td></tr>'
        +'<tr><td><i class="fad fa-dot-circle text-blue"> </i><a target="_blank" href="http://www.todofp.es/convmodulos/ServletHomologModulos" class="btn btn-lightblue">Convalidación y transversalidad de módulos profesionales</a></td></tr>'
        +'<tr><td><i class="fad fa-dot-circle text-blue"> </i><a target="_blank" href="https://www.educacion.gob.es/centros/home.do" class="btn btn-lightblue">Registro Estatal de Centros Docentes no Universitarios (RCD)</a></td></tr>'
        + '</div></table>',
		buttons: {
			heyThere: {
				text: 'Cerrar', // text for button
				btnClass: 'bg-lightblue ', // class for the button
				action: function(heyThereButton){
				
				}
			},
		}
	});
});

$('body').on('click', '#viewCuenta', function () { 
	$.confirm({
		columnClass: 'short',
		icon: 'fad fa-piggy-bank',
		type: 'blue',
		title: '<b>Cuenta Bancaria de grupoeFP</b>',
		content: '' +
		'<div>'
        + '<p>EL NÚMERO DE CUENTA DE LA EMPRESA SE UTILIZARÁ PARA LOS SIGUIENTES CASOS:</p>'
        + '<ol><li>PAGOS AL CONTADO</li>'
        + '<li>RESERVA DE MATRÍCULA</li>'
		+ "</ol></div>"
		+'<div>'
        + '<h2 style="color:red;">ES57 0075 3103 6406 0756 4145</h2>'
		+ "</div>",
		buttons: {
			copiar: {
				text: 'Copiar Cuenta',
				btnClass: 'btn-blue',
				action: function () {
					$("body").append('<span id="seleccioname">ES57 0075 3103 6406 0756 4145</span>'); //creamos un elemento para enviar al cliente. 
					seleccionaTexto('seleccioname');
					document.execCommand("Copy"); 
					$("#seleccioname").remove();
					$.alert('La cuenta se ha copiado en el portapapeles.\nYa puede pegarla en cualquier lugar deseado.');   
					return false;
				}
			},
			cerrar: {
				text: 'Cerrar', // text for button
				btnClass: 'btn-secondary ', // class for the button
			}
		}
	});
});



//* leads comerciales diarios y mensuales


    @if (session('flash'))
        $.dialog({
            title: '',
            content: "<i class='fad fa-quote-left text-lightblue'></i><h5 class='text-lightblue'> {{ session('flash')->quote }}</h5>"+
                        "<br>" +  
                        "<p class='text-gray mt-n3'><b>-{{ session('flash')->author }}</b></p>" +  
                        "<p class='ml-2 mt-n2 text-lightblue'>{{ session('flash')->description }}</p>"   ,   
        });
    @endif
    
    let schedulesAux = [];
    let schedulesNew= [];
    $.when(
		@php
			$urlcalendar = 'eventmaster';
			if (Auth::user()->hasRole('teacher')){
				$urlcalendar = 'eventmastercontract';
			}
		@endphp
        $.get("{{url($urlcalendar)}}" +'/', (x)=>{
            x.map(y=>{
                schedulesAux.push({name : 'alert',
                    type : '\nObservacion:  <span class="text-xs badge badge-pill '+y.status_color+'">'+y.status_name+'</span><b>'+y.title+'</b>'
                            +'\nFecha y Hora: <b>'+ y.start+'</b>'
                            +'\nCliente: <b>'+ y.student_first_name +' '+ y.student_last_name+'</b>'
                            +'\nAsesor: <b>'+ y.agent_name+'</b>',
                    date:moment(y.start).format("YYYY-MM-DD")});
				schedulesNew.push({
					id : y.id,
					name: y.student_first_name +' '+ y.student_last_name,
                    description : '\n<span class="text-xs badge badge-pill '+y.status_color+'">'+y.status_name+'</span>'
                            + '<br>'+y.title
							+'<br>Fecha y Hora: <b>'+ y.start+'</b>'
                            +'<br>Asesor: <b>'+ y.agent_name+'</b>',
					date:	moment(y.start),
					type: 'event',
					color: '#63d867'
					});
            });
        })
    ).then(function(){
        $('.calendar-schedules-home').pignoseCalendar({
            format: 'DD/MM/YYYY',
            theme: 'blue',
			lang: 'es',
			week: 1,
            scheduleOptions: {
                colors: {
                    alert: '#f90202',
                }
            },
            schedules: schedulesAux,
            select: function (date, context) {
                var message = `
                    <div class="callout callout-lightblue">
                        <h5>Eventos para el día: ${(date[0] === null ? ' ' : date[0].format('DD/MM/YYYY'))}</h5>

                        <p><div class="schedules-date-home"></div></p>
                    </div>
                    `;
                var $target = context.calendar.parent().next().show().html(message);

                for (var idx in context.storage.schedules) {
                    var schedule = context.storage.schedules[idx];
                    if (typeof schedule !== 'object') {
                        continue;
                    }
                    $target.find('.schedules-date').append('<p">' + schedule.type.replace(/(?:\r\n|\r|\n)/g, '<br />') + '</p>');
                }
            }
        });
		$('#calendar').evoCalendar({
			'language': 'es',
			'format': 'dd MM, yyyy',
			'firstDayOfWeek': 1,
			'todayHighlight': true,
			'theme': 'Royal Navy',
			'calendarEvents': schedulesNew,
			'sidebarDisplayDefault': true
		});
    });
    
	

	@if (!(Auth::user()->hasRole('teacher')))
		const tableHome = $('.table-home').DataTable({
			dom: 'rtip',
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
			columnDefs: [
				{
					targets: [1,2,3,4,5],
					className: 'dt-body-center'
				},
			]
		});
	@endif
	
	$('body').on('click', '.calendarshow', function () {
		$('#calendarModal').modal('show');
		$("#url_header").html('<a class="text-xs mt-3 text-white" href="{{url('appointments')}}"><i class="fad text-white text-lightblue fa-calendar"></i>&nbsp;(Ir al Calendario)&nbsp; </a>');
	});

	$("#calendarModal").on('hidden.bs.modal', function(){
		$("#url_header").html('');
	});



</script>