<?php

include_once '../../../../data/certificacionesDataBaseLinker.class.php';

$dbloc = new certificacionesDataBaseLinker();

$result = $dbloc->porProfesionaExcel($_POST);


echo json_encode($result);