<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$obj = new LocacionesDataBaseLinker();

$response = $obj->eliminarDetalleLocacion($_POST['IddetalleLocacion']);


echo json_encode($response);