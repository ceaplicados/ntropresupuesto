$(document).ready(function(){
	setTimeout(function(){
		$("header").toggleClass('active-slide-side-header');
	},1000);
	canvasChart = document.getElementById('canvas-chart-cuaderno').getContext('2d');
	updateCuaderno();
});

var _Estados=new Array();
_Estados[1]='AGS';
_Estados[2]='BC';
_Estados[3]='BCS';
_Estados[4]='CAMP';
_Estados[5]='COAH';
_Estados[6]='COL';
_Estados[7]='CHIS';
_Estados[8]='CHIH';
_Estados[9]='CDMX';
_Estados[10]='DGO';
_Estados[11]='GTO';
_Estados[12]='GRO';
_Estados[13]='HGO';
_Estados[14]='JAL';
_Estados[15]='EDOMEX';
_Estados[16]='MICH';
_Estados[17]='MOR';
_Estados[18]='NAY';
_Estados[19]='NL';
_Estados[20]='OAX';
_Estados[21]='PUE';
_Estados[22]='QRO';
_Estados[23]='QROO';
_Estados[24]='SLP';
_Estados[25]='SIN';
_Estados[26]='SON';
_Estados[27]='TAB';
_Estados[28]='TAMPS';
_Estados[29]='TLAX';
_Estados[30]='VER';
_Estados[31]='YUC';
_Estados[32]='NULL';

