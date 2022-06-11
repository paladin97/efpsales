<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700" rel="stylesheet">

    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
body {
    margin: 0 auto !important;
    padding: 0 !important;
    height: 100% !important;
    width: 100% !important;
    background: #f1f1f1;
}

/* What it does: Stops email clients resizing small text.*/
* {
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
}

/* What it does: Centers email on Android 4.4 */
div[style*="margin: 16px 0"] {
    margin: 0 !important;
}

/* What it does: Stops Outlook from adding extra spacing to tables. */
table,
td {
    mso-table-lspace: 0pt !important;
    mso-table-rspace: 0pt !important;
}

/* What it does: Fixes webkit padding issue. */
table {
    border-spacing: 0 !important;
    border-collapse: collapse !important;
    table-layout: fixed !important;
    margin: 0 auto !important;
}

/* What it does: Uses a better rendering method when resizing images in IE. */
img {
    -ms-interpolation-mode:bicubic;
}

/* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
a {
    text-decoration: none;
}

/* What it does: A work-around for email clients meddling in triggered links. */
*[x-apple-data-detectors],  /* iOS */
.unstyle-auto-detected-links *,
.aBn {
    border-bottom: 0 !important;
    cursor: default !important;
    color: inherit !important;
    text-decoration: none !important;
    font-size: inherit !important;
    font-family: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
}

/* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
.a6S {
    display: none !important;
    opacity: 0.01 !important;
}

/* What it does: Prevents Gmail from changing the text color in conversation threads. */
.im {
    color: inherit !important;
}

/* If the above doesn't work, add a .g-img class to any image in question. */
img.g-img + div {
    display: none !important;
}

/* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
/* Create one of these media queries for each additional viewport size you'd like to fix */

/* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
    u ~ div .email-container {
        min-width: 320px !important;
    }
}
/* iPhone 6, 6S, 7, 8, and X */
@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
    u ~ div .email-container {
        min-width: 375px !important;
    }
}
/* iPhone 6+, 7+, and 8+ */
@media only screen and (min-device-width: 414px) {
    u ~ div .email-container {
        min-width: 414px !important;
    }
}


    </style>

    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

	    .primary{
	background: #17bebb;
}
.bg_white{
	background: #ffffff;
}
.bg_light{
	background: #f7fafa;
}
.bg_black{
	background: #000000;
}
.bg_dark{
	background: rgba(0,0,0,.8);
}
.email-section{
	padding:2.5em;
}

/*BUTTON*/
.btn{
	padding: 10px 15px;
	display: inline-block;
}
.btn.btn-primary{
	border-radius: 5px;
	background: #17bebb;
	color: #ffffff;
}
.btn.btn-white{
	border-radius: 5px;
	background: #ffffff;
	color: #000000;
}
.btn.btn-white-outline{
	border-radius: 5px;
	background: transparent;
	border: 1px solid #fff;
	color: #fff;
}
.btn.btn-black-outline{
	border-radius: 0px;
	background: transparent;
	border: 2px solid #000;
	color: #000;
	font-weight: 700;
}
.btn-custom{
	color: rgba(0,0,0,.3);
	text-decoration: underline;
}

h1,h2,h3,h4,h5,h6{
	font-family: 'Poppins', sans-serif;
	color: #000000;
	margin-top: 0;
	font-weight: 400;
}

body{
	font-family: 'Poppins', sans-serif;
	font-weight: 400;
	font-size: 15px;
	line-height: 1.8;
	color: rgba(0,0,0,.4);
}

a{
	color: #17bebb;
}

table{
}
/*LOGO*/

.logo h1{
	margin: 0;
}
.logo h1 a{
	color: #17bebb;
	font-size: 24px;
	font-weight: 700;
	font-family: 'Poppins', sans-serif;
}

/*HERO*/
.hero{
	position: relative;
	z-index: 0;
}

.hero .text{
	color: rgba(0,0,0,.3);
}
.hero .text h2{
	color: #000;
	font-size: 34px;
	margin-bottom: 0;
	font-weight: 200;
	line-height: 1.4;
}
.hero .text h3{
	font-size: 24px;
	font-weight: 300;
}
.hero .text h2 span{
	font-weight: 600;
	color: #000;
}

