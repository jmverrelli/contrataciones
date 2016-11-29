<?php
include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';
include_once '../../../../data/usuario/usuario.class.php';
session_start();
$usuario = $_SESSION['usuario'];
$data = unserialize($usuario);

$obj = new UsuarioDatabaseLinker();

$registro = $obj->cambiarContrasenaUsuario($data->getApodo(), $_POST);

echo json_encode($registro);

?>