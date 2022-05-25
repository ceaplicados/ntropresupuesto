<?php
require_once 'modelos/np_base.php';
require_once 'modelos/ObservablePP.php';

class DaoObservablePP extends np_base{

  public function add(ObservablePP $ObservablePP){
    $sql="INSERT INTO ObservablePP (Observable,Programa,BornDate,BornBy) VALUES (:Observable,:Programa,:BornDate,:BornBy);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Observable' => $ObservablePP->getObservable(), ':Programa' => $ObservablePP->getPrograma(), ':BornDate' => $ObservablePP->getBornDate(), ':BornBy' => $ObservablePP->getBornBy()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ObservablePP;
  }

  public function update(ObservablePP $ObservablePP){
    $sql="UPDATE ObservablePP SET BornDate=:BornDate, BornBy=:BornBy WHERE  Observable=:Observable AND  Programa=:Programa;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Observable' => $ObservablePP->getObservable(), ':Programa' => $ObservablePP->getPrograma(), ':BornDate' => $ObservablePP->getBornDate(), ':BornBy' => $ObservablePP->getBornBy()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ObservablePP;
  }


  public function delete(ObservablePP $ObservablePP){
    $sql="DELETE FROM ObservablePP  WHERE  Observable=:Observable AND  Programa=:Programa;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Observable' => $ObservablePP->getObservable(), ':Programa' => $ObservablePP->getPrograma()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }


  public function showAll(){
    $sql="SELECT * FROM ObservablePP";
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
    $ObservablePP=new ObservablePP();
    $ObservablePP->setObservable($row['Observable']);
    $ObservablePP->setPrograma($row['Programa']);
    $ObservablePP->setBornDate($row['BornDate']);
    $ObservablePP->setBornBy($row['BornBy']);
    return $ObservablePP;
  }
  
  public function getByObservable($Observable){
    $sql="SELECT * FROM ObservablePP WHERE Observable=$Observable";
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