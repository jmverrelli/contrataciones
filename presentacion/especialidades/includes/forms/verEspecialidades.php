<h1 class="centered">Especialidades</h1>

<div class="centered">

</div>

    <script type="text/javascript">
        
        $("#jqVerEspecialidades").jqGrid(
        { 
            url:'presentacion/especialidades/includes/ajaxFunctions/MostrarEspecialidades.php', 
            mtype: "POST",
            datatype: "json",
            colNames:['IdEspecialidad','Especialidad',''],
            colModel:[ 
                {name:'IdEspecialidad', index:'esp.IdEspecialidad',width:'0%',align:"left",fixed:true,editable:false},
                {name:'Especialidad', index:'esp.`Especialidad`',width:'100%',align:"left",fixed:true,editable:true},
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
            caption:"Especialidades",
            rowNum:20, 
            rowList:[10,20,30,50],
            pager: '#jqEspecialidadesfoot',
            sortname: 'esp.IdEspecialidad',
            sortorder: "desc",
            editurl :'presentacion/especialidades/includes/ajaxFunctions/EliminarEspecialidad.php',
            width: '100%',
            height: '100%'
        });

        jQuery("#jqVerEspecialidades").jqGrid('filterToolbar', {
            stringResult: true, 
            searchOnEnter: false, 
            defaultSearch : "cn"
        }); 

        $('#jqVerEspecialidades').jqGrid('navGrid', '#jqEspecialidadesfoot', {
            edit:false,
            add:false,
            del:false,
            trash:false,
            search:false
        });

    </script>


    <div id="cuadro" align="center" >
        <table id="jqVerEspecialidades"></table>
        <div id="jqEspecialidadesfoot"></div>
    </div>
    <div id="dialogEspecialidad" name="dialogEspecialidad"></div>
