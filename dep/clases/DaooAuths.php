<?php
require_once 'modelos/np_base.php';
require_once 'modelos/oAuths.php';

class DaooAuths extends np_base{

  public function add(oAuths $oAuths){
    $sql="INSERT INTO oAuths (Usuario,Servicio,UID,AccessKey,RefreshKey,DateDeath,DateBorn,NeedsReauthorization) VALUES (:Usuario,:Servicio,:UID,:AccessKey,:RefreshKey,:DateDeath,:DateBorn,:NeedsReauthorization);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Usuario' => $oAuths->getUsuario(), ':Servicio' => $oAuths->getServicio(), ':UID' => $oAuths->getUID(), ':AccessKey' => $oAuths->getAccessKey(), ':RefreshKey' => $oAuths->getRefreshKey(), ':DateDeath' => $oAuths->getDateDeath(), ':DateBorn' => $oAuths->getDateBorn(), ':NeedsReauthorization' => $oAuths->getNeedsReauthorization()));
      $oAuths->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $oAuths;
  }

  public function update(oAuths $oAuths){
    $sql="UPDATE oAuths SET Usuario=:Usuario, Servicio=:Servicio, UID=:UID, AccessKey=:AccessKey, RefreshKey=:RefreshKey, DateDeath=:DateDeath, DateBorn=:DateBorn, NeedsReauthorization=:NeedsReauthorization WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $oAuths->getId(), ':Usuario' => $oAuths->getUsuario(), ':Servicio' => $oAuths->getServicio(), ':UID' => $oAuths->getUID(), ':AccessKey' => $oAuths->getAccessKey(), ':RefreshKey' => $oAuths->getRefreshKey(), ':DateDeath' => $oAuths->getDateDeath(), ':DateBorn' => $oAuths->getDateBorn(), ':NeedsReauthorization' => $oAuths->getNeedsReauthorization()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $oAuths;
  }

  public function addOrUpdate(oAuths $oAuths){
    if($oAuths->getId()>0){
      $oAuths=$this->update($oAuths);
    }else{
      $oAuths=$this->add($oAuths);
    }
    return $oAuths;
  }

  public function delete($Id){
    $sql="DELETE FROM oAuths  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM oAuths WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $oAuths=new oAuths();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $oAuths=$this->createObject($result[0]);
    }
    return $oAuths;
  }

  public function showAll(){
    $sql="SELECT * FROM oAuths";
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
    $oAuths=new oAuths();
    $oAuths->setId($row['Id']);
    $oAuths->setUsuario($row['Usuario']);
    $oAuths->setServicio($row['Servicio']);
    $oAuths->setUID($row['UID']);
    $oAuths->setAccessKey($row['AccessKey']);
    $oAuths->setRefreshKey($row['RefreshKey']);
    $oAuths->setDateDeath($row['DateDeath']);
    $oAuths->setDateBorn($row['DateBorn']);
    $oAuths->setNeedsReauthorization($row['NeedsReauthorization']);
    return $oAuths;
  }


}