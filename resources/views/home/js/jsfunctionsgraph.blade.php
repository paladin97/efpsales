<script>
// Grafica Barras Ingresos
    var rangoMes = {!!$rangoMes!!};
    var exp = [
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
                            $agentTotalSalesMoneyAdvance = App\Models\Contract::from('contracts as ct')
                                    ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                                    ->where('ct.contract_status_id','=',3)
                                    ->whereBetween('ct.dt_created',[$inner_dt_ini,$inner_dt_end])
                                    ->where('ct.agent_id','=',$cData->agent_id)
                                    ->select(DB::raw('coalesce(ct.pp_initial_payment,0)AS advance'))
                                    ->groupBy('ct.id','advance')
                                    ->get();
                            $agentTotalSalesMoney = App\Models\Contract::from('contracts as ct')
                                    ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                                    ->where('ct.contract_status_id','=',3)
                                    ->whereBetween('ct.dt_created',[$inner_dt_ini,$inner_dt_end])
                                    ->where('ct.agent_id','=',$cData->agent_id)
                                    ->sum('cf.fee_value');
                            $agentTotalSalesMoney = $agentTotalSalesMoneyAdvance->sum('advance') + $agentTotalSalesMoney;
                        @endphp
                        '{!!$agentTotalSalesMoney!!}',
                    @endforeach
                    ]
            },
        @endforeach
    ];

    var barChartData = {
        labels: rangoMes,
            datasets: exp
    };
    var barChartDataY = {
        labels: rangoMes,
            datasets: expY
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
                responsive: true,
                title: {
                    display: true,
                    text: 'Matrículas de los últimos 6 meses'
                }
            }
        });
        var ctx = document.getElementById("canvasY").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartDataY,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Ventas en euros(€) por asesor de los últimos 6 meses'
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
    };


    

</script>