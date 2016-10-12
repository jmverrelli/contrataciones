<?php

include_once '../../../../data/prestacionesDataBaseLinker.class.php';

$obj = new PrestacionesDataBaseLinker();

$response = $obj->agregarNuevaPrestacion($_POST);


echo json_encode($response);