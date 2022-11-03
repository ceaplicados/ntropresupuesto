<?php 
exit();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require $_SERVER['DOCUMENT_ROOT'].'/../dep/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoUnidadPresupuestal.php");
require_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoUnidadResponsable.php");
require_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoVersionesPresupuesto.php");
require_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoPartidasGenericas.php");
require_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoObjetoDeGasto.php");
require_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoProgramas.php");
require_once($_SERVER['DOCUMENT_ROOT']."/../dep/clases/DaoProgramasMonto.php");

$DaoUnidadPresupuestal=new DaoUnidadPresupuestal();
$DaoUnidadResponsable=new DaoUnidadResponsable();
$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();
$DaoPartidasGenericas=new DaoPartidasGenericas();
$DaoObjetoDeGasto=new DaoObjetoDeGasto();
$DaoProgramas=new DaoProgramas();
$DaoProgramasMonto=new DaoProgramasMonto();

if(isset($_POST['action'])){
	if($_POST['action']=="readFile"){
		$resp=array();
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/../files/".$_POST["nonce"].".xlsx");
		$i=1;
		$blanks=0;
		do{
			$valor=$spreadsheet->getActiveSheet()->getCell(chr(64+$i).'1')->getValue();
			if(strlen($valor)>0){
				$column=array();
				$column["letra"]=chr(64+$i);
				$column["valor"]=$valor;
				array_push($resp, $column);
			}else{
				$blanks+=1;
			}
			$i+=1;
		}while($blanks<3 && $i<20);
		
		echo(json_encode($resp));
	}
	if($_POST['action']=="missingUPUR"){
		$resp=array();
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/../files/".$_POST["nonce"].".xlsx");
		
		$resp["actualUP"]=array();
		$arrayUP=array();
		foreach($DaoUnidadPresupuestal->getByEstado($_POST["Estado"]) as $UP){
			if(!isset($resp["actualUP"][$UP->getClave()])){
				$resp["actualUP"][$UP->getClave()]=array();
				$resp["actualUP"][$UP->getClave()]["Nombre"]=$UP->getNombre();
				$resp["actualUP"][$UP->getClave()]["Id"]=$UP->getId();
				$arrayUP[$UP->getId()]=$UP;
			}
		}
		$resp["actualUR"]=array();
		foreach($DaoUnidadResponsable->getByEstado($_POST["Estado"]) as $UR){
			$Clave=$arrayUP[$UR->getUnidadPresupuestal()]->getClave()."-".$UR->getClave();
			if(!isset($resp["actualUR"][$Clave])){
				$resp["actualUR"][$Clave]=array();
				$resp["actualUR"][$Clave]["Nombre"]=$UR->getNombre();
				$resp["actualUR"][$Clave]["IdUP"]=$UR->getUnidadPresupuestal();
				$resp["actualUR"][$Clave]["ClaveUP"]=$arrayUP[$UR->getUnidadPresupuestal()]->getClave();
				$resp["actualUR"][$Clave]["NombreUP"]=$arrayUP[$UR->getUnidadPresupuestal()]->getNombre();
				$resp["actualUR"][$Clave]["Id"]=$UR->getId();
			}
		}
		$i=2;
		$blanks=0;
		$resp["missingUP"]=array();
		do{
			$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
			if(strlen($UP)>0){
				if(!isset($resp["actualUP"][$UP])){
					if(!in_array($UP, $resp["missingUP"])){
						array_push($resp["missingUP"], $UP);
					}
				}
			}else{
				$blanks+=1;
			}
			$i+=1;
		}while($blanks<3);
		$resp["maxRow"]=$i;
		
		$i=2;
		$blanks=0;
		$resp["missingUR"]=array();
		do{
			$UR=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUR"].$i)->getValue();
			if(strlen($UR)>0){
				$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
				$UR=$UP."-".$UR;
				if(!isset($resp["actualUR"][$UR])){
					if(!in_array($UR, $resp["missingUR"])){
						array_push($resp["missingUR"], $UR);
					}
				}
			}else{
				$blanks+=1;
			}
			$i+=1;
		}while($blanks<3);
		echo(json_encode($resp));
	}
	
	if($_POST['action']=="crearUP_UR"){
		$fecha=strtotime(str_replace(",", "", $_POST['Fecha']));
		$VersionesPresupuesto=new VersionesPresupuesto();
		$VersionesPresupuesto->setEstado($_POST['Estado']);
		$VersionesPresupuesto->setAnio($_POST['Anio']);
		$VersionesPresupuesto->setNombre($_POST['tipoPresupuesto']);
		$VersionesPresupuesto->setDescripcion($_POST['Descripcion']);
		$VersionesPresupuesto->setFecha(date("Y-m-d",$fecha));
		$VersionesPresupuesto->setObjetoGasto(1);
		$VersionesPresupuesto->setActual(0);
		$VersionesPresupuesto=$DaoVersionesPresupuesto->add($VersionesPresupuesto);
		
		if(isset($_POST['newUPs'])){
			foreach($_POST['newUPs'] as $newUP){
				$UnidadPresupuestal=new UnidadPresupuestal();
				$UnidadPresupuestal->setClave($newUP['Clave']);
				$UnidadPresupuestal->setNombre($newUP['Nombre']);
				$UnidadPresupuestal->setEstado($_POST['Estado']);
				$UnidadPresupuestal=$DaoUnidadPresupuestal->add($UnidadPresupuestal);
			}
		}
		
		$arrayUP=array();
		$arrayUPbyId=array();
		foreach($DaoUnidadPresupuestal->getByEstado($_POST["Estado"]) as $UP){
			$arrayUP[$UP->getClave()]=$UP;
			$arrayUPbyId[$UP->getId()]=$UP;
		}
		
		if(isset($_POST['newURs'])){
			foreach($_POST['newURs'] as $newUR){
				$posGuin=strpos($newUR['Clave'], "-");
				$claveUR=substr($newUR['Clave'], ($posGuin+1));
				$claveUP=substr($newUR['Clave'], 0,$posGuin);
				$UnidadResponsable=new UnidadResponsable();
				$UnidadResponsable->setClave($claveUR);
				$UnidadResponsable->setNombre($newUR['Nombre']);
				$UnidadResponsable->setUnidadPresupuestal($arrayUP[$claveUP]->getId());
				$UnidadResponsable=$DaoUnidadResponsable->add($UnidadResponsable);
			}
		}
		
		$arrayUR=array();
		foreach($DaoUnidadResponsable->getByEstado($_POST["Estado"]) as $UR){
			$arrayUR[$arrayUPbyId[$UR->getUnidadPresupuestal()]->getClave()."-".$UR->getClave()]=$UR;
		}
		
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/../files/".$_POST["nonce"].".xlsx");
		$arrayPG=array();
		$i=2;
		$blanks=0;
		do{
			$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
			if(strlen($UP)>0){
				$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
				$UR=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUR"].$i)->getValue();
				
				$PG=$spreadsheet->getActiveSheet()->getCell($_POST["columnaClaveOG"].$i)->getValue();
				$PG=substr($PG, 0,3)."0";
				if(!isset($arrayPG[$PG])){
					$arrayPG[$PG]=$DaoPartidasGenericas->getByClave($PG);
				}
				
				$ObjetoDeGasto=new ObjetoDeGasto();
				$ObjetoDeGasto->setClave($spreadsheet->getActiveSheet()->getCell($_POST["columnaClaveOG"].$i)->getValue());
				$ObjetoDeGasto->setNombre($spreadsheet->getActiveSheet()->getCell($_POST["columnaDescripcionOG"].$i)->getValue());
				$ObjetoDeGasto->setPartidaGenerica($arrayPG[$PG]->getId());
				$ObjetoDeGasto->setUnidadResponsable($arrayUR[$UP.'-'.$UR]->getId());
				$ObjetoDeGasto->setVersionPresupuesto($VersionesPresupuesto->getId());
				$ObjetoDeGasto->setMonto($spreadsheet->getActiveSheet()->getCell($_POST["columnaMonto"].$i)->getValue());
				$ObjetoDeGasto=$DaoObjetoDeGasto->add($ObjetoDeGasto);
			}else{
				$blanks+=1;
			}
			$i+=1;
		}while($blanks<3);
		echo(json_encode($VersionesPresupuesto));
	}
	
	if($_POST['action']=="setPrincipal"){
		$version=$DaoVersionesPresupuesto->show($_POST['13']);
		foreach($DaoVersionesPresupuesto->getByEstadoAnio($version->getEstado(),$version->getAnio()) as $versionAnio){
			if($version->getId()==$versionAnio->getId()){
				$versionAnio->setActual(1);
				$DaoVersionesPresupuesto->update($versionAnio);
			}else{
				if($versionAnio->getActual()==1){
					$versionAnio->setActual(0);
					$DaoVersionesPresupuesto->update($versionAnio);
				}
			}
		}
		echo(json_encode($version));
	}
	if($_POST['action']=="missingPP"){
		$resp=array();
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/../files/".$_POST["nonce"].".xlsx");
		
		// Revisar que existan todas las UP y UR
		$resp["actualUP"]=array();
		$arrayUP=array();
		foreach($DaoUnidadPresupuestal->getByEstado($_POST["Estado"]) as $UP){
			if(!isset($resp["actualUP"][$UP->getClave()])){
				$resp["actualUP"][$UP->getClave()]=array();
				$resp["actualUP"][$UP->getClave()]["Nombre"]=$UP->getNombre();
				$resp["actualUP"][$UP->getClave()]["Id"]=$UP->getId();
				$arrayUP[$UP->getId()]=$UP;
			}
		}
		$resp["actualUR"]=array();
		$arrayUR=array();
		foreach($DaoUnidadResponsable->getByEstado($_POST["Estado"]) as $UR){
			$Clave=$arrayUP[$UR->getUnidadPresupuestal()]->getClave()."-".$UR->getClave();
			if(!isset($arrayUR[$UR->getId()])){
				$resp["actualUR"][$Clave]=array();
				$resp["actualUR"][$Clave]["Nombre"]=$UR->getNombre();
				$resp["actualUR"][$Clave]["IdUP"]=$UR->getUnidadPresupuestal();
				$resp["actualUR"][$Clave]["ClaveUP"]=$arrayUP[$UR->getUnidadPresupuestal()]->getClave();
				$resp["actualUR"][$Clave]["NombreUP"]=$arrayUP[$UR->getUnidadPresupuestal()]->getNombre();
				$resp["actualUR"][$Clave]["Id"]=$UR->getId();
				$arrayUR[$UR->getId()]=$UR;
			}
		}
		$resp["actualPP"]=array();
		foreach($DaoProgramas->searchByEstado($_POST["Estado"]) as $PP){
			$Clave=$arrayUP[$arrayUR[$PP->getUnidadResponsable()]->getUnidadPresupuestal()]->getClave()."-".$arrayUR[$PP->getUnidadResponsable()]->getClave()."-".$PP->getClave();
			if(!isset($resp["actualPP"][$Clave])){
				$resp["actualPP"][$Clave]=array();
				$resp["actualPP"][$Clave]["Nombre"]=$PP->getNombre();
				$resp["actualPP"][$Clave]["IdUR"]=$PP->getUnidadResponsable();
				$resp["actualPP"][$Clave]["ClaveUR"]=$arrayUR[$PP->getUnidadResponsable()]->getClave();
				$resp["actualPP"][$Clave]["NombreUR"]=$arrayUR[$PP->getUnidadResponsable()]->getNombre();
				$resp["actualPP"][$Clave]["Id"]=$PP->getId();
			}
		}
		$i=2;
		$blanks=0;
		$resp["missingUP"]=array();
		do{
			$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
			if(strlen($UP)>0){
				if(!isset($resp["actualUP"][$UP])){
					if(!in_array($UP, $resp["missingUP"])){
						array_push($resp["missingUP"], $UP);
					}
				}
			}else{
				$blanks+=1;
			}
			$i+=1;
		}while($blanks<3);
		$resp["maxRow"]=$i;
		
		$i=2;
		$blanks=0;
		$resp["missingUR"]=array();
		do{
			$UR=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUR"].$i)->getValue();
			if(strlen($UR)>0){
				$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
				$UR=$UP."-".$UR;
				if(!isset($resp["actualUR"][$UR])){
					if(!in_array($UR, $resp["missingUR"])){
						array_push($resp["missingUR"], $UR);
					}
				}
			}else{
				$blanks+=1;
			}
			$i+=1;
		}while($blanks<3);
		
		$i=2;
		$blanks=0;
		$resp["missingPP"]=array();
		$missingPP=array();
		do{
			$UR=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUR"].$i)->getValue();
			if(strlen($UR)>0){
				$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
				$PP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaClavePP"].$i)->getValue();
				$PP=$UP."-".$UR."-".$PP;
				$UR=$UP."-".$UR;
				if(!isset($resp["actualPP"][$PP])){
					if(!in_array($PP, $missingPP)){
						array_push($missingPP, $PP);
						$PPmissing=array();
						$PPmissing["Clave"]=$PP;
						$PPmissing["Nombre"]=$spreadsheet->getActiveSheet()->getCell($_POST["columnaNombrePP"].$i)->getValue();
						$PPmissing["UR"]=$UR;
						array_push($resp["missingPP"], $PPmissing);
					}
				}
			}else{
				$blanks+=1;
			}
			$i+=1;
		}while($blanks<3);
		
		echo(json_encode($resp));
	}
	
	if($_POST['action']=="writePP"){
		$VersionesPresupuesto=$DaoVersionesPresupuesto->show($_POST["Version"]);
		$VersionesPresupuesto->setProgramaPresupuestal(1);
		$VersionesPresupuesto=$DaoVersionesPresupuesto->update($VersionesPresupuesto);
		
		if(isset($_POST['newPPs'])){
			foreach($_POST['newPPs'] as $newPP){
				$Programas=new Programas();
				$strrPos=strrpos($newPP['Clave'], "-")+1;
				if(substr($newPP['Clave'],-2)=="--"){
					$Programas->setClave("-");
				}else{
					$Programas->setClave(substr($newPP['Clave'], $strrPos));
				}
				$Programas->setNombre($newPP['Nombre']);
				$Programas->setUnidadResponsable($newPP['UnidadResponsable']);
				$Programas=$DaoProgramas->add($Programas);
				if(!isset($newPP['UnidadResponsable'])){
					var_dump($newPP);
				}
			}
		}
		
		$arrayUP=array();
		$arrayUPbyId=array();
		foreach($DaoUnidadPresupuestal->getByEstado($_POST["Estado"]) as $UP){
			$arrayUP[$UP->getClave()]=$UP;
			$arrayUPbyId[$UP->getId()]=$UP;
		}
		
		$arrayUR=array();
		foreach($DaoUnidadResponsable->getByEstado($_POST["Estado"]) as $UR){
			$arrayUR[$arrayUPbyId[$UR->getUnidadPresupuestal()]->getClave()."-".$UR->getClave()]=$UR;
			$arrayURbyId[$UR->getId()]=$UR;
		}
		
		$arrayPP=array();
		foreach($DaoProgramas->searchByEstado($_POST["Estado"]) as $PP){
			$arrayPP[$arrayUPbyId[$arrayURbyId[$PP->getUnidadResponsable()]->getUnidadPresupuestal()]->getClave()."-".$arrayURbyId[$PP->getUnidadResponsable()]->getClave()."-".$PP->getClave()]=$PP;
		}
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/../files/".$_POST["nonce"].".xlsx");
		$i=2;
		$blanks=0;
		do{
			$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
			if(strlen($UP)>0){
				$UP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUP"].$i)->getValue();
				$UR=$spreadsheet->getActiveSheet()->getCell($_POST["columnaUR"].$i)->getValue();
				$PP=$spreadsheet->getActiveSheet()->getCell($_POST["columnaClavePP"].$i)->getValue();
				
				$Programa=$arrayPP[$UP."-".$UR."-".$PP];
				$Programa->setUnidadResponsable($arrayUR[$UP."-".$UR]->getId());
				$Programa->setNombre($spreadsheet->getActiveSheet()->getCell($_POST["columnaNombrePP"].$i)->getValue());
				$DaoProgramas->update($Programa);
				
				$ProgramasMonto=new ProgramasMonto();
				$ProgramasMonto->setPrograma($Programa->getId());
				$ProgramasMonto->setVersion($VersionesPresupuesto->getId());
				$ProgramasMonto->setMonto($spreadsheet->getActiveSheet()->getCell($_POST["columnaMonto"].$i)->getValue());
				$ProgramasMonto=$DaoProgramasMonto->add($ProgramasMonto);
			}else{
				$blanks+=1;
			}
			$i+=1;
		}while($blanks<3);
		echo(json_encode($VersionesPresupuesto));
	}
	
	if($_POST['action']=="consolidarProgramasDuplicados"){
		$result=array();
		$duplicados=$DaoProgramas->_query('SELECT COUNT(Id) AS Duplicados, MIN(Id) AS IdIni, UnidadResponsable, Clave FROM programas GROUP BY UnidadResponsable, Clave HAVING Duplicados>1');
		foreach($duplicados as $duplicado){
			$delProgramas=$DaoProgramas->advancedQuery('SELECT * FROM Programas WHERE UnidadResponsable='.$duplicado["UnidadResponsable"].' AND Clave="'.$duplicado["Clave"].'" AND Id<>'.$duplicado["IdIni"]);
			foreach($delProgramas as $delPrograma){
				$montos=$DaoProgramasMonto->advancedQuery('SELECT * FROM ProgramasMonto WHERE Programa='.$delPrograma->getId());
				foreach($montos as $monto){
					$monto->setPrograma($duplicado["IdIni"]);
					$DaoProgramasMonto->update($monto);
				}
				$DaoProgramas->delete($delPrograma->getId());
			}
			
		}
		$result["duplicados"]=$duplicados;
		echo(json_encode($result));
	}
}
if(isset($_GET["action"])){
	if($_GET['action']=="uploadFile"){
		$resp=array();
		$nonce=$DaoUnidadResponsable->nonce();
		if(isset($_GET['base64'])) {
			// If the browser does not support sendAsBinary ()
			$dataFile=file_get_contents('php://input');
			if(isset($_POST['fileExplorer'])){
				//If the browser support readAsArrayBuffer ()
				//PHP handles spaces in base64 encoded string differently
				//so to prevent corruption of data, convert spaces to +
				$dataFile=$_POST['fileExplorer'];
				$dataFile = str_replace(' ', '+', $dataFile);
			}
			$bin = base64_decode($dataFile);
			$nombre=$_GET["filename"];
			$tipo=$_GET["TypeFile"];
			$resp["nonce"]=$nonce;
			$resp["metodo"]="GET";
		}else{
			$nombre=$_FILES["upload"]["filename"];
			$tipo=mime_content_type($_FILES["upload"]["tmp_name"]);
			$bin=file_get_contents($_FILES["upload"]["tmp_name"]);
			$resp["metodo"]="FILES";
			$resp["nonce"]=$nonce;
		}
		$resp["write"]=file_put_contents($_SERVER['DOCUMENT_ROOT']."/../files/$nonce.xlsx", $bin);
		echo(json_encode($resp));
	}
}
