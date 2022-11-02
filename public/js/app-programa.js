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
			ANIOS_HIST.unshift(resp.versiones[i].Anio+" "+resp.versiones[i].Nombre)
			if(resp.resumen[resp.versiones[i].Id]){
				DATA_HIST.unshift(resp.resumen[resp.versiones[i].Id]);
			}else{
				DATA_HIST.unshift(0)
			}
		}
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