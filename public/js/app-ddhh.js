$(document).ready(function(){
	setTimeout(function(){
		$("header").toggleClass('active-slide-side-header');
	},1000);
	getPropuestasUsuario();
});

function setPasoPropuesta(paso){
	$("#interfazPropuesta").removeClass("paso0");
	$("#interfazPropuesta").removeClass("paso1");
	$("#interfazPropuesta").removeClass("paso2");
	$("#interfazPropuesta").removeClass("paso3");
	$("#interfazPropuesta").removeClass("paso4");
	$("#interfazPropuesta").addClass("paso"+paso);
}

function do_filtrarURs(){
	if($("#filtrarURs").val().trim().length>0){
		$("#unidadesResponsables li").hide();
		$("#unidadesResponsables li").each(function(){
			if($(this).text().toUpperCase().indexOf($("#filtrarURs").val().trim().toUpperCase())>=0){
				$(this).show();
			}
		})
	}else{
		$("#unidadesResponsables li").show();
	}
}

function selectUR(Obj){
	setPasoPropuesta(2);
	$("#paso2 h6 span.ur").html($(Obj).text());
	$("#unidadesResponsables").attr("data-id",$(Obj).attr("data-id"));
	$("#programasUR").html("Obteniendo programas...");
	var params=new Object();
	params.action="getProgramasByURSinODS";
	params.UR=$("#unidadesResponsables").attr("data-id");
	$.post("/backend",params,function(resp){
		$("#programasUR").html("");
		for (let i = 0; i < resp.Programas.length; i++) {
			$("#programasUR").append('<li data-id="'+resp.Programas[i].Id+'"><p><b>'+resp.Programas[i].Clave+'</b> '+resp.Programas[i].Nombre+' <a target="_blank" href="/JAL/programa/'+resp.UP.Clave+'-'+resp.UR.Clave+'-'+resp.Programas[i].Clave+'"><i class="fa fa-external-link" aria-hidden="true"></i></a></p><button class="button theme_button" onclick="seleccionarPrograma(this)">Seleccionar</button></li>');
		}
		if(resp.length==0){
			$("#programasUR").html('No hay programas pendientes de clasificar para esta Unidad Presupuestal.<br/> <button class="button theme_button" onclick="setPasoPropuesta(1)">Volver a comenzar</button>');
		}
	},"json")
}

function seleccionarPrograma(Obj){
	setPasoPropuesta(3);
	$("#paso2").attr("data-id",$(Obj).closest("li").attr("data-id"));
	$("#paso3 .programa").html($(Obj).closest("li").find("p").html());
	$("#paso4 .programa").html($(Obj).closest("li").find("p").html());
}

function selectODS(Obj){
	if($(Obj).hasClass("selected")){
		$(Obj).removeClass("selected");
		deselectODS(Obj);
	}else{
		$(Obj).addClass("selected");
		var params=new Object();
		params.action="getMetasODS";
		params.ODS=$(Obj).attr("data-id");
		$("#selecionarMetas").html('');
		$("#selecionarMetas").attr("data-ods",$(Obj).attr("data-id"));
		$.post("/backend",params,function(resp){
			for (let i = 0; i < resp.length; i++) {
				$("#selecionarMetas").append('<li class="list-group-item"><div class="checkbox"><label><input class="selectODS" type="checkbox" value="'+resp[i].Id+'" onchange="selectMetaODS(this)"><b>'+resp[i].Clave+'</b> '+resp[i].Nombre+'</label></div></li>');
			}
		},"json");
		if($('#ODSseleccionados img[data-id="'+$(Obj).attr("data-id")+'"]').length==0){
			$('#ODSseleccionados').append('<img src="/imgs/ODS/S-WEB-Goal-'+$(Obj).attr("data-id")+'.png" class="ODS" data-id="'+$(Obj).attr("data-id")+'" onclick="deselectODS(this)"/>');
		}
	}
}

