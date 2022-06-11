<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contrato</title>
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
        <div class="container" style="padding:0; margin:0;">
            <form id="acceptContractForm" name="acceptContractForm" class="form-horizontal" enctype="multipart/form-data">   
                <div class="row">
                    {{-- <h1>Hola{{$contractResponse->id}}</h1> --}}
                    <div class="col-xs-12">
                        {!!$conditions!!}
                        {!!$acceptedArea!!}
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

