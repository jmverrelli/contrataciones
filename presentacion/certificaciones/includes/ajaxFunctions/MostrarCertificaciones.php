<?php

include_once '../../../../data/certificacionesDataBaseLinker.class.php';

$obj = new CertificacionesDataBaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
	$arr = json_decode($_POST['filters'], true);
}

$response = $obj->getCertificacionesJson($_REQUEST['page'], $_REQUEST['rows'], $arr, $_REQUEST['sidx'], $_REQUEST['sord']);


echo $response;