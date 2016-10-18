function traerValor(){
	$.ajax({
          	data: $('#nuevaPrestacion').serialize(),
          	type: "POST",
          	dataType: "json",
          	url: "presentacion/prestaciones/includes/ajaxFunctions/traerValorCertificacion.php",
         	success: function(data)
          	{
          		if(data == false){
          			
          			//alert("Hubo un error encontrando el valor para esta prestacion.");
          		}
          		else{
          			$("#valor").val(data);
	        	}
        		

          	}
        });
}

function calculartotal(){ //TODO VALIDAR EL INGRESO DE LOS DETALLES

	var valor = $("#valor").val();
	var cantidad = $("#cantidad").val()
	$("#total").val(valor * cantidad);
}

$(document).ready(function(){

    $('#agregarPrestacion').click(function(event){
    	event.preventDefault(event);
    		$.ajax({
	            data: $('#nuevaPrestacion').serialize(),
	            type: "POST",
	            dataType: "json",
	            url: "presentacion/certificaciones/includes/ajaxFunctions/AgregarDetalle.php",
	            success: function(data)
	            {
	                if(data.ret == false){
	                    alert(data.message);
	                    $('#prestacion').focus();
	                }
	                else{
	                    
	                    alert(data.message);
	                    $('#prestacion').focus();
	                }
	            }
	        });

	        $("#valor").val('');
	        $("#cantidad").val('');
	        $("#total").val('');
	    	$('#prestacion>option:eq(0)').prop('selected', true);
	    	traerDetalles($("#IdCertificacion").val());
	});


	 $('#agregarNuevaPrestacionBtn').click(function(event){
    	event.preventDefault(event);
    		$.ajax({
	            data: $('#agregarNuevaPrestacionForm').serialize(),
	            type: "POST",
	            dataType: "json",
	            url: "presentacion/prestaciones/includes/ajaxFunctions/AgregarNuevaPrestacion.php",
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

	         $('#agregarNuevaPrestacionForm')[0].reset();
	});

});