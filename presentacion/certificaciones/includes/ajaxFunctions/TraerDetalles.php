<?php

include_once '../../../../data/detalleCertificacionesDataBaseLinker.class.php';

$obj = new DetalleCertificacionesDataBaseLinker();

$stdClass = new stdClass();

$response = $obj->traerDetallesCertificacion($_POST['IdCertificacion']);

if(!$response){

	$stdClass->ret = false;
	$stdClass->message = "Esta Certificacion no tiene detalles.";

}
else
{
	$stdClass->ret = true;
	$stdClass->message = "Detalles Encontrados";
	$stdClass->detalles = $response;
}



echo json_encode($stdClass);