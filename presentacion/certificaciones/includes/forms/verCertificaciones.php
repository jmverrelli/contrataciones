
<?php 

    /*Agregado para que tenga el usuario*/
    include_once '../../../../data/usuario/usuarioDatabaseLinker.class.php';
    session_start();

    if(!isset($_SESSION['usuario']))
    {
        header ("Location:../../../../index.php?logout=1");
    }

    $usuario = $_SESSION['usuario'];

    $data = unserialize($usuario);
    /*fin de agregado usuario*/


if (!$data->tienePermiso('CERTIFICAR')){ echo "<div class='info' align='center'>No posee permisos para realizar esta accion.</div>"; exit;}

?>
<h1 class="centered">Certificaciones</h1>

<div class="centered">

</div>

    <script type="text/javascript">
        
        $("#jgVerCertificaciones").jqGrid(
        { 
            url:'presentacion/certificaciones/includes/ajaxFunctions/MostrarCertificaciones.php', 
            mtype: "POST",
            datatype: "json",
            colNames:['IdCertificacion','Fecha','IdModulado','IdProfesionales','IdHospital','FechaInicio','FechaFinal','',''],
            colModel:[ 
                {name:'IdCertificacion', index:'cer.IdCertificacion',width:'100%',align:"left",fixed:true,editable:false},
                {name:'Fecha', index:'cer.Fecha',width:'100%',align:"left",fixed:true,editable:true},
                {name:'IdModulado', index:'cer.IdModulado',width:'100%',align:"left",fixed:true,editable:true},
                {name:'IdProfesionales', index:'cer.IdProfesionales',width:'100%',align:"left",fixed:true,editable:true},
                {name:'IdHospital', index:'cer.IdHospital',width:'100%',align:"left",fixed:true,editable:true},
                {name:'FechaInicio', index:'cer.FechaInicio',width:'100%',align:"left",fixed:true,editable:true},
                {name:'FechaFinal', index:'cer.FechaFinal',width:'100%',align:"left",fixed:true,editable:true},
                {name:'verCert',width:'40%',align:"left",fixed:true,editable:false, search: false},
                {name: 'myac', width: '40%', fixed: true, sortable: false, resize: false, formatter: 'actions', search: false, formatoptions: 
                    {
                        keys: true,
                        delbutton: true,
                        editbutton: false,
                        onError: function(_, xhr) {
                            alert(xhr.responseText);
                        }
                    }}
                
            ],
            rowNum:true,
            viewrecords: true,
            altRows : true,
            caption:"Certificaciones",
            rowNum:20, 
            rowList:[10,20,30,50],
            pager: '#jqCertificacionesfoot',
            sortname: 'cer.IdCertificacion',
            sortorder: "desc",
            editurl :'presentacion/certificaciones/includes/ajaxFunctions/EliminarCertificacion.php',
            width: '100%',
            height: '100%',
            gridComplete: function()
            { 
                var ids = jQuery("#jgVerCertificaciones").jqGrid('getDataIDs'); 
                for(var i=0;i < ids.length;i++)
                { 
                    var cl = ids[i];
                    be = "<input style='height:22px;width:35px;' class='button-secondary' type='button' value='VER' onclick=\"javascript:detalleCertificacion('"+cl+"');\" />";
                    jQuery("#jgVerCertificaciones").jqGrid('setRowData',ids[i],{verCert:be});
                }
            }
        });

        jQuery("#jgVerCertificaciones").jqGrid('filterToolbar', {
            stringResult: true, 
            searchOnEnter: false, 
            defaultSearch : "cn"
        }); 

        $('#jgVerCertificaciones').jqGrid('navGrid', '#jqCertificacionesfoot', {
            edit:false,
            add:false,
            del:false,
            trash:false,
            search:false
        });

    </script>
    <script src="presentacion/certificaciones/includes/js/certificaciones.js"></script>
<form id="formParentCert" name="formParentCert">
    <div id="cuadro" align="center" >
        <table id="jgVerCertificaciones"></table>
        <div id="jqCertificacionesfoot"></div>
         <input class="btnin" type="submit" value="Nueva Certificacion" id="nuevaCertificacion">
    </div>
    <div id="dialogNuevaCertificacion" name="dialogNuevaCertificacion"></div>
    <div id="dialogAgregarPrestaciones" name="dialogAgregarPrestaciones"></div>
    <div id="dialogVistaCertificacion" name="dialogVistaCertificacion"></div>
</form>
