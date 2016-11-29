<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$obj = new LocacionesDataBaseLinker();

$response = $obj->agregarMonto($_POST);


echo json_encode($response);