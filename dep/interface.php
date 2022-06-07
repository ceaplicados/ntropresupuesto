<?php
$file_script=$_SERVER['SCRIPT_FILENAME'];
$file_script=substr($file_script,0, strpos($file_script,".php"));
while(strpos($file_script,"/")!== false){
	$file_script=substr($file_script, strpos($file_script,"/")+1);
}
include_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoSessions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoUsuarios.php");
include_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoEstados.php");

$DaoSessions= new DaoSessions();
$DaoUsuarios=new DaoUsuarios();
$DaoEstados=new DaoEstados();
$Session=new Sessions();
if(isset($_COOKIE["SessionUID"])){
	$Session=$DaoSessions->getSession($_COOKIE["SessionUID"]);
	$deathSession=strtotime($Session->getDateDeath());
	if($deathSession<time()){
		$Session=new Sessions();
	}
}

$EstadoUsuario=new Estados();
if($Session->getUsuario()>0){
	$Usuario=$DaoUsuarios->show($Session->getUsuario());
	if($Usuario->getEstado()!==NULL){
		$EstadoUsuario=$DaoEstados->show($Usuario->getEstado());
	}
}else{
	$Usuario=new Usuarios();
}

function headerPageAdmin($title="",$descripcion="",$thumb="",$tags=array()){
	global $file_script,$Usuario,$DaoEstados,$EstadoUsuario;
	if($title!==""){
		$title=" | ".$title;
	}
	$url="";
	if($file_script!=="index"){
		$url=$file_script;
	}
	if($thumb==""){
		$thumb="https://www.nuestropresupuesto.mx/imgs/thumb_inicio.jpg";
	}
	?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
	<title>#NuestroPresupuesto | <?php echo($title); ?></title>
	<meta charset="utf-8">
	<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<![endif]-->
	<meta name="description" content="<?php echo($descripcion); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
	<link rel="stylesheet" href="/css/animations.css">
	<link rel="stylesheet" href="/css/main.css" class="color-switcher-link">
	<link rel="stylesheet" href="/css/dashboard.css" class="color-switcher-link">
	<?php if(file_exists("css/$file_script.css")){ ?>
	<link rel="stylesheet" href="/css/<?php echo($file_script); ?>.css">
	<?php } ?>
	
	<script src="https://kit.fontawesome.com/aa9a577cfb.js" crossorigin="anonymous"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z18PG9MX4L"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'G-Z18PG9MX4L');
	</script>
</head>

<body class="admin">
	<!--[if lt IE 9]>
		<div class="bg-danger text-center">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/" class="highlight">upgrade your browser</a> to improve your experience.</div>
	<![endif]-->

	<div class="preloader">
		<div class="preloader_image"></div>
	</div>

	<!-- search modal -->
	<div class="modal" tabindex="-1" role="dialog" aria-labelledby="search_modal" id="search_modal">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">
				<i class="rt-icon2-cross2"></i>
			</span>
		</button>
		<?php /* ?>
		<div class="widget widget_search">
			<form method="get" class="searchform search-form form-inline" action="./">
				<div class="form-group">
					<input type="text" value="" name="search" class="form-control" placeholder="Search keyword" id="modal-search-input">
				</div>
				<button type="submit" class="theme_button">Search</button>
			</form>
		</div>
		<?php */ ?>
	</div>

	<!-- Unyson messages modal -->
	<div class="modal fade" tabindex="-1" role="dialog" id="messages_modal">
		<div class="fw-messages-wrap ls with_padding">
			<!-- Uncomment this UL with LI to show messages in modal popup to your user: -->
			<!--
		<ul class="list-unstyled">
			<li>Message To User</li>
		</ul>
		-->

		</div>
	</div>
	<!-- eof .modal -->

	<!-- Unyson messages modal -->
	<div class="modal fade" tabindex="-1" role="dialog" id="admin_contact_modal">
		<!-- <div class="ls with_padding"> -->
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form class="with_padding contact-form" method="post" action="./">
					<div class="row">
						<div class="col-sm-12">
							<h3>Contact Admin</h3>
							<div class="contact-form-name">
								<label for="name">Full Name
									<span class="required">*</span>
								</label>
								<input type="text" aria-required="true" size="30" value="" name="name" id="name" class="form-control" placeholder="Full Name">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="contact-form-subject">
								<label for="subject">Subject
									<span class="required">*</span>
								</label>
								<input type="text" aria-required="true" size="30" value="" name="subject" id="subject" class="form-control" placeholder="Subject">
							</div>
						</div>

						<div class="col-sm-12">

							<div class="contact-form-message">
								<label for="message">Message</label>
								<textarea aria-required="true" rows="6" cols="45" name="message" id="message" class="form-control" placeholder="Message"></textarea>
							</div>
						</div>

						<div class="col-sm-12 text-center">
							<div class="contact-form-submit">
								<button type="submit" id="contact_form_submit" name="contact_submit" class="theme_button wide_button color1">Send Message</button>
								<button type="reset" id="contact_form_reset" name="contact_reset" class="theme_button wide_button">Clear Form</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- eof .modal -->

	<!-- wrappers for visual page editor and boxed version of template -->
	<div id="canvas">
		<div id="box_wrapper">

			<!-- template sections -->

			<header class="page_header_side page_header_side_sticked active-slide-side-header ds">
				<div class="side_header_logo ds ms">
					<a href="app">
						<span class="logo margin_0">
							<img src="/imgs/logo_blanco.svg" alt="#NuestroPresupuesto">
						</span>
					</a>
				</div>
				<span class="toggle_menu_side toggler_light header-slide">
					<span></span>
				</span>
				<div class="scrollbar-macosx">
					<div class="side_header_inner">

						<!-- user -->
						<?php if($Usuario->getId()>0){ ?>
						<div class="user-menu">
							<ul class="menu-click">
								<li>
									<a href="#">
										<div class="media">
											<div class="media-left media-middle">
												<img src="<?php echo($Usuario->getImage()); ?>" alt="">
											</div>
											<div class="media-body media-middle">
												<h4><?php echo($Usuario->getNombre()); ?></h4>
												<?php 
												if($EstadoUsuario->getId()>0){
													echo($EstadoUsuario->getNombre());
												} ?>

											</div>

										</div>
									</a>
									<ul>
										<?php /* ?>
										<li>
											<a href="admin_profile.html">
												<i class="fa fa-user"></i>
												Profile
											</a>
										</li>
										<li>
											<a href="admin_profile_edit.html">
												<i class="fa fa-edit"></i>
												Edit Profile
											</a>
										</li>
										<li>
											<a href="admin_inbox.html">
												<i class="fa fa-envelope-o"></i>
												Inbox
											</a>
										</li>
										*/ ?>
										<li>
											<a href="app-profile">
												<i class="fa fa-user" aria-hidden="true"></i>
												Mi información
											</a>
										</li>
										<li>
											<a href="logout">
												<i class="fa fa-sign-out"></i>
												Cerrar sesión
											</a>
										</li>
									</ul>
								</li>
							</ul>

						</div>
						<?php } ?>
						<!-- main side nav start -->
						<nav class="mainmenu_side_wrapper">
							<h3 class="dark_bg_color">Dashboard</h3>
							<ul class="menu-click">
								<li>
									<a href="/cuadernos">
										<i class="fa fa-book" aria-hidden="true"></i>
									Cuadernos
									</a>
								</li>
								<li>
									<a href="/">
										<i class="fa fa-th-large"></i>
										Gasto federalizado
									</a>

								</li>
								<?php foreach($DaoEstados->showConVersiones() as $Estado){ ?>
								<li>
									<a href="/<?php echo($Estado->getCodigo()); ?>">
										<i class="fa fa-th-large"></i>
										<?php echo($Estado->getNombre()); ?>
									</a>
								
								</li>
								<?php } ?>
							</ul>
							
							<h3 class="dark_bg_color">Participa</h3>
							<ul class="menu-click">
								<li>
									<a href="#">
										<i class="fa fa-user"></i>
										Capacitaciones
									</a>
									<ul>
										<!--<li>
											<a href="">
							Recaudación
						</a>
										</li>
										<li>
											<a href="">
							Ciclo presupuestal
						</a>
										</li>-->
										<li>
											<a href="https://www.youtube.com/watch?v=OJb6qJMXSrg" target="_blank">
							¿Cómo leer el presupuesto?
						</a>
										</li>
										<li>
											<a href="https://www.youtube.com/watch?v=KrAzDBiD-OA" target="_blank">
							¿Cómo colaborar en el análisis?
						</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="https://www.youtube.com/watch?v=7rwNmEbz5sQ" target="_blank">
										<i class="fas fa-question-circle"></i>
										Instructivo
									</a>
								</li>
							</ul>
						</nav>
						<!-- eof main side nav -->
						<?php /* 
						<div class="with_padding grey text-center">
							10GB of
							<strong>250GB</strong> Free
						</div>  */ ?>

					</div>
				</div>
			</header>

			<header class="page_header header_darkgrey">
				<?php /* 
				<div class="widget widget_search">
					<form method="get" class="searchform form-inline" action="./">
						<div class="form-group">
							<label class="screen-reader-text" for="widget-search-header">Search for:</label>
							<input id="widget-search-header" type="text" value="" name="search" class="form-control" placeholder="Search">
						</div>
						<button type="submit" class="theme_button color1">Search</button>
					</form>
				</div>
				*/ ?>

				<div class="pull-right big-header-buttons">
					<ul class="inline-dropdown inline-block">

						<?php /*
						<li class="dropdown header-notes-dropdown">
							<a class="header-button" data-target="#" href="./" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
								<i class="fa fa-bell-o grey"></i>
								<span class="header-button-text">Messages</span>
								<span class="header-dropdown-number">
									12
								</span>
							</a>

							<div class="dropdown-menu ls">
								<div class="dropdwon-menu-title with_background">
									<strong>12 Pending</strong> Notifications

									<div class="pull-right darklinks">
										<a href="#">View All</a>
									</div>

								</div>
								<ul class="list-unstyled">

									<li>
										<div class="media with_background">
											<div class="media-left media-middle">
												<div class="teaser_icon label-success">
													<i class="fa fa-user"></i>
												</div>
											</div>
											<div class="media-body media-middle">
												<span class="grey">
													New user registered
												</span>
												<span class="pull-right">Just Now</span>
											</div>
										</div>
									</li>
									<li>
										<div class="media with_background">
											<div class="media-left media-middle">
												<div class="teaser_icon label-info">
													<i class="fa fa-bullhorn"></i>
												</div>
											</div>
											<div class="media-body media-middle">
												<span class="grey">
													New user registered
												</span>
												<span class="pull-right">20 minutes</span>
											</div>
										</div>
									</li>

									<li>
										<div class="media with_background">
											<div class="media-left media-middle">
												<div class="teaser_icon label-danger">
													<i class="fa fa-bolt"></i>
												</div>
											</div>
											<div class="media-body media-middle">
												<span class="grey">
													Server overloaded
												</span>
												<span class="pull-right">1 hour</span>
											</div>
										</div>
									</li>

									<li>
										<div class="media with_background">
											<div class="media-left media-middle">
												<div class="teaser_icon label-success">
													<i class="fa fa-shopping-cart"></i>
												</div>
											</div>
											<div class="media-body media-middle">
												<span class="grey">
													New order
												</span>
												<span class="pull-right">3 hours</span>
											</div>
										</div>
									</li>

									<li>
										<div class="media with_background">
											<div class="media-left media-middle">
												<div class="teaser_icon label-warning">
													<i class="fa fa-bell-o"></i>
												</div>
											</div>
											<div class="media-body media-middle">
												<span class="grey">
													Long database query
												</span>
												<span class="pull-right">4 hours</span>
											</div>
										</div>
									</li>

									<li>
										<div class="media with_background">
											<div class="media-left media-middle">
												<div class="teaser_icon label-success">
													<i class="fa fa-user"></i>
												</div>
											</div>
											<div class="media-body media-middle">
												<span class="grey">
													New user registered
												</span>
												<span class="pull-right">4 hours</span>
											</div>
										</div>
									</li>

								</ul>
							</div>
						</li>

						<li class="dropdown header-notes-dropdown">
							<a class="header-button" data-target="#" href="./" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
								<i class="fa fa-envelope-o grey"></i>
								<span class="header-button-text">Inbox</span>
								<span class="header-dropdown-number">
									8
								</span>
							</a>

							<div class="dropdown-menu ls">
								<div class="dropdwon-menu-title with_background">
									<strong>8 New</strong> Messages

									<div class="pull-right darklinks">
										<a href="#">View All</a>
									</div>

								</div>
								<ul class="list1 no-bullets no-top-border no-bottom-border">

									<li>
										<div class="media">
											<div class="media-left">
												<img src="images/team/01.jpg" alt="...">
											</div>
											<div class="media-body">
												<h5 class="media-heading">
													<a href="#">
													Alex Walker <span class="pull-right">23 feb at 11:36</span>
												</a>
												</h5>
												<div>
													Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum, corporis.
												</div>
											</div>
										</div>
									</li>
									<li>
										<div class="media">
											<div class="media-left">
												<img src="images/team/02.jpg" alt="...">
											</div>
											<div class="media-body">
												<h5 class="media-heading">
													<a href="#">
													Janet C. Donnalds <span class="pull-right">23 feb at 12:17</span>
												</a>
												</h5>
												<div>
													Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero itaque dolor.
												</div>
											</div>
										</div>
									</li>
									<li>
										<div class="media">
											<div class="media-left">
												<img src="images/team/03.jpg" alt="...">
											</div>
											<div class="media-body">
												<h5 class="media-heading">
													<a href="#">
													Victoria Grow <span class="pull-right">23 feb at 16:44</span>
												</a>
												</h5>
												<div>
													Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore, esse, magni.
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</li>
						*/ ?>
						<?php /* ?>
						<li class="dropdown header-notes-dropdown">
							<a class="header-button" data-target="#" href="./" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
								<i class="fa fa-calendar-o grey"></i>
								<span class="header-button-text">User</span>
							</a>
							<div class="dropdown-menu ls">

								<div class="dropdwon-menu-title with_background">
									<strong>14 Pending</strong> Tasks

									<div class="pull-right darklinks">
										<a href="#">View All</a>
									</div>

								</div>

								<ul class="list-unstyled">

									<li>
										<p class="progress-bar-title grey">
										<strong>Progress</strong>
									</p>
										<div class="progress">
											<div class="progress-bar progress-bar-success" role="progressbar" data-transitiongoal="90">
												<span>90%</span>
											</div>
										</div>
									</li>

									<li>
										<p class="progress-bar-title grey">
										<strong>Success</strong>
									</p>
										<div class="progress">
											<div class="progress-bar progress-bar-info" role="progressbar" data-transitiongoal="52">
												<span>52%</span>
											</div>
										</div>
									</li>

									<li>
										<p class="progress-bar-title grey">
										<strong>Knowing</strong>
									</p>
										<div class="progress">
											<div class="progress-bar progress-bar-warning" role="progressbar" data-transitiongoal="75">
												<span>75%</span>
											</div>
										</div>
									</li>

									<li>
										<p class="progress-bar-title grey">
										<strong>Rating</strong>
									</p>
										<div class="progress">
											<div class="progress-bar progress-bar-danger" role="progressbar" data-transitiongoal="95">
												<span>95%</span>
											</div>
										</div>
									</li>

								</ul>

							</div>

						</li>
						<?php */ ?>

					<?php if($Usuario->getId()>0){ ?>
					<li class="dropdown user-dropdown-menu">
						<a class="header-button" id="user-dropdown-menu" data-target="#" href="./" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
							<i class="fa fa-user grey"></i> <span class="header-button-text">User</span>
						</a>
						<div class="dropdown-menu ls">
							<ul class="nav darklinks">
								<!--<li>
									<a href="admin_profile.html">
										<i class="fa fa-user"></i>
										Profile
									</a>
								</li>
								<li>
									<a href="admin_profile_edit.html">
										<i class="fa fa-edit"></i>
										Edit Profile
									</a>
								</li>-->
								<li>
									<a href="/profile">
										<i class="fa fa-user" aria-hidden="true"></i>
										Mi información
									</a>
								</li>
								<li><a href="/cuadernos">
									<i class="fa fa-book" aria-hidden="true"></i>
									Mis cuadernos
								</a></li>
								<li>
									<a href="/logout">
										<i class="fa fa-sign-out"></i>
										Cerrar sesión
									</a>
								</li>
							</ul>

						</div>

					</li>
					<?php }else{ ?>
					<li class=""><a class="btn-small header-button" href="/login">Iniciar sesión</a></li>
					<?php } ?>
						<li class="dropdown visible-xs-inline-block">
							<a href="#" class="search_modal_button header-button">
								<i class="fa fa-search grey"></i>
								<span class="header-button-text">Search</span>
							</a>
						</li>
					</ul>
				</div>
				<!-- eof .header_right_buttons -->
			</header>
			<?php if($Usuario->getId()>0 && $Usuario->getEstado()==NULL){ ?>
			<div class="modal bd-example-modal-sm" tabindex="-1" role="dialog" id="modalSelecEstado">
				<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<div class="form-group">
								<label for="estadoUsuario">Selecciona tu estado:</label>
								<select id="estadoUsuario" class="custom-select">
									<option>...</option>
									<option value="14">Jalisco</option>
									<option value="9">CDMX</option>
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<a id="btn_estadoUsuario" name="estadoUsuario_submit" class="btn btn-primary theme_button color1" onclick="guardarEstadoUsuario()">Guardar</a>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
<?php
}
function footerPageAdmin(){
	global $file_script;
	?>

</div>
<!-- eof #box_wrapper -->
</div>
<!-- eof #canvas -->

<?php /* 
<!-- chat -->
<div class="side-dropdown side-chat dropdown">
<a class="side-button side-chat-button" id="chat-dropdown"  href="https://participa.centrodeestudiosaplicados.org/processes/presupuesto2021/f/1/" target="_blank" aria-haspopup="true" aria-expanded="false">
	<i class="fa fa-comments-o"></i>
</a>


</div>
<!-- .chat-dropdown -->


<a class="side-button side-contact-button" data-target="#admin_contact_modal" href="#admin_contact_modal" data-toggle="modal" role="button">
<i class="fa fa-envelope"></i>
</a>
*/ ?>

<div id="toast"></div>

<!-- template init -->
<script src="/js/compressed.js"></script>
<script src="/js/main.js"></script>

<!-- dashboard libs -->

<!-- events calendar 
<script src="js/admin/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.js"></script>
<!-- range picker 
<script src="js/admin/daterangepicker.js"></script> -->

<!-- charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js" integrity="sha512-sW/w8s4RWTdFFSduOTGtk4isV1+190E/GghVffMA9XczdJ2MDzSzLEubKAs5h0wzgSJOQTRYyaz73L3d6RtJSg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- vector map -->
<script src="/js/admin/jquery-jvectormap-2.0.3.min.js"></script>
<script src="/js/admin/jquery-jvectormap-world-mill.js"></script>
<!-- small charts -->
<script src="/js/admin/jquery.sparkline.min.js"></script>

<!-- dashboard init -->
<?php if(file_exists("js/$file_script.js")){ ?>
	<script src="/js/<?php echo($file_script); ?>.js"></script>
<?php } ?>
</body>

</html>
<?php
}