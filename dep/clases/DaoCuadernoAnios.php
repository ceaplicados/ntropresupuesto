<?php
require_once 'modelos/np_base.php';
require_once 'modelos/CuadernoAnios.php';

class DaoCuadernoAnios extends np_base{

  public function add(CuadernoAnios $CuadernoAnios){
    $sql="INSERT INTO CuadernoAnios (Cuaderno,Anio,Data) VALUES (:Cuaderno,:Anio,:Data);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Cuaderno' => $CuadernoAnios->getCuaderno(), ':Anio' => $CuadernoAnios->getAnio(), ':Data' => $CuadernoAnios->getData()));
      $CuadernoAnios->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $CuadernoAnios;
  }

  public function update(CuadernoAnios $CuadernoAnios){
    $sql="UPDATE CuadernoAnios SET Cuaderno=:Cuaderno, Anio=:Anio, Data=:Data WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $CuadernoAnios->getId(), ':Cuaderno' => $CuadernoAnios->getCuaderno(), ':Anio' => $CuadernoAnios->getAnio(), ':Data' => $CuadernoAnios->getData()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $CuadernoAnios;
  }

  public function addOrUpdate(CuadernoAnios $CuadernoAnios){
    if($CuadernoAnios->getId()>0){
      $CuadernoAnios=$this->update($CuadernoAnios);
    }else{
      $CuadernoAnios=$this->add($CuadernoAnios);
    }
    return $CuadernoAnios;
  }

  public function delete($Id){
    $sql="DELETE FROM CuadernoAnios  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM CuadernoAnios WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $CuadernoAnios=new CuadernoAnios();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $CuadernoAnios=$this->createObject($result[0]);
    }
    return $CuadernoAnios;
  }

  public function showAll(){
    $sql="SELECT * FROM CuadernoAnios";
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
    $CuadernoAnios=new CuadernoAnios();
    $CuadernoAnios->setId($row['Id']);
    $CuadernoAnios->setCuaderno($row['Cuaderno']);
    $CuadernoAnios->setAnio($row['Anio']);
    $CuadernoAnios->setData($row['Data']);
    return $CuadernoAnios;
  }
  
  public function getByCuaderno($Cuaderno){
  	$sql="SELECT * FROM CuadernoAnios WHERE Cuaderno=$Cuaderno ORDER BY Anio ASC";
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
  
  public function deleteByAnioCuaderno($Anio,$Cuaderno){
    $sql="DELETE FROM CuadernoAnios  WHERE Anio=$Anio AND Cuaderno=$Cuaderno;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }
}