function selectMetaODS(Obj){
	$("#metasSeleccionadas .sinMeta").remove();
	if($(Obj).is(":checked")){
		if($('#metasSeleccionadas li[data-id="'+$(Obj).val()+'"]').length==0){
			$('#metasSeleccionadas').append('<li class="list-group-item" data-id="'+$(Obj).val()+'" data-ods="'+$("#selecionarMetas").attr("data-ods")+'"><div class="checkbox"><label><input type="checkbox" checked onchange="deselectMetaODS('+$(Obj).val()+')">'+$(Obj).closest("label").html()+'</label></div></li>');
			$('#metasSeleccionadas li[data-id="'+$(Obj).val()+'"] input.selectODS').remove();
			$('#paso3 .ODSs img.ODS[data-id="'+$("#selecionarMetas").attr("data-ods")+'"]').addClass("metaSelected");
			$(Obj).closest("li").hide();
			if($('#ODSseleccionados img[data-id="'+$("#selecionarMetas").attr("data-ods")+'"]').length==0){
				$('#ODSseleccionados').append('<img src="/imgs/ODS/S-WEB-Goal-'+$("#selecionarMetas").attr("data-ods")+'.png" class="ODS" data-id="'+$("#selecionarMetas").attr("data-ods")+'" onclick="deselectODS(this)"/>');
			}
		}
	}else{
		deselectMetaODS($(Obj).val());
	}
}

function deselectMetaODS(idMeta){
	var ODS=$('#metasSeleccionadas li[data-id="'+idMeta+'"]').attr("data-ods");
	$('#metasSeleccionadas li[data-id="'+idMeta+'"]').remove();
	$('#selecionarMetas input[value="'+idMeta+'"]').prop("checked",false);
	$('#selecionarMetas input[value="'+idMeta+'"]').closest("li").show();
	if($('#metasSeleccionadas li[data-ods="'+ODS+'"]').length==0){
		$('#paso3 .ODSs img.ODS[data-id="'+ODS+'"]').removeClass("metaSelected");
		if(!$('#paso3 .ODSs img.ODS[data-id="'+ODS+'"]').hasClass("selected")){
			$('#ODSseleccionados img[data-id="'+ODS+'"]').remove();
		}
	}
	if($('#metasSeleccionadas li').length==0){
		$('#metasSeleccionadas').html('<li class="sinMeta list-group-item"><i>Comienza seleccionando un ODS</i></li>');
	}
}

function deselectODS(Obj){
	if($('#metasSeleccionadas li[data-ods="'+$(Obj).attr("data-id")+'"]').length>0){
		toast('Existen metas seleccionadas para este ODS');
	}else{
		$('#paso3 .ODSs img.ODS[data-id="'+$(Obj).attr("data-id")+'"]').removeClass("selected");
		$('#ODSseleccionados img[data-id="'+$(Obj).attr("data-id")+'"]').remove();
	}
}

function confirmarODS(){
	if($('#ODSseleccionados img').length==0){
		toast('Debes seleccionar ODSs y las metas a las que abona este programa presupuestal.');
	}else{
		setPasoPropuesta(4);
		$('#ODSseleccionadosConfirm').html('');
		$('#metasSeleccionadasConfirm').html('');
		$('#paso4 .instruccionPrincipal').hide();
		$('#ODSseleccionados img').each(function(){
			$('#ODSseleccionadosConfirm').append('<img src="/imgs/ODS/S-WEB-Goal-'+$(this).attr("data-id")+'.png" class="ODS" data-id="'+$(this).attr("data-id")+'"/>');
		});
		if($('#ODSseleccionados img').length>1){
			$('#paso4 .instruccionPrincipal').show();
			$('#ODSseleccionadosConfirm img').click(function(){
				$('#ODSseleccionadosConfirm img').removeClass("selected");
				$(this).addClass("selected");
			});
		}
		$('#metasSeleccionadas li').each(function(){
			if(!$(this).hasClass("sinMeta")){
				$('#metasSeleccionadasConfirm').append('<li class="list-group-item" data-id="'+$(this).attr("data-id")+'">'+$(this).text()+'</li>');
			}
		})
		if($('#metasSeleccionadas li').length==0){
			$('#metasSeleccionadasConfirm').append('<li class="list-group-item sinMeta">No se seleccionaron metas específicas</li>');
		}
	}
}

