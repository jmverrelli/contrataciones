<!-- por aca hay que ver que se dibuje -->

<h1 class="centered">Certificaciones</h1>

<div class="centered">

</div>

    <script type="text/javascript">
        
        $("#jgVerCertificaciones").jqGrid(
        { 
            url:'presentacion/certificaciones/includes/ajaxFunctions/MostrarCertificaciones.php', 
            mtype: "POST",
            datatype: "json",
            colNames:['IdCertificacion','Fecha','IdModulado','IdProfesionales','IdHospital','FechaInicio','FechaFinal','Horas','PorMonto',''],
            colModel:[ 
                {name:'IdCertificacion', index:'cer.IdCertificacion',width:'100%',align:"left",fixed:true,editable:false},
                {name:'Fecha', index:'cer.Fecha',width:'100%',align:"left",fixed:true,editable:true},
                {name:'IdModulado', index:'cer.IdModulado',width:'100%',align:"left",fixed:true,editable:true},
                {name:'IdProfesionales', index:'cer.IdProfesionales',width:'100%',align:"left",fixed:true,editable:true},
                {name:'IdHospital', index:'cer.IdHospital',width:'100%',align:"left",fixed:true,editable:true},
                {name:'FechaInicio', index:'cer.FechaInicio',width:'100%',align:"left",fixed:true,editable:true},
                {name:'FechaFinal', index:'cer.FechaFinal',width:'100%',align:"left",fixed:true,editable:true},
                {name:'Horas', index:'cer.Horas',width:'100%',align:"left",fixed:true,editable:true},
                {name:'PorMonto', index:'cer.PorMonto',width:'100%',align:"left",fixed:true,editable:true},
                {name: 'myac', width: '40%', fixed: true, sortable: false, resize: false/*, formatter: 'actions'*/, search: false}
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
            /*editurl :'includes/ajaxFunctions/eliminarReclamo.php',*/
            width: '100%',
            height: '100%',
            gridComplete: function()
            { 
                var ids = jQuery("#jgVerCertificaciones").jqGrid('getDataIDs'); 
                for(var i=0;i < ids.length;i++)
                { 
                    var cl = ids[i];
                    be = "<input style='height:22px;width:35px;' class='button-secondary' type='button' value='VER' onclick=\"javascript:detalleCertificacion('"+cl+"');\" />";
                    jQuery("#jgVerCertificaciones").jqGrid('setRowData',ids[i],{myac:be});
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

    <div id="cuadro" align="center" >
        <table id="jgVerCertificaciones"></table>
        <div id="jqCertificacionesfoot"></div>
    </div>
    <div id="dialogNuevaCertificacion" name="dialogNuevaCertificacion"></div>
    <div id="dialogAgregarPrestaciones" name="dialogAgregarPrestaciones"></div>
    <div id="dialogVistaCertificacion" name="dialogVistaCertificacion"></div>
    <input class="button-secondary" type="submit" value="Nueva Certificacion" id="nuevaCertificacion">
