<?php
	$entidad = "CONTRATACIONES_CERTIFICACIONES";
	session_start();
?>
<html>
<head>
	<title>CONTRATACIONES</title>
	<link media="screen" type="text/css" rel="stylesheet" href="presentacion/includes/css/login.css">
	<link media="screen" type="text/css" rel="stylesheet" href="presentacion/includes/css/barra.css">
</head>
<body>
    <!-- barra -->
	<div id="barra" >
		<!-- navegar -->
 		<div id="barraImage" >
        </div>
        <div id="navegar">
        	<p>&nbsp;<a href="/sistemas.php3">Sistema Salud</a>&nbsp;&gt;
            <a>CONTRATACIONES</a></p>
        </div>
        <!-- /navegar -->
	</div>
	<!-- /barra -->
	<div class="post">
		<div class="page">
			<div class="logo">
				<h1>CONTRATACIONES</h1>
			</div>
			<div align="center">
				<form method="post" action="validarInicioSession.php">
					<table>
						<tr>
							<td>
								<input class="input" id="inputUsuario" name="usuario" placeholder="Nombre Usuario">
							</td>
						</tr>
						<tr>
							<td>
								<input class="input" type="password" id="inputContrasenia" name="contra" placeholder="Password"></br>
							</td>
						</tr>
						<tr>
							<td>
								<input type="hidden" id="entidad" name="entidad" value="<?=$entidad; ?>" />
								<input class="boton" type="submit" id="acceder" value="Acceder" >
							</td>
						</tr>
						<tr>
							<td>
								<?php 
								if (isset($_GET['error']))
								{
								?>
								<div >
									<div class="error">
										<p >Credenciales de Sesion incorrectos</p>
									</div>
								</div>
								<?php
								}
								else if(isset($_GET['logout']))
								{
									unset($_SESSION);
									session_destroy();
									?>
									<div >
									<div class="error">
										<p >El usuario ha terminado su session!</p>
									</div>
									</div>
									<?php
								}
								else
								{
								?>
								<div id="errores" style="clear: both;">
									
								</div>
								<?php
								}
								?>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
</html>