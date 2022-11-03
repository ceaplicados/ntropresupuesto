$(document).ready(function(){
	$("header").toggleClass('active-slide-side-header');
	
	var params=new Object()
	params.action="getHistoricoPP"
	params.PP=$("#paramPP").val()
	params.INPC=$("#paramINPC").val()
	$.post("/backend",params,function(resp){
		var ANIOS_HIST=new Array();
		var DATA_HIST=new Array();
		for (i = 0; i < resp.versiones.length; i++){
			ANIOS_HIST.unshift(resp.versiones[i].Anio+" "+resp.versiones[i].Nombre);
			$('#tablaHistorico thead tr').prepend('<th>'+resp.versiones[i].Anio+'</th>');
			if(resp.resumen[resp.versiones[i].Id]){
				DATA_HIST.unshift(resp.resumen[resp.versiones[i].Id]);
				$('#tablaHistorico tbody tr.montos').prepend('<td class="text-right">$ '+number_format(resp.resumen[resp.versiones[i].Id],0)+'</td>');
			}else{
				DATA_HIST.unshift(0);
				$('#tablaHistorico tbody tr.montos').prepend('<td>-</td>');
			}
			if(i < resp.versiones.length-1){
				if(resp.resumen[resp.versiones[i+1].Id]>0){
					var incremento=(resp.resumen[resp.versiones[i].Id]-resp.resumen[resp.versiones[i+1].Id])/resp.resumen[resp.versiones[i+1].Id]*100;
					$('#tablaHistorico tbody tr.incremento').prepend('<td class="text-right">'+incremento.toFixed(1)+'%</td>');
				}else{
					$('#tablaHistorico tbody tr.incremento').prepend('<td>-</td>');
				}
			}else{
				$('#tablaHistorico tbody tr.incremento').prepend('<td>-</td>');
			}
			if(resp.versiones[i].Id==$('#paramVersion').val()){
				$('#montoTotal').html(number_format(resp.resumen[resp.versiones[i].Id]));
			}
		}
		$('#tablaHistorico tbody tr.montos').prepend('<td>Presupuesto</td>');
		$('#tablaHistorico tbody tr.incremento').prepend('<td>Variación</td>');
		$('#tablaHistorico thead tr').prepend('<th></th>');
		var $presupuestoFederalTotal = jQuery('.canvas-chart-line-programa-historico');
		if ($presupuestoFederalTotal.length) {
			$presupuestoFederalTotal.each(function(i){
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
						}, 
						//put new dataset here if needed to show multiple datasets on one graph
						]
					},
					options: {
						chartArea: {
							backgroundColor: 'rgba(100, 100, 100, 0.02)',
						},
						
					}
				};
	
				var canvas = jQuery(this)[0].getContext("2d");;
				new Chart(canvas, config);
			});
		} 
	},"json")
})

function changePresupuesto(){
	window.location.href="app-pp?p="+$("#paramPP").val()+"&v="+$("#visualizarAnio").val()+"&i="+$("#valoresAnio").val();
}