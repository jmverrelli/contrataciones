<?php

include_once '../../../../data/especialidadesDataBaseLinker.class.php';

$obj = new EspecialidadesDataBaseLinker();

$response = $obj->agregarEspecialidad($_POST);


echo json_encode($response);