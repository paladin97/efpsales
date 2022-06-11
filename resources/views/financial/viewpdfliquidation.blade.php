<!DOCTYPE html>
<html lang="en">
<head>
  <title>LIQUIDACIÓN VENTAS/TUTORÍAS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="all" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  {{-- <link rel="stylesheet" href="{{ asset('css/sepa.css') }}"> --}}
  
    <style >
        table.table-bordered{
        border:5px solid white;
        margin-top:5px;
        margin-bottom: 1px;

    }
    table.table-bordered > thead > tr > th{
        border:5px solid white;
        padding: 3px;
    }
    table.table-bordered > tbody > tr > td{
        border:5px solid white;
        padding: 3px;
    }
    .invoice-box {
        max-width: 950px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 10px;
        font-size:11px;
    }

    .invoice-box table tr.heading td {
        background: #d8ecf8;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .rtl table {
        text-align: right;
    }

    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    .alignleft {
        float: left;
    }
    .alignright {
        float: right;
    }
    .nonhide-mobile{
        display: none;
    }
    .header-data{
        background-color:#d8ecf8;
        color:black;
        padding: 6px;
        padding-left: 3px;
        border-radius: 6px;
        font-weight: 700;
    }
    .new-page {
        page-break-before: always;
    }

    @media print{ 
        .pagebreak {
            margin-top: 30px!important;
        }
    }

    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-contract elevation-3" style="padding-top:100px">
            <div class="row d-flex justify-content-center mt-5" style="margin:auto;">
                <div class="col-sm-12">
                    <div align="center"><img src="{{$logo_empresa}}" style="width:65%"></div>
                    <br>
                    <h4 class="mt-5 text-uppercase text-lightblue" style="color: #3c8dbc!important;">Liquidación de Gastos del Mes de {{$fechaLiq}}</h4>
                    <div class="table-responsive">
                        <table class="small-table table-sm row-border cell-border order-column compact table data-table" style="width:100%">
                            <thead>
                                <tr style="background-color: #3c8dbc!important;color:white">
                                    <th>Concepto</th>
                                    <th>Matrículas</th>
                                    <th>Suma de Precio</th>
                                    <th>Suma de Comisión Asesor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agentSalesperGroup as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        @php
                                            if(App\Models\User::find($agent_list_filter)->hasRole('comercial')){
                                                $agentTotalSales = App\Models\Contract::from('contracts as ct')
                                                        ->leftJoin('users as us','ct.agent_id','=','us.id')
                                                        ->leftJoin('courses as crse','ct.course_id','=','crse.id')
                                                        ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                                                        ->where('ct.contract_status_id','=',3)
                                                        ->where('crar.id','=',$item->id)
                                                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                                        ->where('ct.agent_id','=',$agent_list_filter)
                                                        ->count();
                                            }else{
                                                $agentTotalSales = App\Models\Contract::from('contracts as ct')
                                                        ->leftJoin('users as us','ct.agent_id','=','us.id')
                                                        ->leftJoin('courses as crse','ct.course_id','=','crse.id')
                                                        ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                                                        ->where('ct.contract_status_id','=',3)
                                                        ->where('crar.id','=',$item->id)
                                                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                                        ->where('ct.teacher_id','=',$agent_list_filter)
                                                        ->count();
                                            }
                                            $agentTotalSalesMoney =  App\Models\Contract::from('contracts as ct')
                                                ->leftJoin('courses as crse','ct.course_id','=','crse.id')
                                                ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                                                ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                                                ->leftJoin('users as us','ct.agent_id','=','us.id')
                                                ->where('ct.contract_status_id','=',3)
                                                ->where('crar.id','=',$item->id)
                                                ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                                ->where('ct.agent_id','=',$agent_list_filter)
                                                ->sum('cf.fee_value');
                                            $currencyFormat = new \NumberFormatter( 'de_DE', \NumberFormatter::CURRENCY );
                                            $agentLiquidationModel = App\Models\LiquidationModel::from('liquidation_models as lm')
                                                        ->leftJoin('people_inf as pi','pi.liquidation_model_id','=','lm.id')
                                                        ->leftJoin('users as us','us.person_id','=','pi.id')
                                                        ->where('us.id','=',$agent_list_filter)
                                                        ->select('lm.*')
                                                        ->get()->first();
                                            //Dependiendo si es delegado o no saca la información de delegación
                                            $agentPersonId = App\Models\User::find($agent_list_filter)->person_id;
                                            $agentDelegates = App\Models\PersonInf::from('people_inf as pi')
                                                        ->leftJoin('users as us','us.person_id','=','pi.id')
                                                        ->where('pi.id','=',$agentPersonId)
                                                        ->select('us.*')
                                                        ->get()->pluck('us.id');
                                            //Se obtiene la información de ventas de los comerciales
                                            //Total Ventas Delegación
                                            $agentDelegationTotalSales = App\Models\Contract::from('contracts as ct')
                                                        ->leftJoin('users as us','ct.agent_id','=','us.id')
                                                        ->where('ct.contract_status_id','=',3)
                                                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                                        ->whereIn('ct.agent_id',$agentDelegates)
                                                        ->count();
                                            //las ventas reales del comercial en euros de la Delegación
                                            $agentDelegationTotalSalesMoney = App\Models\Contract::from('contracts as ct')
                                                        ->leftJoin('contract_fees as cf','cf.contract_id','=','ct.id')
                                                        ->leftJoin('users as us','ct.agent_id','=','us.id')
                                                        ->where('ct.contract_status_id','=',3)
                                                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                                        ->whereIn('ct.agent_id',$agentDelegates)
                                                        ->sum('cf.fee_value');
                                            //Genera Datatable
                                            //De acuerdo al modelo de liquidación se revisa si tiene bonificación
                                            //Matriculas sin bonificación
                                            $salesNoBonus = App\Models\Contract::from('contracts as ct')
                                                        ->leftJoin('users as us','ct.agent_id','=','us.id')
                                                        ->leftJoin('courses as crse','ct.course_id','=','crse.id')
                                                        ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                                                        ->where('ct.contract_status_id','=',3)
                                                        ->where('ct.enroll','=',2)
                                                        ->where('crar.id','=',$item->id)
                                                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                                        ->where('ct.agent_id','=',$agent_list_filter)
                                                        ->count();
                                            //Matriculas con bonificación
                                            $salesBonus = App\Models\Contract::from('contracts as ct')
                                                        ->leftJoin('users as us','ct.agent_id','=','us.id')
                                                        ->leftJoin('courses as crse','ct.course_id','=','crse.id')
                                                        ->leftJoin('course_areas as crar','crse.area_id','=','crar.id')
                                                        ->where('ct.contract_status_id','=',3)
                                                        ->where('ct.enroll','=',1)
                                                        ->where('crar.id','=',$item->id)
                                                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                                        ->where('ct.agent_id','=',$agent_list_filter)
                                                        ->count();
                                            //Matriculas de Profesores
                                            $teacherCount = App\Models\Contract::from('contracts as ct')
                                                        ->leftJoin('users as us','ct.agent_id','=','us.id')
                                                        ->where('ct.contract_status_id','=',3)
                                                        ->whereBetween('ct.dt_created',[$dt_ini_filter,$dt_last_filter])
                                                        ->where('ct.teacher_id','=',$agent_list_filter)
                                                        ->count();
                                            if($agentLiquidationModel){
                                                if(App\Models\User::find($agent_list_filter)->hasRole('teacher')){
                                                    $agentLiquidationPerSales = $teacherCount *(float)$agentLiquidationModel->enroll_commission ;
                                                }
                                                elseif (App\Models\User::find($agent_list_filter)->hasRole('comercial')) {
                                                    $agentLiquidationPerSales = ($salesNoBonus * (float)$agentLiquidationModel->enroll_commission) + 
                                                                        ($salesBonus * ((float)$agentLiquidationModel->enroll_commission +
                                                                                        (float)$agentLiquidationModel->enroll_bonus_commission)) ;
                                                }
                                                else{
                                                    $agentLiquidationPerSales = 0.0;
                                                }
                                                
                                                
                                                $agentDelegationLiquidationPerSales = $agentDelegationTotalSales * (float)$agentLiquidationModel->enroll_delegation;
                                            }
                                            else{
                                                $agentLiquidationPerSales = 0.0;
                                                $agentDelegationLiquidationPerSales = 0.0;
                                            }
                                        @endphp
                                        <td>{{$agentTotalSales}}</td>
                                        <td>{{$currencyFormat->format($agentTotalSalesMoney)}}</td>
                                        <td>{{$currencyFormat->format($agentLiquidationPerSales)}}</td>
                                    </tr>
                                @endforeach
                                <tr style="background-color: #3c8dbc!important;color:white;font-weight:bold">
                                    <td>TOTAL GENERAL</td>
                                    <td>{{$agentTotalSalesT}}</td>
                                    <td>{{$agentTotalSalesMoneyT}}</td>
                                    <td>{{$agentLiquidationPerSalesT}}</td>
                                </tr>
                                <tr>
                                    {{-- {{dd($delegationperSaleEarning)}} --}}
                                    <td>COMISIÓN DELEGACIÓN ASESOR</td>
                                    <td>{{$agentDelegationTotalSalesT}}</td>
                                    <td>{{$delegationperSaleEarningT}}</td>
                                    <td>{{$agentDelegationLiquidationPerSalesT}}</td>
                                </tr>
                                <tr>
                                    <td>BONIFICACIONES DEL ASESOR/TUTOR</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-green">{{$positive_valueT}}</td>
                                </tr>
                                <tr>
                                    <td>DESCUENTOS DEL ASESOR/TUTOR</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-red"> -{{$negative_valueT}}</td>
                                </tr>
                                <tr style="background-color: #3c8dbc!important;color:white;font-weight:bold;font-size:1.6em">
                                    <td class="text-lg" colspan="3" align="right">TOTAL COMISIÓN POR VENTAS/TUTORÍAS</td>
                                    <td class="text-lg">{{$agentGrandTotal}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:150px"></div>
                    <div class="row" style="margin:auto;">
                        <div class="col-xs-6">
                            <h5 class="text-lightblue"><u>EL CENTRO</u></h5>
                            <img src="{{$firma_empresa}}" style="width:65%">
                        </div>
                        <div class="col-xs-6">
                            <h5 class="text-lightblue float-right"><u>EL ASESOR</u></h5>
                            {!!$firma_asesor!!}
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</body>

