$(document).ready(function(){

    $("#dialogNuevaLocacion").keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });

    $("#dialogAgregarMonto").keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });


    $('#agregarDetLoc').click(function(event){
    event.preventDefault(event);
        $.ajax({
            data: $('#nuevoMontoLoc').serialize(),
            type: "POST",
            dataType: "json",
            url: "presentacion/locaciones/includes/ajaxFunctions/AgregarMonto.php",
            success: function(data)
            {
                if(data.ret == false){
                    alert(data.message);
                }
                else{
                    
                    alert(data.message);
                }
            }
        });

        $("#valor").val('');
        $('#hospital>option:eq(0)').prop('selected', true);
        traerDetallesLocaciones($("#IdLocacion").val());
});



    $('#nuevaLocacion').click(function(event){
        event.preventDefault(event);
        //$("#nuevaLocacion").off('click');
         $("#dialog:ui-dialog").dialog( "destroy" );
            $("#dialogNuevaLocacion").css('visibility',"visible");
            $("#dialogNuevaLocacion").load("presentacion/locaciones/includes/forms/nuevaLocacion.php",function() 
            {
                $("#dialogNuevaLocacion").dialog({
                    title: "Nueva Locacion",
                    modal: true,
                    resizable : true,
                    width: 800,
                    open: function (type, data) {
                        var formLoc = $("#formParentLoc");
                    $(this).parent().appendTo(formLoc);
                },
                    buttons:
                            {
                                "Agregar Locacion":function()
                                {
                                    /*if(!validar()){
                                    	alert("Debe seleccionar fechas de periodo.");
                                    	return;
                                    }*/
                                    $.ajax({
                                        data: $('#nuevaLocacionForm').serialize(),
                                        type: "POST",
                                        dataType: "json",
                                        context: $(this),
                                        url: "presentacion/locaciones/includes/ajaxFunctions/AgregarLocacion.php",
                                        success: function(data)
                                        {
                                            if(data.ret == false){
                                                alert(data.message);
                                                $(this).dialog("close");
                                            }
                                            else{
                                                
                                                alert(data.message);
                                                $(this).dialog("close");
                                                agregarMonto(data.lastId);
                                            }
                                             $('#jgVerLocaciones').trigger( 'reloadGrid' );
                                        }
                                    })
                                    $(this).dialog("close");   
                                },
                                "Cerrar":function()
                                {
                                    $(this).dialog("close");
                                }
                            }
                });
            });


    });


    $('#verPorLocacionbtn').click(function(event){
    event.preventDefault(event);
    if($("verPorLocacionLocacion").val() == '')
    {
        alert("Debe ingresar una Locacion");
    }
    else{
            $.ajax({
                data: $('#verPorLocacionForm').serialize(),
                type: "POST",
                dataType: "json",
                url: "presentacion/locaciones/includes/ajaxFunctions/PorLocacion.php",
                success: function(data)
                {
                    if(data.ret == false){
                        alert(data.message);
                    }
                    else{
                        document.getElementById('porLocacionContainer').innerHTML = '';
                        var locas = data.message;
                        $("#porLocacionContainer").load("presentacion/locaciones/includes/forms/porMontoCarga.php",{locas:locas},function(){
                        });
                    }
                }
            });
        }

});

    $('#verPorHospProbtn').click(function(event){
    
    event.preventDefault(event);

    if($("#verPorHospProInicio").val() == ''){

        alert("Debe ingresar una fecha de Inicio.");
    }
    else if($("#verPorHospProFinal").val() == ''){

        alert("Debe ingresar una fecha de Final.");
    }

    else{
        $("#verPorHospProForm").submit();
    }



});

    


    $('#verPorProfesionalbtn').click(function(event){
    event.preventDefault(event);
        $.ajax({
            data: $('#verPorProfesionalForm').serialize(),
            type: "POST",
            dataType: "json",
            url: "presentacion/locaciones/includes/ajaxFunctions/PorProfesional.php",
            success: function(data)
            {
                if(data.ret == false){
                    alert(data.message);
                }
                else{
                    document.getElementById('porProfesionalCointainer').innerHTML = '';
                    var profes = data.message;
                    $("#porProfesionalCointainer").load("presentacion/locaciones/includes/forms/porProfesionalCarga.php",{profes:profes},function(){
                    });
                }
            }
        });

});



});

function agregarMonto(id){

            $("#dialog:ui-dialog").dialog( "destroy" );
            $("#dialogAgregarMonto").css('visibility',"visible");
            $("#dialogAgregarMonto").load("presentacion/locaciones/includes/forms/locacionesmontos.php",{id:id},function() 
            {
                $("#dialogAgregarMonto").dialog({
                    title: "Agregar Montos",
                    modal: true,
                    resizable : true,
                    width: "80%",
                    buttons:
                            {
                                "Cerrar":function()
                                {
                                    $(this).dialog("close");
                                }
                            }
                });
            });
}


