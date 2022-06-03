<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoEstados.php");
require_once("../dep/clases/DaoCuadernos.php");
$DaoEstados=new DaoEstados();
$DaoCuadernos=new DaoCuadernos();

$descripcion="Administra tus cuadernos de trabajo";
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
$title="Mis cuadernos";
headerPageAdmin($title,$descripcion,$thumb,$tags);
$Owners=array();
	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/">Dashboard</a></li>
					<li class="active">Cuadernos</li>
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
		<?php if($Usuario->getId()>0){ ?>
		<div class="row">
			<div class="col-md-12">
				<h3 class="dashboard-page-title">Mis cuadernos
					<small></small>
				</h3>
				<p>Crea cuadernos de trabajo para analizar las asignaciones presupuestales de tu tema de interés.</p>
			</div>
		</div>
		<!-- .row -->
		<div class="row listCuadernos">
			<?php foreach($DaoCuadernos->getByUsuario($Usuario->getId()) as $Cuaderno){ 
				if(!isset($Owners[$Cuaderno->getOwner()])){
					$Owners[$Cuaderno->getOwner()]=$DaoUsuarios->show($Cuaderno->getOwner());
				}
				$Owner=$Owners[$Cuaderno->getOwner()]
			?>
			<div class="col-xs-12 col-md-6 col-lg-4 cuaderno">
				<div class="with_border with_padding">
					<h5><?php echo($Cuaderno->getNombre()); ?></h5>
					<a href="/cuaderno/<?php echo($Cuaderno->getId()); ?>" class="ligaCuaderno"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>
					<p class="descripcion"><?php echo($Cuaderno->getDescripcion()); ?></p>
					<ul class="participantes">
						<li title="<?php echo($Owner->getNombre()); ?>">
							<img src="<?php echo($Owner->getImage()); ?>" />
						</li>
						<?php foreach($Cuaderno->getUsuarios() as $UsuarioCuaderno){ ?>
						<li title="<?php echo($UsuarioCuaderno->getNombre()); ?>">
							<img src="<?php echo($UsuarioCuaderno->getImage()); ?>" />
						</li>
						<?php } ?>
					</ul>
					<span class="publico">
						<?php if($Cuaderno->getPublico()==1){ ?>
						<i class="fa fa-globe" aria-hidden="true"  title="Público"></i>
						<?php }else{ ?>
						<i class="fa fa-user-secret" title="Privado" aria-hidden="true"></i>
						<?php } ?>
					</span>
					<!--<ul class="ODSs">
						<li>
							<img src="imgs/ODS/S-WEB-Goal-2.png" title="Fin de la pobreza" />
						</li>
						<li>
							<img src="imgs/ODS/S-WEB-Goal-12.png" title="Fin de la pobreza" />
						</li>
					</ul>-->
				</div>
			</div>
			<?php } ?>
			<div class="col-xs-12 text-right">
				<button class="button" onclick="nuevoCuaderno()">Crear cuaderno</button>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-md-12">
				<h3 class="dashboard-page-title">Cuadernos
					<small>públicos</small>
				</h3>
				<p>Explora los cuadernos creados por la comunidad de #NuestroPresupuesto.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<label for="nombre">Filtrar:</label>
				<input type="text" id="filtrarCuadernos" class="form-control" onkeyup="do_filtrarCuadernos()" value="<?php if(isset($_GET['q'])){ echo($_GET['q']); } ?>">
			</div>
		</div>
		<!-- .row -->
		<div class="row listCuadernos" id="cuadernosPublicos">
			<?php foreach($DaoCuadernos->getPublicos() as $Cuaderno){ 
				if(!isset($Owners[$Cuaderno->getOwner()])){
					$Owners[$Cuaderno->getOwner()]=$DaoUsuarios->show($Cuaderno->getOwner());
				}
				$Owner=$Owners[$Cuaderno->getOwner()]
			?>
			<div class="col-xs-12 col-md-6 col-lg-4 cuaderno">
				<div class="with_border with_padding">
					<h5><?php echo($Cuaderno->getNombre()); ?></h5>
					<a href="/cuaderno/<?php echo($Cuaderno->getId()); ?>" class="ligaCuaderno"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>
					<p class="descripcion"><?php echo($Cuaderno->getDescripcion()); ?></p>
					<ul class="participantes">
						<li title="<?php echo($Owner->getNombre()); ?>">
							<img src="<?php echo($Owner->getImage()); ?>" />
						</li>
						<?php foreach($Cuaderno->getUsuarios() as $UsuarioCuaderno){ ?>
						<li title="<?php echo($UsuarioCuaderno->getNombre()); ?>">
							<img src="<?php echo($UsuarioCuaderno->getImage()); ?>" />
						</li>
						<?php } ?>
					</ul>
					<span class="publico">
						<?php if($Cuaderno->getPublico()==1){ ?>
						<i class="fa fa-globe" aria-hidden="true"  title="Público"></i>
						<?php }else{ ?>
						<i class="fa fa-user-secret" title="Privado" aria-hidden="true"></i>
						<?php } ?>
					</span>
					<!--<ul class="ODSs">
						<li>
							<img src="imgs/ODS/S-WEB-Goal-2.png" title="Fin de la pobreza" />
						</li>
						<li>
							<img src="imgs/ODS/S-WEB-Goal-12.png" title="Fin de la pobreza" />
						</li>
					</ul>-->
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<!-- .container -->
</section>
<div class="modal fade" id="modalNuevoCuaderno" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Nuevo cuaderno</h5>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="nombre">Nombre del cuaderno de trabajo:</label>
					<input type="text" autocomplete="off" id="nombre" class="form-control" />
					<p class="error">Ingresa un nombre para el cuaderno</p>
				</div>
			</div>
			<div class="modal-footer">
				<button class="button" id="crearCuadernoTrabajo" onclick="crearCuadernoTrabajo()">Crear</button>
			</div>
		</div>
	</div>
</div>
	
</div>
<?php
footerPageAdmin();