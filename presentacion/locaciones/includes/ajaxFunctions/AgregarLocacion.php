<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$obj = new LocacionesDataBaseLinker();

$response = $obj->agregarLocacion($_POST);


echo json_encode($response);