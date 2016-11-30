<?php

include_once '../../../../data/especialidadesDataBaseLinker.class.php';

$obj = new EspecialidadesDataBaseLinker();

$response = $obj->eliminarEspecialidad($_POST['id']);

echo json_encode($response);