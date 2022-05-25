<?php
require_once 'modelos/np_base.php';
require_once 'modelos/FollowObservaciones.php';

class DaoFollowObservaciones extends np_base{
  public function addOrUpdate(FollowObservaciones $FollowObservaciones){
    $sql="SELECT * FROM FollowObservaciones WHERE Observable=:Observable AND Usuario=:Usuario;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Usuario' => $FollowObservaciones->getUsuario(), ':Observable' => $FollowObservaciones->getObservable()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $result=$sth->fetchAll();
    if(!count($result)>0){
      $sql="INSERT INTO FollowObservaciones (Observable,Usuario) VALUES (:Observable,:Usuario);";
      try {
        $sth=$this->_dbh->prepare($sql);
        $sth->execute(array(':Usuario' => $FollowObservaciones->getUsuario(), ':Observable' => $FollowObservaciones->getObservable()));
      } catch (Exception $e) {
        var_dump($e);
        echo($sql);
      }
    }
    return $FollowObservaciones;
  }

  public function delete(FollowObservaciones $FollowObservaciones){
    $sql="DELETE FROM FollowObservaciones WHERE Observable=:Observable AND Usuario=:Usuario;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Usuario' => $FollowObservaciones->getUsuario(), ':Observable' => $FollowObservaciones->getObservable()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
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
    $FollowObservaciones=new FollowObservaciones();
    $FollowObservaciones->setUsuario($row['Usuario']);
    $FollowObservaciones->setObservable($row['Observable']);
    return $FollowObservaciones;
  }


}