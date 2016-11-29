<?php
include_once '../../../../data/locacionesDataBaseLinker.class.php';
$dbLoc = new locacionesDataBaseLinker();

$locacion = $dbLoc->traerLocacion($_POST['id']);
$detallesLocacion = $dbLoc->traerDetallesLocacion($_POST['id']);

$detalles = '';

if($detallesLocacion == false){

	$detalles = "Esta Locacion no tiene detalles.";
}

?>
<div>
	<div><h5>Locacion</h5></div>
	<table class="padd border">
		<tr>
			<th>Profesional</th><th>Locacion</th><th>Inicio</th><th>Final</th>
		</tr>
		<tr>
			<td> <?php echo $locacion->nombreProfesional; ?></td>
			<td> <?php echo $locacion->Locacion; ?></td>
			<td><?php echo $locacion->FechaInicio; ?></td>
			<td> <?php echo $locacion->FechaFinal; ?></td>
		</tr>
	</table>
</div>
<hr />
<div><h5>Detalles</h5></div>

<div id="detallesEncontradosLoc" name="detallesEncontradosLoc"> <?php echo $detalles; ?> </div>

<?php 

if($detallesLocacion != false){

	$total = 0;
	$totalTotal = 0;

	echo "<table class='padd border'>";
		echo "<tr>";
		echo "<th>Hospital</th><th>Monto</th><th>Total</th>";
		echo "</tr>";
		

	for ($i=0; $i < count($detallesLocacion) ; $i++) { 
		$total += $detallesLocacion[$i]->Monto;
		echo "<tr>";
		echo "<td>".$detallesLocacion[$i]->Hospital."</td>";
		echo "<td>".$detallesLocacion[$i]->Monto."</td>";
		echo "<td></td>";
		echo "</tr>";
	}

	echo "<tr><td></td><td></td><td><b>".round($total,2)."</b></td>";
	echo "</table>";
}

 ?>

