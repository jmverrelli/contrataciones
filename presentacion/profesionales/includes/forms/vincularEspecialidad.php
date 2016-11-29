<?php

include_once '../../../../data/profesionalesDataBaseLinker.class.php';
include_once '../../../../data/especialidadesDataBaseLinker.class.php';

$dbespe = new EspecialidadesDataBaseLinker();
$dbpro = new ProfesionalesDataBaseLinker();

$espSelect = $dbespe->EspecialidadesSelect();
$proSelect = $dbpro->getProfesionalesSelect();

?>

<h1 class="centered">Vincular Profesional y Especialidad</h1>

<form id="vincularEspecialidadForm" name="vincularEspecialidadForm">
	<div class="field" style="float:right; width:auto; margin-right:40%;">
	<u>Especialidades ya vinculadas</u>
	<div id="especialidadesYaVinculadas" name="especialidadesYaVinculadas">
	No hay vinculaciones.
	</div>
	</div>
	<div>
	<div class="field">
	<label class="labelForm" for="VincularProfesional">Profesional</label>
	<select id="VincularProfesional" name="VincularProfesional" onchange="traerEspecialidadesLoca(document.getElementById('VincularProfesional'), $('#especialidadesYaVinculadas'), 'LIST');">
	<option value="SELECCIONAR">Seleccione un profesional</option>
		<?php echo $proSelect; ?>
	</select>
	</div>

	<div class="field">
	<label class="labelForm" for="VincularEspecialidad">Especialidad</label>
	<select id="VincularEspecialidad" name="VincularEspecialidad">
		<?php echo $espSelect; ?>
	</select>
	</div>
	
	<div class="field" style="margin-left:2em;">
	<input type="submit" class="btnin" name="vincularEspecialidadBtn" id="vincularEspecialidadBtn" value="Vincular" onclick="javascript:vincularEspecialidad();" />
	</div>
	</div>
</form>