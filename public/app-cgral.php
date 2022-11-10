<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoEstados.php");
require_once("../dep/clases/DaoVersionesPresupuesto.php");
require_once("../dep/clases/DaoINPC.php");
require_once("../dep/clases/DaoCapitulosGasto.php");
require_once("../dep/clases/DaoConceptosGenerales.php");
$DaoEstados=new DaoEstados();
$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();
$DaoINPC=new DaoINPC();
$DaoCapitulosGasto=new DaoCapitulosGasto();
$DaoConceptosGenerales=new DaoConceptosGenerales();
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
if(strlen($navURI[0])>6){
	header("Location: /404");
	exit();	
}
$Estado=$DaoEstados->getByCodigo($navURI[0]);
if(!$Estado->getId()>0){
	header("Location: /404");
	exit();
}
$ConceptoGeneral=$DaoConceptosGenerales->getByClave($navURI[2]);
$CapituloGasto=$DaoCapitulosGasto->show($ConceptoGeneral->getCapituloGasto());
if(!$ConceptoGeneral->getId()>0){
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
	header("Location: /".$Estado->getCodigo()."/ConceptosGenerales/".$ConceptoGeneral->getClave()."?v=$v&i=$i");
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
$title="Presupuesto para el concepto general ".$ConceptoGeneral->getClave()." - ".$ConceptoGeneral->getNombre()."@ ".$Estado->getNombre();
headerPageAdmin($title,$descripcion,$thumb,$tags);
	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/"><img src="/imgs/logo.svg" alt="Dashboard"/></a></li>
					<li><a href="/<?php echo($Estado->getCodigo()); ?>"><?php echo($Estado->getNombre()); ?></a></li>
					<li><a href="/<?php echo($Estado->getCodigo()); ?>/CapituloGasto">Capítulos de Gasto</a></li>
					<li><a href="/<?php echo($Estado->getCodigo()); ?>/CapituloGasto/<?php echo($CapituloGasto->getClave()); ?>"><?php echo($CapituloGasto->getClave()); ?></a></li>
					<li class="active"><?php echo($ConceptoGeneral->getClave()." - ".$ConceptoGeneral->getNombre()); ?></li>
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
				<h3 class="dashboard-page-title"><?php echo($ConceptoGeneral->getClave()." - ".$ConceptoGeneral->getNombre()); ?>
				</h3>
				<p><?php echo($Estado->getNombre()); ?>. A valores del <?php echo($INPCActual->getAnio()); ?></p>
			</div>
		</div>
		<!-- .row -->
		<div class="row">
			<!-- Presupuesto total -->
			<div class="col-xs-12 contenedorGraficas" data-graph="all">
				<div class="with_border with_padding" id="barrasCG">
					<h4>Histórico por Partida Genérica</h4>
					<div class="canvas-chart-wrapper">
						<canvas class="chart-bar-historico-cg"></canvas>
					</div>
				</div>
			</div>
			<!-- .col-* -->
		</div>
		<div class="row tablasCG">
			<div class="col-xs-12 text-right tiposTabla">
				<button title="Detalle histórico por Concepto general" onclick="showTable(this)" class="button"  data-tabla="tablaConceptoGeneral"><i class="fa fa-bar-chart" aria-hidden="true"></i></button>
				<button title="Variación histórica por Concepto general" onclick="showTable(this)" class="button"  data-tabla="tablaConceptoGeneralVariaciones"><i class="fa fa-line-chart" aria-hidden="true"></i></button>
			</div>
			<div class="col-xs-12 overflow-x" id="contenedorTablas">
				<div id="tablaConceptoGeneral" >
					<h5>Detalle histórico por Partida Genérica</h5>
					<table class="table">
						<thead>
							<tr class="anio">
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr class="total"></tr>
						</tfoot>
					</table>
				</div>
				<div id="tablaConceptoGeneralVariaciones">
					<h5>Variación histórica por Partida Genérica</h5>
					<table class="table">
						<thead>
							<tr class="anio">
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr class="total"></tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- Presupuesto total -->
			<div class="col-xs-12 contenedorGraficas" data-graph="all">
				<div class="with_border with_padding" id="areasCG">
					<h4>Histórico por Unidad Responsable</h4>
					<div class="canvas-chart-wrapper">
						<canvas class="chart-area-historico-ur"></canvas>
					</div>
				</div>
			</div>
			<!-- .col-* -->
		</div>
		<div class="row tablasUR">
			<div class="col-xs-12 text-right tiposTabla">
				<button title="Detalle histórico por Unidad Responsable" onclick="showTableUR(this)" class="button"  data-tabla="tablaUnidadResponsable"><i class="fa fa-bar-chart" aria-hidden="true"></i></button>
				<button title="Variación histórica por Unidad Responsable" onclick="showTableUR(this)" class="button"  data-tabla="tablaUnidadResponsableVariaciones"><i class="fa fa-line-chart" aria-hidden="true"></i></button>
			</div>
			<div class="col-xs-12 overflow-x" id="contenedorTablasUR">
				<div id="tablaUnidadResponsable">
					<h5>Detalle histórico por Unidad Responsable</h5>
					<table class="table">
						<thead>
							<tr class="anio">
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr class="total"></tr>
						</tfoot>
					</table>
				</div>
				<div id="tablaUnidadResponsableVariaciones">
					<h5>Variación histórica por Unidad Responsable</h5>
					<table class="table">
						<thead>
							<tr class="anio">
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr class="total"></tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- .container -->
</section>
<input type="hidden" id="paramVersion" value="<?php echo($v); ?>"/>
<input type="hidden" id="paramEstado" value="<?php echo($Estado->getId()); ?>"/>
<input type="hidden" id="paramCodigoEstado" value="<?php echo($Estado->getCodigo()); ?>"/>
<input type="hidden" id="paramConceptoGeneral" value="<?php echo($ConceptoGeneral->getId()); ?>"/>
<input type="hidden" id="paramCodigoConceptoGeneral" value="<?php echo($ConceptoGeneral->getClave()); ?>"/>
<input type="hidden" id="paramINPC" value="<?php echo($INPCActual->getAnio()); ?>"/>
<script src="https://d3js.org/d3.v3.min.js"></script>
<?php
footerPageAdmin();