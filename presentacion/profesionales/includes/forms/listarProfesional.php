<h1 class="centered">Profesionales</h1>

<div class="centered">

</div>

    <script type="text/javascript">
        
        $("#jqVerProfesionales").jqGrid(
        { 
            url:'presentacion/profesionales/includes/ajaxFunctions/MostrarProfesionales.php', 
            mtype: "POST",
            datatype: "json",
            colNames:['IdProfesional','Apellido y Nombre','Nro Convenio','Nro Proveedor','Telefono',''],
            colModel:[ 
                {name:'IdProfesional', index:'pro.IdProfesional',width:'0%',align:"left",fixed:true,editable:false},
                {name:'Apellido y Nombre', index:'pro.`Apellido y Nombre`',width:'100%',align:"left",fixed:true,editable:true},
                {name:'Nro Convenio', index:'pro.`Nro Convenio`',width:'100%',align:"left",fixed:true,editable:true},
                {name:'Nro Proveedor', index:'pro.`Nro Proveedor`',width:'100%',align:"left",fixed:true,editable:true},
                {name:'Telefono', index:'pro.Telefono',width:'100%',align:"left",fixed:true,editable:true},
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
            caption:"Profesionales",
            rowNum:20, 
            rowList:[10,20,30,50],
            pager: '#jqProfesionalesfoot',
            sortname: 'pro.IdProfesional',
            sortorder: "desc",
            editurl :'presentacion/profesionales/includes/ajaxFunctions/EliminarProfesional.php',
            width: '100%',
            height: '100%'
        });

        jQuery("#jqVerProfesionales").jqGrid('filterToolbar', {
            stringResult: true, 
            searchOnEnter: false, 
            defaultSearch : "cn"
        }); 

        $('#jqVerProfesionales').jqGrid('navGrid', '#jqProfesionalesfoot', {
            edit:false,
            add:false,
            del:false,
            trash:false,
            search:false
        });

    </script>


    <div id="cuadro" align="center" >
        <table id="jqVerProfesionales"></table>
        <div id="jqProfesionalesfoot"></div>
    </div>
    <div id="dialogProfesional" name="dialogProfesional"></div>
