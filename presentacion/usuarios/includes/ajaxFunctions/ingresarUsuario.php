<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcUsuario.'usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$registro = $obj->confirmarRegistroUsuario($_POST);

echo json_encode($registro);

?>