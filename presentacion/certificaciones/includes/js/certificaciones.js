$(document).ready(function(){


    $('#nuevaCertificacion').click(function(event){
        event.preventDefault();

         $("#dialog:ui-dialog").dialog( "destroy" );
            $("#dialogNuevaCertificacion").css('visibility',"visible");
            $("#dialogNuevaCertificacion").load("presentacion/certificaciones/includes/forms/nuevaCertificacion.php",function() 
            {
                $("#dialogNuevaCertificacion").dialog({
                    title: "Nueva Certificacion",
                    modal: true,
                    resizable : true,
                    width: 800,
                    buttons:
                            {
                                "Agregar Certificacion":function()
                                {
                                    if(!validar()){
                                    	alert("Debe seleccionar fechas de periodo.");
                                    	return;
                                    }
                                    $.ajax({
                                        data: $('#nuevaCertificacionForm').serialize(),
                                        type: "POST",
                                        dataType: "json",
                                        context: $(this),
                                        url: "presentacion/certificaciones/includes/ajaxFunctions/AgregarCertificacion.php",
                                        success: function(data)
                                        {
                                            if(data.ret == false){
                                                alert(data.message);
                                                $(this).dialog("close");
                                            }
                                            else{
                                                
                                                alert(data.message);
                                                $(this).dialog("close");
                                                agregarPrestaciones(data.lastId);
                                            }
                                        }
                                    });
                                },
                                "Cerrar":function()
                                {
                                    $(this).dialog("close");
                                }
                            }
                });
            });


    });

});

function agregarPrestaciones(id){

         	$("#dialog:ui-dialog").dialog( "destroy" );
            $("#dialogAgregarPrestaciones").css('visibility',"visible");
            $("#dialogAgregarPrestaciones").load("presentacion/prestaciones/includes/forms/prestacionCertificacion.php",{id:id},function() 
            {
                $("#dialogAgregarPrestaciones").dialog({
                    title: "Agregar Prestaciones",
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


function validar(){
	if($('#periodoDe').val() === ''){
		return false;
	}
	if($('#periodoHasta').val() === ''){
		return false;
	}
	return true;

}

function traerDetalles(IdCertificacion){

	$.ajax({
			data: {IdCertificacion : IdCertificacion},
            type: "POST",
            dataType: "json",
            url: "presentacion/certificaciones/includes/ajaxFunctions/TraerDetalles.php",
            success: function(data)
            {
                if(data.ret == false){
                    $("#detallesEncontrados").text(data.message);
                }
                else{
                	var text = "<table class='padd border'>";
                	text += "<tr><th>Prestacion</th><th>Cantidad</th><th>Valor</th><th>Total</th><th></th></tr>";
                	var total;
                	var resultado = 0;
                    for (valor in data.detalles){
                    	text += "<tr>";
                    	text += "<td>";
                    	text += data.detalles[valor].nombrePrestacion;
                    	text += "</td>";
                    	text += "<td>";
                    	text += data.detalles[valor].cantidad;
                    	text += "</td>";
                    	text += "<td>";
                    	text += data.detalles[valor].valor;
                    	text += "</td>";
                    	text += "<td class='textTotal'>";
                    	total = data.detalles[valor].valor * data.detalles[valor].cantidad;
                    	text += total.toFixed(2);
                    	text += "</td>";
                    	text += "<td>";
                    	text += "<input type='image' width='18px' height='18px' src='presentacion/includes/images/trash.png' onclick='eliminarDetalle(" + data.detalles[valor].IddetalleCertificacion + ");' />";
                    	text += "</td>";
                    	text += "</tr>";
                    	resultado += total; 
                    }
                    
                    text += "<tr><td></td><td></td><td></td><td class='totalTotal textTotal'><b>" + resultado.toFixed(2) + "</b></td></tr>";
                 	text += "</table>";
                 	 document.getElementById('detallesEncontrados').innerHTML = text;

                }
            }
        });


}

function eliminarDetalle(IddetalleCertificacion){

	event.preventDefault();
	if(confirm("¿Seguro que desea eliminar el detalle?"))
	{
		$.ajax({
				data: {IddetalleCertificacion : IddetalleCertificacion},
	            type: "POST",
	            dataType: "json",
	            url: "presentacion/certificaciones/includes/ajaxFunctions/EliminarDetalle.php",
	            success: function(data)
	            {
	                if(data.ret == false){
	                    alert(data.message);
	                }
	                else{

	                	traerDetalles(data.IdCertificacion);
	                }
	            }
	        });
	}

}

function detalleCertificacion(id){

	$("#dialog:ui-dialog").dialog( "destroy" );
            $("#dialogVistaCertificacion").css('visibility',"visible");
            $("#dialogVistaCertificacion").load("presentacion/certificaciones/includes/forms/vistaCertificacion.php",{id:id},function() 
            {
                $("#dialogVistaCertificacion").dialog({
                    title: "Certificacion",
                    modal: true,
                    resizable : true,
                    width: "80%",
                    buttons:
                            {
                            	"Imprimir":function()
                                {
                                    loadOtherPage(id);
                                },
                                "Cerrar":function()
                                {
                                    $(this).dialog("close");
                                }
                            }
                });
            });
}


function loadOtherPage(id) {

    var iframes = document.querySelectorAll('iframe');
    for (var i = 0; i < iframes.length; i++) {
        iframes[i].parentNode.removeChild(iframes[i]);
    }

    $("<iframe id='iframeprint' name='iframeprint'>")
        .hide()
        .attr("src", "presentacion/certificaciones/includes/forms/imprimirCertificacion.php?id=" + id)
        .appendTo("body");  

         $('#iframeprint').load(function () {
            document.iframeprint.printTHIS();
    });             

}