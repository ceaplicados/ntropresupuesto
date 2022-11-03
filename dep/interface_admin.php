<?php
exit();
require_once($_SERVER['DOCUMENT_ROOT'].'/../dep/clases/DaoUsuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../dep/clases/DaoSessions.php');
$DaoUsuarios=new DaoUsuarios();
$DaoSessions=new DaoSessions();

$Usuario=new Usuarios();
$Session=new Sessions();

if(isset($_COOKIE["SessionUID"])){
	$Session=$DaoSessions->getSession($_COOKIE["SessionUID"]);
}

if(!$Session->getId()>0){
	$Session=new Sessions();
}

$file_script=$_SERVER['SCRIPT_FILENAME'];
$file_script=substr($file_script,0, strpos($file_script,".php"));
while(strpos($file_script,"/")!== false){
	$file_script=substr($file_script, strpos($file_script,"/")+1);
}

$noSessionFiles=array();
array_push($noSessionFiles, "login");
array_push($noSessionFiles, "forgot_password");
array_push($noSessionFiles, "logout");
array_push($noSessionFiles, "recover_pss");
array_push($noSessionFiles, "presupuestos");
array_push($noSessionFiles, "inconsistencias_presupuestos");

if(in_array($file_script, $noSessionFiles)==false && !$Session->getId()>0 && $_POST['action']!=="getSession"){
	header('Location: /admin/login?url='.urlencode($_SERVER["REQUEST_URI"]));
	exit();
}
if(in_array($file_script, $noSessionFiles)==false && strtotime($Session->getDateDeath())<time() && $_POST['action']!=="getSession"){
	header('Location: /admin/login?url='.urlencode($_SERVER["REQUEST_URI"]));
	exit();
}
if($Session->getId()>0){
	$Usuario=$DaoUsuarios->show($Session->getId());
}

function interfaceHeader($titulo="",$descripcion=""){
	global $Usuario,$file_script,$DaoUsuarios;
	?><!doctype html>
<html class="no-js" lang="es">

<head>
  <meta charset="utf-8">
  <title><?php echo($titulo); ?></title>
  <meta name="description" content="<?php echo($descripcion); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="/admin/css/main.css">
  <?php if(file_exists($_SERVER['DOCUMENT_ROOT']."/admin/css/$file_script.css")){ ?>
  <link rel="stylesheet" href="/admin/css/<?php echo($file_script); ?>.css">
  <?php } ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Material+Icons&display=swap" rel="stylesheet">

  <meta name="theme-color" content="#fafafa">
  <?php /*
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-365B9XNVNT"></script>
  <script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
  
	gtag('config', 'G-365B9XNVNT');
  </script>
   */ ?>
</head>

<body>
	<?php if($Usuario->getId()>0){ ?>
	<span class="material-icons cursor" data-bs-toggle="offcanvas" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" id="toogleMenu">menu</span>
	<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="menuPrincipal" aria-labelledby="menuPrincipalLabel">
		<div class="offcanvas-header">
			<a href="/admin"><h5 class="offcanvas-title" id="menuPrincipalLabel">
					<img src="/admin/img/logo.png"/>
				</h5></a>
			<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<nav class="navbar">
				<ul class="navbar-nav flex-grow-1 pe-3">
					<li class="nav-item">
						<a class="nav-link" href="/admin"><span class="material-icons">dashboard</span> Inicio</a>
					</li>
				</ul>
			</nav>
		</div>
	</div><?php
	}
}

function interfaceFooter(){
	global $file_script;
	?>
	<div class="modal fade" id="modalReportarError" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalReportarErrorLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalReportarErrorLabel">Reporta un error</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Sentimos mucho que hayas tenido una mala experiencia, tu reporte nos servirá para mejorar Pouki.</p>
					<div class="form-group">
						<label>Ayúdanos a entender describiendo el problema que tuviste:</label>
						<textarea id="comentariosError" class="form-control"></textarea>
					</div>
					<p class="notaError">Junto con tus comentarios recolectaremos información que nos servirá para determinar la causa del error. <a onclick="showInfoAdicionalError()">Ver la información que será enviada.</a></p>
					<div class="infoAdicional"></div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-primary" id="btnEnviarError" onclick="do_enviarError()">Enviar</button>
				</div>
			</div>
		</div>
	</div>
	<div aria-live="polite" aria-atomic="true" id="toastContainer">
		<div id="toastList">
			<div class="toast align-items-center" id="toastEx" role="alert" aria-live="assertive" aria-atomic="true">
				  <div class="d-flex">
					  <div class="toast-body">
					  Hello, world! This is a toast message.
					  </div>
					  <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
				  </div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
	<script src="/admin/js/plugins.js"></script>
	<script src="/admin/js/main.js"></script>
	<?php if(file_exists($_SERVER['DOCUMENT_ROOT']."/admin/js/$file_script.js")){ ?>
	<script src="/admin/js/<?php echo($file_script); ?>.js"></script>
	<?php } ?>
</body>

</html>

	<?php
}