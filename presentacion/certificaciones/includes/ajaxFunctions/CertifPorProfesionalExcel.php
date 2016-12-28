<?php

include_once '../../../../data/certificacionesDataBaseLinker.class.php';

$dbloc = new certificacionesDataBaseLinker();

$dbloc->porProfesionaDibujarExcel();
