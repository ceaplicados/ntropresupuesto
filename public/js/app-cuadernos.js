$(document).ready(function(){
	setTimeout(function(){
		$("header").toggleClass('active-slide-side-header');
	},1000);
	do_filtrarCuadernos();
});

function nuevoCuaderno(){
	$("#modalNuevoCuaderno").modal("toggle");
}

function crearCuadernoTrabajo(){
	if($("#nombre").val().length>3){
		$("#crearCuadernoTrabajo").html("Creando...");
		$("#crearCuadernoTrabajo").addClass("disabled");
		var params=new Object();
		params.action="crearCuadernoTrabajo";
		params.Nombre=$("#nombre").val();
		$.post("/backend",params,function(resp){
			toast('Cuaderno creado!');
			window.location.href="/cuaderno/"+resp.Id;
		},"json")
	}else{
		$("#nombre").closest(".form-group").addClass("error");
	}
	
}

function do_filtrarCuadernos(){
	if($("#filtrarCuadernos").val().trim().length>0){
		$("#cuadernosPublicos .cuaderno").hide();
		$("#cuadernosPublicos .cuaderno").each(function(){
			if($(this).text().toUpperCase().indexOf($("#filtrarCuadernos").val().trim().toUpperCase())>=0){
				$(this).show();
			}
		});
	}else{
		$("#cuadernosPublicos .cuaderno").show();
	}
}