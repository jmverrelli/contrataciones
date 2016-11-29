<?php

include_once '../../../../data/certificacionesDataBaseLinker.class.php';

$obj = new CertificacionesDataBaseLinker();

$response = $obj->eliminarCertificacion($_POST['id']);


echo json_encode($response);