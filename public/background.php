<?php
require_once("../dep/interface.php");
require_once("../dep/clases/DaoVersionesPresupuesto.php");
require_once("../dep/clases/DaoUnidadResponsable.php");
require_once("../dep/clases/DaoUnidadPresupuestal.php");
require_once("../dep/clases/DaoCapitulosGasto.php");
require_once("../dep/clases/DaoConceptosGenerales.php");
require_once("../dep/clases/DaoPartidasGenericas.php");
require_once("../dep/clases/DaoObjetoDeGasto.php");
require_once("../dep/clases/DaoProgramas.php");
require_once("../dep/clases/DaoINPC.php");
require_once("../dep/clases/DaoObservable.php");
require_once("../dep/clases/DaoTematicas.php");
require_once("../dep/clases/DaoFollowObservaciones.php");
require_once("../dep/clases/DaoObservableUR.php");
require_once("../dep/clases/DaoObservablePP.php");
require_once("../dep/clases/DaoObservacion.php");
require_once("../dep/clases/DaoComentarios.php");
require_once("../dep/clases/DaoCuadernos.php");
require_once("../dep/clases/DaoUsuariosCuaderno.php");
require_once("../dep/clases/DaoCuadernoAnios.php");
require_once("../dep/clases/DaoRenglonCuaderno.php");
require_once("../dep/clases/DaoEstados.php");

$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();
$DaoUnidadResponsable=new DaoUnidadResponsable();
$DaoUnidadPresupuestal=new DaoUnidadPresupuestal();
$DaoCapitulosGasto=new DaoCapitulosGasto();
$DaoConceptosGenerales=new DaoConceptosGenerales();
$DaoPartidasGenericas=new DaoPartidasGenericas();
$DaoObjetoDeGasto=new DaoObjetoDeGasto();
$DaoProgramas=new DaoProgramas();
$DaoINPC=new DaoINPC();
$DaoObservable=new DaoObservable();
$DaoTematicas=new DaoTematicas();
$DaoFollowObservaciones=new DaoFollowObservaciones();
$DaoObservableUR=new DaoObservableUR();
$DaoObservablePP=new DaoObservablePP();
$DaoObservacion=new DaoObservacion();
$DaoComentarios=new DaoComentarios();
$DaoCuadernos=new DaoCuadernos();
$DaoUsuariosCuaderno=new DaoUsuariosCuaderno();
$DaoCuadernoAnios=new DaoCuadernoAnios();
$DaoRenglonCuaderno=new DaoRenglonCuaderno();
$DaoEstados=new DaoEstados();

