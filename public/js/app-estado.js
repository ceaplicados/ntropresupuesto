var totalPresupuesto=0;
$(document).ready(function(){
	setTimeout(function(){
		$("header").toggleClass('active-slide-side-header');
	},1000)
	
	
	var params=new Object()
	params.action="getTotalesEstado";
	params.Estado=$("#paramEstado").val();
	params.INPC=$("#paramINPC").val();
	$.post("/backend",params,function(resp){
		var ANIOS_HIST=new Array();
		var DATA_HIST=new Array();
		for (i = 0; i < resp.length; i++){
			ANIOS_HIST.unshift(resp[i].Anio);
			DATA_HIST.unshift(resp[i].Monto);
			if(resp[i].Id==$('#paramVersion').val()){
				$('#montoTotal').html(number_format(resp[i].Monto));
			}
			var YoY='-';
			if(i < resp.length -1){
				YoY=(resp[i].Monto-resp[i+1].Monto)/resp[i+1].Monto*100;
				YoY=YoY.toFixed(1)+'%';
			}
			$('#tablaHistorico tbody').append('<tr data-anio="'+resp[i].Anio+'" data-monto="'+resp[i].Monto+'"><td>'+resp[i].Anio+' <span class="small">'+resp[i].Nombre+'</span></td><td class="text-right">$ '+number_format(resp[i].Monto)+'</td><td class="YoY text-right" >'+YoY+'</td></tr>');
		}
		var $presupuestoFederalTotal = jQuery('.canvas-chart-line-presupuesto-historico');
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
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var value = data.datasets[0].data[tooltipItem.index];
										value = value.toString();
										value = value.split(/(?=(?:...)*$)/);
										value = value.join(',');
										return value;
									},
								  }, 
							}, 
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero:true,
									userCallback: function(value, index, values) {
										value = value.toString();
										value = value.split(/(?=(?:...)*$)/);
										value = value.join(',');
										return value;
									}
								}
							}],
							xAxes: [{
								ticks: {
								},
							}]
						},
					},
				}
				var canvas = jQuery(this)[0].getContext("2d");
				new Chart(canvas, config);
			});
		} 
	},"json")
	
	var params=new Object()
	params.action="getByCapitulosGasto"
	params.Version=$("#paramVersion").val()
	params.Deflactor=$("#deflactor").val()
	$.post("/backend",params,function(resp){
		var donaLabels=new Array();
		var donaValores=new Array();
		var total=0;
		for (i = 0; i < resp.length; i++){
			donaLabels.push(resp[i].Nombre);
			donaValores.push(resp[i].Monto);
			total+=resp[i].Monto;
		}
		for (i = 0; i < resp.length; i++){
			var porcentaje=resp[i].Monto/total*100;
			$('#tablaOG tbody').append('<tr><td>'+resp[i].Clave+' - '+resp[i].Nombre+'  <a href="'+$('#paramCodigoEstado').val()+'/CapituloGasto/'+resp[i].Clave+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td><td class="text-right">$'+number_format(resp[i].Monto,0)+'</td><td class="text-right">'+porcentaje.toFixed(1)+'%</td></tr>')
		}
		var $canvasesCapituloGasto = jQuery('.canvas-chart-donut-capitulos-gasto');
		if ($canvasesCapituloGasto.length) {
			$canvasesCapituloGasto.each(function(i){
				var config = {
					type: 'doughnut',
					data: {
						labels: donaLabels,
						datasets: [{
							label: "Gasto federalizado",
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
	},"json")
	
	var params=new Object()
	params.action="getOGByUR"
	params.Version=$("#paramVersion").val()
	params.Deflactor=$("#deflactor").val()
	$.post("/backend",params,function(resp){
		$("#tablaMontos tbody").html("")
		var arrayURs=new Array()
		for (i = 0; i < resp.UR.length; i++){
			$("#tablaMontos tbody").append('<tr data-id="'+resp.UR[i].Id+'">'
			+'<td class="hidden">'+resp.UP[resp.UR[i].UnidadPresupuestal].Nombre+' </td>'
			+'<td class="clave">'+resp.UP[resp.UR[i].UnidadPresupuestal].Clave+'-'+resp.UR[i].Clave+'</td>'
			+'<td class="nombre">'+resp.UR[i].Nombre+' <a href="/'+$('#paramCodigoEstado').val()+'/ur/'+resp.UP[resp.UR[i].UnidadPresupuestal].Clave+'-'+resp.UR[i].Clave+'?i='+$("#paramINPC").val()+'&v='+$("#paramVersion").val()+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>'
			+'<td class="monto" data-monto="'+resp.UR[i].Monto+'">'+number_format(resp.UR[i].Monto)+'</td>'
			+'<td class="porcentaje"></td><td class="porcentajeFiltrado"></td>'
			+'</tr>');
			var objUR=new Object();
			objUR.UP=resp.UP[resp.UR[i].UnidadPresupuestal].Nombre;
			objUR.ClaveUP=resp.UP[resp.UR[i].UnidadPresupuestal].Clave;
			objUR.Clave=resp.UP[resp.UR[i].UnidadPresupuestal].Clave+'-'+resp.UR[i].Clave;
			objUR.Nombre=resp.UR[i].Nombre;
			objUR.Monto=resp.UR[i].Monto;
			arrayURs.push(objUR);
			totalPresupuesto+=resp.UR[i].Monto;
		}
		$("#tablaMontos tfoot tr.total td.monto").html(number_format(totalPresupuesto));
		$("#tablaMontos tfoot tr.filtrado").hide()
		$("#tablaMontos .porcentajeFiltrado").hide()
		updatePorcentajeTabla();
		
		nivel1=new Array();
		nivel2=new Array();
		totalNivel2=0;
		for (i = 0; i < arrayURs.length; i++){
			if(arrayURs[i].Monto>totalPresupuesto*.01){
				nivel1.push(arrayURs[i]);
			}else{
				nivel2.push(arrayURs[i]);
				totalNivel2+=arrayURs[i].Monto;
			}
		}
		/* [
			{"key": "Afghanistan", // UR
			"region": "Asia", // UP
			"subregion": "Southern Asia", // UR
			"value": 25500100}]
		*/
		var objTreeMap=new Array();
		for (i = 0; i < nivel1.length; i++){
			var obj=new Object();
			obj.key=nivel1[i].Clave+" "+nivel1[i].Nombre
			obj.region=nivel1[i].Clave+" "+nivel1[i].Nombre
			obj.subregion=nivel1[i].Clave+" "+nivel1[i].Nombre
			obj.value=nivel1[i].Monto
			objTreeMap.push(obj);
		}
		for (i = 0; i < nivel2.length; i++){
			var obj=new Object();
			obj.key=nivel2[i].Clave+" "+nivel2[i].Nombre
			obj.region="Otros < 1%"
			obj.subregion=nivel2[i].Clave+" "+nivel2[i].Nombre
			obj.value=nivel2[i].Monto
			objTreeMap.push(obj);
		}
		doTreemap(objTreeMap)
	},"json")
	
})

function changePresupuesto(){
	window.location.href="/"+$("#paramCodigoEstado").val()+"?v="+$("#visualizarAnio").val()+"&i="+$("#valoresAnio").val();
}
function updatePorcentajeTabla(){
	var totalFiltrado=0
	$("#tablaMontos tbody tr").each(function(){
		porcentaje=Math.round(parseFloat($(this).find(".monto").attr("data-monto"))/totalPresupuesto*10000)/100;
		$(this).find(".porcentaje").attr("data-total",porcentaje);
		$(this).find(".porcentaje").html(number_format(porcentaje)+"%");
		if(!$(this).hasClass("hide")){
			totalFiltrado+=parseFloat($(this).find(".monto").attr("data-monto"))
		}
	})
	$("#tablaMontos tbody tr").each(function(){
		if(!$(this).hasClass("hide")){
			porcentaje=Math.round(parseFloat($(this).find(".monto").attr("data-monto"))/totalFiltrado*1000)/10;
			$(this).find(".porcentajeFiltrado").attr("data-total",porcentaje);
			$(this).find(".porcentajeFiltrado").html(number_format(porcentaje)+"%");
		}
	})
	$("#tablaMontos tfoot tr.filtrado td.monto").html(number_format(totalFiltrado));
	$("#tablaMontos tfoot tr.filtrado td.porcentaje").html(number_format(totalFiltrado/totalPresupuesto*100)+"%");
	$("#tablaMontos tfoot tr.filtrado td.porcentajeFiltrado").html("100%");
}
function filtrarTabla(){
	if($("#busquedaTabla").val().length==0){
		$("#tablaMontos tbody tr").removeClass("hide")
		$("#tablaMontos tfoot tr.filtrado").hide()
		$("#tablaMontos .porcentajeFiltrado").hide()
	}else{
		$("#tablaMontos tbody tr").addClass("hide")
		$("#tablaMontos tbody tr").each(function(){
			if($(this).text().toUpperCase().indexOf($("#busquedaTabla").val().toUpperCase())>=0){
				$(this).removeClass("hide")
			}
		})
		$("#tablaMontos tfoot tr.filtrado").show()
		$("#tablaMontos .porcentajeFiltrado").show()
		updatePorcentajeTabla()
	}
}

// Treemap (http://bl.ocks.org/ganeshv/6a8e9ada3ab7f2d88022)
window.addEventListener('message', function(e) {
	var opts = e.data.opts,
		data = e.data.data;

	return main(opts, data);
});

var defaults = {
	margin: {top: 24, right: 0, bottom: 0, left: 0},
	rootname: "TOP",
	format: ",d",
	title: "",
	width: $("#chart").width(),
	height: $("#chart").height()
};

function main(o, data) {
  var root,
	  opts = $.extend(true, {}, defaults, o),
	  formatNumber = d3.format(opts.format),
	  rname = opts.rootname,
	  margin = opts.margin,
	  theight = 36 + 16;

  $('#chart').width(opts.width).height(opts.height);
  var width = opts.width - margin.left - margin.right,
	  height = opts.height - margin.top - margin.bottom - theight,
	  transitioning;
  
  var color = d3.scale.category20c();
  
  var x = d3.scale.linear()
	  .domain([0, width])
	  .range([0, width]);
  
  var y = d3.scale.linear()
	  .domain([0, height])
	  .range([0, height]);
  
  var treemap = d3.layout.treemap()
	  .children(function(d, depth) { return depth ? null : d._children; })
	  .sort(function(a, b) { return a.value - b.value; })
	  .ratio(height / width * 0.5 * (1 + Math.sqrt(5)))
	  .round(false);
  
  var svg = d3.select("#chart").append("svg")
	  .attr("width", width + margin.left + margin.right)
	  .attr("height", height + margin.bottom + margin.top)
	  .style("margin-left", -margin.left + "px")
	  .style("margin.right", -margin.right + "px")
	.append("g")
	  .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
	  .style("shape-rendering", "crispEdges");
  
  var grandparent = svg.append("g")
	  .attr("class", "grandparent");
  
  grandparent.append("rect")
	  .attr("y", -margin.top)
	  .attr("width", width)
	  .attr("height", margin.top);
  
  grandparent.append("text")
	  .attr("x", 6)
	  .attr("y", 6 - margin.top)
	  .attr("dy", ".75em");

  if (opts.title) {
	$("#chart").prepend("<p class='title'>" + opts.title + "</p>");
  }
  if (data instanceof Array) {
	root = { key: rname, values: data };
  } else {
	root = data;
  }
	
  initialize(root);
  accumulate(root);
  layout(root);
  console.log(root);
  display(root);

  if (window.parent !== window) {
	var myheight = document.documentElement.scrollHeight || document.body.scrollHeight;
	window.parent.postMessage({height: myheight}, '*');
  }

  function initialize(root) {
	root.x = root.y = 0;
	root.dx = width;
	root.dy = height;
	root.depth = 0;
  }

  // Aggregate the values for internal nodes. This is normally done by the
  // treemap layout, but not here because of our custom implementation.
  // We also take a snapshot of the original children (_children) to avoid
  // the children being overwritten when when layout is computed.
  function accumulate(d) {
	return (d._children = d.values)
		? d.value = d.values.reduce(function(p, v) { return p + accumulate(v); }, 0)
		: d.value;
  }

  // Compute the treemap layout recursively such that each group of siblings
  // uses the same size (1×1) rather than the dimensions of the parent cell.
  // This optimizes the layout for the current zoom state. Note that a wrapper
  // object is created for the parent node for each group of siblings so that
  // the parent’s dimensions are not discarded as we recurse. Since each group
  // of sibling was laid out in 1×1, we must rescale to fit using absolute
  // coordinates. This lets us use a viewport to zoom.
  function layout(d) {
	if (d._children) {
	  treemap.nodes({_children: d._children});
	  d._children.forEach(function(c) {
		c.x = d.x + c.x * d.dx;
		c.y = d.y + c.y * d.dy;
		c.dx *= d.dx;
		c.dy *= d.dy;
		c.parent = d;
		layout(c);
	  });
	}
  }

  function display(d) {
	grandparent
		.datum(d.parent)
		.on("click", transition)
	  .select("text")
		.text(name(d));

	var g1 = svg.insert("g", ".grandparent")
		.datum(d)
		.attr("class", "depth");

	var g = g1.selectAll("g")
		.data(d._children)
	  .enter().append("g");

	g.filter(function(d) { return d._children; })
		.classed("children", true)
		.on("click", transition);

	var children = g.selectAll(".child")
		.data(function(d) { return d._children || [d]; })
	  .enter().append("g");

	children.append("rect")
		.attr("class", "child")
		.call(rect)
	  .append("title")
		.text(function(d) { return d.key + " (" + formatNumber(d.value) + ")"; });
	children.append("text")
		.attr("class", "ctext")
		.text(function(d) { return d.key; })
		.call(text2);

	g.append("rect")
		.attr("class", "parent")
		.call(rect);

	var t = g.append("text")
		.attr("class", "ptext")
		.attr("dy", ".75em")

	t.append("tspan")
		.text(function(d) { return d.key; });
	t.append("tspan")
		.attr("dy", "1.0em")
		.text(function(d) { return formatNumber(d.value); });
	t.call(text);

	g.selectAll("rect")
		.style("fill", function(d) { return color(d.key); });

	function transition(d) {
	  if (transitioning || !d) return;
	  transitioning = true;

	  var g2 = display(d),
		  t1 = g1.transition().duration(750),
		  t2 = g2.transition().duration(750);

	  // Update the domain only after entering new elements.
	  x.domain([d.x, d.x + d.dx]);
	  y.domain([d.y, d.y + d.dy]);

	  // Enable anti-aliasing during the transition.
	  svg.style("shape-rendering", null);

	  // Draw child nodes on top of parent nodes.
	  svg.selectAll(".depth").sort(function(a, b) { return a.depth - b.depth; });

	  // Fade-in entering text.
	  g2.selectAll("text").style("fill-opacity", 0);

	  // Transition to the new view.
	  t1.selectAll(".ptext").call(text).style("fill-opacity", 0);
	  t1.selectAll(".ctext").call(text2).style("fill-opacity", 0);
	  t2.selectAll(".ptext").call(text).style("fill-opacity", 1);
	  t2.selectAll(".ctext").call(text2).style("fill-opacity", 1);
	  t1.selectAll("rect").call(rect);
	  t2.selectAll("rect").call(rect);

	  // Remove the old node when the transition is finished.
	  t1.remove().each("end", function() {
		svg.style("shape-rendering", "crispEdges");
		transitioning = false;
	  });
	}

	return g;
  }

  function text(text) {
	text.selectAll("tspan")
		.attr("x", function(d) { return x(d.x) + 6; })
	text.attr("x", function(d) { return x(d.x) + 6; })
		.attr("y", function(d) { return y(d.y) + 6; })
		.style("opacity", function(d) { return this.getComputedTextLength() < x(d.x + d.dx) - x(d.x) ? 1 : 0; });
  }

  function text2(text) {
	text.attr("x", function(d) { return x(d.x + d.dx) - this.getComputedTextLength() - 6; })
		.attr("y", function(d) { return y(d.y + d.dy) - 6; })
		.style("opacity", function(d) { return this.getComputedTextLength() < x(d.x + d.dx) - x(d.x) ? 1 : 0; });
  }

  function rect(rect) {
	rect.attr("x", function(d) { return x(d.x); })
		.attr("y", function(d) { return y(d.y); })
		.attr("width", function(d) { return x(d.x + d.dx) - x(d.x); })
		.attr("height", function(d) { return y(d.y + d.dy) - y(d.y); });
  }

  function name(d) {
	return d.parent
		? name(d.parent) + " / " + d.key + " (" + formatNumber(d.value) + ")"
		: d.key + " (" + formatNumber(d.value) + ")";
  }
}
function doTreemap(res){
	var data = d3.nest().key(function(d) { return d.region; }).key(function(d) { return d.subregion; }).entries(res);
	main({title: ""}, {key: "Total", values: data});
}

function buscarPrograma(){
	if($("#buscarPrograma").val().length>2){
		var params=new Object()
		params.action="buscarPrograma"
		params.buscar=$("#buscarPrograma").val()
		params.Estado=$("#paramEstado").val()
		params.INPC=$("#paramINPC").val()
		$.post("/backend",params,function(resp){
			if($("#buscarPrograma").val()==resp.buscar){
				$("#tablaProgramas tbody").html('')
				if(resp.programas.length>0){
					for (i = 0; i < resp.programas.length; i++){
						unidadResponsable=resp.URs[resp.programas[i].UnidadResponsable];
						claveUnidadResponsable=resp.UPs[unidadResponsable.UnidadPresupuestal].Clave+"-"+unidadResponsable.Clave;
						unidadResponsable=resp.UPs[unidadResponsable.UnidadPresupuestal].Clave+"-"+unidadResponsable.Clave+' '+unidadResponsable.Nombre+'';
						$("#tablaProgramas tbody").append('<tr data-id="'+resp.programas[i].Id+'"><td><span class="colorDot"></span></td><td>'+resp.programas[i].Clave+'</td><td>'+resp.programas[i].Nombre+'</td><td class="unidadResponsable">'+unidadResponsable+' <a href="/'+$('#paramCodigoEstado').val()+'/ur/'+claveUnidadResponsable+'?i='+$("#paramINPC").val()+'&v='+$("#paramVersion").val()+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td><td></td><td><a href="/'+$('#paramCodigoEstado').val()+'/programa/'+claveUnidadResponsable+'-'+resp.programas[i].Clave+'?i='+$("#paramINPC").val()+'&v='+$("#paramVersion").val()+'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td></tr>')
					}
				}else{
					$("#tablaProgramas tbody").html('<tr><td colspan="5">No se localizaron programas</td></tr>')
				}
			}
			console.log(resp)
		},"json")
		
		
	}
}