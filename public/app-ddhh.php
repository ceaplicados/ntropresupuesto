<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoUnidadResponsable.php");
require_once("../dep/clases/DaoPropuestaProgramaODS.php");
$DaoUnidadResponsable=new DaoUnidadResponsable();
$DaoPropuestaProgramaODS=new DaoPropuestaProgramaODS();

$descripcion="Ejercicio colaborativo para clasificar los programas presupuestales de acuerdo a los Objetivos de Desarrollo Sostenible.";
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
$title="Presupuesto con perspectiva de derechos";
headerPageAdmin($title,$descripcion,$thumb,$tags);
	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/">Dashboard</a></li>
					<li class="active">Presupuesto con perspectiva de derechos</li>
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
			<div class="col-md-12 mb-3">
				<h3 class="dashboard-page-title">Presupuesto con perspectiva de derechos</h3>
				<p>Colabora para clasificar el presupuesto de acuerdo a los Objetivos de Desarrollo Sostenible.</p>
			</div>
			<div id="interfazPropuesta" class="paso0">
				<div class="col-md-12">
					<h4>Realiza una propuesta</h4>
					<button class="button theme_button" id="do_comenzarPropuesta" onclick="setPasoPropuesta(1)">Comenzar</button>
				</div>
				<div id="indicadoresPropuesta">
					<div class="col-sm-3">
						<div class="indicadorPropuesta paso1"></div>
					</div>
					<div class="col-sm-3">
						<div class="indicadorPropuesta paso2"></div>
					</div>
					<div class="col-sm-3">
						<div class="indicadorPropuesta paso3"></div>
					</div>
					<div class="col-sm-3">
						<div class="indicadorPropuesta paso4"></div>
					</div>
				</div>
				<div id="paso1">
					<div class="col-md-12">
						<h6>1. Selecciona una unidad responsable</h6>
						<p>Las unidades responsables son las dependencias del gobierno u órganos autónomos que ejercen presupuesto público.</p>
						<div class="row mb-3">
							<div class="col-md-6">
								<input type="text" class="form-control" id="filtrarURs" onkeyup="do_filtrarURs()" placeholder="Filtrar Unidad Responsable" />
							</div>
						</div>
						<ul id="unidadesResponsables">
							<?php foreach($DaoUnidadResponsable->pendientesPresupuestoDDHHbyEstado(14) as $UnidadResponsable){ ?>
							<li data-id="<?php echo($UnidadResponsable->getId()); ?>" onclick="selectUR(this)">
								<?php echo $UnidadResponsable->getNombre(); ?>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div id="paso2">
					<div class="col-md-12">
						<h6>2. Selecciona un programa presupuestal de <span class="ur"></span></h6>
						<ul id="programasUR">
							
						</ul>
					</div>
				</div>
				<div id="paso3">
					<div class="col-md-12">
						<h6>3. Selecciona los ODS y metas a los que abona.</h6>
						<p>Da click sobre un ODS para proponerlo como un objetivo al que este programa presupuestal abona, además puedes seleccionar las metas específicas a las que el programa aporta.  Puedes ver el detalle del programa y sus indicadores dando clic sobre el ícono del enlace.</p>
						<p class="programa"></p>
					</div>
					<div class="col-md-6 ODSs">
						<?php for ($i = 1; $i <= 17; $i++){ ?>
						<img src="/imgs/ODS/S-WEB-Goal-<?php echo($i); ?>.png" class="ODS" data-id="<?php echo($i); ?>" onclick="selectODS(this)"/>
						<?php } ?>
					</div>
					<div class="col-md-6 metasODSs">
						<p>ODS seleccionados:</p>
						<div id="ODSseleccionados"></div>
						<p>Metas seleccionadas:</p>
						<ul id="metasSeleccionadas" class="list-group">
							<li class="sinMeta list-group-item"><i>Comienza seleccionando un ODS</i></li>
						</ul>
						<div id="metasODS">
							<p>Selecciona una o más metas del ODS <span class="numero"></span></p>
							<ul id="selecionarMetas" class="list-group"></ul>
						</div>
					</div>
					<div class="col-md-12 text-center">
						<button class="button theme_button" onclick="confirmarODS()">Confirmar</button>
					</div>
				</div>
				<div id="paso4">
					<div class="row">
						<div class="col-md-12">
							<h6>4. Confirma la información.</h6>
							<p class="programa"></p>
							<p><a href="#" onclick="setPasoPropuesta(3)">Regresar al paso 3</a></p>
						</div>
						<div class="col-md-6">
							<p>ODSs seleccionados</p>
							<p class="instruccionPrincipal">Da click sobre un ODS para señalarlo como al que principalmente abona este programa.</p>
							<div id="ODSseleccionadosConfirm"></div>
						</div>
						<div class="col-md-6">
							<p>Metas seleccionadas</p>
							<ul id="metasSeleccionadasConfirm" class="list-group row"></ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6 form-group">
							<label>Explica brevemente por qué tu propuesta de clasificación</label>
							<textarea id="argumentacion" class="form-control"></textarea>
						</div>
						<div class="col-md-12 text-center">
							<button class="button theme_button" id="guardarPropuesta" onclick="do_guardarPropuesta()">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!--
			<div class="col-md-12">
				<hr/>
			</div>
			
			<div class="col-md-12">
				<h4>Revisa las propuestas de la comunidad</h4>
			</div>
			-->
			<div class="col-md-12">
				<hr/>
			</div>
			<div class="col-md-12">
				<h4>Mis propuestas</h4>
				<table class="table" id="misPropuestas">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Programa</th>
							<th>ODS y metas</th>
							<th>Justificación</th>
							<th>Estatus</th>
						</tr>
					</thead>
					<tbody>
						<tr class="sinPropuestas">
							<td colspan="5">Sin propuestas</td>
						</tr>
					</tbody>
				</table>
			</div>
		
		</div>
		<?php }else{ ?>
		<div class="row">
			<div class="col-md-12">
				<h3 class="dashboard-page-title">¿Quieres saber cuánto invierte el gobierno para que puedas acceder a tus derechos humanos?</h3>
			</div>
			<div class="col-md-6">
				<p>Con tu ayuda podemos <b>clasificar los programas presupuestales</b> para saber cómo es que el gobierno está gastando el dinero público para que podamos tener acceso a nuestros derechos.</p>
				<p>Te presentaremos los programas presupuestales para que puedas sugerir a qué objetivo del desarrollo sostenible abona de manera directa o indirecta, podrás revisar las propuestas realizadas por otras personas y proponer correcciones.</p>
				<p>Para ayudarnos con la clasificación inicia sesión o crea una cuenta:</p>
				<p class="text-center">
					<a href="https://accounts.google.com/o/oauth2/auth?scope=<?php echo(urlencode("profile email")); ?>&redirect_uri=https%3A%2F%2F<?php echo $DaoUsuarios->getParam('dominio'); ?>%2Foauth_Google&response_type=code&client_id=<?php echo $DaoUsuarios->getParam('Google_ClientId'); ?>&access_type=offline&approval_prompt=auto" class="btn"><img id="loginWithGoogle" src="imgs/sing_with_google.svg" title="Inicia sesión con Google" class="right" style="vertical-align: bottom;"></a>
				</p>
			</div>
			<div class="col-md-6">
				<?php for ($i = 1; $i <= 17; $i++){ ?>
				<img src="/imgs/ODS/S-WEB-Goal-<?php echo($i); ?>.png" class="metaODS"/>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
	<!-- .container -->
</section>
<?php
footerPageAdmin();