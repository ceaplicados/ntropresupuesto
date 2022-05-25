<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Sessions.php';

class DaoSessions extends np_base{

  public function add(Sessions $Sessions){
    $sql="INSERT INTO Sessions (UID,Usuario,Cliente,DateBorn,DateDeath,IP,Agent) VALUES (:UID,:Usuario,:Cliente,:DateBorn,:DateDeath,:IP,:Agent);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':UID' => $Sessions->getUID(), ':Usuario' => $Sessions->getUsuario(), ':Cliente' => $Sessions->getCliente(), ':DateBorn' => $Sessions->getDateBorn(), ':DateDeath' => $Sessions->getDateDeath(), ':IP' => $Sessions->getIP(), ':Agent' => $Sessions->getAgent()));
      $Sessions->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Sessions;
  }

  public function update(Sessions $Sessions){
    $sql="UPDATE Sessions SET UID=:UID, Usuario=:Usuario, Cliente=:Cliente, DateBorn=:DateBorn, DateDeath=:DateDeath, IP=:IP, Agent=:Agent WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Sessions->getId(), ':UID' => $Sessions->getUID(), ':Usuario' => $Sessions->getUsuario(), ':Cliente' => $Sessions->getCliente(), ':DateBorn' => $Sessions->getDateBorn(), ':DateDeath' => $Sessions->getDateDeath(), ':IP' => $Sessions->getIP(), ':Agent' => $Sessions->getAgent()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Sessions;
  }

  public function addOrUpdate(Sessions $Sessions){
    if($Sessions->getId()>0){
      $Sessions=$this->update($Sessions);
    }else{
      $Sessions=$this->add($Sessions);
    }
    return $Sessions;
  }

  public function delete($Id){
    $sql="DELETE FROM Sessions  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM Sessions WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Sessions=new Sessions();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Sessions=$this->createObject($result[0]);
    }
    return $Sessions;
  }

  public function showAll(){
    $sql="SELECT * FROM Sessions";
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
    $Sessions=new Sessions();
    $Sessions->setId($row['Id']);
    $Sessions->setUID($row['UID']);
    $Sessions->setUsuario($row['Usuario']);
    $Sessions->setCliente($row['Cliente']);
    $Sessions->setDateBorn($row['DateBorn']);
    $Sessions->setDateDeath($row['DateDeath']);
    $Sessions->setIP($row['IP']);
    $Sessions->setAgent($row['Agent']);
    return $Sessions;
  }
  public function getSession($UID){
    $sql="SELECT * FROM Sessions WHERE UID='$UID';";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Sessions=new Sessions();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Sessions=$this->createObject($result[0]);
    }
    return $Sessions;
  }


}