<?php
require_once("../dep/interface.php");
$title="Iniciar sesión";
$descripcion="La app de #NuestroPresupuesto es una plataforma se pueden visualizar los presupuestos desde distintas perspectivas: ¿Quién lo gasta?, ¿En qué se gasta?, ¿Cómo se gasta?. La app es un esfuerzo para que la ciudadanía pueda entender mejor la información presupuestal publicada por el Estado.";
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
headerPageAdmin($title,$descripcion,$thumb,$tags);
	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/">Dashboard</a></li>
					<li class="active">Iniciar sesión</li>
				</ol>
			</div>
		</div>
		<!-- .row -->
	</div>
	<!-- .container -->
</section>

<section class="ls section_padding_top_50 section_padding_bottom_50 columns_padding_10">
	<div class="container-fluid">

		<!-- .row -->
		<div class="row">
			<!-- Histórico del gasto federalizado -->
			<div class="col-xs-12 col-md-6 col-md-offset-3">
				<div class="aligncenter" id="loginArea">
					<h4>Inicia sesión</h4>
					<a href="https://accounts.google.com/o/oauth2/auth?scope=<?php echo(urlencode("profile email")); ?>&redirect_uri=https%3A%2F%2F<?php echo $DaoUsuarios->getParam('dominio'); ?>%2Foauth_Google&response_type=code&client_id=<?php echo $DaoUsuarios->getParam('Google_ClientId'); ?>&access_type=offline&approval_prompt=auto" class="btn"><img id="loginWithGoogle" src="imgs/sing_with_google.svg" title="Inicia sesión con Google" class="right" style="vertical-align: bottom;"></a>
				</div>
			</div>
			
		</div>
	</div>
	<!-- .container -->
</section>
<?php
footerPageAdmin();