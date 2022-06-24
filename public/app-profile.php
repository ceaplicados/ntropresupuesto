<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoEstados.php");
$DaoEstados=new DaoEstados();

$descripcion="Administra tu información";
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
$title="Mi perfil de usuario";
headerPageAdmin($title,$descripcion,$thumb,$tags);
	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/">Dashboard</a></li>
					<li class="active">Mi perfil</li>
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
				<h3 class="dashboard-page-title">Mi perfil
					<small></small>
				</h3>
			</div>
		</div>
		<!-- .row -->
		<div class="row">
			<!-- Presupuesto total -->
			<div class="col-xs-12 col-md-6">
				<div class="with_border with_padding">
					<h4>Mis datos</h4>
					<div class="form-group mb-3">
						<label for="nombre">Nombre completo</label>
						<input type="text" id="nombre" class="form-control" value="<?php echo($Usuario->getNombre()); ?>"/>
					</div>
					<div class="form-group mb-3">
						<label for="sobrenombre">¿Cómo te gusta que te llamen?</label>
						<input type="text" id="sobrenombre" class="form-control" value="<?php echo($Usuario->getSobrenombre()); ?>"/>
					</div>
					<div class="form-group mb-3">
						<label for="email">Correo electrónico</label>
						<input type="email" id="email" class="form-control" value="<?php echo($Usuario->getEmail()); ?>"/>
						<p class="error">Proporciona un correo electrónico correcto</p>
					</div>
					<div class="form-group mb-3">
							<label for="telefono">Teléfono</label>
							<input type="number" id="telefono" class="form-control" value="<?php echo($Usuario->getTelefono()); ?>"/>
							<p class="error">Proporciona tu teléfono a 10 dígitos</p>
						</div>
					<div class="form-group mb-4">
						<label for="estado">Estado de procedencia/preferencia</label>
						<select id="estado" class="form-control">
							<option value="" disabled selected>Selecciona un estado</option>
							<?php foreach($DaoEstados->showAll() as $Estado){ ?>
							<option value="<?php echo($Estado->getId()); ?>" <?php if($Estado->getId()==$Usuario->getEstado()){ echo('selected'); } ?>><?php echo($Estado->getNombre()); ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group mb-3 text-right">
						<button class="button" id="guardarUsuario" onclick="guardarUsuario()">Guardar</button>
					</div>
				</div>
			</div>
			<div class="col-xs-12">
				<p>Consulta nuestro aviso de privacidad en: <a href="https://www.nuestropresupuesto.mx/privacidad" target="_blank">nuestropresupuesto.mx/privacidad</a></p>
			</div>
			<!-- .col-* -->
	</div>
	<!-- .container -->
</section>
<?php
footerPageAdmin();