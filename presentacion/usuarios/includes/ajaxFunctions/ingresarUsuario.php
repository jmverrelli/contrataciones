<?php
include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$registro = $obj->confirmarRegistroUsuario($_POST);

echo json_encode($registro);

?>