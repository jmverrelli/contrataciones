<h1 class="centered">Certificaciones</h1>

<div class="centered">

</div>

    <script type="text/javascript">
        
        $("#jqVerPrestaciones").jqGrid(
        { 
            url:'presentacion/prestaciones/includes/ajaxFunctions/MostrarPrestaciones.php', 
            mtype: "POST",
            datatype: "json",
            colNames:['IdPrestacion','Prestacion','Valor',''],
            colModel:[ 
                {name:'IdPrestacion', index:'pres.IdPrestacion',width:'0%',align:"left",fixed:true,editable:false},
                {name:'Prestacion', index:'pres.Prestacion',width:'100%',align:"left",fixed:true,editable:false},
                {name:'Valor', index:'pres.Valor',width:'100%',align:"left",fixed:true,editable:true},
                {name: 'myac', width: '100%', fixed: true, sortable: false, resize: false, formatter: 'actions', search: false, formatoptions: 
                    {
                        keys: true,
                        delbutton: true,
                        editbutton: true,
                        onError: function(_, xhr) {
                            alert(xhr.responseText);
                        }
                    }}
            ],
            rowNum:true,
            viewrecords: true,
            altRows : true,
            caption:"Prestaciones",
            rowNum:20, 
            rowList:[10,20,30,50],
            pager: '#jqPrestacionesfoot',
            sortname: 'pres.IdPrestacion',
            sortorder: "desc",
            editurl :'presentacion/prestaciones/includes/ajaxFunctions/EliminarPrestacion.php',
            width: '100%',
            height: '100%'
        });

        jQuery("#jqVerPrestaciones").jqGrid('filterToolbar', {
            stringResult: true, 
            searchOnEnter: false, 
            defaultSearch : "cn"
        }); 

        $('#jqVerPrestaciones').jqGrid('navGrid', '#jqPrestacionesfoot', {
            edit:false,
            add:false,
            del:false,
            trash:false,
            search:false
        });

    </script>
    <div id="cuadro" align="center" >
        <table id="jqVerPrestaciones"></table>
        <div id="jqPrestacionesfoot"></div>
    </div>
    <div id="dialogPrestaciones" name="dialogPrestaciones"></div>
