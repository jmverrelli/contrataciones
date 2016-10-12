<?php

include_once '../../../../data/prestacionesDataBaseLinker.class.php';

$obj = new prestacionesDataBaseLinker();

$valor = $obj->traerValor($_POST);

echo json_encode($valor);