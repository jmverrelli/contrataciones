<?php

include_once '../../../../data/prestacionesDataBaseLinker.class.php';

$obj = new prestacionesDataBaseLinker();

$valor = $obj->traerPrestacionesJson();

echo $valor;