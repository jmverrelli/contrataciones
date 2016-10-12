<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcUsuario.'usuarioDatabaseLinker.class.php';
include_once nspcUsuario.'usuario.class.php';
session_start();
$usuario = $_SESSION['usuario'];
$data = unserialize($usuario);

$obj = new UsuarioDatabaseLinker();

$registro = $obj->cambiarContrasenaUsuario($data->getApodo(), $_POST);

echo json_encode($registro);

?>