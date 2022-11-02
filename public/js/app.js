var aniosFed=[2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022];

$(document).ready(function() {
	chartPresupuestoFederal();
	chartGastoFederalizado();
	chartGastoFederalizadoComposicion();
});

const canvasPresupuestoFederal = document.getElementById('canvas-chart-line-presupuesto-federal').getContext('2d');
var chartPresupuestoFederal;

function chartPresupuestoFederal(){
	var labels=new Array();
	var valores=new Array();
	var data=new Array();
	
	labels=aniosFed;
	valores=[5942.2,6427.3,6559.5,6194.1,6052.2,6206.4,6670.6,6700.2,6544.3,7088.3];
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
				data: [806.7,831.9,848.3,882.5,919.3,954.5,1051,1043.7,957.8,1019.5],
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
				data: [924.7,960.4,1000.4,964.6,955.5,952.2,902.5,975.9,869.1,890.5],
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
				data: [177.1,210.6,231.7,193.3,149.3,146.9,136.7,138.8,104.7,111.4],
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
				data: [59,103.2,101.7,86.6,61.6,52.7,15.3,16.7,9.5,9.9],
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
				data: [1019.5,890.5,111.4,9.9],
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