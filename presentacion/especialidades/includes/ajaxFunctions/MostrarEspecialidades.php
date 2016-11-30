<?php

include_once '../../../../data/especialidadesDataBaseLinker.class.php';

$obj = new EspecialidadesDataBaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
	$arr = json_decode($_POST['filters'], true);
}

$response = $obj->getEspecialidadesJson($_REQUEST['page'], $_REQUEST['rows'], $arr, $_REQUEST['sidx'], $_REQUEST['sord']);


echo $response;