function traerDetallesLocaciones(IdLocacion){

    $.ajax({
            data: {IdLocacion : IdLocacion},
            type: "POST",
            dataType: "json",
            url: "presentacion/locaciones/includes/ajaxFunctions/TraerDetalles.php",
            success: function(data)
            {
                if(data.ret == false){
                    $("#detallesLocEncontrados").text(data.message);
                }
                else{
                    var text = "<table class='padd border'>";
                    text += "<tr><th>Hospital</th><th>Valor</th></tr>";
                    var total;
                    var resultado = 0;
                    for (valor in data.detalles){
                        text += "<tr>";
                        text += "<td>";
                        text += data.detalles[valor].Hospital;
                        text += "</td>";
                        text += "<td>";
                        text += data.detalles[valor].Monto;
                        text += "</td>";
                        text += "<td>";
                        text += "</td>";
                        text += "<td>";
                        text += "<input type='image' width='18px' height='18px' src='presentacion/includes/images/trash.png' onclick='eliminarDetalleLoc(event, " + data.detalles[valor].IdDetallesLocacion + ");' />";
                        text += "</td>";
                        text += "</tr>";
                        resultado = resultado + Number(data.detalles[valor].Monto);
                    }
                    
                    text += "<tr><td><td class='totalTotal textTotal'><b> " + resultado.toFixed(2) + " </b></td></tr>";
                    text += "</table>";
                     document.getElementById('detallesLocEncontrados').innerHTML = text;

                }
            }
        });


}

function detalleLocacion(id){

    $("#dialog:ui-dialog").dialog( "destroy" );
            $("#dialogDetalleLocacion").css('visibility',"visible");
            $("#dialogDetalleLocacion").load("presentacion/locaciones/includes/forms/detallesLocacion.php",{id:id},function() 
            {
                $("#dialogDetalleLocacion").dialog({
                    title: "Detalles Locacion",
                    modal: true,
                    resizable : true,
                    width: "80%",
                    buttons:
                            {
                                "Cerrar":function()
                                {
                                    $(this).dialog("close");
                                }
                            }
                });
            });
}

function traerEspecialidadesLoca(e,s,type){

    var idProfesional = e.options[e.selectedIndex].value; 
     $.ajax({
            data: {idProfesional : idProfesional},
            type: "POST",
            dataType: "json",
            url: "presentacion/profesionales/includes/ajaxFunctions/TraerEspecialidades.php",
            success: function(data)
            {
                if(data.ret == false){
                   s.empty();
                }
                else{
                    if(type == 'SELECT'){
                        s.empty();
                        for (var d = 0; d < data.especialidadesProfesional.length ; d++) {
                            s.append('<option value="'+data.especialidadesProfesional[d].IdEspecialidad+'">'+data.especialidadesProfesional[d].Especialidad+'</option>');
                            
                        }
                    }
                    else if(type == 'LIST'){

                        s.empty();
                        s.append('<ul>');
                        for (var d = 0; d < data.especialidadesProfesional.length ; d++) {
                            s.append('<li>'+data.especialidadesProfesional[d].Especialidad+'</li>');
                            
                        }
                        s.append('</ul>')

                    }
                    
                }
            }
        });
}

function traerLocacionesAnteriores(){
    var e = document.getElementById("Profesional");
    var idProfesional = e.options[e.selectedIndex].value; 
     $.ajax({
            data: {idProfesional : idProfesional},
            type: "POST",
            dataType: "json",
            url: "presentacion/locaciones/includes/ajaxFunctions/TraerLocacionesAnteriores.php",
            success: function(data)
            {
                if(!data.ret){
                
                    document.getElementById('locacionesAnteriores').innerHTML = data.message;


                }
                else{
                        var text = "Locaciones anteriores: <ul>";
                        for (var i = 0; i < data.message.length; i++) {
                            
                            text += "<li>";
                            text += data.message[i].Locacion;
                            text += "</li>";

                            document.getElementById('locacionesAnteriores').innerHTML = text;
                        }

                }
                    
            }
        });
}


function eliminarDetalleLoc(event, IddetalleLocacion){

    event.preventDefault(event);
    if(confirm("¿Seguro que desea eliminar el detalle?"))
    {
        $.ajax({
                data: {IddetalleLocacion : IddetalleLocacion},
                type: "POST",
                dataType: "json",
                url: "presentacion/locaciones/includes/ajaxFunctions/EliminarDetalle.php",
                success: function(data)
                {
                    if(data.ret == false){
                        alert(data.message);
                    }
                    else{

                        traerDetallesLocaciones(data.IdLocacion);
                    }
                }
            });
    }
}

