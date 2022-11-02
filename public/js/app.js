var aniosFed=[2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023];

$(document).ready(function() {
	$("header").toggleClass('active-slide-side-header');
	setTimeout(function(){
		chartPresupuestoFederal();
		chartGastoFederalizado();
		chartGastoFederalizadoComposicion();
	},500);
});

const canvasPresupuestoFederal = document.getElementById('canvas-chart-line-presupuesto-federal').getContext('2d');
var chartPresupuestoFederal;

function chartPresupuestoFederal(){
	var labels=new Array();
	var valores=new Array();
	var data=new Array();
	
	labels=aniosFed;
	valores=[6734.2,7284,7433.7,7020,6857.7,7038.1,7520.7,7465.5,7139.4,7442.7,8299.6];
	chartPresupuestoFederal = new Chart(canvasPresupuestoFederal, {
		type: 'line',
		data: {
			labels: labels,
			datasets: [{
				label: 'Total',
				data: valores,
				backgroundColor: [
					_colores_a[1]
				],
				borderColor: [
					_colores[1]
				],
				borderWidth: 1,
				fill: true,
				tension: 0
			}]
		},
		options: {
			legend: {
				display: true,
				usePointStyle: true
			},
			scales: {
				x: {
					thicks: {
						stepSize: 12
					}
				}
			}
		}
	});
}


const canvasGastoFederalizado = document.getElementById('canvas-chart-line-gasto-federalizado-historico').getContext('2d');
var chartGastoFederalizado;

function chartGastoFederalizado(){
	var labels=new Array();
	var valores=new Array();
	var data=new Array();
	
	labels=aniosFed;
	chartGastoFederalizado = new Chart(canvasGastoFederalizado, {
		type: 'line',
		data: {
			labels: labels,
			datasets: [{
				label: 'Participaciones',
				data: [914.2,942.8,961.3,1000.2,1041.6,1082.4,1184.9,1163,1044.9,1070.5,1220.3],
				backgroundColor: [
					_colores_a[1]
				],
				borderColor: [
					_colores[1]
				],
				borderWidth: 1,
				fill: false,
				tension: 0
			},
			{
				label: 'Aportaciones',
				data: [1048,1088.4,1133.8,1093.3,1082.6,1079.8,1017.5,1087.4,948.1,935.1,997.1],
				backgroundColor: [
					_colores_a[2]
				],
				borderColor: [
					_colores[2]
				],
				borderWidth: 1,
				fill: false,
				tension: 0
			},
			{
				label: 'Convenios',
				data: [200.7,238.7,262.6,219.1,169.1,166.6,154.1,154.7,114.2,116.9,122.6],
				backgroundColor: [
					_colores_a[3]
				],
				borderColor: [
					_colores[3]
				],
				borderWidth: 1,
				fill: false,
				tension: 0
			},
			{
				label: 'Subsidios (Ramo 23)',
				data: [66.9,117,115.2,98.2,69.8,59.8,17.3,18.6,10.4,10.4,10.9],
				backgroundColor: [
					_colores_a[4]
				],
				borderColor: [
					_colores[4]
				],
				borderWidth: 1,
				fill: false,
				tension: 0
			}]
		},
		options: {
			legend: {
				display: true,
				usePointStyle: true
			},
			scales: {
				x: {
					thicks: {
						stepSize: 12
					}
				}
			}
		}
	});
}

const canvasGastoFederalizadoComposicion = document.getElementById('canvas-chart-donut-gasto-federalizado-composicion').getContext('2d');
var chartGastoFederalizadoComposicion;

function chartGastoFederalizadoComposicion(){
	var labels=new Array();
	var valores=new Array();
	var data=new Array();
	
	labels=["Participaciones","Aportaciones","Convenios","Subsidios (Ramo 23)"];
	chartGastoFederalizadoComposicion = new Chart(canvasGastoFederalizadoComposicion, {
		type: 'doughnut',
		data: {
			labels: labels,
			datasets: [{
				label: 'Gasto federalizado',
				data: [1220.3,997.1,122.6,10.9],
				backgroundColor: [
					_colores_a[1], _colores_a[2], _colores_a[3], _colores_a[4]
				],
				borderColor: [
					_colores[1], _colores[2], _colores[3], _colores[4]
				],
				borderWidth: 1,
				fill: false,
				tension: 0
			}]
		},
		options: {
			legend: {
				display: true,
				usePointStyle: true
			}
		}
	});
}