<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$obj = new locacionesDataBaseLinker();

$stdClass = new stdClass();

$response = $obj->traerDetallesLocacion($_POST['IdLocacion']);

if(!$response){

	$stdClass->ret = false;
	$stdClass->message = "Esta Locacion no tiene detalles.";

}
else
{
	$stdClass->ret = true;
	$stdClass->message = "Detalles Encontrados";
	$stdClass->detalles = $response;
}



echo json_encode($stdClass);