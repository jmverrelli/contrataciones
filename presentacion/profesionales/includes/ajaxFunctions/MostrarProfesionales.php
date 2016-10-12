<?php

include_once '../../../../data/profesionalesDataBaseLinker.class.php';

$obj = new ProfesionalesDataBaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
	$arr = json_decode($_POST['filters'], true);
}

$response = $obj->getProfesionalesJson($_REQUEST['page'], $_REQUEST['rows'], $arr, $_REQUEST['sidx'], $_REQUEST['sord']);


echo $response;