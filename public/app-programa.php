<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoEstados.php");
require_once("../dep/clases/DaoVersionesPresupuesto.php");
require_once("../dep/clases/DaoINPC.php");
require_once("../dep/clases/DaoUnidadPresupuestal.php");
require_once("../dep/clases/DaoUnidadResponsable.php");
require_once("../dep/clases/DaoProgramas.php");
require_once("../dep/clases/DaoIndicadoresProgramas.php");
$DaoEstados=new DaoEstados();
$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();
$DaoINPC=new DaoINPC();
$DaoUnidadPresupuestal=new DaoUnidadPresupuestal();
$DaoUnidadResponsable=new DaoUnidadResponsable();
$DaoProgramas=new DaoProgramas();
$DaoIndicadoresProgramas=new DaoIndicadoresProgramas();

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

$clavePrograma=$navURI[2];
$clavePrograma=explode("-", $clavePrograma);
if(count($clavePrograma)!==3){
	header("Location: /".$Estado->getCodigo()."?v=$v&i=$i");
	exit();
}

$UnidadPresupuestal=$DaoUnidadPresupuestal->getByClaveEstado($clavePrograma[0],$Estado->getId());
if(!$UnidadPresupuestal->getId()>0){
	header("Location: /".$Estado->getCodigo()."?v=$v&i=$i");
	exit();
}
$UnidadResponsable=$DaoUnidadResponsable->getByClaveUnidadPresupuestal($clavePrograma[1],$UnidadPresupuestal->getId());
if(!$UnidadResponsable->getId()>0){
	header("Location: /".$Estado->getCodigo()."?v=$v&i=$i");
	exit();
}
$Programa=$DaoProgramas->getByClaveUnidadResponsable($clavePrograma[2],$UnidadResponsable->getId());
if(!$Programa->getId()>0){
	header("Location: /".$Estado->getCodigo()."?v=$v&i=$i");
	exit();
}
if($reload){
	header("Location: /".$Estado->getCodigo()."/programa/".$UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()."-".$Programa->getClave()."?v=$v&i=$i");
	exit();
}
$versionActual=$DaoVersionesPresupuesto->show($v);
$INPCActual=$DaoINPC->show($i);
$INPCversion=$DaoINPC->show($versionActual->getAnio());
$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
$descripcion="La app de #NuestroPresupuesto es una plataforma se pueden visualizar los presupuestos desde distintas perspectivas: ¿Quién lo gasta?, ¿En qué se gasta?, ¿Cómo se gasta?. La app es un esfuerzo para que la ciudadanía pueda entender mejor la información presupuestal publicada por el Estado.";
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
$title=$Programa->getNombre()." @ ".$UnidadResponsable->getNombre().". ".$Estado->getNombre();
headerPageAdmin($title,$descripcion,$thumb,$tags);

	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/">Dashboard</a></li>
					<li><a href="/<?php echo($Estado->getCodigo()); ?>"><?php echo($Estado->getNombre()); ?></a></li>
					<li><a href="/<?php echo($Estado->getCodigo()); ?>/ur/<?php echo($UnidadPresupuestal->getClave()); ?>-<?php echo($UnidadResponsable->getClave()); ?>"><?php echo($UnidadResponsable->getNombre()); ?></a></li>
					<li class="active"><?php echo($UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()."-".$Programa->getClave()); ?>. <?php echo($Programa->getNombre()); ?></li>
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
				<h3 class="dashboard-page-title"><?php echo($Programa->getNombre()); ?>
					<small><?php echo($UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave()."-".$Programa->getClave()); ?></small>
				</h3>
				<p>Programa de <?php echo($UnidadResponsable->getNombre()); ?>. <?php echo($versionActual->getDescripcion()); ?>. A valores del <?php echo($INPCActual->getAnio()); ?></p>
			</div>
		</div>
		<!-- .row -->
		<div class="row">
			<!-- Presupuesto total -->
			<div class="col-xs-12">
				<div class="with_border with_padding">
					<h4>Histórico</h4>
					<div class="canvas-chart-wrapper">
						<canvas class="canvas-chart-line-programa-historico"></canvas>
					</div>
				</div>
			</div>
			<!-- .col-* -->

			<!-- Histórico del gasto federalizado -->
			<div class="col-xs-12">
				<div class="with_border with_padding">
					<h4>Métricas</h4>
					<ul id="metricas">
						<?php foreach($DaoIndicadoresProgramas->getByPrograma($Programa->getId()) as $Indicador){ 
							$Data=json_decode($Indicador->getData(),"true");
						?>
						<li>
							<p class="nombre"><?php echo($Indicador->getNombre()); ?></p>
							<p class="resumen"><?php echo($Indicador->getResumen()); ?></p>
							<p class="medios"><?php echo($Indicador->getMedios()); ?></p>
							<p class="responsable"><?php echo($Data['UEG']); ?></p>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<!-- .col-* -->
		</div>
	</div>
	<!-- .container -->
</section>
<input type="hidden" id="paramVersion" value="<?php echo($versionActual->getId()); ?>"/>
<input type="hidden" id="paramEstado" value="<?php echo($Estado->getId()); ?>"/>
<input type="hidden" id="paramPP" value="<?php echo($Programa->getId()); ?>"/>
<input type="hidden" id="paramCodigoEstado" value="<?php echo($Estado->getCodigo()); ?>"/>
<input type="hidden" id="paramINPC" value="<?php echo($INPCActual->getAnio()); ?>"/>
<input type="hidden" id="deflactor" value="<?php echo($deflactor); ?>"/>
<script src="https://d3js.org/d3.v3.min.js"></script>
<?php
footerPageAdmin();