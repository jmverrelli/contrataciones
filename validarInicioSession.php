<?php
include_once 'data/usuario/usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

session_start();

$nomUsuario=$_POST['usuario'];

$contraUsuario=$_POST['contra'];

$entidad=$_POST['entidad'];

$acceso = $obj->acceso($nomUsuario,$contraUsuario,$entidad);

$data->result = true;

$direccion = $_SERVER['SERVER_NAME'];

if($acceso!=false)
{
	header ("Location: menuPrincipal.php");
}
else 
{
	$data->result = false;

	$data->message = "Password o usuario incorrecto";	

	header ("Location: index.php?error=1");
}
?>
