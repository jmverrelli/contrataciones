<script src="presentacion/locaciones/includes/js/locaciones.js"></script>
<script type="text/javascript">
	
$('#verPorHospProInicio').datepicker({ dateFormat: 'yy-mm-dd' });
$('#verPorHospProFinal').datepicker({ dateFormat: 'yy-mm-dd' });


</script>

<h1 class="centered">Ver Por Hospital y Profesional</h1>

<form id="verPorHospProForm" name="verPorHospProForm" action="presentacion/locaciones/includes/ajaxFunctions/ImporteHospPro.php" method="POST" target="_blank">
	<div class="centered">
	
	<div class="block">
		<label class="labelForm" for="verPorHospProInicio">Fecha Inicio</label>
		<input name="verPorHospProInicio" id="verPorHospProInicio">
	</div>

	<div class="block">
		<label class="labelForm" for="verPorHospProFinal">Fecha Final</label>
		<input name="verPorHospProFinal" id="verPorHospProFinal">
	</div>
	
	
	<div class="field">

	<input type="submit" class="btnin" name="verPorHospProbtn" id="verPorHospProbtn" value="Exportar" />

	</div>
	</div>


</form>