$(document).ready(function(){


    $('#certifPorProfesionalbtn').click(function(event){

        event.preventDefault(event);

        var certifPorProfesionalInicio = $('#certifPorProfesionalInicio').val();
        var certifPorProfesionalFinal = $('#certifPorProfesionalFinal').val();

        if(certifPorProfesionalInicio == ''){
            alert("Debe ingresar una fecha de inicio.");
            return;
        }

        if(certifPorProfesionalFinal == ''){
            alert("Debe ingresar una fecha de final.");
            return;
        }

        $.ajax({
                data: {certifPorProfesionalInicio : certifPorProfesionalInicio, certifPorProfesionalFinal : certifPorProfesionalFinal},
                type: "POST",
                dataType: "json",
                url: "presentacion/certificaciones/includes/ajaxFunctions/CertifPorProfesional.php",
                success: function(data)
                {
                    if(data.ret == false){
                        alert(data.message);
                    }
                    else{

                        window.location.href = 'presentacion/certificaciones/includes/ajaxFunctions/CertifPorProfesionalExcel.php';
                    }
                }
            });


    
    });

    $('#nuevaCertificacion').click(function(event){
        event.preventDefault(event);
        //$("#nuevaCertificacion").off('click');
         $("#dialog:ui-dialog").dialog( "close" );
         $("#dialog:ui-dialog").dialog( "destroy" );
            $("#dialogNuevaCertificacion").css('visibility',"visible");
            $("#dialogNuevaCertificacion").load("presentacion/certificaciones/includes/forms/nuevaCertificacion.php",function() 
            {
                $("#dialogNuevaCertificacion").dialog({
                    title: "Nueva Certificacion",
                    modal: true,
                    resizable : true,
                    width: 800,
                    open: function (type, data) {
                        var form = $("#formParentCert");
                    $(this).parent().appendTo(form);
                },
                    buttons:
                            {
                                "Agregar Certificacion":function()
                                {

                                    if($("#Profesional").val() == 'SELECCIONAR')
                                    {
                                        alert("Debe seleccionar un Profesional de la lista.");
                                        return;
                                    }

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
                                                $(this).append("<div id='dialogAgregarPrestaciones' name='dialogAgregarPrestaciones'></div>");
                                                $("#dialog:ui-dialog").dialog( "close" );
                                                $("#dialog:ui-dialog").dialog( "destroy" );
                                                $("#dialogAgregarPrestaciones").css('visibility',"visible");
                                                $("#dialogAgregarPrestaciones").load("presentacion/prestaciones/includes/forms/prestacionCertificacion.php",{id:data.lastId},function() 
                                                {
                                                    $("#dialogAgregarPrestaciones").dialog({
                                                        title: "Agregar Prestaciones",
                                                        modal: true,
                                                        resizable : true,
                                                         closeOnEscape: false,
                                                        open: function(event, ui) {
                                                            $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                                        },
                                                        width: "80%",
                                                        buttons:
                                                                {
                                                                    "Cerrar":function()
                                                                    {
                                                                        //$("#dialogAgregarPrestaciones").remove();
                                                                        $(this).dialog("close").remove();
                                                                    }
                                                                }
                                                    });
                                                });
                                            }

                                            $('#jgVerCertificaciones').trigger( 'reloadGrid' );
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


function traerEspecialidades(){
    var e = document.getElementById("Profesional");
    var idProfesional = e.options[e.selectedIndex].value; 
     $.ajax({
            data: {idProfesional : idProfesional},
            type: "POST",
            dataType: "json",
            url: "presentacion/profesionales/includes/ajaxFunctions/TraerEspecialidades.php",
            success: function(data)
            {
                if(data.ret == false){
                   
                }
                else{
                    
                    var select = $('#EspecialidadCer');
                    select.empty();
                    for (var i = 0; i < data.especialidadesProfesional.length ; i++) {
                        select.append('<option value="'+data.especialidadesProfesional[i].IdEspecialidad+'">'+data.especialidadesProfesional[i].Especialidad+'</option>');
                        
                    }
                    
                }
            }
        });
}

function agregarPrestaciones(id){


         	
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
                    	text += "<input type='image' width='18px' height='18px' src='presentacion/includes/images/trash.png' onclick='eliminarDetalle(event, " + data.detalles[valor].IddetalleCertificacion + ");' />";
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

function eliminarDetalle(event, IddetalleCertificacion){

	event.preventDefault(event);
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

    $("#dialogVistaCertificacion").load("presentacion/certificaciones/includes/forms/vistaCertificacion.php",{id:id});
	$("#dialog:ui-dialog").dialog( "destroy" );
    $("#dialogVistaCertificacion").css('visibility',"visible");
   
        //$('#nuevaPrestacion').empty();

    $("#dialogVistaCertificacion").dialog({
        title: "Certificacion",
        modal: true,
        resizable : true,
        width: "80%",
        buttons:
                {
                    "Firmar":function()
                    {
                        if(confirm("¿Seguro que desea Firmar la Certificacion?"))
                        {
                            $.ajax({
                                data: {id:id},
                                type: "POST",
                                dataType: "json",
                                url: "presentacion/certificaciones/includes/ajaxFunctions/FirmarCertificacion.php",
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
                        }
                    },
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

