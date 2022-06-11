@extends('adminlte::master')
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
div.scroll {
    margin:4px, 4px; 
    padding:4px; 
    height: 450px; 
    overflow-x: hidden; 
    overflow-x: auto; 
    text-align:justify; 
} 
.container-contract {
    max-width: 1200px!important;margin-left: auto;
    margin-right: auto;
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
    font-size:15px;
}

.invoice-box table tr.heading td {
    background-color:#d8ecf8;
    color:black;
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

@media only screen and (max-width: 400px) {
    .invoice-box table tr.top table td {
        width: 100%;
        display: block;
        text-align: left;
    }
    
    .invoice-box table tr.information table td {
        width: 100%;
        display: block;
        text-align: left;
        
    }
    .invoice-box table tr.heading td {
        background-color:#d8ecf8;
        color:black;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        font-size:10px;
    }
    .invoice-box {
        padding:15px!important;
    }
    .text-mobile{
        font-size: 10px!important;
    }
    .text-mobile-header{
        font-size: 12px!important;
        border-radius: 7px!important;
    }
    .title-center-mobile{
        text-align:center!important;
    }
    .title-center-mobile span{
        text-align:center!important;
        font-size:21px!important;
    }
    .line-break {
        display:block;
    }
    .mobile-color {
        background-color:#d8ecf8;
        color:black;
        border-radius: 7px;
        margin-bottom: 10px;
    }
    .mobile-signature-column{
        border-right: 0px solid black!important;
    }
    .hide-mobile{
        display: none;
    }
    .nonhide-mobile{
        display: revert;
    }
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

</style>

@section('body')
    <div class="wrapper" style="display: none">
        <div class="container-contract">
            <div class="row d-flex justify-content-center">
                <div class="col-sm-11">
                    <form id="acceptContractForm" name="acceptContractForm" class="form-horizontal" enctype="multipart/form-data">   
                        <input type="hidden" name="contract_enrollment_number" id="contract_enrollment_number" value="{{$contractF->enrollment_number}}">
                        <input type="hidden" name="typeRoleRequest" id="typeRoleRequest" value="{{$contractF->typeRoleRequest}}">
                        <input type="hidden" name="contract_id" id="contract_id" value="{{$contractF->id}}">
                        {!!$contractF->conditions!!}
                        {!!$contractF->acceptedArea!!}
                        <div class="row d-none" style="margin: auto">
                            <div class="col-sm-12">
                                <p style="margin: 0;padding: 0;"><i>No deseo recibir comunicaciones comerciales de los productos y servicios de <b>{{$contractF->company_name}}</b></i>  <input type="checkbox"  name="information" value="1"  required /></p>
                                <p><i>No deseo recibir comunicaciones comerciales de los productos y servicios de terceras empresas</i> <input type="checkbox"  name="informationtp" value="1" required /></p>
                                <p style="text-align:center; font-size:1.2em; color:#d8ecf8"><b><i>Para cualquier duda, envíe un correo a su asesor.</i></b></p>
                            </div>
                        </div>
                        <div class="panel-body" align="right">
                            {{-- <button type="submit" style="background-color: #d8ecf8 !important; color:black;" class="btn btn-success btn-lg mb-2" id="acceptButton" value="create-product">Aceptar Contrato</button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop   
@section('adminlte_js')
{{-- Carga funciones js del módulo --}}
@include('contracts.js.jsfunctionsAcceptContract')
{{-- Fin carga --}}
@stop

