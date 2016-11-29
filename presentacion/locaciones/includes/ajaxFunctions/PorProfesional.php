<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$dbloc = new locacionesDataBaseLinker();

$response = $dbloc->PorProfesional($_POST);

echo json_encode($response);