function do_guardarPropuesta(){
	var continuar=true;
	if($('#ODSseleccionadosConfirm img').length>1){
		if($('#ODSseleccionadosConfirm img.selected').length==0){
			toast('Selecciona un ODS como principal.');
			continuar=false;
		}
	}
	if($("#argumentacion").val().length<5){
		toast('Por favor escribe una breve explicación de tu propuesta.');
		continuar=false;
	}
	if(continuar){
		if(!$("#guardarPropuesta").hasClass("disabled")){
			$("#guardarPropuesta").addClass("disabled");
			$("#guardarPropuesta").html('Guardando propuesta...');
			var params=new Object();
			params.action="guardarPropuestaODS";
			params.Programa=$("#paso2").attr("data-id");
			params.ODSs=new Array();
			$('#ODSseleccionadosConfirm img').each(function(){
				params.ODSs.push($(this).attr("data-id"));
			});
			if($('#ODSseleccionadosConfirm img').length>1){
				params.ODSppal=$('#ODSseleccionadosConfirm img.selected').attr("data-id");
			}else{
				params.ODSppal=$('#ODSseleccionadosConfirm img').attr("data-id");
			}
			params.Metas=new Array();
			$('#metasSeleccionadasConfirm li').each(function(){
				if(!$(this).hasClass("sinMeta")){
					params.Metas.push($(this).attr("data-id"));
				}
			})
			params.Argumentacion=$("#argumentacion").val();
			$.post("/backend",params,function(resp){
				toast('Propuesta guardada!');
				$("#guardarPropuesta").html('Confirmar');
				setPasoPropuesta(0);
			},"json")
		}
	}
}

function getPropuestasUsuario(){
	var params=new Object();
	params.action="getPropuestasUsuario";
	$.post("/backend",params,function(resp){
		/*
		<tr>
			<th>Fecha</th>
			<th>Programa</th>
			<th>ODSs y metas</th>
			<th>Justificación</th>
			<th>Estatus</th>
		</tr>
		*/
		if(resp.Propuestas.length>0){
			$("#misPropuestas tbody tr.sinPropuestas").remove();
			for (i = 0; i < resp.Propuestas.length; i++) {
			  if($('#misPropuestas tbody tr[data-id="'+resp.Propuestas[i].Id+'"]').length==0){
				var ODS='';
				var metas='';
				for (let j = 0; j < resp.Propuestas[i].ODS.length; j++) {
					ppal='';
					if(resp.Propuestas[i].ODS[j].Principal==1){
						ppal=' principal';
					}
					ODS+='<img src="/imgs/ODS/S-WEB-Goal-'+resp.Propuestas[i].ODS[j].ODS+'.png" class="ODS'+ppal+'" data-id="'+resp.Propuestas[i].ODS[j].ODS+'"/>'
				}
				for (let j = 0; j < resp.Propuestas[i].Metas.length; j++) {
					metas+='<span data-id="'+resp.Propuestas[i].Metas[j].Id+'"><b>'+resp.Metas[resp.Propuestas[i].Metas[j].Meta].Clave+'</b> '+resp.Metas[resp.Propuestas[i].Metas[j].Meta].Nombre+'</span>';
				}
				$("#misPropuestas tbody").append('<tr data-id="'+resp.Propuestas[i].Id+'">'
					+'<td>'+formatFecha(resp.Propuestas[i].DatePropuesta)+'</td>'
					+'<td class="programa"><b>'+resp.URs[resp.Programas[resp.Propuestas[i].Programa].UnidadResponsable].Clave+'-'+resp.Programas[resp.Propuestas[i].Programa].Clave+' <a target="_blank" href="/JAL/programa/'+resp.URs[resp.Programas[resp.Propuestas[i].Programa].UnidadResponsable].Clave+'-'+resp.Programas[resp.Propuestas[i].Programa].Clave+'"><i class="fa fa-external-link" aria-hidden="true"></i></a></b> '+resp.Programas[resp.Propuestas[i].Programa].Nombre+'</td>'
					+'<td class="ODS">'+ODS+metas+'</td>'
					+'<td class="justificacion">'+resp.Propuestas[i].Argumentacion+'</td>'
					+'<td class="estatus">'+resp.Propuestas[i].TipoPropuesta+'</td>'
					+'</tr>');
			  }
			}
		}else{
			$("#misPropuestas tbody").html('<tr class="sinPropuestas"><td colspan="5">Sin propuestas</td></tr>');
		}
		
	},"json");
}