<?php

include_once '../../../../data/locacionesDataBaseLinker.class.php';


?>

<script type="text/javascript">
    
    $("#jgVerLocacionesAnteriores").jqGrid(
    { 
        url:'presentacion/locaciones/includes/ajaxFunctions/MostrarLocacionesAnteriores.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['FechaElevado',''],
        colModel:[ 
            {name:'FechaElevado', index:'loc.FechaElevado',width:'100%',align:"left",fixed:true,editable:false},
            {name: 'myac', width: '100%', fixed: true, sortable: false, resize: false/*, formatter: 'actions'*/, search: false}
        ],
        rowNum:true,
        viewrecords: true,
        altRows : true,
        caption:"Locaciones ya Elevadas",
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jqLocacionesAnterioresfoot',
        sortname: 'loc.FechaElevado',
        sortorder: "desc",
        /*editurl :'includes/ajaxFunctions/eliminarReclamo.php',*/
        width: '100%',
        height: '100%',
        gridComplete: function()
        { 
            var ids = jQuery("#jgVerLocacionesAnteriores").jqGrid('getDataIDs'); 
            for(var i=0;i < ids.length;i++)
            { 
                var cl = ids[i];
                be = "<input style='height:22px;width:100%;' class='button-secondary' type='button' value='IMPRIMIR' onclick=\"javascript:generarImpresionPorFecha('"+cl+"');\" />";
                jQuery("#jgVerLocacionesAnteriores").jqGrid('setRowData',ids[i],{myac:be});
            }
        }
    });

    jQuery("#jgVerLocacionesAnteriores").jqGrid('filterToolbar', {
        stringResult: true, 
        searchOnEnter: false, 
        defaultSearch : "cn"
    }); 

    $('#jgVerLocacionesAnteriores').jqGrid('navGrid', '#jqLocacionesAnterioresfoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

</script>

<h1 class="centered">Elevacion por Fecha Anterior</h1>

<form id="agregarProfesionalForm" name="agregarProfesionalForm">

	<div id="cuadro" align="center" >
        <table id="jgVerLocacionesAnteriores"></table>
        <div id="jqLocacionesAnterioresfoot"></div>
    </div>
	</div>



</form>




<script type="text/javascript">
	
	function generarImpresionPorFecha(date) {

    var iframes = document.querySelectorAll('iframe');
    for (var i = 0; i < iframes.length; i++) {
        iframes[i].parentNode.removeChild(iframes[i]);
    }
    var fechaz = $("#fechaAnteriorLoca").val();
    $("<iframe id='iframeprintLocacionesAnteriores' name='iframeprintLocacionesAnteriores'>")
        .hide()
        .attr("src", "presentacion/locaciones/includes/forms/imprimirAnterior.php?fechaPedida=" + date)
        .appendTo("body");  

         $('#iframeprintLocacionesAnteriores').load(function () {
            document.iframeprintLocacionesAnteriores.printTHISLocaciones();
    });             
}
</script>