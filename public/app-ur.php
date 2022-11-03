<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoUnidadResponsable.php");
require_once("../dep/clases/DaoUnidadPresupuestal.php");
require_once("../dep/clases/DaoEstados.php");
require_once("../dep/clases/DaoVersionesPresupuesto.php");
require_once("../dep/clases/DaoINPC.php");
require_once("../dep/clases/DaoProgramas.php");
$DaoUnidadResponsable=new DaoUnidadResponsable();
$DaoUnidadPresupuestal=new DaoUnidadPresupuestal();
$DaoEstados=new DaoEstados();
$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();
$DaoINPC=new DaoINPC();
$DaoProgramas=new DaoProgramas();

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

$claveUR=$navURI[2];
$claveUR=explode("-", $claveUR);

if(count($claveUR)!==2){
	header("Location: /".$Estado->getCodigo()."?v=$v&i=$i");
	exit();
}

$UnidadPresupuestal=$DaoUnidadPresupuestal->getByClaveEstado($claveUR[0],$Estado->getId());
if(!$UnidadPresupuestal->getId()>0){
	header("Location: /".$Estado->getCodigo()."?v=$v&i=$i");
	exit();
}

$UnidadResponsable=$DaoUnidadResponsable->getByClaveUnidadPresupuestal($claveUR[1],$UnidadPresupuestal->getId());
if(!$UnidadResponsable->getId()>0){
	header("Location: /".$Estado->getCodigo()."?v=$v&i=$i");
	exit();
}

if($reload){
	header("Location: /".$Estado->getCodigo()."/ur/".$UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()."?v=$v&i=$i");
	exit();
}

$INPCActual=$DaoINPC->show($i);
$deflactores=array();
$IdsVersiones=array();
$versionesEstado=$DaoVersionesPresupuesto->getByEstado($Estado->getId());

foreach($versionesEstado as $version){
	$INPCversion=$DaoINPC->show($version->getAnio());
	$deflactores[$version->getAnio()]=$INPCActual->getValor()/$INPCversion->getValor();
	array_push($IdsVersiones, $version->getId());
}
$versionActual=$DaoVersionesPresupuesto->show($v);

