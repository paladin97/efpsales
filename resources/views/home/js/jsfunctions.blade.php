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
	$("#mesdeliq").html(moment(Date.now()).format('MMMM'));
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
		+'<tr><td><i class="fad fa-dot-circle text-blue"> </i><a target="_blank" href="https://www.todofp.es/sobre-fp/informacion-general/pruebas/pruebas-obtencion-directa/pruebas-libres/pruebas-libres-1921.html" class="btn btn-lightblue">Fecha de accesos a las pruebas libres para la obtención de títulos de FP</a></td></tr>'
		+'<tr><td><i class="fad fa-dot-circle text-blue"> </i><a target="_blank" href="https://www.todofp.es/sobre-fp/actualidad/pruebas-libres/pruebas-libres-1921.html" class="btn btn-lightblue">Fecha fp grado medio y superior a las pruebas libres para la obtención de títulos de FP</a></td></tr>'
		+'<tr><td><i class="fad fa-dot-circle text-blue"> </i><a target="_blank" href="https://www.todofp.es/que-como-y-donde-estudiar/que-estudiar/ciclos.html" class="btn btn-lightblue">Estudios por niveles</a></td></tr>'
        +'<tr><td><i class="fad fa-dot-circle text-blue"> </i><a target="_blank" href="https://www.todofp.es/convalidaciones-equivalencias-homologaciones/convalidaciones.html" class="btn btn-lightblue">Convalidación y transversalidad de módulos profesionales</a></td></tr>'
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

$('.viewTutorial').on('click', function () {
        $('#tutorialModal').modal('show');
    });
    //Al cerrar el modal vacia todo nuevamente para dejar rapida la página
    $("#tutorialModal").on('hidden.bs.modal', function(){
        $('.tutorialhtml').html('');
		// $('#tutorialModal').trigger("reset");
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
			dom: 'rt',
			pageLength: 50,
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

	//Spain maps
	$(function(){
		var palette = ['#66C2A5', '#FC8D62', '#8DA0CB', '#E78AC3', '#A6D854'];
		generateColors = function(){
			var colors = {},
				key;

			for (key in map.regions) {
				colors[key] = palette[Math.floor(Math.random()*palette.length)];
			}
			return colors;
		};
		var gdpData = {
			"ES-NA": 16.63,
			"ES-B": 11.58,
			"ES-CS": 158.97,
			"ZA": 158.97,
			"ES-O": 158.97,
			"ES-OR": 158.97,
			"ES-M": 158.97,
			"ES-L": 158.97,
			"ES-J": 158.97,
			"ES-H": 158.97
			};
		var map = new jvm.Map({
			map: 'es_mill',
			backgroundColor: 'transparent',
			container: $('#spaindMap'),
			series: {
				regions: [{
					values: gdpData,
				}]
			},
			onRegionTipShow: function(e, el, code){
				el.html(el.html()+' (Matrículas: '+gdpData[code]+')');
			}
		});
		map.series.regions[0].setValues(generateColors());
		// $('#spainMap').vectorMap({map: 'es_mill'});
		
	});

	
	// console.log(gdpData);
	//USA MAPS
	var gdpData = {};
	var gdpDataColor = {};
	var aCommunityColor = {};
	var jsprovincesells = @json($provinceSells);
	var provincesall = @json($provinceAll);
	var autonomouscommunityall = @json($autonomousCommunityAll);

	provincesall.forEach(element => {
		gdpData[element['short_name']] = '0';
		gdpDataColor[element['short_name']] = element['color'];
	});
	
	autonomouscommunityall.forEach(element => {
		aCommunityColor[element['name']] = element['color'];
	});
	
	jsprovincesells.forEach(element => {
		gdpData[element['short_name']] = element['total'];
	});

	$(function(){
		var palette = ['#66C2A5', '#FC8D62', '#8DA0CB', '#E78AC3', '#A6D854', '#dc3545'];
		generateColors = function(){
			var colors = {},
				key;

			for (key in map.regions) {
				colors[key] = palette[Math.floor(Math.random()*palette.length)];
			}
			return colors;
		};
		
		var map = new jvm.Map({
			map: 'es_mill',
			hoverOpacity: 0.7,
			backgroundColor: '#b8daff',
			container: $('#spainMap'),
			focusOn: {
				x: 2.5,
				y: -1.5,
				scale: 2.8
			},
			regionLabelStyle: {
				initial: {
					fill: '#fff',
					'font-family': 'Verdana',
					'font-size': '8',
					cursor: 'default'
				},
				hover: {
					fill: '#21576a'
				}
			},
			series: {
				markers: [{
					attribute: 'fill',
					scale: aCommunityColor,
					values: gdpData,
					normalizeFunction: 'polynomial',
					// legend: {
					// 	vertical: true
					// }
				}],
				regions: [{
					attribute: 'fill',
					values: gdpDataColor,
				}]
			},
			labels: {
				regions: {
					render: function(code){
						// gdpData[code]
						// console.log(code);
						return code.split('-')[1] +': '+ gdpData[code];
					},
				}
			},
			onRegionTipShow: function(e, el, code){
				el.html(el.html()+' (Matrículas: '+gdpData[code]+')');
			}
		});
		// map.series.regions[0].setValues(generateColors());
		// map.series.regions[0].setValues(myCustomColors);
	});

</script>