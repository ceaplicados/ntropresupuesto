<?php
require_once 'modelos/np_base.php';
require_once 'modelos/PropuestaMetas.php';

class DaoPropuestaMetas extends np_base{

  public function add(PropuestaMetas $PropuestaMetas){
    $sql="INSERT INTO PropuestaMetas (Propuesta,Meta) VALUES (:Propuesta,:Meta);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Propuesta' => $PropuestaMetas->getPropuesta(), ':Meta' => $PropuestaMetas->getMeta()));
      $PropuestaMetas->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaMetas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $PropuestaMetas;
  }

  public function update(PropuestaMetas $PropuestaMetas){
    $sql="UPDATE PropuestaMetas SET Propuesta=:Propuesta, Meta=:Meta WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $PropuestaMetas->getId(), ':Propuesta' => $PropuestaMetas->getPropuesta(), ':Meta' => $PropuestaMetas->getMeta()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaMetas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $PropuestaMetas;
  }

  public function addOrUpdate(PropuestaMetas $PropuestaMetas){
    if($PropuestaMetas->getId()>0){
      $PropuestaMetas=$this->update($PropuestaMetas);
    }else{
      $PropuestaMetas=$this->add($PropuestaMetas);
    }
    return $PropuestaMetas;
  }

  public function delete($Id){
    $sql="DELETE FROM PropuestaMetas  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (PropuestaMetas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM PropuestaMetas WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $PropuestaMetas=new PropuestaMetas();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $PropuestaMetas=$this->createObject($result[0]);
    }
    return $PropuestaMetas;
  }

  public function showAll(){
    $sql="SELECT * FROM PropuestaMetas";
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
    $PropuestaMetas=new PropuestaMetas();
    $PropuestaMetas->setId($row['Id']);
    $PropuestaMetas->setPropuesta($row['Propuesta']);
    $PropuestaMetas->setMeta($row['Meta']);
    return $PropuestaMetas;
  }
  
  public function getByPropuesta($Propuesta){
    $sql="SELECT * FROM PropuestaMetas WHERE Propuesta=$Propuesta";
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