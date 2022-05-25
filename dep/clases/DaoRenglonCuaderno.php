<?php
require_once 'modelos/np_base.php';
require_once 'modelos/RenglonCuaderno.php';

class DaoRenglonCuaderno extends np_base{

  public function add(RenglonCuaderno $RenglonCuaderno){
    $sql="INSERT INTO RenglonCuaderno (Cuaderno,Tipo,Estado,IdReferencia,TipoFiltro,IdFiltro,Graph,Mostrar,Data) VALUES (:Cuaderno,:Tipo,:Estado,:IdReferencia,:TipoFiltro,:IdFiltro,:Graph,:Mostrar,:Data);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Cuaderno' => $RenglonCuaderno->getCuaderno(), ':Tipo' => $RenglonCuaderno->getTipo(), ':Estado' => $RenglonCuaderno->getEstado(), ':IdReferencia' => $RenglonCuaderno->getIdReferencia(), ':TipoFiltro' => $RenglonCuaderno->getTipoFiltro(), ':IdFiltro' => $RenglonCuaderno->getIdFiltro(), ':Graph' => $RenglonCuaderno->getGraph(), ':Mostrar' => $RenglonCuaderno->getMostrar(), ':Data' => json_encode($RenglonCuaderno->getData())));
      $RenglonCuaderno->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $RenglonCuaderno;
  }

  public function update(RenglonCuaderno $RenglonCuaderno){
    $sql="UPDATE RenglonCuaderno SET Cuaderno=:Cuaderno, Tipo=:Tipo, Estado=:Estado, IdReferencia=:IdReferencia,  TipoFiltro=:TipoFiltro, IdFiltro=:IdFiltro, Graph=:Graph, Mostrar=:Mostrar, Data=:Data WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $RenglonCuaderno->getId(), ':Cuaderno' => $RenglonCuaderno->getCuaderno(), ':Tipo' => $RenglonCuaderno->getTipo(), ':Estado' => $RenglonCuaderno->getEstado(), ':IdReferencia' => $RenglonCuaderno->getIdReferencia(), ':TipoFiltro' => $RenglonCuaderno->getTipoFiltro(), ':IdFiltro' => $RenglonCuaderno->getIdFiltro(), ':Graph' => $RenglonCuaderno->getGraph(), ':Mostrar' => $RenglonCuaderno->getMostrar(), ':Data' => json_encode($RenglonCuaderno->getData())));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $RenglonCuaderno;
  }

  public function addOrUpdate(RenglonCuaderno $RenglonCuaderno){
    if($RenglonCuaderno->getId()>0){
      $RenglonCuaderno=$this->update($RenglonCuaderno);
    }else{
      $RenglonCuaderno=$this->add($RenglonCuaderno);
    }
    return $RenglonCuaderno;
  }

  public function delete($Id){
    $sql="DELETE FROM RenglonCuaderno  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM RenglonCuaderno WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $RenglonCuaderno=new RenglonCuaderno();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $RenglonCuaderno=$this->createObject($result[0]);
    }
    return $RenglonCuaderno;
  }

  public function showAll(){
    $sql="SELECT * FROM RenglonCuaderno";
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
    $RenglonCuaderno=new RenglonCuaderno();
    $RenglonCuaderno->setId($row['Id']);
    $RenglonCuaderno->setCuaderno($row['Cuaderno']);
    $RenglonCuaderno->setTipo($row['Tipo']);
    $RenglonCuaderno->setEstado($row['Estado']);
    $RenglonCuaderno->setIdReferencia($row['IdReferencia']);
    $RenglonCuaderno->setIdFiltro($row['IdFiltro']);
    $RenglonCuaderno->setTipoFiltro($row['TipoFiltro']);
    $RenglonCuaderno->setGraph($row['Graph']);
    $RenglonCuaderno->setMostrar($row['Mostrar']);
    $RenglonCuaderno->setData(json_decode($row['Data'],true));
    return $RenglonCuaderno;
  }
  
  public function getByCuaderno($Cuaderno){
    $sql="SELECT * FROM RenglonCuaderno WHERE Cuaderno=$Cuaderno";
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