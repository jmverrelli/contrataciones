$(document).ready(function(){

    $('#agregarProfesional').click(function(event){
    	event.preventDefault(event);
    		$.ajax({
	            data: $('#agregarProfesionalForm').serialize(),
	            type: "POST",
	            dataType: "json",
	            url: "presentacion/profesionales/includes/ajaxFunctions/AgregarProfesional.php",
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

	         $('#agregarProfesionalForm')[0].reset();
	});

});