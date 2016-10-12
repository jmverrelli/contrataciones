<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcUsuario.'usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$ret = $obj->getUsuariosJson($_POST['entidad'], $_REQUEST['page'], $_REQUEST['rows']);
	
echo $ret;

?>