<?php
require_once 'modelos/np_base.php';
require_once 'modelos/PropuestaProgramaODS.php';
require_once 'DaoPropuestaODSs.php';
require_once 'DaoPropuestaMetas.php';

class DaoPropuestaProgramaODS extends np_base{

  public function add(PropuestaProgramaODS $PropuestaProgramaODS){
    $sql="INSERT INTO PropuestaProgramaODS (Programa,Usuario,DatePropuesta,TipoPropuesta,Argumentacion) VALUES (:Programa,:Usuario,:DatePropuesta,:TipoPropuesta,:Argumentacion);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Programa' => $PropuestaProgramaODS->getPrograma(), ':Usuario' => $PropuestaProgramaODS->getUsuario(), ':DatePropuesta' => $PropuestaProgramaODS->getDatePropuesta(), ':TipoPropuesta' => $PropuestaProgramaODS->getTipoPropuesta(), ':Argumentacion' => $PropuestaProgramaODS->getArgumentacion()));
      $PropuestaProgramaODS->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaProgramaODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $PropuestaProgramaODS;
  }

  public function update(PropuestaProgramaODS $PropuestaProgramaODS){
    $sql="UPDATE PropuestaProgramaODS SET Programa=:Programa, Usuario=:Usuario, DatePropuesta=:DatePropuesta, TipoPropuesta=:TipoPropuesta, Argumentacion=:Argumentacion WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $PropuestaProgramaODS->getId(), ':Programa' => $PropuestaProgramaODS->getPrograma(), ':Usuario' => $PropuestaProgramaODS->getUsuario(), ':DatePropuesta' => $PropuestaProgramaODS->getDatePropuesta(), ':TipoPropuesta' => $PropuestaProgramaODS->getTipoPropuesta(), ':Argumentacion' => $PropuestaProgramaODS->getArgumentacion()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaProgramaODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $PropuestaProgramaODS;
  }

  public function addOrUpdate(PropuestaProgramaODS $PropuestaProgramaODS){
    if($PropuestaProgramaODS->getId()>0){
      $PropuestaProgramaODS=$this->update($PropuestaProgramaODS);
    }else{
      $PropuestaProgramaODS=$this->add($PropuestaProgramaODS);
    }
    return $PropuestaProgramaODS;
  }

  public function delete($Id){
    $sql="DELETE FROM PropuestaProgramaODS  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaProgramaODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM PropuestaProgramaODS WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $PropuestaProgramaODS=new PropuestaProgramaODS();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $PropuestaProgramaODS=$this->createObject($result[0]);
    }
    return $PropuestaProgramaODS;
  }

  public function showAll(){
    $sql="SELECT * FROM PropuestaProgramaODS";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $resp=array();
    foreach($sth->fetchAll() as $row){
      array_push($resp,$this->createObject($row));
    }
    return $resp;
  }

  public function advancedQuery($sql){
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $resp=array();
    foreach($sth->fetchAll() as $row){
      array_push($resp,$this->createObject($row));
    }
    return $resp;
  }

  public function createObject($row){
    $PropuestaProgramaODS=new PropuestaProgramaODS();
    $PropuestaProgramaODS->setId($row['Id']);
    $PropuestaProgramaODS->setPrograma($row['Programa']);
    $PropuestaProgramaODS->setUsuario($row['Usuario']);
    $PropuestaProgramaODS->setDatePropuesta($row['DatePropuesta']);
    $PropuestaProgramaODS->setTipoPropuesta($row['TipoPropuesta']);
    $PropuestaProgramaODS->setArgumentacion($row['Argumentacion']);
    if($row['Id']>0){
      $DaoPropuestaODSs=new DaoPropuestaODSs();
      $DaoPropuestaMetas=new DaoPropuestaMetas();
      $PropuestaProgramaODS->setODS($DaoPropuestaODSs->getByPropuesta($row['Id']));
      $PropuestaProgramaODS->setMetas($DaoPropuestaMetas->getByPropuesta($row['Id']));
    }
    return $PropuestaProgramaODS;
  }

  public function getByUsuario($Usuario,$limit=null,$offset=null){
	  $sql="SELECT * FROM PropuestaProgramaODS WHERE Usuario=$Usuario";
	  if($limit!==null){
		$sql.=" LIMIT $limit";
		if($offset!==null){
			$sql.=" OFFSET $offset";
		}  
	  }
	  try {
		$sth=$this->_dbh->prepare($sql);
		$sth->execute();
	  } catch (Exception $e) {
		var_dump($e);
		echo($sql);
	  }
	  $resp=array();
	  foreach($sth->fetchAll() as $row){
		array_push($resp,$this->createObject($row));
	  }
	  return $resp;
	}
	
	public function getPorRevisar($Usuario){
	  $sql="SELECT * FROM PropuestaProgramaODS WHERE Usuario=$Usuario";
	  try {
		$sth=$this->_dbh->prepare($sql);
		$sth->execute();
	  } catch (Exception $e) {
		var_dump($e);
		echo($sql);
	  }
	  $resp=array();
	  foreach($sth->fetchAll() as $row){
		array_push($resp,$this->createObject($row));
	  }
	  return $resp;
	}

}