<?php
require_once 'modelos/np_base.php';
require_once 'modelos/PropuestaODSs.php';

class DaoPropuestaODSs extends np_base{

  public function add(PropuestaODSs $PropuestaODSs){
    $sql="INSERT INTO PropuestaODSs (Propuesta,ODS,Principal) VALUES (:Propuesta,:ODS,:Principal);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Propuesta' => $PropuestaODSs->getPropuesta(), ':ODS' => $PropuestaODSs->getODS(), ':Principal' => $PropuestaODSs->getPrincipal()));
      $PropuestaODSs->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaODSs): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $PropuestaODSs;
  }

  public function update(PropuestaODSs $PropuestaODSs){
    $sql="UPDATE PropuestaODSs SET Propuesta=:Propuesta, ODS=:ODS, Principal=:Principal WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $PropuestaODSs->getId(), ':Propuesta' => $PropuestaODSs->getPropuesta(), ':ODS' => $PropuestaODSs->getODS(), ':Principal' => $PropuestaODSs->getPrincipal()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaODSs): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $PropuestaODSs;
  }

  public function addOrUpdate(PropuestaODSs $PropuestaODSs){
    if($PropuestaODSs->getId()>0){
      $PropuestaODSs=$this->update($PropuestaODSs);
    }else{
      $PropuestaODSs=$this->add($PropuestaODSs);
    }
    return $PropuestaODSs;
  }

  public function delete($Id){
    $sql="DELETE FROM PropuestaODSs  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaODSs): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM PropuestaODSs WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $PropuestaODSs=new PropuestaODSs();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $PropuestaODSs=$this->createObject($result[0]);
    }
    return $PropuestaODSs;
  }

  public function showAll(){
    $sql="SELECT * FROM PropuestaODSs";
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
    $PropuestaODSs=new PropuestaODSs();
    $PropuestaODSs->setId($row['Id']);
    $PropuestaODSs->setPropuesta($row['Propuesta']);
    $PropuestaODSs->setODS($row['ODS']);
    $PropuestaODSs->setPrincipal($row['Principal']);
    return $PropuestaODSs;
  }
  
  public function getByPropuesta($Propuesta){
	  $sql="SELECT * FROM PropuestaODSs WHERE Propuesta=$Propuesta";
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