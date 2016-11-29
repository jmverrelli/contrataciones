<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$idProfesional = $_POST['idProfesional'];

$dbloc = new locacionesDataBaseLinker();

$response = $dbloc->traerLocacionesAnteriores($idProfesional);

echo json_encode($response);





