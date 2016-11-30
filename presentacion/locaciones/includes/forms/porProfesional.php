<script src="presentacion/locaciones/includes/js/locaciones.js"></script>
<script type="text/javascript">
	
$('#verPorProfesionalInicio').datepicker({ dateFormat: 'yy-mm-dd' });
$('#verPorProfesionalFinal').datepicker({ dateFormat: 'yy-mm-dd' });


</script>

<h1 class="centered">Ver Por Profesional</h1>

<form id="verPorProfesionalForm" name="verPorProfesionalForm">
	<div class="centered">
	
	<div class="block">
		<label class="labelForm" for="verPorProfesionalInicio">Fecha Inicio</label>
		<input name="verPorProfesionalInicio" id="verPorProfesionalInicio">
	</div>

	<div class="block">
		<label class="labelForm" for="verPorProfesionalFinal">Fecha Final</label>
		<input name="verPorProfesionalFinal" id="verPorProfesionalFinal">
	</div>
	
	
	<div class="field">
	<input type="button"  class="btnin" name="verPorProfesionalbtn" id="verPorProfesionalbtn" value="Ver Por Profesional" />
	</div>
	</div>

	<div id="porProfesionalCointainer" name="porProfesionalCointainer"></div>

</form>