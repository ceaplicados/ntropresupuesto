<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoEstados.php");
require_once("../dep/clases/DaoCuadernos.php");
require_once("../dep/clases/DaoVersionesPresupuesto.php");
require_once("../dep/clases/DaoINPC.php");
$DaoEstados=new DaoEstados();
$DaoCuadernos=new DaoCuadernos();
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
$INPCs=$DaoINPC->showAll();
$Cuaderno=$DaoCuadernos->show($navURI[1]);
$edicion=false;
if($Usuario->getId()==$Cuaderno->getOwner()){
  $edicion=true;
}
foreach($Cuaderno->getUsuarios() as $UsuarioCuaderno){
  if($Usuario->getId()==$UsuarioCuaderno->getId()){
	$edicion=true;
  }
}
if($Cuaderno->getPublico()==0 && $edicion==false){
  header("Location: app-cuadernos.php");
  exit();
}
if(strlen($Cuaderno->getAnioINPC())==0){
  $Cuaderno->setAnioINPC($INPCs[0]->getAnio());
}
$Owner=$DaoUsuarios->show($Cuaderno->getOwner());
$descripcion="Cuaderno de trabajo en nuestropresupuesto.mx. ".$Cuaderno->getDescripcion();
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
$title=$Cuaderno->getNombre()." @ #NuestroPresupuesto";
headerPageAdmin($title,$descripcion,$thumb,$tags);

  ?>
<section class="ls with_bottom_border">
  <div class="container-fluid">
	<div class="row">
	  <div class="col-md-6">
		<ol class="breadcrumb darklinks">
		  <li><a href="/">Dashboard</a></li>
		  <li><a href="/cuadernos">Cuadernos</a></li>
		  <li class="active"><?php echo($Cuaderno->getNombre()); ?></li>
		</ol>
	  </div>
	  <!-- .col-* -->
	</div>
	<!-- .row -->
  </div>
  <!-- .container -->
</section>

<section class="ls section_padding_top_50 section_padding_bottom_50 columns_padding_10">
  <div class="container-fluid">

	<div class="row">
	  <div class="col-md-12">
		<h3 class="dashboard-page-title"><?php echo($Cuaderno->getNombre()); ?>.
		  <small>Cuaderno <?php if($Cuaderno->getPublico()==1){ echo('público <i class="fa fa-globe" aria-hidden="true"  title="Público"></i>'); }else{ echo('privado <i class="fa fa-user-secret" title="Privado" aria-hidden="true"></i>'); } ?></small>
		</h3>
		<p><?php echo($Cuaderno->getDescripcion()); ?></p>
		<?php if($edicion){ ?>
		<p><a href="#" onclick="modalEditaCuaderno()"><i class="fa fa-cog" aria-hidden="true"></i> Configuración del cuaderno</a></p>
		<?php } ?>
	  </div>
	  <div class="col-md-12 text-right">
		<ul class="participantes">
		  <?php if($edicion){ ?>
		  <li class="edit" onclick="modalCompartirCuaderno()"><i class="fa fa-pencil" aria-hidden="true"></i></li><?php } ?>
		  <li title="<?php echo($Owner->getNombre()); ?>">
			<img src="<?php echo($Owner->getImage()); ?>" />
		  </li>
		  <?php foreach($Cuaderno->getUsuarios() as $UsuarioCuaderno){ ?>
		  <li title="<?php echo($UsuarioCuaderno->getNombre()); ?>" data-email='<?php echo($UsuarioCuaderno->getEmail()); ?>'>
			<img src="<?php echo($UsuarioCuaderno->getImage()); ?>" />
		  </li>
		  <?php } ?>
		</ul>
	  </div>
	</div>
	<!-- .row -->
	<div class="row">
	  <div class="col-xs-12">
		<div class="with_border with_padding">
		  <div class="canvas-chart-wrapper">
			  <canvas id="canvas-chart-cuaderno"></canvas>
			</div>
		</div>
	  </div>
	  <!--<div class="col-xs-12 text-right tiposGrafica">
		<i class="fa fa-line-chart" aria-hidden="true"></i>
		<i class="fa fa-bar-chart" aria-hidden="true"></i>
	  </div>-->
	</div>
	<div class="row">
	  <div class="col-xs-12">
		<h4>Datos</h4>
		<p class="text-right">A valores del <?php echo($Cuaderno->getAnioINPC()); ?> </p>
		<table class="table" id="tablaDatos">
		  <thead>
			<tr class="titulos">
			  <th colspan="2" style="border-right: 1px white solid;"></th>
			  <th class="anios">Años <?php if($edicion){ ?><i class="fa fa-plus-circle" aria-hidden="true" id="showModalAddYear" onclick="showModalAddYear()"></i><?php } ?></th>
			</tr>
			<tr class="detalles">
			  <th>Nombre</th>
			  <th style="border-right: 1px white solid;"><i class="fa fa-line-chart" aria-hidden="true"></i></th>
			</tr>
		  </thead>
		  <tbody></tbody>
		  <?php if($edicion){ ?><tfoot>
			<tr>
			  <td><a href="#" onclick="modalRenglon()">Agrega un renglón <i class="fa fa-plus-circle" aria-hidden="true"></i></a></td>
			</tr>
		  </tfoot><?php } ?>
		</table>
	  </div>
	</div>
  </div>
  <!-- .container -->
