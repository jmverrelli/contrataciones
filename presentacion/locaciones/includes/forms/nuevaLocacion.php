
<?php
/*Agregado para que valide los usuarios*/
include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';
session_start();

$usuario = $_SESSION['usuario'];
$data = unserialize($usuario);
/*fin de agregado para que valide los usuarios*/

include_once '../../../../data/hospitalesDataBaseLinker.class.php';
include_once '../../../../data/profesionalesDataBaseLinker.class.php';
include_once '../../../../data/locacionesDataBaseLinker.class.php';
include_once '../../../../data/destinosDataBaseLinker.class.php';
include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';



$dbHospitales = new HospitalesDataBaseLinker();

$hospitalesSelect = $dbHospitales->getHospitalesSelect();

$dbProfesionales = new profesionalesDataBaseLinker();

$profesionalesSelect = $dbProfesionales->getProfesionalesSelect();

$dbLocaciones = new locacionesDataBaseLinker();

//$locacionesAnteriores = $dbLocaciones->getLocaciones();


//$dbDestinos = new destinosDataBaseLinker();

//$destinosSelect = $dbDestinos->getDestinosSelect();

?>
<script type="text/javascript">
	$("#fechaLocacion").on('click');
 	$('#fechaLocacion').datepicker({ dateFormat: 'yy-mm-dd' });
 	$('#locacion').datepicker({ dateFormat: 'yy-mm-dd' });

 	function fechas(){

 		var date = new Date($('#locacion').val().replace(/-/g, '\/'));
 		var months = [1,2,3,4,5,6,7,8,9,10,11,12];
 		if(date.getMonth() == 0){
 			var y = date.getFullYear() - 1;
 			var m = months[11];	
 		}
 		else{
 			var y = date.getFullYear();
 			var m = date.getMonth();
 		}
 		
 		var firstDay = new Date(y, m, 1);
 		var lastDay = new Date(y, m, 0);
 		var firstString = String(y) + '-' + String(m) + '-' + firstDay.getDate(); 
 		var lastString = String(y) + '-' + String(m) + '-' + lastDay.getDate(); 
 		var locacionString = String(date.getFullYear()) + '-' + String(date.getMonth() + 1);
 		$('#locacion').val(locacionString);
 		$('#fechaInicioLoc').val(firstString);
 		$('#fechaFinalLoc').val(lastString);



 	}
</script>
<form id="nuevaLocacionForm" name="nuevaLocacionForm">
	<div class="block">
		<label class="labelForm" for="Profesional">Profesional</label>
		<select id="Profesional" name="Profesional" onchange="traerEspecialidadesLoca(document.getElementById('Profesional'), $('#EspecialidadLoc'), 'SELECT');traerLocacionesAnteriores();">
			<option value="SELECCIONAR">SELECCIONE UN PROFESIONAL</option>
			<?php echo $profesionalesSelect; ?>
		</select>
	</div>

	<div class="block" style="float:right; width:30%;">
		<div id="locacionesAnteriores" name="locacionesAnteriores"></div>
	</div>

	<div class="block">
		<label class="labelForm" for="EspecialidadLoc">Especialidad</label>
		<select id="EspecialidadLoc" name="EspecialidadLoc"">
			
		</select>
	</div>

	<div class="block">
		<label class="labelForm" for="fechaLocacion" >Fecha</label>
		<input name="fechaLocacion" id="fechaLocacion">
	</div>
	<div class="block">
		<label class="labelForm" for="locacion">Locacion</label>
		<input name="locacion" id="locacion" onchange="fechas();">
	</div>
	<div class="block">
		<label class="labelForm" for="fechaInicioLoc">Fecha Inicio</label>
		<input name="fechaInicioLoc" id="fechaInicioLoc">
	</div>
	<div class="block">
		<label class="labelForm" for="fechaFinalLoc">Fecha Final</label>
		<input name="fechaFinalLoc" id="fechaFinalLoc">
	</div>

	

	<input type="hidden" name="idUsuario" id="idUsuario" value=<?php echo '"'.$data->id.'"'; ?> />
</form>

