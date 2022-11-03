<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../../dep/interface_admin.php");
require_once("../../dep/clases/DaoVersionesPresupuesto.php");
require_once("../../dep/clases/DaoUnidadPresupuestal.php");
require_once("../../dep/clases/DaoUnidadResponsable.php");
require_once("../../dep/clases/DaoEstados.php");
$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();
$DaoUnidadPresupuestal=new DaoUnidadPresupuestal();
$DaoUnidadResponsable=new DaoUnidadResponsable();
$DaoEstados=new DaoEstados();

$Version=$DaoVersionesPresupuesto->show($_GET["Id"]);
$Estado=$DaoEstados->show($Version->getEstado());
$UPs=array();
foreach($DaoUnidadPresupuestal->getByEstado($Version->getEstado()) as $UnidadPresupuestal ){
	$UPs[$UnidadPresupuestal->getId()]=$UnidadPresupuestal;
}
interfaceHeader("Inconsistencias presupuestos");
$totalOG=0;
$totalPP=0;
$totalDif=0;
?>
	<div class="container">
		<h5>Inconsistencias en <?php echo($Version->getNombre()); ?> <?php echo($Version->getAnio()); ?> de <?php echo($Estado->getNombre()); ?></h5>
		<table>
			<thead>
				<tr>
					<th>Id</th>
					<th>UR</th>
					<th>Unidad Responsable</th>
					<th>Por OG</th>
					<th>Por PP</th>
					<th>Diferencia</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($DaoUnidadResponsable->getByEstado($Version->getEstado()) as $UR){ 
					$porOG=$DaoUnidadResponsable->getPresupuestoByVersion($Version->getId(),$UR->getId());
					if(count($porOG)>0){
						$porOG=$porOG[0]->getMonto();
					}else{
						$porOG=0;
					}
					$porPP=$DaoUnidadResponsable->getPresupuestoByVersionFromPP($Version->getId(),$UR->getId());
					if(count($porPP)>0){
						$porPP=$porPP[0]->getMonto();
					}else{
						$porPP=0;
					}
					$totalOG+=$porOG;
					$totalPP+=$porPP;
					$totalDif+=abs($porOG-$porPP);
				?>
				<tr>
					<td><?php echo($UR->getId()); ?></td>
					<td><?php echo($UPs[$UR->getUnidadPresupuestal()]->getClave()."-".$UR->getClave()); ?></td>
					<td><?php echo($UR->getNombre()); ?></td>
					<td><?php echo(number_format($porOG,2)); ?></td>
					<td><?php echo(number_format($porPP,2)); ?></td>
					<td><?php echo(number_format($porOG-$porPP,2)); ?></td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td>Total</td>
					<td><?php echo(number_format($totalOG,2)); ?></td>
					<td><?php echo(number_format($totalPP,2)); ?></td>
					<td><?php echo(number_format($totalDif,2)); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
<?php 
interfaceFooter();