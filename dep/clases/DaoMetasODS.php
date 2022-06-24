<?php
require_once 'modelos/np_base.php';
require_once 'modelos/MetasODS.php';

class DaoMetasODS extends np_base{

  public function add(MetasODS $MetasODS){
    $sql="INSERT INTO MetasODS (ODS,Clave,Nombre) VALUES (:ODS,:Clave,:Nombre);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':ODS' => $MetasODS->getODS(), ':Clave' => $MetasODS->getClave(), ':Nombre' => $MetasODS->getNombre()));
      $MetasODS->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (MetasODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $MetasODS;
  }

  public function update(MetasODS $MetasODS){
    $sql="UPDATE MetasODS SET ODS=:ODS, Clave=:Clave, Nombre=:Nombre WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $MetasODS->getId(), ':ODS' => $MetasODS->getODS(), ':Clave' => $MetasODS->getClave(), ':Nombre' => $MetasODS->getNombre()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (MetasODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $MetasODS;
  }

  public function addOrUpdate(MetasODS $MetasODS){
    if($MetasODS->getId()>0){
      $MetasODS=$this->update($MetasODS);
    }else{
      $MetasODS=$this->add($MetasODS);
    }
    return $MetasODS;
  }

  public function delete($Id){
    $sql="DELETE FROM MetasODS  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (MetasODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM MetasODS WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $MetasODS=new MetasODS();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $MetasODS=$this->createObject($result[0]);
    }
    return $MetasODS;
  }

  public function showAll(){
    $sql="SELECT * FROM MetasODS";
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
    $MetasODS=new MetasODS();
    $MetasODS->setId($row['Id']);
    $MetasODS->setODS($row['ODS']);
    $MetasODS->setClave($row['Clave']);
    $MetasODS->setNombre($row['Nombre']);
    return $MetasODS;
  }
  
  public function buscarMetaByODS($Buscar,$ODS=NULL){
    $sql="SELECT MetasODS.*, CONCAT(Clave,Nombre) AS Buscar FROM MetasODS WHERE Buscar LIKE '%$Buscar%'";
    if($ODS!==NULL){
      $sql.=" AND ODS=$ODS";
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

}