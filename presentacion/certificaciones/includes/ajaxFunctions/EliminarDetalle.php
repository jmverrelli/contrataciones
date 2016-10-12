<?php

include_once '../../../../data/detalleCertificacionesDataBaseLinker.class.php';

$obj = new DetalleCertificacionesDataBaseLinker();

$response = $obj->eliminarDetalleCertificacion($_POST['IddetalleCertificacion']);


echo json_encode($response);