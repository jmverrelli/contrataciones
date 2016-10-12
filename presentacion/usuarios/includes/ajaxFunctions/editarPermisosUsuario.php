<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcUsuario.'usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$registro = $obj->confirmarPermisosUsuario($_POST);

echo json_encode($registro);

?>