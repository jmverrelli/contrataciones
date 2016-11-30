$(document).ready(function(){

	    $('#agregarEspecialidad').click(function(event){

    		if($("#especialidadAgre").val() == ''){

				alert("Ingrese una descripcion para la Especialidad");
			}
			else{
	    	event.preventDefault(event);
	    		$.ajax({
		            data: $('#agregarEspecialidadForm').serialize(),
		            type: "POST",
		            dataType: "json",
		            url: "presentacion/especialidades/includes/ajaxFunctions/AgregarEspecialidad.php",
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

		         $('#agregarEspecialidadForm')[0].reset();

		     }
		});


});
