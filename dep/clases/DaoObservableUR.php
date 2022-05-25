<?php
require_once 'modelos/np_base.php';
require_once 'modelos/ObservableUR.php';

class DaoObservableUR extends np_base{

  public function add(ObservableUR $ObservableUR){
    $sql="INSERT INTO ObservableUR (Observable,UnidadResponsable,BornDate,BornBy) VALUES (:Observable,:UnidadResponsable,:BornDate,:BornBy);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Observable' => $ObservableUR->getObservable(),':UnidadResponsable' => $ObservableUR->getUnidadResponsable(), ':BornDate' => $ObservableUR->getBornDate(), ':BornBy' => $ObservableUR->getBornBy()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ObservableUR;
  }

  public function update(ObservableUR $ObservableUR){
    $sql="UPDATE ObservableUR SET BornDate=:BornDate, BornBy=:BornBy WHERE Observable=:Observable AND UnidadResponsable=:UnidadResponsable;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Observable' => $ObservableUR->getObservable(), ':UnidadResponsable' => $ObservableUR->getUnidadResponsable(), ':BornDate' => $ObservableUR->getBornDate(), ':BornBy' => $ObservableUR->getBornBy()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ObservableUR;
  }

  public function addOrUpdate(ObservableUR $ObservableUR){
    if(strlen($ObservableUR->getBornDate())>0){
      $ObservableUR=$this->update($ObservableUR);
    }else{
      $ObservableUR=$this->add($ObservableUR);
    }
    return $ObservableUR;
  }

  public function delete(ObservableUR $ObservableUR){
    $sql="DELETE FROM ObservableUR WHERE Observable=:Observable AND UnidadResponsable=:UnidadResponsable;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Observable' => $ObservableUR->getObservable(), ':UnidadResponsable' => $ObservableUR->getUnidadResponsable()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Observable,$UnidadResponsable){
    $sql="SELECT * FROM ObservableUR WHERE Observable=$$Observable AND UnidadResponsable=$UnidadResponsable;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ObservableUR=new ObservableUR();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $ObservableUR=$this->createObject($result[0]);
    }
    return $ObservableUR;
  }

  public function showAll(){
    $sql="SELECT * FROM ObservableUR";
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
    $ObservableUR=new ObservableUR();
    $ObservableUR->setObservable($row['Observable']);
    $ObservableUR->setUnidadResponsable($row['UnidadResponsable']);
    $ObservableUR->setBornDate($row['BornDate']);
    $ObservableUR->setBornBy($row['BornBy']);
    return $ObservableUR;
  }
  public function getByObservable($Observable){
    $sql="SELECT * FROM ObservableUR WHERE Observable=$Observable";
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