</section>

<div class="modal fade" id="modalCompartirCuaderno" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Usuarios con acceso al cuaderno</h5>
	  </div>
	  <div class="modal-body">
		<div class="form-group">
		  <label for="buscarEmail">Añade usuarios:</label>
		  <input type="text" id="buscarEmail" class="form-control" placeholder="Busca por email"/>
		  <button class="button button-on-input" id="buscarUsuario" onclick="buscarUsuario()"><i class="fa fa-search" aria-hidden="true"></i></button>
		  <p class="error">Usuario no encontrado</p>
		</div>
		<table class="table">
		  <tbody>
			<tr data-email="<?php echo($Owner->getEmail()); ?>">
			  <td class="owner">
				<div class="image">
				  <img src="<?php echo($Owner->getImage()); ?>" />
				</div>
				<p><?php echo($Owner->getNombre()); ?></p>
				<span class="propietario">Propietario</span>
			  </td>
			</tr>
			<?php foreach($Cuaderno->getUsuarios() as $UsuarioCuaderno){ ?>
			<tr data-email="<?php echo($UsuarioCuaderno->getEmail()); ?>">
			  <td class="">
				<div class="image">
				  <img src="<?php echo($UsuarioCuaderno->getImage()); ?>" />
				</div>
				<p><?php echo($UsuarioCuaderno->getNombre()); ?></p>
				<i class="fa fa-trash" aria-hidden="true" onclick="deleteUsuarioCuaderno(this)"></i>
			  </td>
			</tr>
			<?php } ?>
		  </tbody>
		</table>
	  </div>
	  <div class="modal-footer">
		<div class="sharer">
		  <input type="text" id="urlToCopy" class="form-control" value="https://www.nuestropresupuesto.mx/app-cuaderno.php?id=<?php echo($Cuaderno->getId()); ?>"/>
		  <i class="fa fa-clipboard" aria-hidden="true" onclick="copyUrl()"></i>
		</div>
		<button class="button" id="guardarUsuariosCuaderno" onclick='$("#modalCompartirCuaderno").modal("toggle");'>Cerrar</button>
	  </div>
	</div>
  </div>
</div>

<div class="modal fade" id="modalConfiguracion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Configuración del cuaderno</h5>
	  </div>
	  <div class="modal-body">
		<div class="form-group">
		  <label for="nombreCuaderno">Nombre:</label>
		  <input type="text" id="nombreCuaderno" class="form-control" value="<?php echo($Cuaderno->getNombre()); ?>"/>
		</div>
		<div class="form-group">
		  <label for="descripcionCuaderno">Descripción:</label>
		  <textarea id="descripcionCuaderno" class="form-control"><?php echo($Cuaderno->getDescripcion()); ?></textarea>
		</div>
		<div class="form-check form-check-inline">
		  <input class="form-check-input" type="radio" name="publicidadCuaderno" id="cuadernoPublico" value="1" <?php if($Cuaderno->getPublico()=="1"){ echo('checked'); } ?>>
		  <label class="form-check-label" for="cuadernoPublico"><i class="fa fa-globe" aria-hidden="true"  title="Público"></i> Cuaderno público</label>
		</div>
		<div class="form-check form-check-inline">
		  <input class="form-check-input" type="radio" name="publicidadCuaderno" id="cuadernoPrivado" value="0" <?php if($Cuaderno->getPublico()!=="1"){ echo('checked'); } ?>>
		  <label class="form-check-label" for="cuadernoPrivado"><i class="fa fa-user-secret" title="Privado" aria-hidden="true"></i> Cuaderno privado</label>
		</div>
		<br/>
		<div class="form-group">
		  <label for="INPCCuaderno">Mostrar montos a valores del: </label><br/>
		  <select class="form-select" id="INPCCuaderno">
			<option value="">Último año disponible</option>
			<?php foreach($INPCs as $INPC){ ?>
			<option value="<?php echo($INPC->getAnio()); ?>"><?php echo($INPC->getAnio()); ?></option>
			<?php } ?>
		  </select>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="button" id="guardarConfiguracionCuaderno" onclick='guardarConfiguracionCuaderno()'>Guardar</button>
	  </div>
	</div>
  </div>
