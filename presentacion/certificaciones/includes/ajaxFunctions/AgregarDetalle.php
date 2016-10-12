<?php

include_once '../../../../data/detalleCertificacionesDataBaseLinker.class.php';

$obj = new DetalleCertificacionesDataBaseLinker();

$response = $obj->agregarDetalleCertificacion($_POST);


echo json_encode($response);