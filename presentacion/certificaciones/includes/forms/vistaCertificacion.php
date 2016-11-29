<?php
include_once '../../../../data/prestacionesDataBaseLinker.class.php';
include_once '../../../../data/certificacionesDataBaseLinker.class.php';
include_once '../../../../data/detalleCertificacionesDataBaseLinker.class.php';

$ultimaCertificacion = $_POST['id'];

$dbPres = new prestacionesDataBaseLinker();
$dbDetalleCert = new DetalleCertificacionesDataBaseLinker();
$dbCert = new certificacionesDataBaseLinker();

$prestacionesSelect = $dbPres->traerPrestacionesSelect();
$detallesCertificacion = $dbDetalleCert->traerDetallesCertificacion($ultimaCertificacion);
$certificacion = $dbCert->traerCertificacion($ultimaCertificacion);

$detalles = '';

if($detallesCertificacion == false){

	$detalles = "Esta Certificacion no tiene detalles.";
}

?>
<div>
	<div><h5>Certificacion Activa</h5></div>
	<table class="padd border">
		<tr>
			<th>Profesional</th><th>Hospital</th><th>Desde</th><th>Hasta</th>
		</tr>
		<tr>
			<td> <?php echo $certificacion->nombreProfesional; ?></td>
			<td> <?php echo $certificacion->nombreHospital; ?></td>
			<td><?php echo $certificacion->FechaInicio; ?></td>
			<td> <?php echo $certificacion->FechaFinal; ?></td>
		</tr>
	</table>
</div>
<hr />
<div><h5>Prestaciones Agregadas </h5></div>

<div id="detallesEncontrados" name="detallesEncontrados"> <?php echo $detalles; ?> </div>

<?php 

if($detallesCertificacion != false){

	$total = 0;
	$totalTotal = 0;

	echo "<table class='padd border'>";
		echo "<tr>";
		echo "<th>Prestacion</th><th>Valor</th><th>Cantidad</th><th>Total</th>";
		echo "</tr>";
		

	for ($i=0; $i < count($detallesCertificacion) ; $i++) { 
		$total = $detallesCertificacion[$i]->valor * $detallesCertificacion[$i]->cantidad;
		$totalTotal += $total;
		echo "<tr>";
		echo "<td>".$detallesCertificacion[$i]->nombrePrestacion."</td>";
		echo "<td>".$detallesCertificacion[$i]->valor."</td>";
		echo "<td>".$detallesCertificacion[$i]->cantidad."</td>";
		echo "<td>".round($total,2)."</td>";
		echo "</tr>";
	}

	echo "<tr><td></td><td></td><td></td><td><b>".round($totalTotal,2)."</b></td>";
	echo "</table>";
}

 ?>

