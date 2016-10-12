<?php

include_once '../../../../data/prestacionesDataBaseLinker.class.php';

$obj = new PrestacionesDataBaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
	$arr = json_decode($_POST['filters'], true);
}

$response = $obj->getPrestacionesJson($_REQUEST['page'], $_REQUEST['rows'], $arr, $_REQUEST['sidx'], $_REQUEST['sord']);


echo $response;