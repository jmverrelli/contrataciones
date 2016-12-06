var presta = "";

function calculartotal(){ //TODO VALIDAR EL INGRESO DE LOS DETALLES

	var valor = $("#valorPrestacionCertificacion").val();
	var cantidad = $("#cantidad").val()
	$("#total").val(valor * cantidad);
}

function traerValorPrestacion(select){

    	var presta = $("#prestacion option:selected").val();
    	var IdCertificacion = $("#IdCertificacion").val();
        $.ajax({
          	data: {prestacion:presta,IdCertificacion:IdCertificacion},
          	type: "POST",
          	dataType: "json",
          	url: "presentacion/prestaciones/includes/ajaxFunctions/traerValorCertificacion.php",
         	success: function(data)
          	{
          		if(data == false){
          			
          			//alert("Hubo un error encontrando el valor para esta prestacion.");
          		}
          		else{
          			$("#valorPrestacionCertificacion").val(data);
	        	}
        		

          	}
        });
}

function cargarPrestaciones(combo){

	$.ajax({
        type: "POST",
        dataType: "json",
        url: "presentacion/prestaciones/includes/ajaxFunctions/traerPrestacionesJson.php",
        success: function(data)
        {
        	
           $.each(data,function(key, value)
			{
			    combo.append('<option value=' + value['IdPrestacion'] + '>' + value['Prestacion'] + '</option>');
			});
           
        }
    }); 
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

	        $("#valorPrestacionCertificacion").val('');
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
	                $('#jqVerPrestaciones').trigger( 'reloadGrid' );
	            }
	        });

	         $('#agregarNuevaPrestacionForm')[0].reset();
	});

});