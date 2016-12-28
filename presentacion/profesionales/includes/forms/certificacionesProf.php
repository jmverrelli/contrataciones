<script src="presentacion/certificaciones/includes/js/certificaciones.js"></script>
<script type="text/javascript">
	
$('#certifPorProfesionalInicio').datepicker({ dateFormat: 'yy-mm-dd' });
$('#certifPorProfesionalFinal').datepicker({ dateFormat: 'yy-mm-dd' });


</script>

<h1 class="centered">Certificaciones por Profesional</h1>

<form id="verPorProfesionalForm" name="verPorProfesionalForm">
	<div class="centered">
	
	<div class="block">
		<label class="labelForm" for="certifPorProfesionalInicio">Fecha Inicio</label>
		<input name="certifPorProfesionalInicio" id="certifPorProfesionalInicio">
	</div>

	<div class="block">
		<label class="labelForm" for="certifPorProfesionalFinal">Fecha Final</label>
		<input name="certifPorProfesionalFinal" id="certifPorProfesionalFinal">
	</div>
	
	
	<div class="field">
	<input type="button"  class="btnin" name="certifPorProfesionalbtn" id="certifPorProfesionalbtn" value="Ver Por Profesional" />
	</div>
	</div>

	<div id="certifPorProfesionalContainer" name="certifPorProfesionalContainer"></div>

</form>