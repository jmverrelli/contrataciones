<?php

include_once '../../../../data/certificacionesDataBaseLinker.class.php';

$obj = new CertificacionesDataBaseLinker();

$response = $obj->agregarFirmaCertificacion($_POST);


echo json_encode($response);