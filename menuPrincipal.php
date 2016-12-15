<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Certificaciones - Malvinas Argentinas</title>
	<meta name="description" content="Blueprint: A basic template for a responsive multi-level menu" />
	<meta name="keywords" content="blueprint, template, html, css, menu, responsive, mobile-friendly" />
	<meta name="author" content="Codrops" />
	<link rel="shortcut icon" href="favicon.ico">
	<!-- food icons -->
	<link rel="stylesheet" type="text/css" href="presentacion/includes/css/organicfoodicons.css" />
	<!-- demo styles -->
	<link rel="stylesheet" type="text/css" href="presentacion/includes/css/demo.css" />
	<!-- menu styles -->
	<link rel="stylesheet" type="text/css" href="presentacion/includes/css/component.css" />
	<script src="presentacion/includes/js/multiLevel/modernizr-custom.js"></script>
	<!-- JQuery imports -->
	<link rel="stylesheet" type="text/css" href="presentacion/includes/js/jQuery/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" href="presentacion/includes/js/jQuery/jquery-ui.structure.min.css" />
	<link rel="stylesheet" type="text/css" href="presentacion/includes/js/jQuery/jquery-ui.theme.min.css" />
	<script src="presentacion/includes/js/jQuery/jquery1-12.js"></script>
	<script src="presentacion/includes/js/jQuery/jquery-ui.min.js"></script>
	<script src="presentacion/includes/js/timePicker/jquery.timepicker.min.js"></script>
	<script src="presentacion/includes/js/timePicker/datepicker-es.js"></script>
	<link rel="stylesheet" type="text/css" href="presentacion/includes/js/timePicker/jquery.timepicker.css" />
	<!-- JQGrid -->
	<link rel="stylesheet" type="text/css" href="presentacion/includes/js/jQGrid/css/ui.jqgrid-bootstrap-ui.css" />
	<link rel="stylesheet" type="text/css" href="presentacion/includes/js/jQGrid/css/ui.jqgrid-bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="presentacion/includes/js/jQGrid/css/ui.jqgrid.css" />
	<script src="presentacion/includes/js/jQGrid/js/i18n/grid.locale-es.js"></script>
	<script src="presentacion/includes/js/jQGrid/js/jquery.jqGrid.min.js"></script>
	<!-- Program -->
	<script src="presentacion/certificaciones/includes/js/certificaciones.js"></script>
	<script src="presentacion/prestaciones/includes/js/prestaciones.js"></script>
	<script src="presentacion/locaciones/includes/js/locaciones.js"></script>
	<script src="presentacion/profesionales/includes/js/profesionales.js"></script>
	<link rel="stylesheet" type="text/css" href="presentacion/certificaciones/includes/css/certificacion.css" />

</head>

