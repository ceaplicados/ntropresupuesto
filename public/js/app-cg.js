$(document).ready(function(){
	$("header").toggleClass('active-slide-side-header');
	var params=new Object()
	params.action="getHistoricoCG";
	params.INPC=$("#paramINPC").val();
	params.Estado=$("#paramEstado").val();
	$.post("/backend",params,function(resp){
		var ANIOS_HIST=new Array();
		var DATA_HIST=new Array();
		var DATA_DIST_HIST=new Array();
		var DATA_VARIACION_HIST=new Array();
		var DATA_CG=new Array();
		var totalesVersiones=new Array();
		for (i = 0; i < resp.versiones.length; i++){
			ANIOS_HIST.unshift(resp.versiones[i].Anio+" "+resp.versiones[i].Nombre);
			$('#tablaHistorico thead .anio').prepend('<th class="text-center">'+resp.versiones[i].Anio+" "+resp.versiones[i].Nombre+' <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>');
			$('#tablaDistribucionHistorica thead .anio').prepend('<th class="text-center">'+resp.versiones[i].Anio+" "+resp.versiones[i].Nombre+' <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>');
			$('#tablaVariaciones thead .anio').prepend('<th class="text-center">'+resp.versiones[i].Anio+" "+resp.versiones[i].Nombre+' <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>');
			var DataYearCP=new Array();
			var total = 0;
			if(resp.resumen[resp.versiones[i].Id]){
				for (let j = 0; j < resp.capitulos.length; j++) {
					if(resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id]){
						total+=resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto;
					}
				}
			}
			totalesVersiones[resp.versiones[i].Id]=total;
			if(resp.versiones[i].Id==$('#paramVersion').val()){
				$('#montoTotal').html(number_format(total));
			}
			DATA_HIST.unshift(total);
			$('#tablaHistorico tfoot tr').prepend('<td class="text-right">$ '+number_format(total,0)+'</td>');
			$('#tablaDistribucionHistorica tfoot tr').prepend('<td class="text-right">100%</td>');
		}
		for (i = 0; i < resp.versiones.length; i++){
			if(i < resp.versiones.length -1){
				var variacion=(totalesVersiones[resp.versiones[i].Id]-totalesVersiones[resp.versiones[i+1].Id])/totalesVersiones[resp.versiones[i+1].Id]*100;
				$('#tablaVariaciones tfoot tr').prepend('<td class="text-right">'+variacion.toFixed(1)+'%</td>');
			}else{
				$('#tablaVariaciones tfoot tr').prepend('<td class="text-right"></td>');
			}
		}
		$('#tablaHistorico tfoot tr').prepend('<td>Total</td>');
		$('#tablaHistorico thead .anio').prepend('<th>Capítulo de gasto <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>');
		$('#tablaDistribucionHistorica tfoot tr').prepend('<td>Total</td>');
		$('#tablaDistribucionHistorica thead .anio').prepend('<th>Capítulo de gasto <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>');
		$('#tablaVariaciones tfoot tr').prepend('<td>Variación total</td>');
		$('#tablaVariaciones thead .anio').prepend('<th>Capítulo de gasto <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>');

		for (let j = 0; j < resp.capitulos.length; j++) {
			var rowAnio='';
			var rowAnioAreas='';
			var rowVariaciones='';
			var TotalCapitulo=0;
			for (i = 0; i < resp.versiones.length; i++){
				var porcentaje=0;
				if(resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id]){
					if(resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto>0){
						porcentaje=resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto/totalesVersiones[resp.versiones[i].Id]*100;
						porcentaje=porcentaje.toFixed(1);
						TotalCapitulo+=resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto;
					}
					rowAnio='<td class="text-right">$'+number_format(resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto)+'</td>'+rowAnio;
					rowAnioAreas='<td class="text-right">'+porcentaje+'%</td>'+rowAnioAreas;
					if(i < resp.versiones.length - 1 ){
						if(resp.resumen[resp.versiones[i+1].Id][resp.capitulos[j].Id]){
							var variacion=(resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto-resp.resumen[resp.versiones[i+1].Id][resp.capitulos[j].Id].Monto)/resp.resumen[resp.versiones[i+1].Id][resp.capitulos[j].Id].Monto*100;
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
			if(TotalCapitulo>0){
				$('#tablaHistorico tbody').append('<tr><td>'+resp.capitulos[j].Clave+' - '+resp.capitulos[j].Nombre+' <a href="CapituloGasto/'+resp.capitulos[j].Clave+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>'+rowAnio+'</tr>');
				$('#tablaDistribucionHistorica tbody').append('<tr><td>'+resp.capitulos[j].Clave+' - '+resp.capitulos[j].Nombre+' <a href="CapituloGasto/'+resp.capitulos[j].Clave+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>'+rowAnioAreas+'</tr>');
				$('#tablaVariaciones tbody').append('<tr><td>'+resp.capitulos[j].Clave+' - '+resp.capitulos[j].Nombre+' <a href="CapituloGasto/'+resp.capitulos[j].Clave+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>'+rowVariaciones+'</tr>');
			}
		}

		for (let i = 0; i < resp.capitulos.length; i++) {
			var DATASET=new Object();
			DATASET.label=resp.capitulos[i].Clave+" "+resp.capitulos[i].Nombre
			DATASET.backgroundColor=_colores[i]
			DATASET.data=new Array();
			for (j = 0; j < resp.versiones.length; j++){
				if(resp.resumen[resp.versiones[j].Id]){
					if(resp.resumen[resp.versiones[j].Id][resp.capitulos[i].Id]){
						DATASET.data.unshift(resp.resumen[resp.versiones[j].Id][resp.capitulos[i].Id].Monto);
					}else{
						DATASET.data.unshift(0);
					}
				}else{
					DATASET.data.unshift(0);
				}
			}
			DATA_CG.push(DATASET);
			
			var DATASET=new Object();
			DATASET.label=resp.capitulos[i].Clave+" "+resp.capitulos[i].Nombre
			DATASET.backgroundColor=_colores[i];
			DATASET.fill=true;
			DATASET.data=new Array();
			for (j = 0; j < resp.versiones.length; j++){
				if(resp.resumen[resp.versiones[j].Id]){
					if(resp.resumen[resp.versiones[j].Id][resp.capitulos[i].Id]){
						var porcentaje=resp.resumen[resp.versiones[j].Id][resp.capitulos[i].Id].Monto/totalesVersiones[resp.versiones[j].Id]*100;
						DATASET.data.unshift(porcentaje.toFixed(1));
					}else{
						DATASET.data.unshift(0);
					}
				}else{
					DATASET.data.unshift(0);
				}
			}
			DATA_DIST_HIST.unshift(DATASET);
			
			var DATASET=new Object();
			DATASET.label=resp.capitulos[i].Clave+" "+resp.capitulos[i].Nombre
			DATASET.backgroundColor=_colores[i];
			DATASET.borderColor=_colores[i];
			DATASET.data=new Array();
			for (j = 0; j < resp.versiones.length; j++){
				if(j < resp.versiones.length-1){
					if(resp.resumen[resp.versiones[j].Id]){
						if(resp.resumen[resp.versiones[j].Id][resp.capitulos[i].Id]){
							if(resp.resumen[resp.versiones[j+1].Id][resp.capitulos[i].Id]){
								var porcentaje=(resp.resumen[resp.versiones[j].Id][resp.capitulos[i].Id].Monto-resp.resumen[resp.versiones[j+1].Id][resp.capitulos[i].Id].Monto)/resp.resumen[resp.versiones[j+1].Id][resp.capitulos[i].Id].Monto*100;
								DATASET.data.unshift(porcentaje.toFixed(1));
							}else{
								DATASET.data.unshift(0);
							}
						}else{
							DATASET.data.unshift(0);
						}
					}else{
						DATASET.data.unshift(0);
					}
				}else{
					DATASET.data.unshift(0);
				}
				
			}
			DATA_VARIACION_HIST.unshift(DATASET);
		}
		
		var $presupuestoCapituloGasto = jQuery('.chart-bar-historico-ur');
		if ($presupuestoCapituloGasto.length) {
			$presupuestoCapituloGasto.each(function(i){
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
		
		var $distribucionCapituloGasto = jQuery('.chart-area-historico-ur');
		if ($distribucionCapituloGasto.length) {
			$distribucionCapituloGasto.each(function(i){
				var config = {
					type: 'line',
					data: {
						labels: ANIOS_HIST,
						datasets: DATA_DIST_HIST
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
								stacked: true,
								max: 100
							}
						}
					}
				};
		
				var canvas = jQuery(this)[0].getContext("2d");;
				new Chart(canvas, config);
			});
		}
		
		var $variacionCapituloGasto = jQuery('.chart-variacion-cg');
		if ($variacionCapituloGasto.length) {
			$variacionCapituloGasto.each(function(i){
				var config = {
					type: 'line',
					data: {
						labels: ANIOS_HIST,
						datasets: DATA_VARIACION_HIST
					},
					options: {
						chartArea: {
							backgroundColor: 'rgba(100, 100, 100, 0.02)',
						},
						scales: {
							
						}
					}
				};
		
				var canvas = jQuery(this)[0].getContext("2d");;
				new Chart(canvas, config);
			});
		}
		listenReorder();
	},"json");
	showGraph($(".tiposGrafica button").eq(0));
});

function showGraph(Obj){
	$("#contenedorGraficas").attr("data-graph",$(Obj).attr("data-target"));
	$("#contenedorTablas").attr("data-tabla",$(Obj).attr("data-tabla"));
	$(".tiposGrafica button").removeClass("active");
	$(Obj).addClass("active");
}

function changePresupuesto(){
	window.location.href='/'+$('#paramCodigoEstado').val()+"/CapituloGasto?v="+$("#visualizarAnio").val()+"&i="+$("#valoresAnio").val();
}