
function validateEmail(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

function guardarUsuario(){
	$("#email").closest(".form-group").removeClass("error");
	$("#telefono").closest(".form-group").removeClass("error");
	if(validateEmail($("#email").val())){
		if($("#telefono").val().length==0 || $("#telefono").val().length==10){
			$("#guardarUsuario").html("Guardando...");
			var params=new Object();
			params.action="guardarUsuario";
			params.Nombre=$("#nombre").val();
			params.Sobrenombre=$("#sobrenombre").val();
			params.Email=$("#email").val();
			params.Telefono=$("#telefono").val();
			params.Estado=$("#estado").val();
			$.post("/backend",params,function(resp){
				toast('Información guardada!');
				$("#guardarUsuario").html("Guardar");
			},"json")
		}else{
			$("#telefono").closest(".form-group").addClass("error");
		}
	}else{
		$("#email").closest(".form-group").addClass("error");
	}
}