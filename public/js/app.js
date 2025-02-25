var aniosFed=[2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023,2024,2025];

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
	valores=[6703.2,7252.8,7384.3,7065.4,6801.9,6982.6,7404.8,7392.9,7288.6,7710,8639.9,9066,8952.9];
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
				data: [910,938.8,955,1006.7,1033.1,1073.8,1166.7,1151.7,1066.7,1108.9,1270.3,1262.8,1289.9],
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
				data: [1043.2,1083.7,1126.2,1100.3,1073.8,1071.3,1001.8,1076.9,968,968.7,1038,1032.5],
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
				data: [199.8,237.7,260.8,220.5,167.8,165.3,151.8,153.2,116.6,121.1,127.6,131.6],
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
				data: [66.6,116.5,114.4,98.8,69.2,59.3,17,18.4,10.6,10.8,11.3,9.6],
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
				data: [1262.80,1032.50,131.60,9.6],
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