</div>	

<div class="modal fade" id="modalAddYear" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Añadir año</h5>
	  </div>
	  <div class="modal-body">
		<div class="form-group">
		  <label for="anioToAdd">Selecciona un año: </label><br/>
		  <select class="form-select" id="anioToAdd">
			<?php foreach($DaoVersionesPresupuesto->showAniosDisponibles() as $anio){ ?>
			<option value="<?php echo($anio->getAnio()); ?>"><?php echo($anio->getAnio()); ?></option>
			<?php } ?>
		  </select>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="button" id="addYear" onclick='addYear()'>Añadir</button>
	  </div>
	</div>
  </div>
</div>

<div class="modal fade" id="modalRenglon" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Añadir renglón</h5>
	  </div>
	  <div class="modal-body">
		<div class="row">
		  <div class="form-group col-xs-12 col-md-6">
			<label for="tipoRengon">Tipo de dato: </label><br/>
			<select class="form-select" id="tipoRengon" onchange="changeTipoRenglon()">
			  <option value="" disabled>Selecciona una opción</option>
			  <option value="Total">Presupuesto Total</option>
			  <option value="CapituloGasto">Capítulo de Gasto</option>
			  <option value="ConceptoGeneral">Concepto General</option>
			  <option value="PartidaGenerica">Partida Genérica</option>
			  <option value="ObjetoGasto">Objeto de Gasto</option>
			  <option value="ProgramaPresupuestal">Programa Presupuestal</option>
			  <!--
			  <option value="Variable">Capturar valores</option>
			  <option value="Formula">Cálculo de renglones</option>-->
			</select>
		  </div>
		  <div class="estado col-xs-12 col-md-6">
			<label for="estadoRenglon">Estado:</label><br/>
			<select id="estadoRenglon" class="form-select">
			  <option value="" disabled selected>Selecciona una opción</option>
			  <?php foreach($DaoEstados->showConVersiones() as $Estado){ ?>
			  <option value="<?php echo($Estado->getId()); ?>"><?php echo($Estado->getNombre()); ?></option>
			  <?php } ?>
			</select>
		  </div>
		  <div class="buscarOG_PP col-xs-12">
			<div class="form-group">
			  <label for="buscarOG_PP">Buscar <span class="nombre"></span></label><br/>
			  <input type="text" id="buscarOG_PP" autocomplete="off"  onkeyup="buscarOG_PP()" placeholder="Por nombre o clave"/>
			  <ul id="resultadosBuscarOG_PP" class="resultadosModalRenglon"></ul>
			</div>
		  </div>
		  <div class="filtroOG col-xs-12 col-md-6">
			<label for="filtroOG">Mostrar para:</label><br/>
			<select id="filtroOG" class="form-select" onchange="changeFiltroOG()">
			  <option value="" disabled selected>Selecciona una opción</option>
			  <option value="Estado">Todo el estado</option>
			  <option value="UP">Unidad Presupuestal</option>
			  <option value="UR">Unidad Responsable</option>
			</select>
		  </div>
		  <div class="filtroOG_UPUR col-xs-12 col-md-6">
			<div class="form-group">
			  <label for="valorFiltroOG">Buscar <span class="nombre"></span></label><br/>
			  <input type="text" id="valorFiltroOG" autocomplete="off" onkeyup="buscarOG_UPUR()" placeholder="Buscar por nombre o clave"/>
			  <ul id="resultadosvalorFiltroOG" class="resultadosModalRenglon"></ul>
			</div>
		  </div>
		  <div class="mostrarComo col-xs-12">
			<p>
			  Mostrar como: <br/>
			  <div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="mostrarRenglon" id="mostrarRenglon_monto" value="monto">
				<label class="form-check-label" for="mostrarRenglon_monto">Monto</label>
			  </div>
			  <div class="form-check form-check-inline">
				<input class="form-check-input" autocomplete="off" type="radio" name="mostrarRenglon" id="mostrarRenglon_YoY" value="YoY">
				<label class="form-check-label" for="mostrarRenglon_YoY">Variación anual</label>
			  </div>
			</p>
		  </div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="button" id="delRenglon" onclick='delRenglon()'>Eliminar</button>
		<button class="button" id="saveRenglon" onclick='saveRenglon()'>Añadir</button>
	  </div>
	</div>
  </div>
</div>	
<input type="hidden" id="IdCuaderno" value="<?php echo($Cuaderno->getId()); ?>" />
<input type="hidden" id="edicion" value="<?php echo($edicion); ?>" />
<input type="hidden" id="INPC" value="<?php echo($Cuaderno->getAnioINPC()); ?>" />
<?php
footerPageAdmin();