<?php

include_once '../../../../data/profesionalesDataBaseLinker.class.php';

$obj = new ProfesionalesDataBaseLinker();

$response = $obj->eliminarProfesional($_POST['id']);

echo json_encode($response);