<body>

	<?php

	/*Agregado para que tenga el usuario*/
	include_once 'data/usuario/usuarioDatabaseLinker.class.php';
	session_start();

	if(!isset($_SESSION['usuario']))
	{
	    //echo "WHOOPSS, No se encontro ningun usuario registrado";
	    header ("Location: index.php?logout=1");
	}

	$usuario = $_SESSION['usuario'];

	$data = unserialize($usuario);
	/*fin de agregado usuario*/

	

	?>
	<!-- Main container -->
	<div class="container">


		<!-- Blueprint header -->
		<header class="bp-header cf">
			<div class="dummy-logo">
				<div><img style="background-color: white; border-radius: 8px; " src="presentacion/includes/images/Check-50.png"></div>
				<h2 class="dummy-heading">Contrataciones - <a href="presentacion/usuarios/mainUsuarios.php" ><img src="presentacion/usuarios/includes/images/userico.png" width="15px" height="15px" />&nbsp;<?=$data->getNombre()?> </a></h2>
			</div>
			
		</header>
		<button class="action action--open" aria-label="Open Menu"><span class="icon icon--menu"></span></button>
		<nav id="ml-menu" class="menu">
			<button class="action action--close" aria-label="Close Menu"><span class="icon icon--cross"></span></button>
			<div class="menu__wrap">
				<ul data-menu="main" class="menu__level">
					<li class="menu__item"><a id="certBtnPrinc" name="certBtnPrinc" class="menu__link" data-submenu="submenu-1" href="#">Certificaciones</a></li>
					<li class="menu__item"><a class="menu__link" data-submenu="submenu-2" href="#">Profesionales</a></li>
					<li class="menu__item"><a class="menu__link" data-submenu="submenu-3" href="#">Prestaciones</a></li>
					<li class="menu__item"><a class="menu__link" data-submenu="submenu-4" href="#">Locaciones</a></li>
					<li class="menu__item"><a class="menu__link" data-submenu="submenu-5" href="#">Especialidades</a></li>
				<!--	
					<li class="menu__item"><a class="menu__link" data-submenu="submenu-5" href="#">Coming Soon!</a></li>
				-->
				</ul>
				<!-- Submenu 1 -->
				<!--
				<ul data-menu="submenu-1" class="menu__level">
					<li class="menu__item"><a class="menu__link" href="#">Agregar Certificaciones</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Listar Certificaciones</a></li>
				-->
					<!-- Para agregar un submenu, el custom attribute data-submenu tiene que ser igual al custom attribute data-menu -->
					<!--<li class="menu__item"><a class="menu__link" data-submenu="submenu-1-1" href="#">Sale %</a></li>--> 
				<!--
				</ul>
				-->
				<!-- Submenu 1-1 -->
				<!--
				<ul data-menu="submenu-1-1" class="menu__level">
					<li class="menu__item"><a class="menu__link" href="#">Fair Trade Roots</a></li>
				</ul>
				-->
				<!-- Submenu 2 -->
				<ul data-menu="submenu-2" class="menu__level">
					<li class="menu__item"><a class="menu__link" href="#">Agregar Profesional</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Listar Profesionales</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Vincular Especialidad</a></li>
				</ul>
				<!-- Submenu 3 -->
				<ul data-menu="submenu-3" class="menu__level">
					<li class="menu__item"><a class="menu__link" href="#">Agregar Prestacion</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Listar Prestaciones</a></li>
				</ul>
				<!-- Submenu 4 -->
				<ul data-menu="submenu-4" class="menu__level">
					<li class="menu__item"><a class="menu__link" href="#">Agregar Locacion</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Duplicado Elevacion</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Elevar Anterior</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Por Locacion</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Por Profesional</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Importe Hospital-Profesional</a></li>
				</ul>
				<!-- Submenu 5 -->
				<ul data-menu="submenu-5" class="menu__level">
					<li class="menu__item"><a class="menu__link" href="#">Agregar Especialidad</a></li>
					<li class="menu__item"><a class="menu__link" href="#">Ver Especialidades</a></li>
				</ul>
			</div>
		</nav>
		<div class="content" id="loaded_content" name="loaded_content">
			<p class="info">Seleccione una opcion.</p>
			<!-- Ajax loaded content here -->
		</div>
	</div>
	<!-- /view -->
	<script src="presentacion/includes/js/multiLevel/classie.js"></script>
	<script src="presentacion/includes/js/multiLevel/dummydata.js"></script>
	<script src="presentacion/includes/js/multiLevel/main.js"></script>
	<script>
	(function() {
		var menuEl = document.getElementById('ml-menu'),
			mlmenu = new MLMenu(menuEl, {
				// breadcrumbsCtrl : true, // show breadcrumbs
				// initialBreadcrumb : 'all', // initial breadcrumb text
				backCtrl : false, // show back button
				// itemsDelayInterval : 60, // delay between each menu item sliding animation
				onItemClick: loadDummyData // callback: item that doesn´t have a submenu gets clicked - onItemClick([event], [inner HTML of the clicked item])
			});

		// mobile menu toggle
		var openMenuCtrl = document.querySelector('.action--open'),
			closeMenuCtrl = document.querySelector('.action--close');

		openMenuCtrl.addEventListener('click', openMenu);
		closeMenuCtrl.addEventListener('click', closeMenu);

		function openMenu() {
			classie.add(menuEl, 'menu--open');
		}

		function closeMenu() {
			classie.remove(menuEl, 'menu--open');
		}

		// simulate grid content loading
		var gridWrapper = document.querySelector('.content');

		function loadDummyData(ev, itemName) {
			ev.preventDefault();

			closeMenu();
			//$("#loaded_content").load("includes/ajaxFunctions/content_distributor.php", {itemName : itemName});
			switch(itemName){
				case 'Certificaciones':
					$("#loaded_content").load("presentacion/certificaciones/includes/forms/verCertificaciones.php");
				break;

				case 'Agregar Profesional':
					$("#loaded_content").load("presentacion/profesionales/includes/forms/agregarProfesional.php");
				break;

				case 'Listar Profesionales':
					$("#loaded_content").load("presentacion/profesionales/includes/forms/listarProfesional.php");
				break;

				case 'Vincular Especialidad':
					$("#loaded_content").load("presentacion/profesionales/includes/forms/vincularEspecialidad.php");
				break;

				case 'Agregar Prestacion':
					$("#loaded_content").load("presentacion/prestaciones/includes/forms/agregarPrestacion.php");
				break;

				case 'Listar Prestaciones':
					$("#loaded_content").load("presentacion/prestaciones/includes/forms/verPrestaciones.php");
				break;

				case 'Agregar Locacion':
					$("#loaded_content").load("presentacion/locaciones/includes/forms/verLocaciones.php");
				break;

				case 'Duplicado Elevacion':
					$("#loaded_content").load("presentacion/locaciones/includes/forms/duplcadoElevacion.php");
				break;

				case 'Elevar Anterior':
					$("#loaded_content").load("presentacion/locaciones/includes/forms/elevarAnterior.php");
				break;

				case 'Por Locacion':
					$("#loaded_content").load("presentacion/locaciones/includes/forms/porLocacion.php");
				break;

				case 'Por Profesional':
					$("#loaded_content").load("presentacion/locaciones/includes/forms/porProfesional.php");
				break;

				case 'Importe Hospital-Profesional':
					$("#loaded_content").load("presentacion/locaciones/includes/forms/importeHospPro.php");
				break;

				case 'Agregar Especialidad':
					$("#loaded_content").load("presentacion/especialidades/includes/forms/agregarEspecialidad.php");
				break;

				case 'Ver Especialidades':
					$("#loaded_content").load("presentacion/especialidades/includes/forms/verEspecialidades.php");
				break;

				case 'Todos':

				location.reload();

				break;

				default: 
					$("#loaded_content").load("presentacion/certificaciones/includes/forms/defaultLoader.php");
				break;
			}
			gridWrapper.innerHTML = '';
		}
	})();
	</script>
</body>

</html>
