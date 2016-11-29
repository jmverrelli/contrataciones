<script src="presentacion/profesionales/includes/js/profesionales.js"></script>
<?php

include_once '../../../../data/especialidadesDataBaseLinker.class.php';

$dbespe = new EspecialidadesDataBaseLinker();

$espSelect = $dbespe->EspecialidadesSelect();

?>

<h1 class="centered">Agregar Profesional</h1>

<form id="agregarProfesionalForm" name="agregarProfesionalForm">
	<div class="centered">
	<div class="field">
	<label class="labelForm" for="apellido">Apellido</label>
	<input type="text" name="apellido" id="apellido" />
	</div>
	
	<div class="field">
	<label class="labelForm" for="nombre">Nombre</label>
	<input type="text" name="nombre" id="nombre" />
	</div>
	
	<div class="field">
	<label class="labelForm" for="especialidad">Especialidad</label>
	<select id="especialidad" name="especialidad">
		<?php echo $espSelect; ?>
	</select>
	</div>
	
	<div class="field">
	<label class="labelForm" for="nroConvenio">Nro. Convenio</label>
	<input type="text" name="nroConvenio" id="nroConvenio" />
	</div>
	
	<div class="field">
	<label class="labelForm" for="nroProveedor">Nro. Proveedor</label>
	<input type="text" name="nroProveedor" id="nroProveedor" />
	</div>
	
	<div class="field">
	<label class="labelForm" for="telefono">Telefono</label>
	<input type="text" name="telefono" id="telefono" />
	</div>
	
	<div class="field">
	<input type="button"  class="btnin" name="agregarProfesional" id="agregarProfesional" value="Agregar Profesional" />
	</div>
	</div>
</form>