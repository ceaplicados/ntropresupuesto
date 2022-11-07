$(document).ready(function(){
	$("header").toggleClass('active-slide-side-header');
	var params=new Object()
	params.action="getHistoricoUR";
	params.UR=$("#paramUR").val();
	params.INPC=$("#paramINPC").val();
	params.Estado=$("#paramEstado").val();
	$.post("/backend",params,function(resp){
		var ANIOS_HIST=new Array();
		var DATA_HIST=new Array();
		var DATA_CG=new Array();
		var totalesVersiones=new Array();
		for (i = 0; i < resp.versiones.length; i++){
			ANIOS_HIST.unshift(resp.versiones[i].Anio+" "+resp.versiones[i].Nombre);
			$('#tablaHistorico thead .anio').prepend('<th colspan="2" class="text-center">'+resp.versiones[i].Anio+" "+resp.versiones[i].Nombre+'</th>');
			$('#tablaHistorico thead .detalle').prepend('<th>$</th><th>%</th>');
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
			$('#tablaHistorico tfoot tr').prepend('<td colspan="2" class="text-right">$ '+number_format(total,0)+'</td>');
		}
		$('#tablaHistorico tfoot tr').prepend('<td>Total</td>');
		$('#tablaHistorico thead .anio').prepend('<th rowspan="2">Capítulo de gasto</th>');
		for (let j = 0; j < resp.capitulos.length; j++) {
			var rowAnio='';
			var TotalCapitulo=0;
			for (i = 0; i < resp.versiones.length; i++){
				if(resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id]){
					var porcentaje='';
					if(resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto>0){
						porcentaje=resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto/totalesVersiones[resp.versiones[i].Id]*100;
						porcentaje=porcentaje.toFixed(1)+'%';
						TotalCapitulo+=resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto;
					}
					rowAnio='<td class="text-right">$'+number_format(resp.resumen[resp.versiones[i].Id][resp.capitulos[j].Id].Monto)+'</td><td>'+porcentaje+'</td>'+rowAnio;
				}else{
					rowAnio='<td></td><td></td>'+rowAnio;
				}
			}
			if(TotalCapitulo>0){
				$('#tablaHistorico tbody').append('<tr><td>'+resp.capitulos[j].Clave+' - '+resp.capitulos[j].Nombre+'</td>'+rowAnio+'</tr>');
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
		}
		var $presupuestoTotal = jQuery('.chart-lineas-ur');
		if ($presupuestoTotal.length) {
			$presupuestoTotal.each(function(i){
				var config = {
					type: 'line',
					data: {
						labels: ANIOS_HIST,
						datasets: [{
							label: "A valores de "+$("#paramINPC").val(),
							backgroundColor: 'rgba(77, 177, 158, 0.5)',
							borderColor: 'rgba(77, 177, 158, 0.5)',
							borderWidth: '0',
							//point options
							pointBorderColor: "transparent",
							pointBackgroundColor: "rgba(77, 177, 158, 1)",
							pointBorderWidth: 0,
							tension: '0',
							//visitors per month
							data: DATA_HIST,
							fill: true,
						}
						]
					},
					options: {
						chartArea: {
							backgroundColor: 'rgba(100, 100, 100, 0.02)',
						},
						scales: {
							y: {
								min: 0
							}
						}
					}
				};
	
				var canvas = jQuery(this)[0].getContext("2d");;
				new Chart(canvas, config);
			});
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
								stacked: true
							}
						}
					}
				};
		
				var canvas = jQuery(this)[0].getContext("2d");;
				new Chart(canvas, config);
			});
		}
	},"json");
	var params=new Object()
	params.action="getURsUP"
	params.UR=$("#paramUR").val()	
	params.INPC=$("#paramINPC").val()
	params.Version=$("#paramVersion").val()
	$.post("/backend",params,function(resp){
		var donaLabels=new Array();
		var donaValores=new Array();
		$("#UnidadesResponsablesHermanas tbody").html("")
		var montoTotal=0
		for (i = 0; i < resp.length; i++){
			if(resp[i].Monto>0){
				donaLabels.push(resp[i].Nombre);
				donaValores.push(resp[i].Monto);
				montoTotal+=resp[i].Monto*1;
				$("#UnidadesResponsablesHermanas tbody").append('<tr data-monto="'+resp[i].Monto+'"><td>'+$("#claveUP").val()+"-"+resp[i].Clave+'</td><td>'+resp[i].Nombre+'</td><td class="monto">'+number_format(resp[i].Monto)+'</td><td class="porcentaje"></td><td><a href="app-ur?u='+resp[i].Id+'&i='+$("#paramINPC").val()+'&v='+$("#paramVersion").val()+'" target="_blank"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td></tr>')
			}
		}
		$("#UnidadesResponsablesHermanas tbody tr").each(function(){
			console.log($(this).attr("data-monto"))
			porcentaje=Math.round(parseFloat($(this).attr("data-monto"))/montoTotal*1000)/10;
			$(this).find(".porcentaje").html(porcentaje+"%")
		})
		var $canvasesUnidadPresupuestaria = jQuery('.canvas-chart-donut-unidad-presupuestaria');
		if ($canvasesUnidadPresupuestaria.length) {
			$canvasesUnidadPresupuestaria.each(function(i){
				var config = {
					type: 'doughnut',
					data: {
						labels: donaLabels,
						datasets: [{
							label: $("#nombreUP").val(),
							//line options
							backgroundColor: ['#BFCA4D','#8CB4C1','#95AB82','#BD905B','#D95B5B','#995ED4','#56CCF2','#F2C94C','#C71616'],
							//point options
							//visitors per month
							data: donaValores,
						}
						//put new dataset here if needed to show multiple datasets on one graph
						]
					},
					options: {
						chartArea: {
							backgroundColor: 'rgba(100, 100, 100, 0.02)',
						}
					}
				};
	
				var canvas = jQuery(this)[0].getContext("2d");;
				new Chart(canvas, config);
			});
		}
	},"json");
	
	$("#tablaProgramas tbody tr").each(function(){
		porcentaje=Math.round($(this).attr("data-monto")/$("#tablaProgramas tfoot tr.total").attr("data-monto")*1000)/10;
		$(this).find("td.porcentaje").html(porcentaje+"%")
	})
	showGraph($(".tiposGrafica button").eq(0));
});

function showGraph(Obj){
	$("#contenedorGraficas").attr("data-graph",$(Obj).attr("data-target"));
	$(".tiposGrafica button").removeClass("active");
	$(Obj).addClass("active");
}

function changePresupuesto(){
	window.location.href='/'+$('#paramCodigoEstado').val()+"/ur/"+$("#paramCodigoUR").val()+"?v="+$("#visualizarAnio").val()+"&i="+$("#valoresAnio").val();
}