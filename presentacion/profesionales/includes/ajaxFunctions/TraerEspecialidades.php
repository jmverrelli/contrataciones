<?php

include_once '../../../../data/profesionalesDataBaseLinker.class.php';

$obj = new ProfesionalesDataBaseLinker();

$response = $obj->traerEspecialidades($_POST['idProfesional']);


echo json_encode($response);