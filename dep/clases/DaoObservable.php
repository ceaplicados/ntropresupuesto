<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Observable.php';

class DaoObservable extends np_base{

  public function add(Observable $Observable){
    $sql="INSERT INTO Observable (Estado,Anio,Tematica,Nombre,Nonce) VALUES (:Estado,:Anio,:Tematica,:Nombre,:Nonce);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Estado' => $Observable->getEstado(), ':Anio' => $Observable->getAnio(), ':Tematica' => $Observable->getTematica(), ':Nombre' => $Observable->getNombre(), ':Nonce' => $Observable->getNonce()));
      $Observable->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Observable;
  }

  public function update(Observable $Observable){
    $sql="UPDATE Observable SET Estado=:Estado, Anio=:Anio, Tematica=:Tematica, Nombre=:Nombre, Nonce=:Nonce WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Observable->getId(), ':Estado' => $Observable->getEstado(), ':Anio' => $Observable->getAnio(), ':Tematica' => $Observable->getTematica(), ':Nombre' => $Observable->getNombre(), ':Nonce' => $Observable->getNonce()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Observable;
  }

  public function addOrUpdate(Observable $Observable){
    if($Observable->getId()>0){
      $Observable=$this->update($Observable);
    }else{
      $Observable=$this->add($Observable);
    }
    return $Observable;
  }

  public function delete($Id){
    $sql="DELETE FROM Observable  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM Observable WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Observable=new Observable();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Observable=$this->createObject($result[0]);
    }
    return $Observable;
  }

  public function showAll(){
    $sql="SELECT * FROM Observable";
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
    $Observable=new Observable();
    $Observable->setId($row['Id']);
    $Observable->setEstado($row['Estado']);
    $Observable->setAnio($row['Anio']);
    $Observable->setTematica($row['Tematica']);
    $Observable->setNombre($row['Nombre']);
    $Observable->setNonce($row['Nonce']);
    return $Observable;
  }
  
  public function getByEstado($Estado){
    $sql="SELECT * FROM Observable WHERE Estado=$Estado";
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
 
  public function getByUsuario($Usuario){
    $sql="SELECT Observable.* FROM Observable JOIN FollowObservaciones ON FollowObservaciones.Observable=Observable.Id WHERE Usuario=$Usuario";
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