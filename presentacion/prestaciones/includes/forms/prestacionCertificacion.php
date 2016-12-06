<script type="text/javascript">
	
$(document).ready(function(){

	cargarPrestaciones($("#prestacion"));

	$("#prestacion").change(function(event){

		traerValorPrestacion($("#prestacion"));

		$("#prestSeleccionada").val($("#prestacion option:selected").val());

	});

});

</script>
<?php
include_once '../../../../data/prestacionesDataBaseLinker.class.php';
include_once '../../../../data/certificacionesDataBaseLinker.class.php';
include_once '../../../../data/detalleCertificacionesDataBaseLinker.class.php';

$ultimaCertificacion = $_POST['id'];

$dbPres = new prestacionesDataBaseLinker();
$dbDetalleCert = new DetalleCertificacionesDataBaseLinker();
$dbCert = new certificacionesDataBaseLinker();

//$prestacionesSelect = $dbPres->traerPrestacionesSelect();
$detallesCertificacion = $dbDetalleCert->traerDetallesCertificacion($ultimaCertificacion);
$certificacion = $dbCert->traerCertificacion($ultimaCertificacion);

if($detallesCertificacion == false){

	$detalles = "Esta Certificacion no tiene detalles aun.";
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
<form id="nuevaPrestacion" name="nuevaPrestacion">
	<div><h5>Ingreso de Prestacion</h5></div>
	<table class="padd border">
	<tr>
		<th>Prestacion</th><th>Valor</th><th>Cantidad</th><th>Subtotal</th><th></th>
	</tr>
	<tr>
		<td>
			<select id="prestacion" name="prestacion" >
				<option value="SELECCIONAR PRESTACION">Seleccione una Prestacion</option>
				<?php //echo $prestacionesSelect; ?>
			</select>
		</td>
		<td><input type="text" name="valorPrestacionCertificacion" id="valorPrestacionCertificacion" readonly /></td>
		<td><input type="number" name="cantidad" id="cantidad" onkeyup="calculartotal();" /></td>
		<td><input type="number" name="total" id="total" disabled="disabled" /></td>
		<td><input type="button" name="agregarPrestacion" id="agregarPrestacion" value="Agregar" />
	</tr>
	</table>
	<br />
	<br />
	<br />
	<hr />

	<div><h5>Prestaciones Agregadas </h5></div>
	<div id="detallesEncontrados" name="detallesEncontrados"> <?php echo $detalles; ?> </div>
	<input type="hidden" name="IdCertificacion" id="IdCertificacion" value=<?php echo "'".$ultimaCertificacion."'"; ?>>
	<input type="hidden" name="prestSeleccionada" id="prestSeleccionada" value="">
</form>



<script src="presentacion/prestaciones/includes/js/prestaciones.js"></script>