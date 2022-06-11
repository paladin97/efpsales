<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contrato</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="all" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  {{-- <link rel="stylesheet" href="{{ asset('css/sepa.css') }}"> --}}
  
    <style>
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

