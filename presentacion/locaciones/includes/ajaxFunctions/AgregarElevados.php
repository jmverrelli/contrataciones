<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$noElevados = $_POST['noElevados'];

$dbloc = new locacionesDataBaseLinker();

$response = $dbloc->elevarLocaciones($noElevados);

echo json_encode($response);





