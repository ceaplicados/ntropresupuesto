<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Estados.php';

class DaoEstados extends np_base{

  public function add(Estados $Estados){
    $sql="INSERT INTO Estados (Nombre,Codigo) VALUES (:Nombre,:Codigo);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Nombre' => $Estados->getNombre(), ':Codigo' => $Estados->getCodigo()));
      $Estados->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (Estados): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Estados;
  }

  public function update(Estados $Estados){
    $sql="UPDATE Estados SET Nombre=:Nombre, Codigo=:Codigo WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Estados->getId(), ':Nombre' => $Estados->getNombre(), ':Codigo' => $Estados->getCodigo()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (Estados): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Estados;
  }

  public function addOrUpdate(Estados $Estados){
    if($Estados->getId()>0){
      $Estados=$this->update($Estados);
    }else{
      $Estados=$this->add($Estados);
    }
    return $Estados;
  }

  public function delete($Id){
    $sql="DELETE FROM Estados  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (Estados): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM Estados WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Estados=new Estados();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Estados=$this->createObject($result[0]);
    }
    return $Estados;
  }

  public function showAll(){
    $sql="SELECT * FROM Estados";
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
    $Estados=new Estados();
    $Estados->setId($row['Id']);
    $Estados->setNombre($row['Nombre']);
    $Estados->setCodigo($row['Codigo']);
    return $Estados;
  }
  
  public function getByCodigo($Codigo){
    $sql="SELECT * FROM Estados WHERE Codigo='$Codigo';";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Estados=new Estados();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Estados=$this->createObject($result[0]);
    }
    return $Estados;
  }
  
  public function showConVersiones(){
    $sql="SELECT DISTINCT Estados.* FROM Estados JOIN VersionesPresupuesto ON VersionesPresupuesto.Estado=Estados.Id";
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