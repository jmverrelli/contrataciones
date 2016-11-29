<?php
include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$registro = $obj->confirmarPermisosUsuario($_POST);

echo json_encode($registro);

?>