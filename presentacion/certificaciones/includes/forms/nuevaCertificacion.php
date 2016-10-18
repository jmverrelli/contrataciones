<?php
/*Agregado para que valide los usuarios*/
include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';
session_start();

$usuario = $_SESSION['usuario'];
$data = unserialize($usuario);
/*fin de agregado para que valide los usuarios*/

include_once '../../../../data/hospitalesDataBaseLinker.class.php';
include_once '../../../../data/profesionalesDataBaseLinker.class.php';
include_once '../../../../data/destinosDataBaseLinker.class.php';
include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';



$dbHospitales = new HospitalesDataBaseLinker();

$hospitalesSelect = $dbHospitales->getHospitalesSelect();

$dbProfesionales = new profesionalesDataBaseLinker();

$profesionalesSelect = $dbProfesionales->getProfesionalesSelect();

$dbDestinos = new destinosDataBaseLinker();

$destinosSelect = $dbDestinos->getDestinosSelect();

?>

<link rel="stylesheet" type="text/css" href="presentacion/includes/js/timePicker/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="presentacion/certificaciones/includes/css/certificacion.css" />

<script type="text/javascript">
	$("#periodoDe").on('click');
 	$('#periodoDe').datepicker({ dateFormat: 'yy-mm-dd' });

	$('#periodoHasta').datepicker({ dateFormat: 'yy-mm-dd' });
</script>
<script src="presentacion/certificaciones/includes/js/certificaciones.js"></script>

<form id="nuevaCertificacionForm" name="nuevaCertificacionForm">
	<div class="block">
		<label class="labelForm" for="hospital">Hospital</label>
		<select id="hospital" name="hospital">
			<?php echo $hospitalesSelect; ?>
		</select>
	</div>
	<div class="block">
		<label class="labelForm">Periodo</label>
	</div>
	<div class="block">
		<label class="labelForm" for="periodoDe">Desde</label>
		<input name="periodoDe" id="periodoDe">
	</div>
	<div class="block">
		<label class="labelForm" for="periodoHasta">Hasta</label>
		<input name="periodoHasta" id="periodoHasta">
	</div>
	<div class="block">
		<label class="labelForm" for="Profesional">Profesional</label>
		<select id="Profesional" name="Profesional">
			<?php echo $profesionalesSelect; ?>
		</select>
	</div>
	<div class="block">
		<label class="labelForm" for="destino">Destino</label>
		<select id="destino" name="destino">
			<?php echo $destinosSelect; ?>
		</select>
	</div>
	<input type="hidden" name="idUsuario" id="idUsuario" value=<?php echo '"'.$data->id.'"'; ?>/>
</form>
