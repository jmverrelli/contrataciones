<?php

include_once '../../../../data/prestacionesDataBaseLinker.class.php';
include_once '../../../../data/certificacionesDataBaseLinker.class.php';
include_once '../../../../data/detalleCertificacionesDataBaseLinker.class.php';
include_once '../../../../data/profesionalesDataBaseLinker.class.php';
include_once '../../../../data/destinosDataBaseLinker.class.php';

$idCertificacion = $_GET['id'];

$dbPres = new prestacionesDataBaseLinker();
$dbDetalleCert = new DetalleCertificacionesDataBaseLinker();
$dbCert = new certificacionesDataBaseLinker();
$dbprof = new profesionalesDataBaseLinker();
$dbdest = new destinosDataBaseLinker();

$detallesCertificacion = $dbDetalleCert->traerDetallesCertificacion($idCertificacion);
$certificacion = $dbCert->traerCertificacion($idCertificacion);
$profesional = $dbprof->traerProfesional($certificacion->IdProfesionales, $idCertificacion);
$destino = $dbdest->traerDestino($idCertificacion);

if (($timestamp = strtotime($certificacion->FechaFinal)) !== false)
{
  $php_date = getdate($timestamp);
}

?>

<style type="text/css">
	
	.impresion{		
		text-align: left;
		margin-top: 2%;
		margin-left: auto;
		margin-right: auto;
		width: 50em;
	}
	.center{
		text-align: center;
	}

	.leblock{
		white-space:nowrap;
	}

	.image{
		display: inline;
		float:left;
	}

	.text{
		display: inline;
		white-space: nowrap;
		word-wrap: break-word;
	}

	.parrafo{
		margin-top: 10%;
	}

	.parra{
		white-space: pre-line;
		line-height: 2em;
	}


	.border{
	width: 100%;
	border-collapse: collapse;
}

.border td{
	text-align: center;
}

.border tr:nth-child(even){background-color: #f2f2f2}

.border th {
    background-color: #2a2b30;
    color: white;
    text-align: center;
}

#content {
    display: table;
    float:right;
}

#pageFooter {
    display: table-footer-group;
}

#pageFooter:after {
    counter-increment: page;
    content:"Pagina " counter(page);
    left: 0; 
    top: 100%;
    white-space: nowrap; 
    z-index: 20px;
    -moz-border-radius: 5px; 
    -moz-box-shadow: 0px 0px 4px #222;  
    background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);  
    background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);  
  }

</style>

<div id="aImprimir" name="aImprimir" class="impresion">

	<div class="leblock">
		<div class="image">
			<img src="../../../includes/images/logo.png" width="128px" height="170px">
		</div>
		<div class="text center">
			<h2 >MUNICIPALIDAD MALVINAS ARGENTINAS</h2>
			<h2>SECRETARIA DE SALUD</h2>
			<h4>Direccion: AV. PTE. PERON 3127 &nbsp;&nbsp;&nbsp;LOS POLVORINES
		</div>
		<div class="text center">
			<h1>CERTIFICACION DE SERVICIOS</h1>
			
		</div>
		<div class="parra">
			<span>Sr. Director.
			Me dirijo a usted a los efectos de informar que durante el periodo correspondiente al mes <?php echo $php_date['mon']; ?> del año <?php echo $php_date['year']; ?> he procedido a cumplimentar las siguentes prestaciones conforme se establecio en el convenio de locacion de obra que suscripto como <b><?php echo $certificacion->nombreEspecialidad; ?></b> con la Municipalidad de Malvinas Argentinas (contrato <b><?php echo $profesional['Nro Convenio']; ?></b>).</span>

		</div>

		<div class="parra">
		<table class="border">
		<tr><th>Cantidad</th><th>Prestacion</th><th>Valor Unitario</th><th>Total</th></tr>
			<?php 

				$total = 0;
				$totalTotal = 0;
				for($i = 0; $i < count($detallesCertificacion); $i++){
					$total = $detallesCertificacion[$i]->cantidad * $detallesCertificacion[$i]->valor;
					echo "<tr>";
					echo "<td>".$detallesCertificacion[$i]->cantidad."</td>";
					echo "<td>".$detallesCertificacion[$i]->nombrePrestacion."</td>";
					echo "<td>".$detallesCertificacion[$i]->valor."</td>";
					echo "<td>".round($total,2)."</td>";
					echo "</tr>";
					$totalTotal += $total;
				}

				echo "<tr><th>Total</th><th><th/><th>".round($totalTotal,2)."</th>";

			?>
		</table>
		</div>

		<div class="text">
			Nombre y Apellido <b><?php echo $certificacion->nombreProfesional; ?></b>
			<br />
			N° Proveedor <b><?php echo $profesional['Nro Proveedor']; ?></b>
			<br />
			<br />
			<br />
			Fecha .........................................
			<br />
			<br />
			<br />
			Firma ........................................
		</div>
		<br />
		<dib class="parra">
			Certifico en mi caracter de <?php echo $destino['Director']; ?>
			que la informacion suministrada por el profesional se corresponde en numero
			y en calidad con las prestaciones efectivamente realizadas durante el periodo
			mencionado.
			<br />
			Los Polvorines, <?php echo $certificacion->Fecha; ?>
		</dib>
	</div>

<div id="content">
  <div id="pageFooter"></div>
</div>

</div>


<script type="text/javascript">

function printTHIS(){
	window.print();
}
</script>