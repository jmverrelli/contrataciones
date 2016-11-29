<?php
include_once '../../../../data/locacionesDataBaseLinker.class.php';
$dbLoc = new locacionesDataBaseLinker();

$noElevados = $dbLoc->duplicadosElevacion();

session_start();

unset($_SESSION['LocNoElev']);

$_SESSION['LocNoElev'] = $noElevados;

if($noElevados == false){

	echo "No hay locaciones sin elevar";
	exit;
}

?>

<style type="text/css">
		
		.borderExt tr:nth-child(even){background-color: #545559}

		.borderExt{
			width: 100%;
			border-collapse: collapse;
		}

		.borderExt td{
			text-align: left;
		}
		.borderExt th {
		    background-color: #2a2b30;
		    color: white;
		    text-align: left;
		}

</style>
<h1 class="centered">Duplicado Elevacion</h1>
<div>
	<div><h5>Elevacion a Hacienda</h5></div>
<table class="padd borderExt">
		<tr>
			<th>Profesional</th><td></td><th>Proveedor</th><th>Especialidad</th><th>Locacion</th><th>Periodo</th><th>Nro Carga</th>
		</tr>
			<?php

			$proAnterior = false;
			$total = 0;

			for ($i=0; $i < count($noElevados) ; $i++) { 
				
				echo "<tr>";

				if($proAnterior != $noElevados[$i]['IdProfesional']){

					if($total != 0){

						echo "<tr><td></td><td><b>Total: </b>".$total."</td></tr>";
						$total = 0;
					}

					echo "<td>".$noElevados[$i]['Profesional']."</td>";
					echo "<td></td>";
					echo "<td>".$noElevados[$i]['Proveedor']."</td>";
					echo "<td>".$noElevados[$i]['Especialidad']."</td>";
					echo "<td>".$noElevados[$i]["Locacion"]."</td>"; 
					echo "<td>".$noElevados[$i]['FechaInicio']." - ".$noElevados[$i]['FechaFinal']."</td>";
					echo "<td>".$noElevados[$i]['IdLocacion']."</td>";

				}

				echo "<tr><td><b>Hospital:</b> ".$noElevados[$i]['Hospital']."</td><td><b>Monto:</b> ".$noElevados[$i]['Monto']."</td></tr>";
				echo "</tr>";
				$total += $noElevados[$i]['Monto'];
				$proAnterior = $noElevados[$i]['IdProfesional'];
			}

			echo "<tr style='background-color:#2a2b30;'><td></td><td><b>Total: </b>".$total."</td></tr>";


			?>
	</table>


</div>
<input type="button"  class="btnin" name="elevarLocaciones" value="Elevar Locaciones" onclick="elevarLocaciones()" />

<script type="text/javascript">
	
	function elevarLocaciones(){

		var noElevados = <?php echo json_encode($noElevados); ?> ;

		generarImpresionElevadas();
		event.preventDefault(event);
        $.ajax({
            data: {noElevados : noElevados},
            type: "POST",
            dataType: "json",
            url: "presentacion/locaciones/includes/ajaxFunctions/AgregarElevados.php",
            success: function(data)
            {
                if(data.ret == false){
                    alert(data.message);
                }
                else{
                    
                    alert(data.message);
                    location.reload();
                }
            }
        });
}


function generarImpresionElevadas(noElevados) {

    var iframes = document.querySelectorAll('iframe');
    for (var i = 0; i < iframes.length; i++) {
        iframes[i].parentNode.removeChild(iframes[i]);
    }

    $("<iframe id='iframeprintLocaciones' name='iframeprintLocaciones'>")
        .hide()
        .attr("src", "presentacion/locaciones/includes/forms/imprimirLocacion.php?noElevados=" + noElevados)
        .appendTo("body");  

         $('#iframeprintLocaciones').load(function () {
            document.iframeprintLocaciones.printTHISLocaciones();
    });             
}



</script>