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
</style>

@section('body')
    <div class="wrapper" style="display: none">
        <div class="container">
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
                                <p style="text-align:center; font-size:1.2em; color:#2a8eac"><b><i>Para cualquier duda, envíe un correo a su asesor.</i></b></p>
                            </div>
                        </div>
                        <div class="panel-body" align="right">
                            {{-- <button type="submit" style="background-color: #2a8eac !important; color:white;" class="btn btn-success btn-lg mb-2" id="acceptButton" value="create-product">Aceptar Contrato</button> --}}
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

