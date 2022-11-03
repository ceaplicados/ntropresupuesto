<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoEstados.php");
require_once("../dep/clases/DaoVersionesPresupuesto.php");
require_once("../dep/clases/DaoINPC.php");
$DaoEstados=new DaoEstados();
$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();
$DaoINPC=new DaoINPC();

$navURI=explode("/", $_SERVER["REQUEST_URI"]);
if($navURI[0]==""){
	array_shift($navURI);
}
if($navURI[count($navURI)-1]==""){
	array_pop($navURI);
}
if(substr($navURI[count($navURI)-1], 0,1)=="?"){
	array_pop($navURI);
}
if(strpos($navURI[count($navURI)-1], "?")!==false){
	$navURI[count($navURI)-1]=substr($navURI[count($navURI)-1], 0, strpos($navURI[count($navURI)-1], "?"));
}
if(strlen($navURI[0])>5){
	header("Location: /404");
	exit();	
}
$Estado=$DaoEstados->getByCodigo($navURI[0]);
if(!$Estado->getId()>0){
	header("Location: /404");
	exit();
}
$Versiones=$DaoVersionesPresupuesto->getByEstado($Estado->getId(),true);
$INPCs=$DaoINPC->showAll();
$reload=false;
if(!isset($_GET["v"])){
	$reload=true;
	$v=$Versiones[0]->getId();
}else{
	$v=$_GET["v"];
}
if(!isset($_GET["i"])){
	$reload=true;
	$i=$INPCs[0]->getAnio();
}else{
	$i=$_GET["i"];
}
if($reload){
	header("Location: /".$Estado->getCodigo()."?v=$v&i=$i");
	exit();
}
$versionActual=$DaoVersionesPresupuesto->show($v);
$INPCActual=$DaoINPC->show($i);
$INPCversion=$DaoINPC->show($versionActual->getAnio());
$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
$descripcion="La app de #NuestroPresupuesto es una plataforma se pueden visualizar los presupuestos desde distintas perspectivas: ¿Quién lo gasta?, ¿En qué se gasta?, ¿Cómo se gasta?. La app es un esfuerzo para que la ciudadanía pueda entender mejor la información presupuestal publicada por el Estado.";
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
$title=$Estado->getNombre();
headerPageAdmin($title,$descripcion,$thumb,$tags);

	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/">Dashboard</a></li>
					<li class="active"><?php echo($Estado->getNombre()); ?></li>
				</ol>
			</div>
			<!-- .col-* -->
			<div class="col-md-6 text-md-right form-inline text-right" id="selectVersion">
				<div class="form-group">
					<label for="visualizarAnio">Presupuesto </label>
					<select id="visualizarAnio" class="form-control">
						<?php foreach($Versiones as $Version){ ?>
						<option value="<?php echo($Version->getId()); ?>" <?php if($v==$Version->getId()){ echo("selected"); } ?>><?php echo($Version->getNombre()); ?> <?php echo($Version->getAnio()); ?></option>
						<?php } ?>
					</select>
					<label for="valoresAnio"> a valores del </label>
					<select id="valoresAnio" class="form-control">
						<?php foreach($INPCs as $INPC){ ?>
						<option value="<?php echo($INPC->getAnio()); ?>" <?php if($i==$INPC->getAnio()){ echo("selected"); } ?>><?php echo($INPC->getAnio()); ?></option>
						<?php } ?>
					</select>
				</div>
				<button class="theme_button color1" onclick="changePresupuesto()">Ir</button>
				</div>
			</div>
		</div>
		<!-- .row -->
	</div>
	<!-- .container -->
</section>

<section class="ls section_padding_top_50 section_padding_bottom_50 columns_padding_10">
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-12">
				<h3 class="dashboard-page-title"><?php echo($Estado->getNombre()); ?>
					<small><?php echo($versionActual->getNombre()); ?> <?php echo($versionActual->getAnio()); ?></small>
				</h3>
				<p><?php echo($versionActual->getDescripcion()); ?>. A valores del <?php echo($INPCActual->getAnio()); ?></p>
			</div>
		</div>
		<!-- .row -->
		<div class="row">
			<!-- Presupuesto total -->
			<div class="col-xs-12">
				<div class="with_border with_padding">
					<h4>¿Quién se lo gasta?</h4>
					<div class="canvas-chart-wrapper">
						<div id="chart"></div>
					</div>
				</div>
			</div>
			<!-- .col-* -->

			<!-- Histórico del gasto federalizado -->
			<div class="col-xs-12 col-md-6">
				<div class="with_border with_padding">
					<h4>¿En qué se gasta?</h4>
					<div class="canvas-chart-wrapper">
						<canvas class="canvas-chart-donut-capitulos-gasto"></canvas>
					</div>
				</div>
			</div>
			<!-- .col-* -->
			<!-- Composición del gasto federalizado -->
				<div class="col-xs-12 col-md-6">
					<div class="with_border with_padding">
						<h4>Histórico</h4>
						<div class="canvas-chart-wrapper">
							<canvas class="canvas-chart-line-presupuesto-historico"></canvas>
						</div>
					</div>
				</div>
				<!-- .col-* -->
		</div>
		<!-- .row -->
		<div class="row">
			<!-- Calendar -->
			<div class="col-xs-12">
				<div class="with_border with_padding">
					<h4>Unidades Responsables</h4>
					<div class="form-inline">
						<div class="form-group">
							<input type="text" class="form-control" id="busquedaTabla" placeholder="Filtrar..." onkeyup="filtrarTabla()"/>
						</div>
					</div>
					<table class="table table-striped" id="tablaMontos">
						<thead>
							<tr>
								<th class="hidden">Texto</th>
								<th class="selected ascendente">Clave <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
								<th>Unidad Responsable <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
								<th>Monto <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
								<th>% total <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
								<th class="porcentajeFiltrado">% filtro <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr class="filtrado">
								<td colspan="2">Total del filtro</td>
								<td class="monto"></td>
								<td class="porcentaje"></td>
								<td class="porcentajeFiltrado"></td>
							</tr>
							<tr class="total">
								<td colspan="2">Total presupuesto</td>
								<td class="monto"></td>
								<td>100%</td>
								<td class="porcentajeFiltrado"></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- .col-* -->
		</div>
		<!-- .row -->
		<div class="row">
			<div class="col-xs-12">
				<div class="with_border with_padding">
					<h4>Programas presupuestales</h4>
					<div class="form-inline">
						<div class="form-group">
							<input type="text" class="form-control" id="buscarPrograma" placeholder="Buscar..." onkeyup="buscarPrograma()"/>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<table class="table table-striped" id="tablaProgramas">
								<thead>
									<tr>
										<th></th>
										<th class="selected ascendente">Clave <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
										<th>Nombre <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
										<th>Unidad Responsable <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
										<th>Monto <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="5">Teclea la clave o nombre del programa en la búsqueda</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="canvas-chart-wrapper">
								<canvas class="canvas-chart-line-programas-presupuestales"></canvas>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
	<!-- .container -->
</section>
<input type="hidden" id="paramVersion" value="<?php echo($versionActual->getId()); ?>"/>
<input type="hidden" id="paramEstado" value="<?php echo($Estado->getId()); ?>"/>
<input type="hidden" id="paramCodigoEstado" value="<?php echo($Estado->getCodigo()); ?>"/>
<input type="hidden" id="paramINPC" value="<?php echo($INPCActual->getAnio()); ?>"/>
<input type="hidden" id="deflactor" value="<?php echo($deflactor); ?>"/>
<script src="https://d3js.org/d3.v3.min.js"></script>
<?php
footerPageAdmin();