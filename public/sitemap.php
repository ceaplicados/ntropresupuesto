<?php 
header("Content-type: text/xml");
require_once("../dep/clases/DaoEstados.php");
require_once("../dep/clases/DaoVersionesPresupuesto.php");
require_once("../dep/clases/DaoINPC.php");
require_once("../dep/clases/DaoUnidadPresupuestal.php");
require_once("../dep/clases/DaoUnidadResponsable.php");
require_once("../dep/clases/DaoProgramas.php");
$DaoEstados=new DaoEstados();
$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();
$DaoINPC=new DaoINPC();
$DaoUnidadPresupuestal=new DaoUnidadPresupuestal();
$DaoUnidadResponsable=new DaoUnidadResponsable();
$DaoProgramas=new DaoProgramas();
$UnidadesPresupuestales=array();
foreach($DaoUnidadPresupuestal->showAll() as $UnidadPresupuestal){
	$UnidadesPresupuestales[$UnidadPresupuestal->getId()]=$UnidadPresupuestal;
}
?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>https://app.nuestropresupuesto.mx/</loc>
		<lastmod>2022-11-02</lastmod>
	</url>
	<?php foreach($DaoEstados->showConVersiones() as $Estado){ ?>
	<url>
		<loc>https://app.nuestropresupuesto.mx/<?php echo($Estado->getCodigo()); ?></loc>
		<lastmod>2022-11-02</lastmod>
	</url>
	<?php foreach($DaoUnidadResponsable->getByEstado($Estado->getId()) as $UnidadResponsable){ ?>
	<url>
		<loc>https://app.nuestropresupuesto.mx/<?php echo($Estado->getCodigo()); ?>/ur/<?php echo($UnidadesPresupuestales[$UnidadResponsable->getUnidadPresupuestal()]->getClave()); ?>-<?php echo($UnidadResponsable->getClave()); ?></loc>
		<lastmod>2022-11-02</lastmod>
	</url>
	<?php foreach($DaoProgramas->searchByEstado($Estado->getId(),NULL,array($UnidadResponsable->getId())) as $ProgramaPresupuestal){ ?>
	<url>
		<loc>https://app.nuestropresupuesto.mx/<?php echo($Estado->getCodigo()); ?>/programa/<?php echo($UnidadesPresupuestales[$UnidadResponsable->getUnidadPresupuestal()]->getClave()); ?>-<?php echo($UnidadResponsable->getClave()); ?>-<?php echo($ProgramaPresupuestal->getClave()); ?></loc>
		<lastmod>2022-11-02</lastmod>
	</url>
	<?php 
	}
	}
	} ?>
</urlset>