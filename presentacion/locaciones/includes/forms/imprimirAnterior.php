<?php
include_once '../../../../data/locacionesDataBaseLinker.class.php';
$dbLoc = new locacionesDataBaseLinker();
$fechaPedida = $_GET['fechaPedida'];
$aImprimirElevados = $dbLoc->duplicadosElevacionFecha($fechaPedida);

if(!$aImprimirElevados){

	echo "No se encontraron elevaciones para esta fecha.";
	?>
	<script>
		
		alert("No se encontraron locaciones para esta fecha.");

	</script>
	<?php 
	exit;
}

?>

<style type="text/css">
	
	.impresion{		
		text-align: center;
		margin-top: 2%;
		margin-left: auto;
		margin-right: auto;
		width: 50em;
	}

	.leblock{
		white-space:nowrap;
	}

	.image{
		display: inline;
		float:left;
	}

	.text{
		display: inline;
		white-space: nowrap;
		word-wrap: break-word;
	}

	.parrafo{
		margin-top: 10%;
	}

	.parra{
		white-space: pre-line;
		line-height: 2em;
	}

	.bordereded{

		border: 1px solid black;
		border-collapse: collapse;
	}

	.bordereded td {

		padding: 0.2%;
	}

	.bordereded th {

		padding-right: 2%; ;
	}

	@page {
  size: A4;
  margin: 0;
	}


.aImprimir{

	margin: 0px;
	width: 100%;
	page-break-after:always 
}

</style>

<div id="aImprimir" name="aImprimir" class="impresion">


	<h1 class="centered">Duplicado Elevacion</h1>
<div>
	<div><h5>Elevacion a Hacienda - <?php echo date('d-m-Y'); ?></h5></div>
<table class="bordereded" border="1">
		<tr>
			<th>Profesional</th><th></th><th>Proveedor</th><th>Especialidad</th><th>Locacion</th><th>Periodo</th><th>Nro Carga</th>
		</tr>
			<?php

			$proAnterior = false;
			$total = 0;

			for ($i=0; $i < count($aImprimirElevados) ; $i++) { 
				
				echo "<tr>";

				if($proAnterior != $aImprimirElevados[$i]['IdProfesional']){

					if($total != 0){

						echo "<tr><td></td><td><b>Total: </b>".$total."</td></tr>";
						$total = 0;
					}
					echo "<td>".$aImprimirElevados[$i]['Profesional']."</td>";
					echo "<td></td>";
					echo "<td>".$aImprimirElevados[$i]['Proveedor']."</td>";
					echo "<td>".$aImprimirElevados[$i]['Especialidad']."</td>";
					echo "<td>".$aImprimirElevados[$i]["Locacion"]."</td>"; 
					echo "<td>".$aImprimirElevados[$i]['FechaInicio']." - ".$aImprimirElevados[$i]['FechaFinal']."</td>";
					echo "<td>".$aImprimirElevados[$i]['IdLocacion']."</td>";

				}

				echo "<tr><td><b>Hospital:</b> ".$aImprimirElevados[$i]['Hospital']."</td><td><b>Monto:</b> ".$aImprimirElevados[$i]['Monto']."</td></tr>";
				echo "</tr>";
				$total += $aImprimirElevados[$i]['Monto'];
				$proAnterior = $aImprimirElevados[$i]['IdProfesional'];
			}

			echo "<tr style='background-color:#2a2b30;'><td></td><td><b>Total: </b>".$total."</td></tr>";


			?>
	</table>


</div>

</div>


<script type="text/javascript">

function printTHISLocaciones(){
	window.print();
}
</script>