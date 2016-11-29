<?php

$locas = $_POST['locas']; 

?>

<div id="printitnow" class="field">
<h1 class="centered">Por Hospital</h1>	
	<table style="width:100%;"><tr><td>Hospital</td><td>Profesional</td><td>Monto</td><td>Periodo</td></tr></table>
	<hr />
	<table style="width:100%;">
		<tr>
			<td><b><?php echo $locas[0]['Hospital']; ?></b></td>
		</tr>
		<?php
			$totalEs = 0;
			echo "<tr><td>".$locas[0]['Especialidad']."</td></tr>";
			$espAnt = $locas[0]['Especialidad'];
			for ($i=0; $i < count($locas) ; $i++) { 

				if($espAnt == $locas[$i]['Especialidad']){
					echo "<tr>";
					echo "<td></td><td>".$locas[$i]['Apellido y nombre']."</td><td>$".$locas[$i]['Monto']."</td><td>".$locas[$i]['FechaInicio']." - ".$locas[$i]['FechaInicio']."</td>";
					echo "</tr>";
					$totalEs += $locas[$i]['Monto']; 
				}
				else{
					echo "<tr><td><b>Total especialidad: $".$totalEs."</b></td></tr>";
					echo "<tr><td><hr style='border: 0; height: 1px;background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));' /><td></tr>";
					echo "<tr><td>".$locas[$i]['Especialidad']."</td></tr>";
					echo "<tr>";
					echo "<td></td><td>".$locas[$i]['Apellido y nombre']."</td><td>$".$locas[$i]['Monto']."</td><td>".$locas[$i]['FechaInicio']." - ".$locas[$i]['FechaInicio']."</td>";
					echo "</tr>";	
					$totalEs = 0;
					$totalEs += $locas[$i]['Monto']; 
				}

				$espAnt = $locas[$i]['Especialidad'];


			}

			echo "<tr><td><b>Total especialidad: $".$totalEs."</b></td></tr>";
		?>
	</table>
<hr />
</div>

<div class="field">
<input type="button"  class="btnin" name="verPorLocacionImprimirbtn" id="verPorLocacionImprimirbtn" value="Imprimir" />
<a href="presentacion/locaciones/includes/ajaxFunctions/porLocacionExcel.php" class="btnin abtnin" >Excel</a>
</div>

<script type="text/javascript">
	
$(document).ready(function(){


	    $('#verPorLocacionImprimirbtn').click(function(event){

	    	var prtContent = document.getElementById("printitnow");
			var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
			WinPrint.document.write(prtContent.innerHTML);
			WinPrint.document.close();
			WinPrint.focus();
			WinPrint.print();
			WinPrint.close();

	    });
});


</script>