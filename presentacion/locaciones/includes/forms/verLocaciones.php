<?php 

    /*Agregado para que tenga el usuario*/
    include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';
    session_start();

    if(!isset($_SESSION['usuario']))
    {
        echo "<div class='info' align='center'>Por favor refresque la pagina.</div>"; exit;
    }

    $usuario = $_SESSION['usuario'];

    $data = unserialize($usuario);
    /*fin de agregado usuario*/


if (!$data->tienePermiso('LOCACIONES')){ echo "<div class='info' align='center'>No posee permisos para realizar esta accion.</div>"; exit;}

?>


<h1 class="centered">Locaciones</h1>

<div class="centered">

</div>

    <script type="text/javascript">
        
        $("#jgVerLocaciones").jqGrid(
        { 
            url:'presentacion/locaciones/includes/ajaxFunctions/MostrarLocaciones.php', 
            mtype: "POST",
            datatype: "json",
            colNames:['IdLocacion','Locacion','Apellido y Nombre',''],
            colModel:[ 
                {name:'IdLocacion', index:'loc.IdLocacion',width:'100%',align:"left",fixed:true,editable:false},
                {name:'Locacion', index:'loc.Locacion',width:'100%',align:"left",fixed:true,editable:true},
                {name:'Apellido y Nombre', index:'loc.Apellido y Nombre',width:'140%',align:"left",fixed:true,editable:true},
                {name: 'myac', width: '40%', fixed: true, sortable: false, resize: false/*, formatter: 'actions'*/, search: false}
            ],
            rowNum:true,
            viewrecords: true,
            altRows : true,
            caption:"Locaciones",
            rowNum:20, 
            rowList:[10,20,30,50],
            pager: '#jqLocacionesfoot',
            sortname: 'loc.IdLocacion',
            sortorder: "desc",
            /*editurl :'includes/ajaxFunctions/eliminarReclamo.php',*/
            width: '100%',
            height: '100%',
            gridComplete: function()
            { 
                var ids = jQuery("#jgVerLocaciones").jqGrid('getDataIDs'); 
                for(var i=0;i < ids.length;i++)
                { 
                    var cl = ids[i];
                    be = "<input style='height:22px;width:35px;' class='button-secondary' type='button' value='VER' onclick=\"javascript:detalleLocacion('"+cl+"');\" />";
                    jQuery("#jgVerLocaciones").jqGrid('setRowData',ids[i],{myac:be});
                }
            }
        });

        jQuery("#jgVerLocaciones").jqGrid('filterToolbar', {
            stringResult: true, 
            searchOnEnter: false, 
            defaultSearch : "cn"
        }); 

        $('#jgVerLocaciones').jqGrid('navGrid', '#jqLocacionesfoot', {
            edit:false,
            add:false,
            del:false,
            trash:false,
            search:false
        });

    </script>
    <script src="presentacion/locaciones/includes/js/locaciones.js"></script>
    <form id="formParentLoc" name="formparentLoc">
    <div id="cuadro" align="center" >
        <table id="jgVerLocaciones"></table>
        <div id="jqLocacionesfoot"></div>
         <input class="btnin" type="submit" value="Nueva Locacion" id="nuevaLocacion">
    </div>
    <div id="dialogNuevaLocacion" name="dialogNuevaLocacion"></div>
    <div id="dialogAgregarMonto" name="dialogAgregarMonto"></div>
    <div id="dialogDetalleLocacion" name="dialogDetalleLocacion"></div>
   </form>