.text-author{
	bordeR: 1px solid rgba(0,0,0,.05);
	max-width: 50%;
	margin: 0 auto;
	padding: 2em;
}
.text-author img{
	border-radius: 50%;
	padding-bottom: 20px;
}
.text-author h3{
	margin-bottom: 0;
}
ul.social{
	padding: 0;
}
ul.social li{
	display: inline-block;
	margin-right: 10px;
}

/*FOOTER*/

.footer{
	border-top: 1px solid rgba(0,0,0,.05);
	color: rgba(0,0,0,.5);
}
.footer .heading{
	color: #000;
	font-size: 20px;
}
.footer ul{
	margin: 0;
	padding: 0;
}
.footer ul li{
	list-style: none;
	margin-bottom: 10px;
}
.footer ul li a{
	color: rgba(0,0,0,1);
}


@media screen and (max-width: 500px) {


}


    </style>


</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
	<center style="width: 100%; background-color: #f1f1f1;">
    <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
      &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>
    <div style="max-width: 600px; margin: 0 auto;" class="email-container">
    	<!-- BEGIN BODY -->
      <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
			<!-- end tr -->
		<tr>
			<td>
				<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="logo" style="text-align: center;">
						  <img src="https://efpsales.com/storage/uploads/banner_matricula_bonificada_2022.png" width="600px" alt="OFERTA_PROMO">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		  	<!-- end tr -->
		<tr>
          <td valign="middle" class="hero bg_white" style="padding: 2em 0 4em 0;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
            		<td style="padding: 0 2.5em; text-align: center; padding-bottom: 3em;">
            			<div class="text" style="color:#0c0c0c!important;text-align: justify;">
            				<p> Hola  {{$data['client_full_name']}},
                            </p>
                            <p>Tal cual hemos hablado telefónicamente... 
                            {{-- <p>Gracias por solicitar información sobre <b>{{$data['course_name']}}</b>.  --}}
                                
                                Te envío la metodología de nuestro centro y acceso a nuestra página web: <a href="https://grupoefp.com" target="_blank">grupoefp.com</a>, 
								para que puedas comprobar todo lo que hemos hablado.
                            </p>
							<p>

                                En el momento que te matriculas:
								<ul>
									<li>Te daremos acceso durante dos años al aula virtual.</li>
									<li>Atención del <b>profesorado especializado</b> tanto <b>por teléfono o correo electrónico.</b></li>
									<li>Te organizamos hasta <b>300 horas de prácticas diseñadas solo para ti</b> en la zona donde vives cuando termines el curso.</li>
									<li><b>Bolsa de trabajo</b> en la cual puedes inscribirte para recibir nuestras ofertas del sector.</li>
									<li>En el <b>aula virtual</b> tienes todos los <b>temarios, ejercicios, exámenes, contacto con profesores y alumnos.</b></li>
								</ul>
                            </p>
							<p>

								Y POR ÚLTIMO 3 GRANDES VENTAJAS ECONÓMICAS:
								<ul>
									<li>*<b>Bonificación de matrícula segundo año</b>* 300€ ......100% (es decir a coste 0).</li>
									<li><b>Bonificación de segundo año</b> .........100% (es decir, estudiarás con todos los servicios activos durante el segundo año a coste 0).</li>
									<li><b>Sólo abonarás el primer año de formación</b>
										@if($data['area_course'] == 1)
											12 cuotas 165,66€
										@elseif($data['area_course'] == 2 || $data['area_course'] == 5)
											12 cuotas 154,33€
										@elseif($data['area_course'] == 3)
											12 cuotas 126,33€
										@elseif($data['area_course'] == 4)
											12 cuotas 134,66€
										@elseif($data['area_course'] == 6)
											12 cuotas 122,16€
										@elseif($data['area_course'] == 7)
											12 cuotas 205,00€
										@else
											12 cuotas 213,33€
										@endif
									.</li>
									<li><b>Para mayor facilidad para ti también te damos las siguientes opciones:</b></li>
									<ul>
										{{-- console.log({{$data['area_course']}}); --}}
										@if($data['area_course'] == 1)
											<li>18 cuotas 118,44€.</li>
											<li>24 cuotas 94,83€.</li>
										@elseif($data['area_course'] == 2 || $data['area_course'] == 5)
											<li>18 cuotas 109,88€.</li>
											<li>24 cuotas 87,66€.</li>
										@elseif($data['area_course'] == 3)
											<li>18 cuotas 90,22€.</li>
											<li>24 cuotas 72,16€.</li>
										@elseif($data['area_course'] == 4)
											<li>18 cuotas 95,77€.</li>
											<li>24 cuotas 76,33€.</li>
										@elseif($data['area_course'] == 6)
											<li>18 cuotas 87,44€.</li>
											<li>24 cuotas 70,08€.</li>	
										@elseif($data['area_course'] == 7)
											<li>18 cuotas 146,66€.</li>
											<li>24 cuotas 117,50€.</li>
										@else
											<li>18 cuotas 152,22€.</li>
											<li>24 cuotas 121,66€.</li>
										@endif
									</ul>
									<li><b>¡Máximo Descuento!:</b></li>
									<ul>
										<li>Al contado {{$data['course_pvp']}}€.</li>
										<li>+ Matrícula 300,00 €.</li>
									</ul>
								</ul>
							</p>
							<p>

								También diseñamos <b>tu plan de pago personalizado a tus circunstancias</b>.
							</p>
							<p>

								Si lo necesitas ponte en contacto conmigo para aclararte todas tu dudas.
							</p>
							<p>

								<b>¡Un verdadero placer conocerte y asesorarte!</b>
							</p>
                            <p>
                                Un cordial saludo,
                            </p>
            			</div>
            		</td>
            	</tr>
            	<tr>
			          <td style="text-align: center;">
			          	<div class="text-author">
				          	<img src="images/person_2.jpg" alt="" style="width: 100px; max-width: 600px; height: auto; margin: auto; display: block;">
				          	<h3 class="name">{{$data['agent_name']}} {{$data['agent_lastname']}}</h3>
				          	<h3 class="name"><a href="tel:{{$data['agent_mobile']}}">{{$data['agent_mobile']}}</a></h3>
				          	<h3 class="name">{{$data['agent_mail']}}</h3>
				          	<span class="position">Asesor Pedagógico Asignado</span>
							<img src="https://efpsales.com/storage/uploads/contract_logos/LogoContract.png" width="190px" alt="EFPSALES">
			           	</div>
			          </td>
			        </tr>
            </table>
          </td>
	      </tr><!-- end tr -->
      <!-- 1 Column Text + Button : END -->
      </table>
      <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
      	<tr>
          <td valign="middle" class="bg_light footer email-section">
            <table>
            	<tr>
                <td valign="top" width="33.333%" style="padding-top: 20px;">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td style="text-align: left; padding-right: 10px;">
                      	<h3 class="heading">Grupo EFP</h3>
                      	<p>Construimos tu futuro | Especialistas en formación profesional</p>
                      </td>
                    </tr>
                  </table>
                </td>
                <td valign="top" width="33.333%" style="padding-top: 20px;">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td style="text-align: left; padding-left: 5px; padding-right: 5px;">
                      	<h3 class="heading">Información de Contacto</h3>
                      	<ul>
					                <li><span class="text">Domicilio: Nacional 340 sn edificio alcoholera planta 1 despacho 4-  Adra (04770) -Almería</span></li>
					                <li><span class="text">Télefono: 637 17 87 20 </span></a></li>
					                <li><span class="text">E-Mail:  info@grupoefp.com</span></a></li>
					              </ul>
                      </td>
                    </tr>
                  </table>
                </td>
                <td valign="top" width="33.333%" style="padding-top: 20px;">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td style="text-align: left; padding-left: 10px;">
                      	<h3 class="heading">Enlaces de Interés</h3>
                      	<ul>
					                <li><a href="https://grupoefp.com/">Nuestro Sitio Web</a></li>
					                <li><a href="https://www.facebook.com/cursosgrupoefp">Facebook</a></li>
					                <li><a href="https://www.instagram.com/grupoefp">Instagram</a></li>
					                <li><a href="https://grupoefp.com/comic/">Novedades</a></li>
					              </ul>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr><!-- end: tr -->
        <tr>
          <td class="bg_light" style="text-align: center;">
          </td>
        </tr>
      </table>

    </div>
  </center>
</body>
</html>