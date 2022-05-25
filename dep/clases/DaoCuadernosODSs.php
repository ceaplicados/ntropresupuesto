<?php
require_once 'modelos/np_base.php';
require_once 'modelos/CuadernosODSs.php';

class DaoCuadernosODSs extends np_base{

  public function add(CuadernosODSs $CuadernosODSs){
    $sql="INSERT INTO CuadernosODSs (Cuaderno,ODS,MetaODS) VALUES (:Cuaderno,:ODS,:MetaODS);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Cuaderno' => $CuadernosODSs->getCuaderno(), ':ODS' => $CuadernosODSs->getODS(), ':MetaODS' => $CuadernosODSs->getMetaODS()));
      $CuadernosODSs->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (CuadernosODSs): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $CuadernosODSs;
  }

  public function update(CuadernosODSs $CuadernosODSs){
    $sql="UPDATE CuadernosODSs SET Cuaderno=:Cuaderno, ODS=:ODS, MetaODS=:MetaODS WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $CuadernosODSs->getId(), ':Cuaderno' => $CuadernosODSs->getCuaderno(), ':ODS' => $CuadernosODSs->getODS(), ':MetaODS' => $CuadernosODSs->getMetaODS()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (CuadernosODSs): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $CuadernosODSs;
  }

  public function addOrUpdate(CuadernosODSs $CuadernosODSs){
    if($CuadernosODSs->getId()>0){
      $CuadernosODSs=$this->update($CuadernosODSs);
    }else{
      $CuadernosODSs=$this->add($CuadernosODSs);
    }
    return $CuadernosODSs;
  }

  public function delete($Id){
    $sql="DELETE FROM CuadernosODSs  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (CuadernosODSs): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM CuadernosODSs WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $CuadernosODSs=new CuadernosODSs();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $CuadernosODSs=$this->createObject($result[0]);
    }
    return $CuadernosODSs;
  }

  public function showAll(){
    $sql="SELECT * FROM CuadernosODSs";
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
    $CuadernosODSs=new CuadernosODSs();
    $CuadernosODSs->setId($row['Id']);
    $CuadernosODSs->setCuaderno($row['Cuaderno']);
    $CuadernosODSs->setODS($row['ODS']);
    $CuadernosODSs->setMetaODS($row['MetaODS']);
    return $CuadernosODSs;
  }
  
  public function getByCuaderno($Cuaderno){
	  $sql="SELECT * FROM CuadernosODSs WHERE Cuaderno=$Cuaderno ORDER BY ODS, MetaODS";
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