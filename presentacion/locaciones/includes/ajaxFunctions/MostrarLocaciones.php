<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$obj = new LocacionesDataBaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
	$arr = json_decode($_POST['filters'], true);
}

$response = $obj->getLocacionesJson($_REQUEST['page'], $_REQUEST['rows'], $arr, $_REQUEST['sidx'], $_REQUEST['sord']);


echo $response;