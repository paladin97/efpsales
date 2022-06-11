<!DOCTYPE html>
<html lang="en">
<head>
  <title>Certificado en Linea</title>
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
    .container{
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        font-size: 16px;
        font-weight: 400;
        line-height: 1;
        color: #212529;
        text-align: left;
        background-color: rgba(255, 255, 255, 0);
    }
    .logo{
        width:450px;
    }
    .text-center {
        text-align: center ;
    }
    .col{
        float: left;
        width:50%;
        text-align: center ;
    }
    .border{
        border:8px double #1E90FF;
        height: 820px;
    }
    .firma{
        width:220px;
    }
    .margin{
        margin:50px;
    }
    .col-cont{
        float: left;
        width:50%;
    }
    .new-page {
        page-break-before: always;
    }
    .bg-waeter{
        background-image: url('https://efpsales.com/storage/uploads/contract_logos/LogoContract.png');
        background-repeat: no-repeat; 
        background-position: center;
        opacity: 5%;
    }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container  style="padding:0; margin:0;">
            <div class="bg-water">
                <div class='container'>
                    <div class='border'>
                      <div class='text-center'>
                        <br>
                        <img class="logo" src="https://efpsales.com/storage/uploads/contract_logos/LogoContract.png" >
                        <br><br>
                        <h1><strong>CERTIFICADO DE ACREDITACIÓN DE LA FORMACIÓN</strong></h1>
                        <h2><strong>{!!Str::upper($company_name)!!} CERTIFICA QUE:</strong></h2>
                        <div class='texto'>
                            <br><br>
                            <p>  Don/ña  <b> {!!$name!!} {!!$last_name!!} </b> con identificacion <b> {!!$dni!!}  </b> ha realizado con aprovechamiento el curso de:</p>
                            <h3><q> {!!Str::upper($course_name)!!} </q></h3>
                            <br>
                            <p>Impartido en modalidad Online, con una duración de <b> {!!$duration!!} Horas</b> de formación, realizada desde el <b> {!!$date_letter!!} </b>.</p>
                            <br><br>
                            <p>Y para que así conste se expide el presente certificado en Madrid a &nbsp; <b> {!!$date_today!!} </b></p>
                            <br><br><br><br>
                            <div class='col'>
                                <h4><strong>Firma del alumno</strong></h4>
                                <img class="firma" src="{!!$student_signature!!}" >
                            </div>
                            <div class='col'>
                                <h4><strong>Director de GrupoeFP</strong></h4>
                                <img class="firma" src="https://efpsales.com/storage/uploads/contract_logos/CertificateSignature.png" >
                                
                                <p>Fdo.:{!!$ceo!!}</p>
                            </div>
                        </div>
                      </div>
                     </div>
                  </div>
                
                <div class="new-page"></div>
                <div class='container'>
                    <div class='border'>
                        <h3 style="margin-left:40px"><strong>PROGRAMA DE CONTENIDOS</strong>
                            <br>
                            <br>
                            {!!$program!!}
                        </h3>
                        <div class='text-center'>
                            <br><br><br><br><br><br><br><br><br>
                            <div class='texto'>
                                <div class='col'>
                                    <h4><strong>Firma del alumno</strong></h4>
                                    <img class="firma" src="{!!$student_signature!!}" >
                                </div>
                                <div class='col'>
                                    <h4><strong>Director de GrupoeFP</strong></h4>
                                    <img class="firma" src="https://efpsales.com/storage/uploads/contract_logos/CertificateSignature.png" >
                                    
                                    <p>Fdo.:{!!$ceo!!}</p>
                                </div>
                            </div>
                          </div>
                    </div>  
                </div>
                {{-- Zona de footer y marca de agua --}}
                <img class="img-responsive" style="left:70px;top:670px;position:absolute;width:100px;"  src="https://efpsales.com/storage/uploads/contract_logos/SelloCertEFP.png" >
                <img class="img-responsive" style="right:220px;top:1200px;opacity:0.1!important;position:absolute;width:800px;z-index:0!important"  src="https://efpsales.com/storage/uploads/contract_logos/LogoContract.png" >
                <img class="img-responsive" style="right:220px;top:300px;opacity:0.1!important;position:absolute;width:800px;z-index:0!important"  src="https://efpsales.com/storage/uploads/contract_logos/LogoContract.png" >
                <p style="right:5px;top:850px;position:absolute;width:500px;color:gray;">Expediente/ Matricula: {!!$enrollment_number!!}     Libro Nº: 1  Registro Nº: 1{!!sprintf("%04d", $id)!!}</p>
                <p style="left:30px;top:1730px;position:absolute;width:1250px;color:gray;">Enseñanza no reglada - {!!$company_name!!}. – {!!$com_address!!}– 04770 {!!$com_town!!} / {!!$com_province!!} – NIF: {!!$leg_rep_nif!!}</p>
               
            </div>
        </div>
    </div>
</body>

