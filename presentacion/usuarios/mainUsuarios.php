<?
/*Agregado para que valide los usuarios*/
include_once '../../data/usuario/usuarioDatabaseLinker.class.php';
session_start();
$usuario = $_SESSION['usuario'];
$data = unserialize($usuario);
/*fin de agregado para que valide los usuarios*/
$entidad = $_SESSION['entidad'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Usuario</title>
        <link rel="stylesheet" type="text/css" href="includes/css/demo.css" />
        <link rel="stylesheet" type="text/css" href="includes/css/styleMain.css" />
        <link type="text/css" rel="Stylesheet" href="../includes/js/jQuery/jquery-ui.min.css" />
        <link type="text/css" rel="Stylesheet" href="../includes/js/jQuery/jquery-ui.theme.min.css" />
        <link type="text/css" rel="Stylesheet" href="../includes/js/jQuery/jquery-ui.structure.min.css" />
        <link type="text/css" rel="Stylesheet" href="../includes/js/jQGrid/css/ui.jqgrid-bootstrap-ui.css" />
        <link type="text/css" rel="Stylesheet" href="../includes/js/jQGrid/css/ui.jqgrid-bootstrap.css" />
        <link type="text/css" rel="Stylesheet" href="../includes/js/jQGrid/css/ui.jqgrid.css" />
        
        <script type="text/javascript" src="../includes/js/jQGrid/js/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="../includes/js/jQuery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../includes/js/jQGrid/js/jquery.jqGrid.min.js"></script>
        
        <script type="text/javascript" src="../includes/js/jQGrid/js/i18n/grid.locale-es.js"></script>


        
        <script type="text/javascript" src="includes/js/usuarios.js"></script>
    </head>
    <body style="background-image: url(includes/images/pattern.png);">
        <div id="barra" >
            <div id = "barraImage" >
            </div>
            <div style="float: left; margin-top:8px;">
                    <p>&nbsp;<a href="../../menuPrincipal.php">Sistema Contrataciones</a>&nbsp;&gt;
                    <a>Usuarios</a></p>
            </div>
            <div style="float: right; text-align: right; margin-top:10px; margin-right:10px;">
                <p><img src="includes/images/users5.png"> Usuario: <b><?=$data->getNombre()?></b></img></p>
            </div>
        </div>
        <div class="container">
            <div id="dialogAgregarUsuario" style="visibility:hidden;"> </div>
            <div id="dialogEliminarUsuario" style="visibility:hidden;"> </div>
            <div id="dialogForm" style="visibility:hidden;"> </div>

            <table align="center">
                <tr>
                    <td>
                        <div id="logo">
                            <h1>Usuarios</h1>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="content">
                            <ul class="bmenu">
                            <?php
                            if ($data->tienePermiso('AGREGAR_USUARIO')){ echo " <li><a id='btnAgregarUsuario' >Nuevo usuario</a></li>";}
                            if ($data->tienePermiso('ELIMINAR_USUARIO')){ echo "<li><a id='btnEliminarUsuario' >Baja de usuario</a></li>";}
                            if ($data->tienePermiso('VER_USUARIOS')){ echo "<li><a id='btnVerUsuarios' >Ver usuarios</a></li>";}
                            echo "<li><a id='btnVerMiUsuario' >Mi Usuario</a></li>";
                            ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
            <div id="page" >

            </div>
            <input type="hidden" id='entidad' value="<?php echo $entidad; ?>" >
    </body>
</html>
