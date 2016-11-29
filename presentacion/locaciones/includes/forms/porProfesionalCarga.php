<?php

$profes = $_POST['profes']; 

?>

<div id="printitnowProfesional" class="field">
<h1 class="centered">Por Profesional Por Mes</h1>	
	<table style="width:100%;"><tr><td>Profesional</td><td>Monto</td></table>
	<hr />
	<table style="width:100%;">		
		<?php
			for ($i=0; $i < count($profes) ; $i++) { 

				echo "<tr>
						<td>".$profes[$i]['Apellido y Nombre']."</td>
						<td>$".$profes[$i]['Monto']."</td>
					</tr>";

				}
		?>
	</table>
<hr />
</div>

<div class="field">
<input type="button"  class="btnin" name="verPorProfesionalImprimirbtn" id="verPorProfesionalImprimirbtn" value="Imprimir" />
<a href="presentacion/locaciones/includes/ajaxFunctions/porProfesionalExcel.php" class="btnin abtnin" >Excel</a>
</div>

<script type="text/javascript">
	
$(document).ready(function(){


	    $('#verPorProfesionalImprimirbtn').click(function(event){

	    	var prtContent = document.getElementById("printitnowProfesional");
			var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
			WinPrint.document.write(prtContent.innerHTML);
			WinPrint.document.close();
			WinPrint.focus();
			WinPrint.print();
			WinPrint.close();

	    });
});


</script>