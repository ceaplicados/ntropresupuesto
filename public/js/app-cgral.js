$(document).ready(function(){
	$("header").toggleClass('active-slide-side-header');
	var params=new Object()
	params.action="getHistoricoPartidasGenericas";
	params.INPC=$("#paramINPC").val();
	params.Estado=$("#paramEstado").val();
	params.ConceptoGeneral=$("#paramConceptoGeneral").val();
	$.post("/backend",params,function(resp){
		var ANIOS_HIST=new Array();
		var DATA_CG=new Array();
		var totalesVersiones=new Array();
		for (i = 0; i < resp.versiones.length; i++){
			ANIOS_HIST.unshift(resp.versiones[i].Anio+" "+resp.versiones[i].Nombre);
			$('#tablaConceptoGeneral thead .anio').prepend('<th class="text-center">'+resp.versiones[i].Anio+" "+resp.versiones[i].Nombre+'</th>');
			$('#tablaConceptoGeneralVariaciones thead .anio').prepend('<th class="text-center">'+resp.versiones[i].Anio+" "+resp.versiones[i].Nombre+'</th>');
			var DataYearCP=new Array();
			var total = 0;
			if(resp.resumen[resp.versiones[i].Id]){
				for (var j in resp.conceptos){
					if(resp.resumen[resp.versiones[i].Id][resp.conceptos[j].Id]){
						total+=resp.resumen[resp.versiones[i].Id][resp.conceptos[j].Id].Monto;
					}
				}
			}
			totalesVersiones[resp.versiones[i].Id]=total;
			if(resp.versiones[i].Id==$('#paramVersion').val()){
				$('#montoTotal').html(number_format(total));
			}
			$('#tablaConceptoGeneral tfoot tr').prepend('<td class="text-right">$ '+number_format(total,0)+'</td>');
		}
		for (i = 0; i < resp.versiones.length; i++){
			if(i < resp.versiones.length -1){
				if(totalesVersiones[resp.versiones[i+1].Id]){
					var variacion=(totalesVersiones[resp.versiones[i].Id]-totalesVersiones[resp.versiones[i+1].Id])/totalesVersiones[resp.versiones[i+1].Id]*100;
					$('#tablaConceptoGeneralVariaciones tfoot tr').prepend('<td class="text-right">'+variacion.toFixed(1)+'%</td>');
				}else{
					$('#tablaConceptoGeneralVariaciones tfoot tr').prepend('<td class="text-right"></td>');
				}
				
			}else{
				$('#tablaConceptoGeneralVariaciones tfoot tr').prepend('<td class="text-right"></td>');
			}
		}
		$('#tablaConceptoGeneral tfoot tr').prepend('<td>Total</td>');
		$('#tablaConceptoGeneral thead .anio').prepend('<th>Concepto general</th>');
		$('#tablaConceptoGeneralVariaciones tfoot tr').prepend('<td>Variación total</td>');
		$('#tablaConceptoGeneralVariaciones thead .anio').prepend('<th>Concepto general</th>');

		for (var j in resp.conceptos){
			var rowAnio='';
			var rowAnioAreas='';
			var rowVariaciones='';
			var TotalConcepto=0;
			for (i = 0; i < resp.versiones.length; i++){
				var porcentaje=0;
				if(resp.resumen[resp.versiones[i].Id][resp.conceptos[j].Id]){
					if(resp.resumen[resp.versiones[i].Id][resp.conceptos[j].Id].Monto>0){
						porcentaje=resp.resumen[resp.versiones[i].Id][resp.conceptos[j].Id].Monto/totalesVersiones[resp.versiones[i].Id]*100;
						porcentaje=porcentaje.toFixed(1);
						TotalConcepto+=resp.resumen[resp.versiones[i].Id][resp.conceptos[j].Id].Monto;
					}
					rowAnio='<td class="text-right">$'+number_format(resp.resumen[resp.versiones[i].Id][resp.conceptos[j].Id].Monto)+'</td>'+rowAnio;
					rowAnioAreas='<td class="text-right">'+porcentaje+'%</td>'+rowAnioAreas;
					if(i < resp.versiones.length - 1 ){
						if(resp.resumen[resp.versiones[i+1].Id][resp.conceptos[j].Id]){
							var variacion=(resp.resumen[resp.versiones[i].Id][resp.conceptos[j].Id].Monto-resp.resumen[resp.versiones[i+1].Id][resp.conceptos[j].Id].Monto)/resp.resumen[resp.versiones[i+1].Id][resp.conceptos[j].Id].Monto*100;
							rowVariaciones='<td class="text-right">'+variacion.toFixed(1)+'%</td>'+rowVariaciones;
						}else{
							rowVariaciones='<td></td>'+rowVariaciones;
						}
					}else{
						rowVariaciones='<td></td>'+rowVariaciones;
					}
				}else{
					rowAnio='<td></td>'+rowAnio;
					rowAnioAreas='<td></td>'+rowAnioAreas;
					rowVariaciones='<td></td>'+rowVariaciones;
				}
			}
			if(TotalConcepto>0){
				$('#tablaConceptoGeneral tbody').append('<tr><td>'+resp.conceptos[j].Clave+' - '+resp.conceptos[j].Nombre+' <a href="/'+$('#paramCodigoEstado').val()+'/PartidasGenericas/'+resp.conceptos[j].Clave+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>'+rowAnio+'</tr>');
				$('#tablaConceptoGeneralVariaciones tbody').append('<tr><td>'+resp.conceptos[j].Clave+' - '+resp.conceptos[j].Nombre+' <a href="/'+$('#paramCodigoEstado').val()+'/PartidasGenericas/'+resp.conceptos[j].Clave+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>'+rowVariaciones+'</tr>');
			}
		}

		for (var i in resp.conceptos){
			var DATASET=new Object();
			DATASET.label=resp.conceptos[i].Clave+" "+resp.conceptos[i].Nombre
			DATASET.backgroundColor=_coloresTableau20(i)
			DATASET.data=new Array();
			for (j = 0; j < resp.versiones.length; j++){
				if(resp.resumen[resp.versiones[j].Id]){
					if(resp.resumen[resp.versiones[j].Id][resp.conceptos[i].Id]){
						DATASET.data.unshift(resp.resumen[resp.versiones[j].Id][resp.conceptos[i].Id].Monto);
					}else{
						DATASET.data.unshift(0);
					}
				}else{
					DATASET.data.unshift(0);
				}
			}
			DATA_CG.push(DATASET);
		}
		
		var $presupuestoConceptoGeneral = jQuery('.chart-bar-historico-cg');
		if ($presupuestoConceptoGeneral.length) {
			$presupuestoConceptoGeneral.each(function(i){
				var config = {
					type: 'bar',
					data: {
						labels: ANIOS_HIST,
						datasets: DATA_CG
					},
					options: {
						chartArea: {
							backgroundColor: 'rgba(100, 100, 100, 0.02)',
						},
						scales: {
							x: {
								stacked: true
							},
							y: {
								stacked: true							}
						}
					}
				};
		
				var canvas = jQuery(this)[0].getContext("2d");;
				new Chart(canvas, config);
			});
		}
	},"json");
	
	
	var params=new Object()
	params.action="getHistoricoConceptosGeneralesByUR";
	params.INPC=$("#paramINPC").val();
	params.Estado=$("#paramEstado").val();
	params.ConceptoGeneral=$("#paramConceptoGeneral").val();
	$.post("/backend",params,function(resp){
		console.log(resp);
		
		var ANIOS_HIST=new Array();
		var DATA_CG=new Array();
		var totalesVersiones=new Array();
		for (i = 0; i < resp.versiones.length; i++){
			ANIOS_HIST.unshift(resp.versiones[i].Anio+" "+resp.versiones[i].Nombre);
			$('#tablaUnidadResponsable thead .anio').prepend('<th class="text-center">'+resp.versiones[i].Anio+" "+resp.versiones[i].Nombre+'</th>');
			$('#tablaUnidadResponsableVariaciones thead .anio').prepend('<th class="text-center">'+resp.versiones[i].Anio+" "+resp.versiones[i].Nombre+'</th>');
			var DataYearCP=new Array();
			var total = 0;
			if(resp.resumen[resp.versiones[i].Id]){
				for (var j in resp.URs){
					if(resp.resumen[resp.versiones[i].Id][resp.URs[j].Id]){
						total+=resp.resumen[resp.versiones[i].Id][resp.URs[j].Id].Monto;
					}
				}
			}
			totalesVersiones[resp.versiones[i].Id]=total;
			if(resp.versiones[i].Id==$('#paramVersion').val()){
				$('#montoTotal').html(number_format(total));
			}
			$('#tablaUnidadResponsable tfoot tr').prepend('<td class="text-right">$ '+number_format(total,0)+'</td>');
		}
		
		for (i = 0; i < resp.versiones.length; i++){
			if(i < resp.versiones.length -1){
				var variacion=(totalesVersiones[resp.versiones[i].Id]-totalesVersiones[resp.versiones[i+1].Id])/totalesVersiones[resp.versiones[i+1].Id]*100;
				$('#tablaUnidadResponsableVariaciones tfoot tr').prepend('<td class="text-right">'+variacion.toFixed(1)+'%</td>');
			}else{
				$('#tablaUnidadResponsableVariaciones tfoot tr').prepend('<td class="text-right"></td>');
			}
		}
		$('#tablaUnidadResponsable tfoot tr').prepend('<td>Total</td>');
		$('#tablaUnidadResponsable thead .anio').prepend('<th>Concepto general</th>');
		$('#tablaUnidadResponsableVariaciones tfoot tr').prepend('<td>Variación total</td>');
		$('#tablaUnidadResponsableVariaciones thead .anio').prepend('<th>Concepto general</th>');
	
		for (var j in resp.URs){
			var rowAnio='';
			var rowAnioAreas='';
			var rowVariaciones='';
			var TotalConcepto=0;
			for (i = 0; i < resp.versiones.length; i++){
				var porcentaje=0;
				if(resp.resumen[resp.versiones[i].Id][resp.URs[j].Id]){
					if(resp.resumen[resp.versiones[i].Id][resp.URs[j].Id].Monto>0){
						porcentaje=resp.resumen[resp.versiones[i].Id][resp.URs[j].Id].Monto/totalesVersiones[resp.versiones[i].Id]*100;
						porcentaje=porcentaje.toFixed(1);
						TotalConcepto+=resp.resumen[resp.versiones[i].Id][resp.URs[j].Id].Monto;
					}
					rowAnio='<td class="text-right">$'+number_format(resp.resumen[resp.versiones[i].Id][resp.URs[j].Id].Monto)+'</td>'+rowAnio;
					rowAnioAreas='<td class="text-right">'+porcentaje+'%</td>'+rowAnioAreas;
					if(i < resp.versiones.length - 1 ){
						if(resp.resumen[resp.versiones[i+1].Id][resp.URs[j].Id]){
							var variacion=(resp.resumen[resp.versiones[i].Id][resp.URs[j].Id].Monto-resp.resumen[resp.versiones[i+1].Id][resp.URs[j].Id].Monto)/resp.resumen[resp.versiones[i+1].Id][resp.URs[j].Id].Monto*100;
							rowVariaciones='<td class="text-right">'+variacion.toFixed(1)+'%</td>'+rowVariaciones;
						}else{
							rowVariaciones='<td></td>'+rowVariaciones;
						}
					}else{
						rowVariaciones='<td></td>'+rowVariaciones;
					}
				}else{
					rowAnio='<td></td>'+rowAnio;
					rowAnioAreas='<td></td>'+rowAnioAreas;
					rowVariaciones='<td></td>'+rowVariaciones;
				}
			}
			if(TotalConcepto>0){
				$('#tablaUnidadResponsable tbody').append('<tr><td>'+resp.URs[j].Clave+' - '+resp.URs[j].Nombre+' <a href="/'+$('#paramCodigoEstado').val()+'/ur/'+resp.URs[j].Clave+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>'+rowAnio+'</tr>');
				$('#tablaUnidadResponsableVariaciones tbody').append('<tr><td>'+resp.URs[j].Clave+' - '+resp.URs[j].Nombre+' <a href="/'+$('#paramCodigoEstado').val()+'/ur/'+resp.URs[j].Clave+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>'+rowVariaciones+'</tr>');
			}
		}
	
		for (var i in resp.URs){
			var DATASET=new Object();
			DATASET.label=resp.URs[i].Clave+" "+resp.URs[i].Nombre
			DATASET.borderColor=_coloresTableau20(i);
			DATASET.backgroundColor=_coloresTableau20(i);
			DATASET.data=new Array();
			for (j = 0; j < resp.versiones.length; j++){
				if(resp.resumen[resp.versiones[j].Id]){
					if(resp.resumen[resp.versiones[j].Id][resp.URs[i].Id]){
						DATASET.data.unshift(resp.resumen[resp.versiones[j].Id][resp.URs[i].Id].Monto);
					}else{
						DATASET.data.unshift(0);
					}
				}else{
					DATASET.data.unshift(0);
				}
			}
			DATA_CG.push(DATASET);
		}
		
		var $presupuestoUnidadResponsable = jQuery('.chart-area-historico-ur');
		if ($presupuestoUnidadResponsable.length) {
			$presupuestoUnidadResponsable.each(function(i){
				var config = {
					type: 'line',
					data: {
						labels: ANIOS_HIST,
						datasets: DATA_CG
					},
					options: {
						chartArea: {
							backgroundColor: 'rgba(100, 100, 100, 0.02)',
						},
						plugins: {
							legend: {
								display: false
							}
						}
						
					}
				};
		
				var canvas = jQuery(this)[0].getContext("2d");;
				new Chart(canvas, config);
			});
		}
	},"json");
	
	showTable($(".tablasCG .tiposTabla button").eq(0));
	showTableUR($(".tablasUR .tiposTabla button").eq(0));
});

function showTable(Obj){
	$("#contenedorTablas").attr("data-tabla",$(Obj).attr("data-tabla"));
	$(".tablasCG .tiposTabla button").removeClass("active");
	$(Obj).addClass("active");
}

function showTableUR(Obj){
	$("#contenedorTablasUR").attr("data-tabla",$(Obj).attr("data-tabla"));
	$(".tablasUR .tiposTabla button").removeClass("active");
	$(Obj).addClass("active");
}

function changePresupuesto(){
	window.location.href='/'+$('#paramCodigoEstado').val()+"/ConceptosGenerales/"+$('#paramCodigoConceptoGeneral').val()+"?v="+$("#visualizarAnio").val()+"&i="+$("#valoresAnio").val();
}