<script src="presentacion/locaciones/includes/js/locaciones.js"></script>
<script type="text/javascript">
	
$('#verPorLocacionLocacion').datepicker({ dateFormat: 'yy-mm-dd' });

function fechasPorLocacion(){

var date = new Date($('#verPorLocacionLocacion').val().replace(/-/g, '\/'));
var months = [1,2,3,4,5,6,7,8,9,10,11,12];
var locacionStringLocacion = String(date.getFullYear()) + '-' + String(date.getMonth() + 1);
$('#verPorLocacionLocacion').val(locacionStringLocacion);

}

</script>
<?php

include_once '../../../../data/hospitalesDataBaseLinker.class.php';

$dbHosp = new hospitalesDataBaseLinker();

$hospSelect = $dbHosp->getHospitalesSelect();

?>

<h1 class="centered">Ver Por Locacion</h1>

<form id="verPorLocacionForm" name="verPorLocacionForm">
	<div class="centered">
	
	<div class="field">
	<label class="labelForm" for="porLocHospital">Hospital</label>
	<select id="porLocHospital" name="porLocHospital">
		<?php echo $hospSelect; ?>
	</select>
	</div>
	
		<div class="block">
		<label class="labelForm" for="verPorLocacionLocacion">Locacion</label>
		<input name="verPorLocacionLocacion" id="verPorLocacionLocacion" onchange="fechasPorLocacion();">
	</div>
	
	
	<div class="field">
	<input type="button"  class="btnin" name="verPorLocacionbtn" id="verPorLocacionbtn" value="Ver Por Locacion" />
	</div>
	</div>

	<div id="porLocacionContainer" name="PorLocacionContainer"></div>

</form>