$INPCversion=$DaoINPC->show($versionActual->getAnio());
$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
$descripcion="La app de #NuestroPresupuesto es una plataforma se pueden visualizar los presupuestos desde distintas perspectivas: ¿Quién lo gasta?, ¿En qué se gasta?, ¿Cómo se gasta?. La app es un esfuerzo para que la ciudadanía pueda entender mejor la información presupuestal publicada por el Estado.";
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
$title=$UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()." ".$UnidadResponsable->getNombre()." @ ".$Estado->getNombre();
headerPageAdmin($title,$descripcion,$thumb,$tags);
	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/">Dashboard</a></li>
					<li><a href="/<?php echo($Estado->getCodigo()); ?>"><?php echo($Estado->getNombre()); ?></a></li>
					<li class="active"><?php echo($UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()." ".$UnidadResponsable->getNombre()); ?></li>
				</ol>
			</div>
			<!-- .col-* -->
			<div class="col-md-6 text-md-right form-inline text-right" id="selectVersion">
				<div class="form-group">
					<label for="visualizarAnio">Presupuesto </label>
					<select id="visualizarAnio" class="form-control">
						<?php foreach($versionesEstado as $Version){ ?>
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
				<h3 class="dashboard-page-title"><?php echo($UnidadResponsable->getNombre()); ?>.
					<small><?php echo($UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()); ?></small>
				</h3>
				<p><?php echo($Estado->getNombre()); ?>. A valores del <?php echo($INPCActual->getAnio()); ?></p>
			</div>
		</div>
		<!-- .row -->
		<div class="row">
			<!-- Presupuesto total -->
			<div class="col-xs-12" id="contenedorGraficas" data-graph="all">
				<div class="with_border with_padding" id="barrasCG">
					<h4>Histórico por Capítulo de Gasto</h4>
					<div class="canvas-chart-wrapper">
						<canvas class="chart-bar-historico-ur"></canvas>
					</div>
				</div>
				<div class="with_border with_padding" id="lineasUR">
					<h4>Histórico de la Unidad Responsable</h4>
					<div class="canvas-chart-wrapper">
						<canvas class="chart-lineas-ur"></canvas>
					</div>
				</div>
				<div class="with_border with_padding" id="variacionUR">
					<h4>Variación de la Unidad Responsable</h4>
					<div class="canvas-chart-wrapper">
						<canvas class="chart-variacion-ur"></canvas>
					</div>
				</div>
				<div class="with_border with_padding" id="variacionPorcentualUR">
					<h4>Variación porcentual de la Unidad Responsable</h4>
					<div class="canvas-chart-wrapper">
						<canvas class="chart-variacion-porcentual-ur"></canvas>
					</div>
				</div>
				<div class="col-xs-12 text-right tiposGrafica">
					<button title="Histórico por Capítulo de Gasto" onclick="showGraph(this)" class="button" data-target="barrasCG"><i class="fa fa-bar-chart" aria-hidden="true"></i></button>
					<button title="Histórico de la Unidad Responsable" onclick="showGraph(this)" class="button" data-target="lineasUR"><i class="fa fa-line-chart" aria-hidden="true"></i></button>
				</div>
			</div>
			<!-- .col-* -->
		</div>
		<div class="row">
			<!-- Calendar -->
			<div class="col-xs-12">
				<div class="with_border with_padding">
					<h4>Programas presupuestales</h4>
					<table class="table table-striped" id="tablaProgramas">
						<thead>
							<tr>
								<th class="hidden">Texto</th>
								<th>Clave</th>
								<th>Programa</th>
								<th>Monto</th>
								<th>% total</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$montoTotal=0;
							foreach($DaoProgramas->getMontoByURVersion($UnidadResponsable->getId(), $v) as $Programa){ 
								$Programa->setMonto($Programa->getMonto()/$deflactor);
								$montoTotal+=$Programa->getMonto();
								?>
							<tr data-id="<?php echo($Programa->getId()); ?>" data-monto="<?php echo($Programa->getMonto()); ?>">
								<td class="hidden"></td>
								<td><?php echo($Programa->getClave()); ?></td>
								<td><?php echo($Programa->getNombre()); ?></td>
								<td class="monto"><?php echo(number_format($Programa->getMonto(),2)); ?></td>
								<td class="porcentaje"></td>
								<td><a href="/<?php echo($Estado->getCodigo()); ?>/programa/<?php echo($UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()); ?>-<?php echo($Programa->getClave()."?i=$i&v=$v"); ?>"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr class="total" data-monto="<?php echo($montoTotal); ?>">
								<td colspan="2">Total presupuesto</td>
								<td class="monto"><?php echo(number_format($montoTotal,2)); ?></td>
								<td>100%</td>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- .col-* -->
		</div>
		
		<div class="row">
			<!-- Histórico del gasto federalizado -->
			<div class="col-xs-12">
				<div class="with_border with_padding">
					<h4>Unidad Presupuestaria</h4>
					<p><?php echo($UnidadPresupuestal->getClave()); ?> <?php echo($UnidadPresupuestal->getNombre()); ?></p>
					<input type="hidden" id="claveUP" value="<?php echo($UnidadPresupuestal->getClave()); ?>"/>
					<input type="hidden" id="nombreUP" value="<?php echo($UnidadPresupuestal->getClave()); ?> <?php echo($UnidadPresupuestal->getNombre()); ?>"/>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="canvas-chart-wrapper">
								<canvas class="canvas-chart-donut-unidad-presupuestaria"></canvas>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<table id="UnidadesResponsablesHermanas" class="table table-striped">
								<thead>
									<tr>
										<th>Clave</th>
										<th>Nombre</th>
										<th>Monto</th>
										<th>Porcentaje</th>
										<th></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- .col-* -->
		</div>
	</div>
	<!-- .container -->
</section>
<input type="hidden" id="paramVersion" value="<?php echo($v); ?>"/>
<input type="hidden" id="paramUR" value="<?php echo($UnidadResponsable->getId()); ?>"/>
<input type="hidden" id="paramCodigoUR" value="<?php echo($UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()); ?>"/>
<input type="hidden" id="paramEstado" value="<?php echo($Estado->getId()); ?>"/>
<input type="hidden" id="paramCodigoEstado" value="<?php echo($Estado->getCodigo()); ?>"/>
<input type="hidden" id="paramINPC" value="<?php echo($INPCActual->getAnio()); ?>"/>
<script src="https://d3js.org/d3.v3.min.js"></script>
<?php
footerPageAdmin();