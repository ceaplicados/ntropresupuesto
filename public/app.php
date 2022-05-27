<?php
require_once("../dep/interface.php");

$descripcion="La app de #NuestroPresupuesto es una plataforma se pueden visualizar los presupuestos desde distintas perspectivas: ¿Quién lo gasta?, ¿En qué se gasta?, ¿Cómo se gasta?. La app es un esfuerzo para que la ciudadanía pueda entender mejor la información presupuestal publicada por el Estado.";
$tags=array();
$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_app.jpg";
$title="Dashboard";
headerPageAdmin($title,$descripcion,$thumb,$tags);
	?>
<section class="ls with_bottom_border">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<ol class="breadcrumb darklinks">
					<li><a href="/">Dashboard</a></li>
					<li class="active">Gasto Federalizado</li>
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
				<h3 class="dashboard-page-title">Dashboard
					<small>Gasto federalizado</small>
				</h3>
				<p>Miles de millones de pesos a valores del 2022.</p>
			</div>
		</div>
		<!-- .row -->
		<div class="row">
			<!-- Presupuesto total -->
			<div class="col-xs-12 col-md-6">
				<div class="with_border with_padding">
					<h4>Presupuesto federal</h4>
					<div class="canvas-chart-wrapper">
						<canvas id="canvas-chart-line-presupuesto-federal" class="canvas-chart-line"></canvas>
					</div>
				</div>
			</div>
			<!-- .col-* -->

			<!-- Histórico del gasto federalizado -->
			<div class="col-xs-12 col-md-6">
				<div class="with_border with_padding">
					<h4>Gasto federalizado</h4>
					<div class="canvas-chart-wrapper">
						<canvas id="canvas-chart-line-gasto-federalizado-historico" class="canvas-chart-line"></canvas>
					</div>
				</div>
			</div>
			<!-- .col-* -->
			<!-- Composición del gasto federalizado -->
				<div class="col-xs-12 col-md-6">
					<div class="with_border with_padding">
						<h4>Gasto federalizado 2022</h4>
						<div class="canvas-chart-wrapper">
							<canvas id="canvas-chart-donut-gasto-federalizado-composicion" class="canvas-chart-donut"></canvas>
						</div>
					</div>
				</div>
				<!-- .col-* -->
		</div>
		<!-- .row -->

	</div>
	<!-- .container -->
</section>
<?php
footerPageAdmin();