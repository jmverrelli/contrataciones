<?php

include_once '../../../../data/prestacionesDataBaseLinker.class.php';

$obj = new PrestacionesDataBaseLinker();

$response = $obj->eliminarPrestacion($_POST['id']);

echo json_encode($response);