if(strpos($_SERVER["HTTP_REFERER"], "ntropresupuesto:8888")!==false || strpos($_SERVER["HTTP_REFERER"], "nuestropresupuesto.mx")!==false){
	if(isset($_POST["action"])){
		if($_POST['action']=="guardarUsuario"){
			$Usuario->setNombre($_POST["Nombre"]);
			$Usuario->setSobrenombre($_POST["Sobrenombre"]);
			$Usuario->setEmail($_POST["Email"]);
			$Usuario->setTelefono($_POST["Telefono"]);
			$Usuario->setEstado($_POST["Estado"]);
			$DaoUsuarios->update($Usuario);
			echo(json_encode($Usuario));
		}
		if($_POST["action"]=="getTotalesEstado"){
			$resultado=array();
			$INPCActual=$DaoINPC->show($_POST["INPC"]);
			$INPCs=array();
			foreach($DaoVersionesPresupuesto->showMontosByEstado($_POST["Estado"],true) as $Version){
				$INPCversion=$DaoINPC->show($Version->getAnio());
				$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
				$Version->setMonto($Version->getMonto()/$deflactor);
				array_push($resultado, $Version);
			}
			echo(json_encode($resultado));
		}
		if($_POST["action"]=="buscarPrograma"){
			$resp=array();
			$resp["buscar"]=$_POST["buscar"];
			$resp["URs"]=array();
			$resp["UPs"]=array();
			$resp["programas"]=$DaoProgramas->searchByEstado($_POST["Estado"],$_POST["buscar"]);
			foreach($resp["programas"] as $programa){
				if(!isset($resp["URs"][$programa->getUnidadResponsable()])){
					$UR=$DaoUnidadResponsable->show($programa->getUnidadResponsable());
					$resp["URs"][$programa->getUnidadResponsable()]=$UR;
					if(!isset($resp["UPs"][$UR->getUnidadPresupuestal()])){
						$resp["UPs"][$UR->getUnidadPresupuestal()]=$DaoUnidadPresupuestal->show($UR->getUnidadPresupuestal());
					}
				}	
			}
			echo(json_encode($resp));
		}
		if($_POST["action"]=="getOGByUR"){
			$resp=array();
			$resp["UP"]=array();
			$resp["UR"]=array();
			foreach($DaoUnidadResponsable->getPresupuestoByVersion($_POST["Version"]) as $UR){
				if(!isset($resp["UP"][$UR->getUnidadPresupuestal()])){
					$resp["UP"][$UR->getUnidadPresupuestal()]=$DaoUnidadPresupuestal->show($UR->getUnidadPresupuestal());
				}
				$UR->setMonto($UR->getMonto()/$_POST["Deflactor"]);
				array_push($resp["UR"], $UR);
			}
			echo(json_encode($resp));
		}
		if($_POST["action"]=="getByCapitulosGasto"){
			$resultado=array();
			foreach($DaoCapitulosGasto->getPresupuestoByVersion($_POST["Version"]) as $CapGasto){
				$CapGasto->setMonto($CapGasto->getMonto()/$_POST["Deflactor"]);
				array_push($resultado, $CapGasto);
			}
			echo(json_encode($resultado));
		}
		if($_POST["action"]=="getHistoricoUR"){
			$resp=array();
			$resp["versiones"]=$DaoVersionesPresupuesto->getByEstado($_POST["Estado"],true);
			$resp["capitulos"]=$DaoCapitulosGasto->showAll();
			$INPCActual=$DaoINPC->show($_POST["INPC"]);
			$INPCs=array();
			foreach($resp["versiones"] as $version){
				$resp["resumen"][$version->getId()]=array();
				$INPCversion=$DaoINPC->show($version->getAnio());
				foreach($DaoCapitulosGasto->getPresupuestoByURVersion($_POST["UR"],$version->getId()) as $CapGasto){
					$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
					$CapGasto->setMonto(round($CapGasto->getMonto()/$deflactor,2));
					$resp["resumen"][$version->getId()][$CapGasto->getId()]=$CapGasto;
				}
			}
			echo(json_encode($resp));
		}
		if($_POST["action"]=="getHistoricoCG"){
			$resp=array();
			$resp["versiones"]=$DaoVersionesPresupuesto->getByEstado($_POST["Estado"],true);
			$resp["capitulos"]=$DaoCapitulosGasto->showAll();
			$INPCActual=$DaoINPC->show($_POST["INPC"]);
			$INPCs=array();
			foreach($resp["versiones"] as $version){
				$resp["resumen"][$version->getId()]=array();
				$INPCversion=$DaoINPC->show($version->getAnio());
				foreach($DaoCapitulosGasto->getPresupuestoByVersion($version->getId()) as $CapGasto){
					$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
					$CapGasto->setMonto(round($CapGasto->getMonto()/$deflactor,2));
					$resp["resumen"][$version->getId()][$CapGasto->getId()]=$CapGasto;
				}
			}
			echo(json_encode($resp));
		}
		if($_POST["action"]=="getURsUP"){
			$resp=array();
			$INPCActual=$DaoINPC->show($_POST["INPC"]);
			$version=$DaoVersionesPresupuesto->show($_POST["Version"]);
			$INPCversion=$DaoINPC->show($version->getAnio());
			$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
			$UnidadResponsable=$DaoUnidadResponsable->show($_POST["UR"]);
			foreach($DaoUnidadResponsable->getByUnidadPresupuestal($UnidadResponsable->getUnidadPresupuestal()) as $UR){
				$UR=$DaoUnidadResponsable->getPresupuestoByVersion($_POST["Version"],$UR->getId());
				if(count($UR)>0){
					$UR=$UR[0];
					$UR->setMonto(round($UR->getMonto()/$deflactor,2));
					array_push($resp, $UR);
				}
			}
			echo(json_encode($resp));
		}
		if($_POST["action"]=="getHistoricoPP"){
			$resp=array();
			$Programa=$DaoProgramas->show($_POST["PP"]);
			$UR=$DaoUnidadResponsable->show($Programa->getUnidadResponsable());
			$UP=$DaoUnidadPresupuestal->show($UR->getUnidadPresupuestal());
			$resp["versiones"]=$DaoVersionesPresupuesto->getByEstado($UP->getEstado(),true);
			$INPCActual=$DaoINPC->show($_POST["INPC"]);
			$INPCs=array();
			foreach($resp["versiones"] as $version){
				$ProgramaVersion=$DaoProgramas->getMontoByURVersion($UR->getId(),$version->getId(),$Programa->getId());
				if(count($ProgramaVersion)>0){
					$ProgramaVersion=$ProgramaVersion[0];
					if($ProgramaVersion->getMonto()>0){
						$INPCversion=$DaoINPC->show($version->getAnio());
						$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
						$resp["resumen"][$version->getId()]=round($ProgramaVersion->getMonto()/$deflactor,2);
					}
				}
				
			}
			echo(json_encode($resp));
		}
		if($_POST["action"]=="getHistoricoConceptosGenerales"){
			$resp=array();
			$resp["versiones"]=$DaoVersionesPresupuesto->getByEstado($_POST["Estado"],true);
			$INPCActual=$DaoINPC->show($_POST["INPC"]);
			$INPCs=array();
			$resp["conceptos"]=array();
			foreach($resp["versiones"] as $version){
				$resp["resumen"][$version->getId()]=array();
				$INPCversion=$DaoINPC->show($version->getAnio());
				foreach($DaoConceptosGenerales->getPresupuestoByCapituloGastoVersion($_POST['CapituloGasto'],$version->getId()) as $ConceptoGeneral){
					$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
					$ConceptoGeneral->setMonto(round($ConceptoGeneral->getMonto()/$deflactor,2));
					$resp["resumen"][$version->getId()][$ConceptoGeneral->getId()]=$ConceptoGeneral;
					if(!isset($resp["conceptos"][$ConceptoGeneral->getId()])){
						$resp["conceptos"][$ConceptoGeneral->getId()]=$ConceptoGeneral;
					}
				}
			}
			echo(json_encode($resp));
		}
		
		if($_POST["action"]=="getHistoricoCapituloGastoByUR"){
			$resp=array();
			$resp["versiones"]=$DaoVersionesPresupuesto->getByEstado($_POST["Estado"],true);
			$INPCActual=$DaoINPC->show($_POST["INPC"]);
			$INPCs=array();
			$resp["URs"]=array();
			foreach($resp["versiones"] as $version){
				$resp["resumen"][$version->getId()]=array();
				$INPCversion=$DaoINPC->show($version->getAnio());
				foreach($DaoUnidadResponsable->getPresupuestoByVersionCapGasto($version->getId(),$_POST['CapituloGasto']) as $UnidadResponsable){
					$deflactor=$INPCActual->getValor()/$INPCversion->getValor();
					$UnidadResponsable->setMonto(round($UnidadResponsable->getMonto()/$deflactor,2));
					$resp["resumen"][$version->getId()][$UnidadResponsable->getId()]=$UnidadResponsable;
					if(!isset($resp["URs"][$UnidadResponsable->getId()])){
						$resp["URs"][$UnidadResponsable->getId()]=$UnidadResponsable;
					}
				}
			}
			echo(json_encode($resp));
		}
		
		if($_POST["action"]=="getObservables"){
			$resp=array();
			$resp["observables"]=$DaoObservable->getByEstado($_POST["Estado"]);
			$resp["temas"]=array();
			foreach($DaoTematicas->showAll() as $Tema){
				$resp["temas"][$Tema->getId()]=$Tema;
			}
			echo(json_encode($resp));
		}
		if($_POST["action"]=="getInfoObservable"){
			$resp=array();
			$resp["UnidadesResponsables"]=array();
			$resp["Usuarios"]=array();
			$Observable=$DaoObservable->show($_POST["Observable"]);
			$resp["Versiones"]=$DaoVersionesPresupuesto->getByEstado($Observable->getEstado(),1);
			$VersionActual=$resp["Versiones"][0];
			$INPCs=array();
			foreach($resp["Versiones"] as $Version){
				$INPCs[$Version->getAnio()]=$DaoINPC->show($Version->getAnio());
			}
			foreach($DaoObservableUR->getByObservable($_POST["Observable"]) as $ObservableUR){
				$UnidadResponsable=$DaoUnidadResponsable->show($ObservableUR->getUnidadResponsable());
				$Obj=array();
				$Obj["Relacion"]=$ObservableUR;
				$UnidadPresupuestal=$DaoUnidadPresupuestal->show($UnidadResponsable->getUnidadPresupuestal());
				$UnidadResponsable->setClave($UnidadPresupuestal->getClave()."-".$UnidadResponsable->getClave());
				$Obj["Unidad"]=$UnidadResponsable;
				if(!isset($resp["Usuarios"][$ObservableUR->getBornBy()])){
					$resp["Usuarios"][$ObservableUR->getBornBy()]=$DaoUsuarios->show($ObservableUR->getBornBy());
				}
				$Obj["Montos"]=array();
				foreach($resp["Versiones"] as $Version){
					$MontoUR=$DaoUnidadResponsable->getPresupuestoByVersion($Version->getId(),$UnidadResponsable->getId());
					if(count($MontoUR)>0){
						$MontoUR=$MontoUR[0]->getMonto();
						$MontoUR=$MontoUR/($INPCs[$VersionActual->getAnio()]->getValor()/$INPCs[$Version->getAnio()]->getValor());
						$Obj["Montos"][$Version->getId()]=round($MontoUR);
					}else{
						$Obj["Montos"][$Version->getId()]=0;
					}
					
				}
				array_push($resp["UnidadesResponsables"],$Obj);
				
			}
			$resp["Programas"]=array();
			foreach($DaoObservablePP->getByObservable($_POST["Observable"]) as $ObservablePP){
				$Programa=$DaoProgramas->show($ObservablePP->getPrograma());
				$Obj=array();
				$Obj["Relacion"]=$ObservablePP;
				$Obj["Programa"]=$Programa;
				$Obj["Montos"]=array();
				foreach($resp["Versiones"] as $Version){
					$MontoPP=$DaoProgramas->getMontoByURVersion($Programa->getUnidadResponsable(),$Version->getId(),$Programa->getId());
					if(count($MontoPP)>0){
						$MontoPP=$MontoPP[0]->getMonto();
						$MontoPP=$MontoPP/($INPCs[$VersionActual->getAnio()]->getValor()/$INPCs[$Version->getAnio()]->getValor());
						$Obj["Montos"][$Version->getId()]=round($MontoPP);
					}else{
						$Obj["Montos"][$Version->getId()]=0;
					}
					
				}
				array_push($resp["Programas"],$Obj);
				if(!isset($resp["Usuarios"][$ObservablePP->getBornBy()])){
					$resp["Usuarios"][$ObservablePP->getBornBy()]=$DaoUsuarios->show($ObservablePP->getBornBy());
				}
			}
			$resp["Observaciones"]=array();
			foreach($DaoObservacion->getByObservable($_POST["Observable"]) as $Observacion){
				$obj=array();
				$obj["Observacion"]=$Observacion;
				$obj["Comentarios"]=$DaoComentarios->getByObservacion($Observacion->getId());
				foreach($obj["Comentarios"] as $Comentario){
					if(!isset($resp["Usuarios"][$Comentario->getUsuario()])){
						$resp["Usuarios"][$Comentario->getUsuario()]=$DaoUsuarios->show($Comentario->getUsuario());
					}
				}
				$obj["Texto"]="";
				if(file_exists("observaciones/ob_".$Observacion->getNonce().".np")){
					$obj["Texto"]=file_get_contents("observaciones/ob_".$Observacion->getNonce().".np");
				}
				if(!isset($resp["Usuarios"][$Observacion->getUsuario()])){
					$resp["Usuarios"][$Observacion->getUsuario()]=$DaoUsuarios->show($Observacion->getUsuario());
				}
				array_push($resp["Observaciones"], $obj);
			}
			echo(json_encode($resp));
		}
		if($_POST["action"]=="buscarProgramaPresupuestal"){
			$resp=array();
			if(strlen($_POST["buscar"])>0){
				$resp=$DaoProgramas->searchByEstado($_POST["Estado"], $_POST["buscar"]);
			}elseif(count($_POST["URs"])>0){
				$resp=$DaoProgramas->searchByEstado($_POST["Estado"],NULL, $_POST["URs"]);
			}
			echo(json_encode($resp));
		}
		if($_POST['action']=="getFullCuaderno"){
			$Cuaderno=$DaoCuadernos->show($_POST["Id"]);
			$URs=array();
			$UPs=array();
			$Renglones=$Cuaderno->getRenglones();
			$Versiones=array();
			$INPCref=$DaoINPC->getLast();
			if($Cuaderno->getAnioINPC()>0){
				$INPCref=$DaoINPC->show($Cuaderno->getAnioINPC());
			}
			$INPCs=array();
			$arrayAnios=array();
			foreach($Cuaderno->getAnios() as $anio){
				array_push($arrayAnios,$anio->getAnio());
			}
			sort($arrayAnios);
			$YoY=false;
			for ($i = 0; $i < count($Renglones); $i++) {
				if($Renglones[$i]->getMostrar()=="YoY"){
					$YoY=true;
				}
			}
			if($YoY){
				foreach($arrayAnios as $anio){
					$anioPrevio=$anio-1;
					if(!in_array($anioPrevio, $arrayAnios)){
						array_push($arrayAnios,$anioPrevio);
					}
				}
			}
			sort($arrayAnios);
			for ($i = 0; $i < count($Renglones); $i++) {
				$Montos=array();
				if(!isset($Versiones[$Renglones[$i]->getEstado()])){
					$Versiones[$Renglones[$i]->getEstado()]=array();
				}
				foreach($arrayAnios as $anio){
					if(!isset($Versiones[$Renglones[$i]->getEstado()][$anio])){
						$versiones=$DaoVersionesPresupuesto->getByEstadoAnio($Renglones[$i]->getEstado(),$anio,true);
						if(count($versiones)>0){
							$Versiones[$Renglones[$i]->getEstado()][$anio]=$versiones[0];
						}
					}
				}
				if($Renglones[$i]->getTipo()=="Total"){
					foreach($arrayAnios as $anio){
						if(isset($Versiones[$Renglones[$i]->getEstado()][$anio])){
							$Montos[$anio]=0;
							if($Renglones[$i]->getTipoFiltro()=="UP"){
								$Monto=$DaoUnidadPresupuestal->getPresupuestoByVersion($Versiones[$Renglones[$i]->getEstado()][$anio]->getId(),$Renglones[$i]->getIdFiltro());
								if(count($Monto)>0){
									$Montos[$anio]=$Monto[0]->getMonto();
								}
								if(!isset($UPs[$Renglones[$i]->getIdFiltro()])){
										$UPs[$Renglones[$i]->getIdFiltro()]=$DaoUnidadPresupuestal->show($Renglones[$i]->getIdFiltro());
								}
								$Renglones[$i]->setNombre($UPs[$Renglones[$i]->getIdFiltro()]->getNombre());
								$Renglones[$i]->setClave($UPs[$Renglones[$i]->getIdFiltro()]->getClave());
								$Renglones[$i]->setReferencia("Presupuesto total");
							}elseif($Renglones[$i]->getTipoFiltro()=="UR"){
								$Monto=$DaoUnidadResponsable->getPresupuestoByVersion($Versiones[$Renglones[$i]->getEstado()][$anio]->getId(),$Renglones[$i]->getIdFiltro());
								if(count($Monto)>0){
									$Montos[$anio]=$Monto[0]->getMonto();
								}
								if(!isset($URs[$Renglones[$i]->getIdFiltro()])){
									$URs[$Renglones[$i]->getIdFiltro()]=$DaoUnidadResponsable->show($Renglones[$i]->getIdFiltro());
								}
								if(!isset($UPs[$URs[$Renglones[$i]->getIdFiltro()]->getUnidadPresupuestal()])){
									$UPs[$URs[$Renglones[$i]->getIdFiltro()]->getUnidadPresupuestal()]=$DaoUnidadPresupuestal->show($URs[$Renglones[$i]->getIdFiltro()]->getUnidadPresupuestal());
								}
								$Renglones[$i]->setNombre($URs[$Renglones[$i]->getIdFiltro()]->getNombre());
								$Renglones[$i]->setClave($UPs[$URs[$Renglones[$i]->getIdFiltro()]->getUnidadPresupuestal()]->getClave()."-".$URs[$Renglones[$i]->getIdFiltro()]->getClave());
								$Renglones[$i]->setReferencia("Presupuesto total");
							}else{
								$Monto=$DaoVersionesPresupuesto->showMontoTotalByVersion($Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
								$Estado=$DaoEstados->show($Renglones[$i]->getEstado());
								$Renglones[$i]->setNombre("");
								$Renglones[$i]->setClave($Estado->getNombre());
								$Renglones[$i]->setReferencia("Presupuesto total");
							}
						}
					}
				}
				if($Renglones[$i]->getTipo()=="CapituloGasto"){
					$CapituloGasto=$DaoCapitulosGasto->show($Renglones[$i]->getIdReferencia());
					$Renglones[$i]->setNombre($CapituloGasto->getNombre());
					$Renglones[$i]->setClave($CapituloGasto->getClave());
					foreach($arrayAnios as $anio){
						if(isset($Versiones[$Renglones[$i]->getEstado()][$anio])){
							$Montos[$anio]=0;
							if($Renglones[$i]->getTipoFiltro()=="UP"){
								$Monto=$DaoCapitulosGasto->getPresupuestoByCGUPVersion($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getIdFiltro(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}elseif($Renglones[$i]->getTipoFiltro()=="UR"){
								$Monto=$DaoCapitulosGasto->getPresupuestoByCGURVersion($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getIdFiltro(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}else{
								$Monto=$DaoCapitulosGasto->getPresupuestoByCGVersion($Renglones[$i]->getIdReferencia(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}
						}
					}
				}
				if($Renglones[$i]->getTipo()=="ConceptoGeneral"){
					$ConceptoGeneral=$DaoConceptosGenerales->show($Renglones[$i]->getIdReferencia());
					$Renglones[$i]->setNombre($ConceptoGeneral->getNombre());
					$Renglones[$i]->setClave($ConceptoGeneral->getClave());
					foreach($arrayAnios as $anio){
						if(isset($Versiones[$Renglones[$i]->getEstado()][$anio])){
							$Montos[$anio]=0;
							if($Renglones[$i]->getTipoFiltro()=="UP"){
								$Monto=$DaoConceptosGenerales->getPresupuestoByCGUPVersion($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getIdFiltro(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}elseif($Renglones[$i]->getTipoFiltro()=="UR"){
								$Monto=$DaoConceptosGenerales->getPresupuestoByCGURVersion($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getIdFiltro(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}else{
								$Monto=$DaoConceptosGenerales->getPresupuestoByCGVersion($Renglones[$i]->getIdReferencia(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}
						}
					}
				}
				if($Renglones[$i]->getTipo()=="PartidaGenerica"){
					$PartidaGenerica=$DaoPartidasGenericas->show($Renglones[$i]->getIdReferencia());
					$Renglones[$i]->setNombre($PartidaGenerica->getNombre());
					$Renglones[$i]->setClave($PartidaGenerica->getClave());
					foreach($arrayAnios as $anio){
						if(isset($Versiones[$Renglones[$i]->getEstado()][$anio])){
							$Montos[$anio]=0;
							if($Renglones[$i]->getTipoFiltro()=="UP"){
								$Monto=$DaoPartidasGenericas->getPresupuestoByPGUPVersion($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getIdFiltro(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}elseif($Renglones[$i]->getTipoFiltro()=="UR"){
								$Monto=$DaoPartidasGenericas->getPresupuestoByPGURVersion($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getIdFiltro(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}else{
								$Monto=$DaoPartidasGenericas->getPresupuestoByPGVersion($Renglones[$i]->getIdReferencia(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}
						}
					}
				}
				if($Renglones[$i]->getTipo()=="ObjetoGasto"){
					$ObjetoGasto=$DaoObjetoDeGasto->getByClaveEstado($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getEstado());
					$Renglones[$i]->setNombre($ObjetoGasto[0]->getNombre());
					$Renglones[$i]->setClave($ObjetoGasto[0]->getClave());
					foreach($arrayAnios as $anio){
						if(isset($Versiones[$Renglones[$i]->getEstado()][$anio])){
							$Montos[$anio]=0;
							if($Renglones[$i]->getTipoFiltro()=="UP"){
								$Monto=$DaoObjetoDeGasto->getPresupuestoByClaveOGUPVersion($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getIdFiltro(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}elseif($Renglones[$i]->getTipoFiltro()=="UR"){
								$Monto=$DaoObjetoDeGasto->getPresupuestoByClaveOGURVersion($Renglones[$i]->getIdReferencia(),$Renglones[$i]->getIdFiltro(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}else{
								$Monto=$DaoObjetoDeGasto->getPresupuestoByClaveOGVersion($Renglones[$i]->getIdReferencia(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}
						}
					}
				}
				if($Renglones[$i]->getTipo()=="CapituloGasto" || $Renglones[$i]->getTipo()=="ConceptoGeneral" || $Renglones[$i]->getTipo()=="PartidaGenerica" || $Renglones[$i]->getTipo()=="ObjetoGasto"){
					if($Renglones[$i]->getTipoFiltro()=="UP"){
						if(!isset($UPs[$Renglones[$i]->getIdFiltro()])){
							$UPs[$Renglones[$i]->getIdFiltro()]=$DaoUnidadPresupuestal->show($Renglones[$i]->getIdFiltro());
						}
						$Renglones[$i]->setReferencia($UPs[$Renglones[$i]->getIdFiltro()]->getClave().": ".$UPs[$Renglones[$i]->getIdFiltro()]->getNombre());
					}
					if($Renglones[$i]->getTipoFiltro()=="UR"){
						if(!isset($URs[$Renglones[$i]->getIdFiltro()])){
							$URs[$Renglones[$i]->getIdFiltro()]=$DaoUnidadResponsable->show($Renglones[$i]->getIdFiltro());
						}
						$UR=$URs[$Renglones[$i]->getIdFiltro()];
						if(!isset($UPs[$UR->getUnidadPresupuestal()])){
							$UPs[$UR->getUnidadPresupuestal()]=$DaoUnidadPresupuestal->show($UR->getUnidadPresupuestal());
						}
						$Renglones[$i]->setReferencia($UPs[$UR->getUnidadPresupuestal()]->getClave()."-".$UR->getClave().": ".$UR->getNombre());
					}
				}
				if($Renglones[$i]->getTipo()=="ProgramaPresupuestal"){
					$ProgramaPresupuestal=$DaoProgramas->show($Renglones[$i]->getIdReferencia());
					$Renglones[$i]->setNombre($ProgramaPresupuestal->getNombre());
					if(!isset($URs[$ProgramaPresupuestal->getUnidadResponsable()])){
						$URs[$ProgramaPresupuestal->getUnidadResponsable()]=$DaoUnidadResponsable->show($ProgramaPresupuestal->getUnidadResponsable());
					}
					if(!isset($UPs[$URs[$ProgramaPresupuestal->getUnidadResponsable()]->getUnidadPresupuestal()])){
						$UPs[$URs[$ProgramaPresupuestal->getUnidadResponsable()]->getUnidadPresupuestal()]=$DaoUnidadPresupuestal->show($URs[$ProgramaPresupuestal->getUnidadResponsable()]->getUnidadPresupuestal());
					}
					$Renglones[$i]->setClave($UPs[$URs[$ProgramaPresupuestal->getUnidadResponsable()]->getUnidadPresupuestal()]->getClave()."-".$URs[$ProgramaPresupuestal->getUnidadResponsable()]->getClave()."-".$ProgramaPresupuestal->getClave());
					$Renglones[$i]->setReferencia($URs[$ProgramaPresupuestal->getUnidadResponsable()]->getNombre());
					foreach($arrayAnios as $anio){
							if(isset($Versiones[$Renglones[$i]->getEstado()][$anio])){
								$Montos[$anio]=0;
								$Monto=$DaoProgramas->getMontoByProgramaVersion($Renglones[$i]->getIdReferencia(),$Versiones[$Renglones[$i]->getEstado()][$anio]->getId());
								$Montos[$anio]=$Monto->getMonto();
							}
						}
				}
				foreach($Montos as $anio=>$valor){
					if(!isset($INPCs[$anio])){
						$INPCs[$anio]=$DaoINPC->show($anio);
					}
					$deflactor=$INPCref->getValor()/$INPCs[$anio]->getValor();
					$Montos[$anio]=$valor/$deflactor;
				}
				
				$Renglones[$i]->setMontos($Montos);
			}
			$Cuaderno->getRenglones($Renglones);
			echo(json_encode($Cuaderno));
		}
		if($Usuario->getId()>0){
			if($_POST["action"]=="guardarEstadoUsuario"){
				$Usuario->setEstado($_POST["Estado"]);
				$DaoUsuarios->update($Usuario);
				echo(json_encode($Usuario));
			}
			if($_POST["action"]=="getFollowsObservables"){
				$resp=array();
				if($Usuario->getId()>0){
					$resp=$DaoObservable->getByUsuario($Usuario->getId());
				}
				echo(json_encode($resp));
			}
			if($_POST["action"]=="seguirObservable"){
				$FollowObservaciones=new FollowObservaciones();
				$FollowObservaciones->setUsuario($Usuario->getId());
				$FollowObservaciones->setObservable($_POST['Observable']);
				$DaoFollowObservaciones->addOrUpdate($FollowObservaciones);
				echo(json_encode($FollowObservaciones));
			}
			if($_POST["action"]=="dejarDeSeguirObservable"){
				$FollowObservaciones=new FollowObservaciones();
				$FollowObservaciones->setUsuario($Usuario->getId());
				$FollowObservaciones->setObservable($_POST['Observable']);
				$DaoFollowObservaciones->delete($FollowObservaciones);
				echo(json_encode($FollowObservaciones));
			}
			if($_POST["action"]=="addUnidadResponsableObservable"){
				$ObservableUR=new ObservableUR();
				$ObservableUR->setObservable($_POST['Observable']);
				$ObservableUR->setUnidadResponsable($_POST['UnidadResponsable']);
				$ObservableUR->setBornDate(date("Y-m-d H:i:s"));
				$ObservableUR->setBornBy($Usuario->getId());
				$DaoObservableUR->add($ObservableUR);
				echo(json_encode($ObservableUR));
			}
			if($_POST["action"]=="addProgramaPresupuestal"){
				$ObservablePP=new ObservablePP();
				$ObservablePP->setObservable($_POST['Observable']);
				$ObservablePP->setPrograma($_POST['ProgramaPresupuestal']);
				$ObservablePP->setBornDate(date("Y-m-d H:i:s"));
				$ObservablePP->setBornBy($Usuario->getId());
				$DaoObservablePP->add($ObservablePP);
				echo(json_encode($ObservablePP));
			}
			if($_POST["action"]=="guardarObservacion"){
				$resp=array();
				$Observacion=new Observacion();
				$Observacion->setObservable($_POST['Observable']);
				$Observacion->setUsuario($Usuario->getId());
				$Observacion->setNonce($DaoObservacion->nonce());
				$Observacion->setDateBorn(date("Y-m-d H:i:s"));
				$Observacion=$DaoObservacion->add($Observacion);
				$resp["Observacion"]=$Observacion;
				$resp["file"]=file_put_contents("observaciones/ob_".$Observacion->getNonce().".np",$_POST["Observacion"]);
				echo(json_encode($resp));
			}
			if($_POST["action"]=="guardarComentario"){
				$Comentarios=new Comentarios();
				$Comentarios->setUsuario($Usuario->getId());
				$Comentarios->setDateborn(date("Y-m-d H:i:s"));
				$Comentarios->setObservacion($_POST['Observacion']);
				//$Comentarios->setEnRespuesta($row['EnRespuesta']);
				$Comentarios->setComentario($_POST['Comentario']);
				$Comentarios=$DaoComentarios->add($Comentarios);
				echo(json_encode($Comentarios));
			}
			if($_POST['action']=="crearCuadernoTrabajo"){
				$Cuadernos=new Cuadernos();
				$Cuadernos->setOwner($Usuario->getId());
				$Cuadernos->setDateBorn(date("Y-m-d H:i:s"));
				$Cuadernos->setNombre($_POST['Nombre']);
				$Cuadernos->setPublico(0);
				$Cuadernos=$DaoCuadernos->add($Cuadernos);
				echo(json_encode($Cuadernos));
			}
			if($_POST['action']=="guardarCuaderno"){
				$Cuadernos=$DaoCuadernos->show($_POST['Id']);
				$Cuadernos->setNombre($_POST['Nombre']);
				$Cuadernos->setDescripcion($_POST['Descripcion']);
				$Cuadernos->setPublico($_POST['Publico']);
				if($_POST["INPC"]>0){
					$Cuadernos->setAnioINPC($_POST["INPC"]);
				}
				$Cuadernos=$DaoCuadernos->update($Cuadernos);
				echo(json_encode($Cuadernos));
			}
			if($_POST['action']=="buscarUsuarioByEmail"){
				$resp=array();
				$User=$DaoUsuarios->getByEmail($_POST['Email']);
				if($User->getId()>0){
					$resp["Nombre"]=$User->getNombre();
					$resp["Email"]=$User->getEmail();
					$resp["Imagen"]=$User->getImage();
					$UsuariosCuaderno=new UsuariosCuaderno();
					$UsuariosCuaderno->setUsuario($User->getId());
					$UsuariosCuaderno->setCuaderno($_POST['Cuaderno']);
					$UsuariosCuaderno=$DaoUsuariosCuaderno->add($UsuariosCuaderno);
					$resp["UC"]=$UsuariosCuaderno;
				}else{
					$resp["error"]="Usuario no encontrado.";
				}
				echo(json_encode($resp));
			}
			if($_POST['action']=="deleteUsuarioCuaderno"){
				$resp=array();
				$User=$DaoUsuarios->getByEmail($_POST['Email']);
				$resp["result"]=$DaoUsuariosCuaderno->deleteByUsuarioCuaderno($User->getId(),$_POST['Cuaderno']);
				echo(json_encode($resp));
			}
			if($_POST['action']=="addYearCuaderno"){
				$CuadernoAnios=new CuadernoAnios();
				$CuadernoAnios->setCuaderno($_POST['Cuaderno']);
				$CuadernoAnios->setAnio($_POST['Anio']);
				$CuadernoAnios=$DaoCuadernoAnios->add($CuadernoAnios);
				echo(json_encode($CuadernoAnios));
			}
			if($_POST['action']=="delYearCuaderno"){
				$resp=array();
				$resp["result"]=$DaoCuadernoAnios->deleteByAnioCuaderno($_POST['Anio'], $_POST['Cuaderno']);
				echo(json_encode($resp));
			}
			if($_POST['action']=="buscarOG_PP"){
				$resp=array();
				$resp["buscar"]=$_POST["buscar"];
				if($_POST["tipoRengon"]=="CapituloGasto"){
					$resp["result"]=$DaoCapitulosGasto->_query("SELECT Id, Clave, Nombre, CONCAT(Clave,Nombre) AS Buscar FROM CapitulosGasto HAVING Buscar LIKE '%".$_POST["buscar"]."%'");
				}
				if($_POST["tipoRengon"]=="ConceptoGeneral"){
					$resp["result"]=$DaoCapitulosGasto->_query("SELECT Id, Clave, Nombre, CONCAT(Clave,Nombre) AS Buscar FROM ConceptosGenerales HAVING Buscar LIKE '%".$_POST["buscar"]."%'");
				}
				if($_POST["tipoRengon"]=="PartidaGenerica"){
					$resp["result"]=$DaoCapitulosGasto->_query("SELECT Id, Clave, Nombre, CONCAT(Clave,Nombre) AS Buscar FROM PartidasGenericas HAVING Buscar LIKE '%".$_POST["buscar"]."%'");
				}
				if($_POST["tipoRengon"]=="ObjetoGasto"){
					$resp["result"]=$DaoCapitulosGasto->_query("SELECT Id, Clave, MIN(Nombre) AS Nombre FROM (SELECT DISTINCT ObjetoDeGasto.Clave AS Id, ObjetoDeGasto.Clave, ObjetoDeGasto.Nombre, CONCAT(ObjetoDeGasto.Clave,ObjetoDeGasto.Nombre) AS Buscar FROM ObjetoDeGasto JOIN VersionesPresupuesto ON VersionesPresupuesto.Id=ObjetoDeGasto.VersionPresupuesto WHERE Estado=".$_POST["estado"]." HAVING Buscar LIKE '%".$_POST["buscar"]."%' ORDER BY Clave, Nombre) AS Datos GROUP BY Clave");
				}
				if($_POST["tipoRengon"]=="ProgramaPresupuestal"){
					$resp["result"]=$DaoCapitulosGasto->_query("SELECT DISTINCT Programas.Id, CONCAT(LPAD(UnidadPresupuestal.Clave,3,0),\"-\",LPAD(UnidadResponsable.Clave,3,0),\"-\",LPAD(Programas.Clave,3,0)) AS Clave, Programas.Nombre, CONCAT(LPAD(UnidadPresupuestal.Clave,3,0),\"-\",LPAD(UnidadResponsable.Clave,3,0),\"-\",LPAD(Programas.Clave,3,0),Programas.Nombre) AS Buscar, UnidadResponsable.Nombre AS Referencia FROM Programas JOIN UnidadResponsable ON UnidadResponsable.Id=Programas.UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal WHERE Estado=".$_POST["estado"]." HAVING Buscar LIKE '%".$_POST["buscar"]."%' ORDER BY Clave, Nombre");
				}
				echo(json_encode($resp));
			}
			if($_POST['action']=="buscarOG_UPUR"){
				$resp=array();
				$resp["buscar"]=$_POST["buscar"];
				if($_POST["tipoFiltro"]=="UP"){
					$resp["result"]=$DaoUnidadPresupuestal->buscarByEstado($_POST["buscar"],$_POST["estado"]);
				}
				if($_POST["tipoFiltro"]=="UR"){
					$resp["result"]=$DaoUnidadResponsable->buscarByEstado($_POST["buscar"],$_POST["estado"]);
				}
				echo(json_encode($resp));
			}
			if($_POST['action']=="saveRenglon"){
				$RenglonCuaderno=new RenglonCuaderno();
				if($_POST['Id']>0){
					$RenglonCuaderno=$DaoRenglonCuaderno->show($_POST['Id']);
				}
				$RenglonCuaderno->setCuaderno($_POST['Cuaderno']);
				$RenglonCuaderno->setTipo($_POST['Tipo']);
				$RenglonCuaderno->setEstado($_POST['Estado']);
				if(strlen($_POST['IdReferencia'])>0){
					$RenglonCuaderno->setIdReferencia($_POST['IdReferencia']);
				}
				if(strlen($_POST['TipoFiltro'])>0){
					$RenglonCuaderno->setTipoFiltro($_POST['TipoFiltro']);
				}
				if(strlen($_POST['IdFiltro'])>0){
					$RenglonCuaderno->setIdFiltro($_POST['IdFiltro']);
				}
				$RenglonCuaderno->setMostrar($_POST['Mostrar']);
				// $RenglonCuaderno->setData();
				$RenglonCuaderno=$DaoRenglonCuaderno->addOrUpdate($RenglonCuaderno);
				echo(json_encode($RenglonCuaderno));
			}
			if($_POST['action']=="delRenglon"){
				$RenglonCuaderno=$DaoRenglonCuaderno->show($_POST['Id']);
				$DaoRenglonCuaderno->delete($_POST['Id']);
				echo(json_encode($RenglonCuaderno));
			}
			if($_POST['action']=="toogleGraphRenglon"){
				$RenglonCuaderno=$DaoRenglonCuaderno->show($_POST['Id']);
				$RenglonCuaderno->setGraph($_POST['Graph']);
				$DaoRenglonCuaderno->update($RenglonCuaderno);
				echo(json_encode($RenglonCuaderno));
			}
			if($_POST['action']=="getRenglon"){
				$resp=array();
				$RenglonCuaderno=$DaoRenglonCuaderno->show($_POST['Id']);
				$resp["Renglon"]=$RenglonCuaderno;
				if($RenglonCuaderno->getTipo()=="CapituloGasto"){
					$resp["Tipo"]=$DaoCapitulosGasto->show($RenglonCuaderno->getIdReferencia());
				}
				if($RenglonCuaderno->getTipo()=="ConceptoGeneral"){
					$resp["Tipo"]=$DaoConceptosGenerales->show($RenglonCuaderno->getIdReferencia());
				}
				if($RenglonCuaderno->getTipo()=="PartidaGenerica"){
					$resp["Tipo"]=$DaoPartidasGenericas->show($RenglonCuaderno->getIdReferencia());
				}
				if($RenglonCuaderno->getTipo()=="ObjetoGasto"){
					$resp["Tipo"]=$DaoObjetoDeGasto->show($RenglonCuaderno->getIdReferencia());
				}
				if($RenglonCuaderno->getTipo()=="ProgramaPresupuestal"){
					$Programa=$DaoProgramas->show($RenglonCuaderno->getIdReferencia());
					$UR=$DaoUnidadResponsable->show($Programa->getUnidadResponsable());
					$UP=$DaoUnidadPresupuestal->show($UR->getUnidadPresupuestal());
					$Programa->setClave($UP->getClave()."-".$UR->getClave()."-".$Programa->getClave());
					$resp["Tipo"]=$Programa;
				}
				if($RenglonCuaderno->getTipoFiltro()=="UR"){
					$UR=$DaoUnidadResponsable->show($RenglonCuaderno->getIdFiltro());
					$UP=$DaoUnidadPresupuestal->show($UR->getUnidadPresupuestal());
					$UR->setClave($UP->getClave()."-".$UR->getClave());
					$resp["Filtro"]=$UR;
				}
				if($RenglonCuaderno->getTipoFiltro()=="UP"){
					$UP=$DaoUnidadPresupuestal->show($RenglonCuaderno->getIdFiltro());
					$UP->setClave($UP->getClave());
					$resp["Filtro"]=$UP;
				}
				echo(json_encode($resp));
			}
			/*
				app-ddhh.php
			*/
			if($_POST['action']=="getPropuestasUsuario"){
				require_once("../dep/clases/DaoPropuestaProgramaODS.php");
				require_once("../dep/clases/DaoPropuestaODSs.php");
				require_once("../dep/clases/DaoPropuestaMetas.php");
				require_once("../dep/clases/DaoMetasODS.php");
				$DaoPropuestaProgramaODS=new DaoPropuestaProgramaODS();
				$DaoPropuestaODSs=new DaoPropuestaODSs();
				$DaoPropuestaMetas=new DaoPropuestaMetas();
				$DaoMetasODS=new DaoMetasODS();
				$resp=array();
				$Propuestas=$DaoPropuestaProgramaODS->getByUsuario($Usuario->getId());
				$resp['Propuestas']=array();
				$resp['Programas']=array();
				$resp['URs']=array();
				$resp['Metas']=array();
				foreach($Propuestas as $Propuesta){
					$Propuesta->setODS($DaoPropuestaODSs->getByPropuesta($Propuesta->getId()));
					$Propuesta->setMetas($DaoPropuestaMetas->getByPropuesta($Propuesta->getId()));
					array_push($resp['Propuestas'], $Propuesta);
					if(!isset($resp['Programas'][$Propuesta->getPrograma()])){
						$resp['Programas'][$Propuesta->getPrograma()]=$DaoProgramas->show($Propuesta->getPrograma());
					}
					if(!isset($resp['URs'][$resp['Programas'][$Propuesta->getPrograma()]->getUnidadResponsable()])){
						$UR=$DaoUnidadResponsable->show($resp['Programas'][$Propuesta->getPrograma()]->getUnidadResponsable());
						$UP=$DaoUnidadPresupuestal->show($UR->getUnidadPresupuestal());
						$UR->setClave($UP->getClave()."-".$UR->getClave());
						$resp['URs'][$resp['Programas'][$Propuesta->getPrograma()]->getUnidadResponsable()]=$UR;
					}
					foreach($Propuesta->getMetas() as $Meta){
						if(!isset($resp['Metas'][$Meta->getMeta()])){
							$resp['Metas'][$Meta->getMeta()]=$DaoMetasODS->show($Meta->getMeta());
						}
					}
				}
				echo(json_encode($resp));
			}
			
		}
		if($_POST['action']=="getProgramasByURSinODS"){
			$resp=array();
			$resp['UR']=$DaoUnidadResponsable->show($_POST['UR']);
			$resp['UP']=$DaoUnidadPresupuestal->show($resp['UR']->getUnidadPresupuestal());
			$resp['Programas']=$DaoProgramas->sinPropuestaODS($_POST['UR']);
			echo(json_encode($resp));
		}
		if($_POST['action']=="getMetasODS"){
			require_once("../dep/clases/DaoMetasODS.php");
			$DaoMetasODS=new DaoMetasODS();
			$resp=$DaoMetasODS->getByODS($_POST['ODS']);
			echo(json_encode($resp));
		}
		if($_POST['action']=="guardarPropuestaODS"){
			require_once("../dep/clases/DaoPropuestaProgramaODS.php");
			require_once("../dep/clases/DaoPropuestaODSs.php");
			require_once("../dep/clases/DaoPropuestaMetas.php");
			$DaoPropuestaProgramaODS=new DaoPropuestaProgramaODS();
			$DaoPropuestaODSs=new DaoPropuestaODSs();
			$DaoPropuestaMetas=new DaoPropuestaMetas();
			
			$PropuestaProgramaODS=new PropuestaProgramaODS();
			$PropuestaProgramaODS->setPrograma($_POST['Programa']);
			$PropuestaProgramaODS->setUsuario($Usuario->getId());
			$PropuestaProgramaODS->setDatePropuesta(date("Y-m-d H:i:s"));
			$PropuestaProgramaODS->setTipoPropuesta('Inicial');
			$PropuestaProgramaODS->setArgumentacion($_POST['Argumentacion']);
			$PropuestaProgramaODS=$DaoPropuestaProgramaODS->add($PropuestaProgramaODS);
			
			foreach($_POST['ODSs'] as $ODS){
				$PropuestaODSs=new PropuestaODSs();
				$PropuestaODSs->setPropuesta($PropuestaProgramaODS->getId());
				$PropuestaODSs->setODS($ODS);
				$PropuestaODSs->setPrincipal(0);
				if($ODS==$_POST['ODSppal']){
					$PropuestaODSs->setPrincipal(1);
				}
				$PropuestaODSs=$DaoPropuestaODSs->add($PropuestaODSs);
			}
			foreach($_POST['Metas'] as $Meta){
				$PropuestaMetas=new PropuestaMetas();
				$PropuestaMetas->setPropuesta($PropuestaProgramaODS->getId());
				$PropuestaMetas->setMeta($Meta);
				$PropuestaMetas=$DaoPropuestaMetas->add($PropuestaMetas);
			}
			echo(json_encode($PropuestaProgramaODS));
		}
	}
}else{
	$resp=array();
	$resp["error"]="Bad referer";
	echo(json_encode($resp));
}