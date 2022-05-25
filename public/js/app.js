_colores=new Array();
_colores[0]="#BFCA4D";
_colores[1]="#8CB4C1";
_colores[2]="#95AB82";
_colores[3]="#BD905B";
_colores[4]="#D95B5B";
_colores[5]="#8162B4";

"use strict";

(function(){
	
	//Charts initialization
	//http://www.chartjs.org/docs/

		//Global Defaults
			//fonts
	Chart.defaults.global.defaultFontColor = '#666666';
	Chart.defaults.global.defaultFontFamily = 'Poppins, Arial, sans-serif';
	Chart.defaults.global.defaultFontSize = 12;
			//responsive
	Chart.defaults.global.maintainAspectRatio = false;

			//legends
	Chart.defaults.global.legend.labels.usePointStyle = true;

			//scale
	Chart.defaults.scale.gridLines.color = 'rgba(100,100,100,0.15)';
	Chart.defaults.scale.gridLines.zeroLineColor = 'rgba(100,100,100,0.15)';
	// Chart.defaults.scale.gridLines.drawTicks = false;
	
	// Chart.defaults.scale.ticks.min = 0;
	Chart.defaults.scale.ticks.beginAtZero = true;
	Chart.defaults.scale.ticks.maxRotation = 0;

		//padding for Y axes
	Chart.defaults.scale.ticks.padding = 3;
	Chart.defaults.scale.ticks.autoSkipPadding = 10;

		//points
	Chart.defaults.global.elements.point.radius = 5;
	Chart.defaults.global.elements.point.borderColor = 'transparent';


		//custom Chart plugin for set a background to chart
	Chart.pluginService.register({
	beforeDraw: function (chart, easing) {
		if (chart.config.options.chartArea && chart.config.options.chartArea.backgroundColor) {
			var ctx = chart.chart.ctx;
			var chartArea = chart.chartArea;
				ctx.save();
				ctx.fillStyle = chart.config.options.chartArea.backgroundColor;
				ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
				ctx.restore();
			}
		}
	});
			
	var ANIOS_FED = [2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022];

	//presupuesto-federal
	var $presupuestoFederalTotal = jQuery('.canvas-chart-line-presupuesto-federal');
	if ($presupuestoFederalTotal.length) {
		$presupuestoFederalTotal.each(function(i){
			var config = {
				type: 'line',
				data: {
					labels: ANIOS_FED,
					datasets: [{
						label: "Total",
						backgroundColor: 'rgba(77, 177, 158, 0.5)',
						borderColor: 'rgba(77, 177, 158, 0.5)',
						borderWidth: '0',
						//point options
						pointBorderColor: "transparent",
						pointBackgroundColor: "rgba(77, 177, 158, 1)",
						pointBorderWidth: 0,
						tension: '0',
						//visitors per month
						data: [5942.2,6427.3,6559.5,6194.1,6052.2,6206.4,6670.6,6700.2,6544.3,7088.3],
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
	} //presupuesto-federal

	// Gasto federalizado historico
	var $canvasesGastoFederalizadoHistorico = jQuery('.canvas-chart-line-gasto-federalizado-historico');
	if ($canvasesGastoFederalizadoHistorico.length) {
		$canvasesGastoFederalizadoHistorico.each(function(i){

			var config = {
				type: 'line',
				data: {
					labels: ANIOS_FED,
					datasets: [{
						label: "Participaciones",
						backgroundColor: 'rgba(236, 104, 46, 0.5)',
						borderColor: 'rgba(236, 104, 46, 0.5)',
						borderWidth: 3,

						//point options
						pointBorderColor: "transparent",
						pointBackgroundColor: "rgba(236, 104, 46, 1)",
						pointBorderWidth: 0,

						tension: '0',
						//visitors per month
						data: [806.7,831.9,848.3,882.5,919.3,954.5,1051,1043.7,957.8,1019.5],
						fill: false,
					}, 
					{
						label: "Aportaciones",
						backgroundColor: 'rgba(111, 211, 227, 0.6)',
						borderColor: 'rgba(91, 173, 186, 0.5)',
						borderWidth: 3,

						//point options
						pointBorderColor: "transparent",
						pointBackgroundColor: "rgba(111, 211, 227, 1)",
						pointBorderWidth: 0,

						tension: '0',
						//sells per month
						data: [924.7,960.4,1000.4,964.6,955.5,952.2,902.5,975.9,869.1,890.5],
						fill: false,
					}, 
					{
						label: "Convenios",
						backgroundColor: 'rgba(129, 98, 180, 0.6)',
						borderColor: 'rgba(129, 98, 180, 0.5)',
						borderWidth: 3,

						//point options
						pointBorderColor: "transparent",
						pointBackgroundColor: "rgba(129, 98, 180, 1)",
						pointBorderWidth: 0,

						tension: '0',
						//sells per month
						data: [177.1,210.6,231.7,193.3,149.3,146.9,136.7,138.8,104.7,111.4],
						fill: false,
					}, 
					{
						label: "Subsidios (Ramo 23)",
						backgroundColor: 'rgba(149, 171, 130, 0.6)',
						borderColor: 'rgba(149, 171, 130, 0.5)',
						borderWidth: 3,

						//point options
						pointBorderColor: "transparent",
						pointBackgroundColor: "rgba(149, 171, 130, 1)",
						pointBorderWidth: 0,

						tension: '0',
						//sells per month
						data: [59,103.2,101.7,86.6,61.6,52.7,15.3,16.7,9.5,9.9],
						fill: false,
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
	// Dona gasto federalizado
	var $canvasesGastoFederalizadoComposicion = jQuery('.canvas-chart-donut-gasto-federalizado-composicion');
	if ($canvasesGastoFederalizadoComposicion.length) {
		$canvasesGastoFederalizadoComposicion.each(function(i){
			var config = {
				type: 'doughnut',
				data: {
					labels: ["Participaciones","Aportaciones","Convenios","Subsidios (Ramo 23)"],
					datasets: [{
						label: "Gasto federalizado",
						//line options
						backgroundColor: ['rgba(191, 202, 77, 1)','rgba(140, 180, 193, 1)','rgba(149,171,130,1)','rgba(189,144,91,1)'],
						//point options
						//visitors per month
						data: [1019.5,890.5,111.4,9.9],
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
	} //dona gasto federalizado
	
	/////////////
	//sparkline//
	/////////////
	//http://omnipotent.net/jquery.sparkline/
	jQuery('.sparklines').each(function(){
		//sparkline type: 'line' (default), 'bar', 'tristate', 'discrete', 'bullet', 'pie', 'box'
		var $this = jQuery(this);
		var data = $this.data();
		
		var type = data.type ? data.type : 'bar';
		var lineColor = data.lineColor ? data.lineColor : '#4db19e';
		var negBarColor = data.negColor ? data.negColor : '#dc5753';
		var barWidth = data.barWidth ? data.barWidth : 4;
		var height = data.height ? data.height : false;
		
		var values = data.values ? JSON.parse("[" + data.values + "]") : false;
		
		$this.sparkline(values, {
			type: type,
			lineColor: lineColor,
			barColor: lineColor,
			negBarColor: negBarColor,
			barWidth: barWidth,
			height: height,
		});
	});


})();