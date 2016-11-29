<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';

$dbloc = new locacionesDataBaseLinker();

$dbloc->importeHospProExcel($_POST);

