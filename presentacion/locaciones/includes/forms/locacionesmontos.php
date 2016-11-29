<?php
include_once '../../../../data/locacionesDataBaseLinker.class.php';
include_once '../../../../data/hospitalesDataBaseLinker.class.php';

$ultimaLocacion = $_POST['id'];

$dbLoc = new locacionesDataBaseLinker();
$dbHosp = new hospitalesDataBaseLinker();

$detallesLocacion = $dbLoc->traerDetallesLocacion($ultimaLocacion);
$locacion = $dbLoc->traerLocacion($ultimaLocacion);
$hospSelect = $dbHosp->getHospitalesSelect();

if($detallesLocacion == false){

	$detalles = "Esta Locacion no tiene detalles aun.";
}

?>
<div>
	<div><h5>Locacion Activa</h5></div>
	<table class="padd border">
		<tr>
			<th>Profesional</th><th>Especialidad</th><th>Locacion</th><th>Inicio</th><th>Final</th>
		</tr>
		<tr>
			<td> <?php echo $locacion->nombreProfesional; ?></td>
			<td> <?php echo $locacion->Especialidad; ?></td>
			<td> <?php echo $locacion->Locacion; ?></td>
			<td><?php echo $locacion->FechaInicio; ?></td>
			<td> <?php echo $locacion->FechaFinal; ?></td>
		</tr>
	</table>
</div>
<hr />
<form id="nuevoMontoLoc" name="nuevoMontoLoc">
	<div><h5>Ingreso de Montos</h5></div>
	<table class="padd border">
	<tr>
		<th>Hospital</th><th>Valor</th><th></th>
	</tr>
	<tr>
		<td>
			<select id="hospital" name="hospital">
				<?php echo $hospSelect; ?>

			</select>
		</td>
		<td><input type="text" name="valor" id="valor" /></td>
		<td><input type="button" name="agregarDetLoc" id="agregarDetLoc" value="Agregar" />
	</tr>
	</table>
	<br />
	<br />
	<br />
	<hr />

	<div><h5>Locaciones Agregadas </h5></div>
	<div id="detallesLocEncontrados" name="detallesLocEncontrados"> <?php echo $detalles; ?> </div>
	<input type="hidden" name="IdLocacion" id="IdLocacion" value=<?php echo "'".$ultimaLocacion."'"; ?>>
</form>

 <script type="text/javascript">

$(document).ready(function(){
    $('#agregarDetLoc').click(function(event){
    event.preventDefault(event);
        $.ajax({
            data: $('#nuevoMontoLoc').serialize(),
            type: "POST",
            dataType: "json",
            url: "presentacion/locaciones/includes/ajaxFunctions/AgregarMonto.php",
            success: function(data)
            {
                if(data.ret == false){
                    alert(data.message);
                }
                else{
                    
                    alert(data.message);
                }
            }
        });

        $("#valor").val('');
        $('#hospital>option:eq(0)').prop('selected', true);
        traerDetallesLocaciones($("#IdLocacion").val());
	});

});

</script>