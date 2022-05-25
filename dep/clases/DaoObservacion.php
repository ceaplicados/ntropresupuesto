<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Observacion.php';

class DaoObservacion extends np_base{

  public function add(Observacion $Observacion){
    $sql="INSERT INTO Observacion (Observable,Usuario,Nonce,DateBorn) VALUES (:Observable,:Usuario,:Nonce,:DateBorn);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Observable' => $Observacion->getObservable(), ':Usuario' => $Observacion->getUsuario(), ':Nonce' => $Observacion->getNonce(), ':DateBorn' => $Observacion->getDateBorn()));
      $Observacion->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Observacion;
  }

  public function update(Observacion $Observacion){
    $sql="UPDATE Observacion SET Observable=:Observable, Usuario=:Usuario, Nonce=:Nonce, DateBorn=:DateBorn WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Observacion->getId(), ':Observable' => $Observacion->getObservable(), ':Usuario' => $Observacion->getUsuario(), ':Nonce' => $Observacion->getNonce(), ':DateBorn' => $Observacion->getDateBorn()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Observacion;
  }

  public function addOrUpdate(Observacion $Observacion){
    if($Observacion->getId()>0){
      $Observacion=$this->update($Observacion);
    }else{
      $Observacion=$this->add($Observacion);
    }
    return $Observacion;
  }

  public function delete($Id){
    $sql="DELETE FROM Observacion  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM Observacion WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Observacion=new Observacion();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Observacion=$this->createObject($result[0]);
    }
    return $Observacion;
  }

  public function showAll(){
    $sql="SELECT * FROM Observacion";
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
    $Observacion=new Observacion();
    $Observacion->setId($row['Id']);
    $Observacion->setObservable($row['Observable']);
    $Observacion->setUsuario($row['Usuario']);
    $Observacion->setNonce($row['Nonce']);
    $Observacion->setDateBorn($row['DateBorn']);
    return $Observacion;
  }
  
  public function getByObservable($Observable){
    $sql="SELECT * FROM Observacion WHERE Observable=$Observable";
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