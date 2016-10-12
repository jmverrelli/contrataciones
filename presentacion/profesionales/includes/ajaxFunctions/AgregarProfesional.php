<?php

include_once '../../../../data/profesionalesDataBaseLinker.class.php';

$obj = new ProfesionalesDataBaseLinker();

$response = $obj->agregarProfesional($_POST);


echo json_encode($response);