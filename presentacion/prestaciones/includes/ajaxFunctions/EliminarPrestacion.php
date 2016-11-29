<?php

include_once '../../../../data/prestacionesDataBaseLinker.class.php';

$obj = new PrestacionesDataBaseLinker();

$oper = $_POST['oper'];

var_dump($_POST);

if($oper == 'edit'){

	$response = $obj->modificarPrestacion($_POST);

}
elseif($oper == 'del'){

	$response = $obj->eliminarPrestacion($_POST['id']);
}


echo json_encode($response);