function updateCuaderno(){
	var params=new Object();
	params.action="getFullCuaderno";
	params.Id=$("#IdCuaderno").val();
	$.post("/backend",params,function(resp){
		if(resp.Anios.length>0){
			$("#tablaDatos thead tr.detalles th.noAnio").remove();
			$("#tablaDatos thead tr.detalles th.anio").addClass("remove");
			for (let i = 0; i < resp.Anios.length; i++) {
				edicion="";
				if($("#edicion").val()){
					edicion='<i class="fa fa-minus-circle" aria-hidden="true" onclick="delAnio(this)"></i>';
				}
				if($("#tablaDatos thead tr.detalles th[data-anio='"+resp.Anios[i].Anio+"']").length==0){
					$("#tablaDatos thead tr.detalles").append('<th class="anio" data-anio="'+resp.Anios[i].Anio+'">'+resp.Anios[i].Anio+edicion+'</th>');
				}
				$("#tablaDatos thead tr.detalles th.anio[data-anio='"+resp.Anios[i].Anio+"']").removeClass("remove");
			}
			$("#tablaDatos tbody tr").addClass("remove");
			for (let i = 0; i < resp.Renglones.length; i++) {
				if($("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"']").length==0){
					$("#tablaDatos tbody").append('<tr data-id="'+resp.Renglones[i].Id+'"></tr>');
				}
				$("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"']").removeClass("remove");
				nombre="";
				liga='';
				if(resp.Renglones[i].Tipo=="ProgramaPresupuestal"){
					liga='<a target="_blank" href="/'+_Estados[resp.Renglones[i].Estado]+'/programa/'+resp.Renglones[i].Clave+'?i='+$("#INPC").val()+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
				}
				if(resp.Renglones[i].Tipo=="Total"){
					if(resp.Renglones[i].TipoFiltro=="UR"){
						liga='<a target="_blank" href="/'+_Estados[resp.Renglones[i].Estado]+'/ur/'+resp.Renglones[i].Clave+'?i='+$("#INPC").val()+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
					}
					if(resp.Renglones[i].TipoFiltro=="Estado"){
						liga='<a target="_blank" href="/'+_Estados[resp.Renglones[i].Estado]+'?i='+$("#INPC").val()+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
					}
				}
				if(resp.Renglones[i].Tipo=="Total" || resp.Renglones[i].Tipo=="CapituloGasto" || resp.Renglones[i].Tipo=="ConceptoGeneral" || resp.Renglones[i].Tipo=="PartidaGenerica" || resp.Renglones[i].Tipo=="ObjetoGasto" || resp.Renglones[i].Tipo=="ProgramaPresupuestal"){
					edicion="";
					if($("#edicion").val()){
						edicion=' <i class="fa fa-pencil-square-o" aria-hidden="true" onclick="modalRenglon(this)"></i>';
					}
					nombre='<span class="clave">'+resp.Renglones[i].Clave+'</span><span class="nombre">'+resp.Renglones[i].Nombre+liga+' '+edicion+'</span><span class="Referencia">'+resp.Renglones[i].Referencia+'</span><span class="estado">'+$("#estadoRenglon option[value='"+resp.Renglones[i].Estado+"']").text()+'</span>';
				}
				if(resp.Renglones[i].Mostrar=="monto"){
					nombre=nombre+'<span class="mostrar">Monto</span>';
				}else{
					nombre=nombre+'<span class="mostrar">Variación anual</span>';
				}
				$("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"']").html('<td>'+nombre+'</td>');
				classGraph="";
				if(resp.Renglones[i].Graph=="line"){
					classGraph=" active";
				}
				edicion="";
				if($("#edicion").val()){
					edicion='onclick="toogleGraph(this)"';
				}
				$("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"']").append('<td class="graph '+classGraph+'"><i class="fa fa-line-chart" aria-hidden="true" '+edicion+'></i></td>');
				for (j = 0; j < resp.Anios.length; j++) {
					$("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"']").append('<td class="valor" data-anio="'+resp.Anios[j].Anio+'" data-valor="">-</td>');
					monto="-";
					notaMonto="";
					if(resp.Renglones[i].Mostrar=="monto"){
						monto=resp.Renglones[i].Montos[resp.Anios[j].Anio];
					}else if(resp.Renglones[i].Mostrar=="YoY"){
						AnioPrev=resp.Anios[j].Anio-1;
						if(resp.Renglones[i].Montos[AnioPrev]>=0){
							monto=resp.Renglones[i].Montos[resp.Anios[j].Anio]-resp.Renglones[i].Montos[AnioPrev];
							notaMonto='<span class="notaMonto"><b>'+AnioPrev+'</b>: '+number_format(resp.Renglones[i].Montos[AnioPrev])+'<br/><b>'+resp.Anios[j].Anio+'</b>: '+number_format(resp.Renglones[i].Montos[resp.Anios[j].Anio])+'</span>'
						}
					}
					if(monto=="-"){
						$("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"'] td[data-anio='"+resp.Anios[j].Anio+"']").html('<span class="nota">Sin dato previo</span>');
						$("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"'] td[data-anio='"+resp.Anios[j].Anio+"']").attr("data-monto",0);
					}else{
						$("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"'] td[data-anio='"+resp.Anios[j].Anio+"']").html(number_format(monto)+notaMonto);
						$("#tablaDatos tbody tr[data-id='"+resp.Renglones[i].Id+"'] td[data-anio='"+resp.Anios[j].Anio+"']").attr("data-monto",monto);
					}
				}
			}
			$("#tablaDatos tbody tr.remove").remove();
			$("#tablaDatos thead tr.titulos th.anios").attr("colspan",resp.Anios.length);
			$("#tablaDatos tfoot tr td").attr("colspan",resp.Anios.length+2);
			$("#tablaDatos thead tr.detalles th.anio.remove").remove()
		}else{
			if($("#tablaDatos thead tr.detalles th.noAnio").length==0){
				$("#tablaDatos thead tr.detalles").append('<th class="noAnio">-</th>');
			}
		}
		updateGraph();
	},"json")
}

function toogleGraph(Obj){
	$(Obj).closest("td.graph").toggleClass("active");
	var params=new Object();
	params.action="toogleGraphRenglon";
	params.Id=$(Obj).closest("tr").attr("data-id");
	params.Graph="line";
	if(!$(Obj).closest("td.graph").hasClass("active")){
		params.Graph="";
	}
	$.post("/backend",params,function(resp){
		if($(Obj).closest("td.graph").hasClass("active")){
			toast('<p>Dato añadido a gráfica</p>');
		}else{
			toast('<p>Dato sin graficar</p>');
		}
		updateGraph();
	},"json")
}
var canvasChart, chartDatos;

function updateGraph(){
	var labels=new Array();
	$("#tablaDatos thead tr.detalles th.anio").each(function(){
		labels.push($(this).attr("data-anio"));
	});
	var data=new Array();
	$("#tablaDatos tbody tr td.graph.active").each(function(){
		var datos=new Array();
		for (i = 0; i < labels.length; i++) {
			datos.push($(this).closest("tr").find("td[data-anio='"+labels[i]+"']").attr("data-monto"));
		}
		
		var dataset=new Object();
		dataset.label=$(this).closest("tr").find(".clave").text()+": "+$(this).closest("tr").find(".nombre").text()+". "+$(this).closest("tr").find(".Referencia").text()+". "+$(this).closest("tr").find(".estado").text();
		dataset.data= datos;
		dataset.tension=0.1;
		dataset.fill=false;
		dataset.borderColor=_colores[data.length];
		data.push(dataset);
	});
	if(labels.length>0 && data.length>0){
		chartDatos = new Chart(canvasChart, {
			type: 'line',
			data: {
				labels: labels,
				datasets: data
			},
			options: {
				legend: {
					display: true,
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var valor = parseFloat(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
							return "$" + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
						}
					},
				}, 
				scales: {
					y: {
						beginAtZero: true,
						ticks: {
							beginAtZero:true,
							userCallback: function(value) {
								value = value.toString();
								value = value.split(/(?=(?:...)*$)/);
								value = value.join(',');
								return value;
							}
						}
					}
				}
			}
		});
		$(".canvas-chart-wrapper p.sinDatos").remove();
	}else{
		$(".canvas-chart-wrapper").append('<p class="sinDatos">No hay datos para graficar</p>');
		chartDatos=null;
		$("#canvas-chart-cuaderno").html('');
	}
	
}

function modalEditaCuaderno(){
	$("#modalConfiguracion").modal("toggle");
}

function modalCompartirCuaderno(){
	$("#modalCompartirCuaderno").modal("toggle");
}

function guardarConfiguracionCuaderno(){
	$("#guardarConfiguracionCuaderno").addClass("disabled");
	$("#guardarConfiguracionCuaderno").html('Guardando...');
	var params=new Object();
	params.action="guardarCuaderno";
	params.Id=$("#IdCuaderno").val();
	params.Nombre=$("#nombreCuaderno").val();
	params.Descripcion=$("#descripcionCuaderno").val();
	params.Publico=$("input[name='publicidadCuaderno']:checked").val();
	params.INPC=$("#INPCCuaderno").val()
	$.post("/backend",params,function(resp){
		toast('Cuaderno actualizado!');
		window.location.reload();
	},"json")
}

function validateEmail(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}


function buscarUsuario(){
	if(validateEmail($("#buscarEmail").val())){
		var existe=false;
		$("#modalCompartirCuaderno tbody tr").each(function(){
			if($(this).attr("data-email").toLowerCase()==$("#buscarEmail").val().toLowerCase()){
				existe=true;
			}
		});
		if(existe){
			toast('<p>El usuario ya está registrado</p>');
		}else{
			$("#buscarUsuario").addClass("disabled");
			$("#buscarUsuario").html('<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>');
			var params=new Object();
			params.action="buscarUsuarioByEmail";
			params.Email=$("#buscarEmail").val();
			params.Cuaderno=$("#IdCuaderno").val();
			$.post("/backend",params,function(resp){
				if(resp.error){
					toast('<p>⚠️ '+resp.error+'</p>');
				}else{
					$("#modalCompartirCuaderno tbody").append('<tr data-email="'+resp.Email+'">'
						+'<td class=""><div class="image"><img src="'+resp.Imagen+'" /></div>'
							+'<p>'+resp.Nombre+'</p><i class="fa fa-trash" aria-hidden="true" onclick="deleteUsuarioCuaderno(this)"></i></td></tr>');
					$(".participantes").append('<li title="'+resp.Nombre+'" data-email="'+resp.Email+'"><img src="'+resp.Imagen+'" /></li>');
					$("#buscarEmail").val('');
				}
				$("#buscarUsuario").removeClass("disabled");
				$("#buscarUsuario").html('<i class="fa fa-search" aria-hidden="true"></i>');
			},"json");
		}
	}else{
		toast('<p>Proporciona un email válido</p>');
	}
}

function deleteUsuarioCuaderno(Obj){
	var params=new Object();
	params.action="deleteUsuarioCuaderno";
	params.Email=$(Obj).closest("tr").attr("data-email");
	params.Cuaderno=$("#IdCuaderno").val();
	$.post("/backend",params,function(resp){
		if(resp.error){
			toast('<p>⚠️ '+resp.error+'</p>');
		}else{
			$(".participantes li[data-email='"+$(Obj).closest("tr").attr("data-email")+"']").remove();
			$(Obj).closest('tr').remove();
		}
		$("#buscarUsuario").removeClass("disabled");
		$("#buscarUsuario").html('<i class="fa fa-search" aria-hidden="true"></i>');
	},"json");
}

function copyUrl() {
	var copyText = document.getElementById("urlToCopy");
	copyText.select();
	copyText.setSelectionRange(0, 99999); /* For mobile devices */
	navigator.clipboard.writeText(copyText.value);
	toast('<p>Liga copiada!</p>');
}

function showModalAddYear(){
	$("#modalAddYear").modal("toggle");
}

function addYear(){
	var existe=false;
	$("#tablaDatos th.anio").each(function(){
		if($(this).attr("data-anio")==$("#anioToAdd").val()){
			existe=true;
		}
	})
	if(existe){
		toast('<p>El año ya existe en la tabla!</p>');
	}else{
		$("#modalAddYear").modal("toggle");
		var params=new Object();
		params.action="addYearCuaderno";
		params.Cuaderno=$("#IdCuaderno").val();
		params.Anio=$("#anioToAdd").val();
		$.post("/backend",params,function(resp){
			toast('<p>Año añadido!</p>');
			setTimeout(function(){
				updateCuaderno();
			},200)
		},"json")
	}
}

function delAnio(Obj){
	var params=new Object();
	params.action="delYearCuaderno";
	params.Cuaderno=$("#IdCuaderno").val();
	params.Anio=$(Obj).closest("th").attr("data-anio");
	$.post("/backend",params,function(resp){
		toast('<p>Año eliminado!</p>');
		setTimeout(function(){
			updateCuaderno();
		},200)
	},"json")
}

function modalRenglon(Obj){
	$("#modalRenglon h5").html('Añadir renglón');
	$("#modalRenglon").attr('data-id',"");
	$("#tipoRengon option[value='']").prop("selected",true);
	$("#mostrarRenglon_monto").prop("checked",true);
	$("#resultadosBuscarOG_PP").html("");
	$("#buscarOG_PP").val("");
	$("#buscarOG_PP").attr("data-id","");
	$("#filtroOG option[value='']").prop("selected",true);
	$("#valorFiltroOG").val("");
	$("#valorFiltroOG").attr("data-id","");
	$("#saveRenglon").html("Añadir");
	$("#modalRenglon .filtroOG_UPUR").hide();
	$("#delRenglon").hide();
	changeFiltroOG();
	changeTipoRenglon();
	if(Obj){
		$("#modalRenglon h5").html('Editar renglón');
		$("#modalRenglon").attr('data-id',$(Obj).closest("tr").attr("data-id"));
		$("#delRenglon").show();
		var params=new Object();
		params.action="getRenglon";
		params.Id=$(Obj).closest("tr").attr("data-id");
		$.post("/backend",params,function(resp){
			$("#tipoRengon option[value='"+resp.Renglon.Tipo+"']").prop("selected",true);
			if(resp.Tipo){
				$("#buscarOG_PP").val(resp.Tipo.Clave+": "+resp.Tipo.Nombre);
			}
			if(resp.Renglon.IdReferencia){
				$("#buscarOG_PP").attr("data-id",resp.Renglon.IdReferencia);
			}
			$("#filtroOG option[value='"+resp.Renglon.TipoFiltro+"']").prop("selected",true);
			$("#estadoRenglon option[value='"+resp.Renglon.Estado+"']").prop("selected",true);
			if(resp.Filtro){
				$("#valorFiltroOG").val(resp.Filtro.Clave+": "+resp.Filtro.Nombre);
				$("#valorFiltroOG").attr("data-id",resp.Renglon.IdFiltro);
			}
			if(resp.Renglon.Mostrar=="YoY"){
				$("#mostrarRenglon_YoY").prop("checked",true);
			}
			$("#saveRenglon").html("Guardar");
			changeFiltroOG();
			changeTipoRenglon();
			$("#modalRenglon").modal("toggle");
		},"json");
	}else{
		$("#modalRenglon").modal("toggle");
	}
}

function changeTipoRenglon(){
	$("#modalRenglon").attr("data-tipodato",$("#tipoRengon").val());
	$("#modalRenglon label[for='buscarOG_PP'] span.nombre").html($("#tipoRengon option:selected").text());
	$("#buscarOG_PP").val("");
	$("#buscarOG_PP").attr("data-id","");
}

function changeFiltroOG(){
	if($("#filtroOG").val()){
		if($("#filtroOG").val()=="Estado"){
			$("#modalRenglon .filtroOG_UPUR").hide();
		}else{
			$("#modalRenglon .filtroOG_UPUR").show();
			$("#modalRenglon label[for='valorFiltroOG']").html($("#filtroOG option:selected").text());
		}
	}else{
		$("#modalRenglon .filtroOG_UPUR").hide();
	}
	$("#valorFiltroOG").val("");	
	$("#valorFiltroOG").attr("data-id","");	
}

function buscarOG_PP(){
	if($("#buscarOG_PP").val().length>=3){
		if($("#estadoRenglon").val()>0){
			var params=new Object();
			params.action="buscarOG_PP";
			params.tipoRengon=$("#tipoRengon").val();
			params.estado=$("#estadoRenglon").val();
			params.buscar=$("#buscarOG_PP").val();
			$.post("/backend",params,function(resp){
				if(resp.buscar=$("#buscarOG_PP").val()){
					$("#resultadosBuscarOG_PP").html("");
					if(resp.result.length>0){
						for (i = 0; i < resp.result.length; i++) {
							ref="";
							if(resp.result[i].Referencia){
								if(resp.result[i].Referencia.length>0){
									ref='<span class="Referencia">'+resp.result[i].Referencia+'</span>';
								}
							}
							$("#resultadosBuscarOG_PP").append('<li data-id="'+resp.result[i].Id+'" onclick="selectOG_PP(this)"><span class="clave">'+resp.result[i].Clave+'</span><span class="nombre">'+resp.result[i].Nombre+'</span>'+ref+'</li>');
						}
					}else{
						$("#resultadosBuscarOG_PP").html('<li class="noResult">Sin resultados</li>');
					}
				}
			},"json")
		}else{
			toast('<p>Selecciona un estado!</p>');
		}
	}else if($("#buscarOG_PP").val().length==0){
		$("#resultadosBuscarOG_PP").html("");
	}
}

function buscarOG_UPUR(){
	if($("#valorFiltroOG").val().length>=3){
		if($("#estadoRenglon").val()>0){
			var params=new Object();
			params.action="buscarOG_UPUR";
			params.tipoFiltro=$("#filtroOG").val();
			params.estado=$("#estadoRenglon").val();
			params.buscar=$("#valorFiltroOG").val();
			$.post("/backend",params,function(resp){
				if(resp.buscar=$("#filtroOG").val()){
					$("#resultadosvalorFiltroOG").html("");
					if(resp.result.length>0){
						for (i = 0; i < resp.result.length; i++) {
							$("#resultadosvalorFiltroOG").append('<li data-id="'+resp.result[i].Id+'" onclick="selectOG_UPUR(this)"><span class="clave">'+resp.result[i].Clave+'</span><span class="nombre">'+resp.result[i].Nombre+'</span></li>');
						}
					}else{
						$("#resultadosvalorFiltroOG").html('<li class="noResult">Sin resultados</li>');
					}
				}
			},"json")
		}else{
			toast('<p>Selecciona un estado!</p>');
		}
	}else if($("#valorFiltroOG").val().length==0){
		$("#resultadosvalorFiltroOG").html("");
	}
}

function selectOG_PP(Obj){
	$("#buscarOG_PP").val($(Obj).find(".clave").text()+": "+$(Obj).find(".nombre").text());
	$("#buscarOG_PP").attr("data-id",$(Obj).attr("data-id"));
	$("#resultadosBuscarOG_PP").html("");
}

function selectOG_UPUR(Obj){
	$("#valorFiltroOG").val($(Obj).find(".clave").text()+": "+$(Obj).find(".nombre").text());
	$("#valorFiltroOG").attr("data-id",$(Obj).attr("data-id"));
	$("#resultadosvalorFiltroOG").html("");
}

function saveRenglon(){
	var seguir=true;
	if($("#tipoRengon").val()=="CapituloGasto" || $("#tipoRengon").val()=="ConceptoGeneral" || $("#tipoRengon").val()=="PartidaGenerica" || $("#tipoRengon").val()=="ObjetoGasto" || $("#tipoRengon").val()=="ProgramaPresupuestal"){
		if(!$("#buscarOG_PP").attr("data-id")>0){
			seguir=false;
		}
	}
	if(seguir){
		$("#saveRenglon").html("Guardando");
		$("#saveRenglon").addClass("disabled");
		var params=new Object();
		params.action="saveRenglon";
		params.Id=$("#modalRenglon").attr('data-id');
		params.Cuaderno=$("#IdCuaderno").val();
		params.Tipo=$("#tipoRengon").val();
		params.Estado=$("#estadoRenglon").val();
		params.IdReferencia=$("#buscarOG_PP").attr("data-id");
		params.TipoFiltro=$("#filtroOG").val();
		params.IdFiltro=$("#valorFiltroOG").attr("data-id");
		params.Mostrar=$("input[name='mostrarRenglon']:checked").val();
		$.post("/backend",params,function(resp){
			$("#saveRenglon").removeClass("disabled");
			$("#modalRenglon").modal("toggle");
			toast('<p>Tabla actualizada!</p>');
			updateCuaderno();
		},"json")
	}
}

function delRenglon(){
	$("#modalRenglon").modal("toggle");
	var params=new Object();
	params.action="delRenglon";
	params.Id=$("#modalRenglon").attr('data-id');
	$.post("/backend",params,function(resp){
		toast('<p>Renglón eliminado!</p>');
		updateCuaderno();
	},"json")
}