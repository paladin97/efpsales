<script>
    // Grafica Barras Ingresos
    
    var rangoMes = {!!$rangoMes!!};
    var rangoMesM = {!!$rangoMesM!!};
    var rangoMesYDT = {!!$rangoMesYDT!!};
    
    var exp = [
        @foreach($agents as $index => $cData)
            {
                label: '{{$cData->agent_name}}',
                backgroundColor: '{{$cData->agent_color}}',
                borderWidth : 1,
                data: [
                    @foreach($rangoM as $cMonth)
                        @php
                            $inner_dt_ini = Carbon\Carbon::parse($cMonth)->startOfMonth()->format('Y-m-d');
                            $inner_dt_end = Carbon\Carbon::parse($cMonth)->endOfMonth()->format('Y-m-d');
                            $contracts = App\Models\Contract::from('contracts as c')
                                    ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                                    ->where('c.contract_status_id','=',3)
                                    ->where('c.agent_id','=',$cData->agent_id)
                                    ->count();
                        @endphp
                        '{{$contracts}}',
                    @endforeach
                    ]
            },
        @endforeach
    ];
    var expY = [
        @foreach($agents as $index => $cData)
            {
                label: '{{$cData->agent_name}}',
                backgroundColor: '{{$cData->agent_color}}',
                borderWidth : 1,
                data: [
                    @foreach($rango as $cMonth)
                        @php
                            $inner_dt_ini = Carbon\Carbon::parse($cMonth)->startOfMonth()->format('Y-m-d');
                            $inner_dt_end = Carbon\Carbon::parse($cMonth)->endOfMonth()->format('Y-m-d');
                            $contracts = App\Models\Contract::from('contracts as c')
                                    ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                                    ->where('c.contract_status_id','=',3)
                                    ->where('c.agent_id','=',$cData->agent_id)
                                    ->select(DB::raw('SUM(coalesce(c.s_payment,0)+ coalesce(c.pp_initial_payment,0)+ (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0))) AS total'))
                                    ->get()->pluck('total');
                        @endphp
                        '{!!$contracts[0]!!}',
                    @endforeach
                    ]
            },
        @endforeach
    ];

    var expYDT = [//Gráfico YDT
    @php
      $indexsub = 0;
    @endphp
    @foreach($collectionYDT as $index => $cData)
      {
        label: "{{$cData['year']}}",
        backgroundColor: "{{$cData['color']}}",
        borderWidth : 1,
        order:1,
        data: [
          @foreach($rangoYDT as $cMonth)
            @php
              // dd($indexsub);
              $inner_dt_ini = Carbon\Carbon::parse($cMonth)->startOfMonth()->subYears($indexsub)->format('Y-m-d');
              $inner_dt_end = Carbon\Carbon::parse($cMonth)->endOfMonth()->subYears($indexsub)->format('Y-m-d');
              $contracts = App\Models\Contract::from('contracts as c')
                  ->leftJoin('leads as le','c.lead_id','=','le.id')
                  ->whereBetween('c.dt_created',[$inner_dt_ini,$inner_dt_end])
                  ->where('c.contract_status_id','=',3)
                  ->select(DB::raw('SUM(coalesce(c.s_payment,0) + coalesce(c.pp_initial_payment,0) + coalesce(c.m_payment,0) + (coalesce(c.pp_fee_quantity,0) * coalesce(c.pp_fee_value,0))) AS total'))
                  ->get()->pluck('total');
            @endphp
            '{!!$contracts[0]!!}',
          @endforeach
        ]
      },
      @php
        $indexsub++;
      @endphp
    @endforeach
  ];

    var barChartData = {
        labels: rangoMesM,
        datasets: exp
    };
    var barChartDataY = {
        labels: rangoMes,
        datasets: expY
    };

    var barChartDataYDT = {//Grafico de YDT
      labels: rangoMesYDT,
      datasets: expYDT,
    };
    
    
    window.onload = function() {
        
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                data: true,
                responsive: true,
                title: {
                    display: true,
                    text: 'Matrículas por asesor'
                }
            }
        });

        var cty = document.getElementById("canvasY").getContext("2d");
        window.myBarY = new Chart(cty, {
            type: 'bar',
            data: barChartDataY,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom',
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Ventas en euros(€) por asesor'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value, index, values) {
                                return value.toLocaleString("es-ES",{style:"currency", currency:"EUR"});
                            }
                        }
                    }]
                }
            }
        });
        // myLiveChart.destroy();

        var ctYDT = document.getElementById("canvasYDT").getContext("2d"); //Gráfico YDT
        window.myBarYDT = new Chart(ctYDT, {
            type: 'bar',
            data: barChartDataYDT,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                // responsive: true,
                maintainAspectRatio : false,
                legend: {
                    display: true,
                    position: "top",
                    labels: {
                    boxWidth: 15,
                    boxHeight: 1
                    }
                },
                scales: {
                    yAxes: [{
                    display: true,
                    stacked: false,
                    scaleLabel: {
                        display: true,
                        labelString: 'Valores en Euros'
                    },
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                        // return value + ' €';
                        return value.toLocaleString("es-ES",{style:"currency", currency:"EUR"});
                        }
                    }
                    }]
                },
                tooltips: {
                    // fixed: true;
                    callbacks: {
                        label: function(t, d) {
                        var xLabel = d.datasets[t.datasetIndex].label;
                        var yLabel = t.yLabel;
                        return xLabel + ': ' + yLabel.toLocaleString("es-ES",{style:"currency", currency:"EUR"});
                        }
                    }
                },
            }
        });

    };

    $('input[name="ranges"]').daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        locale: {
            "format": "DD/MM/YYYY",  
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Limpiar",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Personalizado",
            "daysOfWeek": [ "Do","Lu", "Ma","Mi", "Ju","Vi","Sa"],
            "monthNames": ["Enero", "Febrero", "Marzo","Abril","Mayo", "Junio", "Julio","Agosto","Septiembre", "Octubre","Noviembre", "Diciembre"],
            // "firstDay": 1
        }  
    }, function(start, end, label) {
        $("#dt_sells_from").val(start.format('YYYY-MM-DD'));
        $("#dt_sells_to").val(end.format('YYYY-MM-DD'));
        // console.log("A new date selection was made: " + start.format('YYYY-MM-DD HH:mm:ss') + ' to ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }); 

    $('input[name="rangesM"]').daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        locale: {
            "format": "DD/MM/YYYY",  
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Limpiar",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Personalizado",
            "daysOfWeek": [ "Do","Lu", "Ma","Mi", "Ju","Vi","Sa"],
            "monthNames": ["Enero", "Febrero", "Marzo","Abril","Mayo", "Junio", "Julio","Agosto","Septiembre", "Octubre","Noviembre", "Diciembre"],
            // "firstDay": 1
        }  
    }, function(start, end, label) {
        $("#dt_sells_fromM").val(start.format('YYYY-MM-DD'));
        $("#dt_sells_toM").val(end.format('YYYY-MM-DD'));
        // console.log("A new date selection was made: " + start.format('YYYY-MM-DD HH:mm:ss') + ' to ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }); 

    $('#dt_sells_filter').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });
    $('#dt_sells_filterM').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    //Limpiar filtros
	$('#btnCleanLeadID').click(function(){
		$("#dt_sells_filter").val('');
		$("#dt_sells_from").val('');
		$("#dt_sells_to").val('');
        $("#agent_list_filter").val([]).trigger("change");
	});

    $('#btnCleanLeadIDM').click(function(){
		$("#dt_sells_filterM").val('');
		$("#dt_sells_fromM").val('');
		$("#dt_sells_toM").val('');
        $("#agent_list_filterM").val([]).trigger("change");
	});


    //Filtrar Grafica de ventas
    $('body').on('click', '.btnFilterGraph', function (e) {
        e.preventDefault();
        var formData = new FormData($('#graphFilter')[0]);
        $.ajax({ 
			data: formData,      
			url:  "{{ route('financialgraph.sellsgraph') }}",
			type: "POST",
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function (data) {
				
                var rangoMes = data['rangoMes'];
                var barChartDataY = {
                    labels: rangoMes,
                    datasets: data['expY']
                };
                // console.log(barChartDataY);
                var cty = document.getElementById("canvasY").getContext("2d"); 
                window.myBarY.destroy();
                window.myBarY = new Chart(cty, {
                    type: 'bar',
                    data: barChartDataY,
                    options: {
                        elements: {
                            rectangle: {
                                borderWidth: 0,
                                borderColor: '#c1c1c1',
                                borderSkipped: 'bottom'
                            }
                        },
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Ventas en euros(€) por asesor'
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    callback: function(value, index, values) {
                                        return value.toLocaleString("es-ES",{style:"currency", currency:"EUR"});
                                    }
                                }
                            }]
                        }
                    }
                });
			}
		});
    });

    //Filtrar Grafica de Matriculas
    $('body').on('click', '.btnFilterGraphM', function (e) {
        e.preventDefault();
        var formData = new FormData($('#graphFilterM')[0]);
        $.ajax({ 
			data: formData,      
			url:  "{{ route('financialgraph.contgraph') }}",
			type: "POST",
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function (data) {
				
                var rangoMesM = data['rangoMesM'];
                var barChartData = {
                    labels: rangoMesM,
                    datasets: data['exp']
                };
                // console.log(barChartDataY);
                var ctx = document.getElementById("canvas").getContext("2d"); 
                window.myBar.destroy();
                window.myBar = new Chart(ctx, {
                    type: 'bar',
                    data: barChartData,
                    options: {
                        elements: {
                            rectangle: {
                                borderWidth: 0,
                                borderColor: '#c1c1c1',
                                borderSkipped: 'bottom'
                            }
                        },
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Matrículas por asesor'
                            }
                    }
                });
			}
		});
    });

    


</script>