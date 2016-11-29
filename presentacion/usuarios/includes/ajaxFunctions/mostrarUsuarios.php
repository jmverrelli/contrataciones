<?php
include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$ret = $obj->getUsuariosJson($_POST['entidad'], $_REQUEST['page'], $_REQUEST['rows']);
	
echo $ret;

?>