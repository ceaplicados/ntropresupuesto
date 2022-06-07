<?php
require_once 'modelos/np_base.php';
require_once 'modelos/UnidadPresupuestal.php';

class DaoUnidadPresupuestal extends np_base{

  public function add(UnidadPresupuestal $UnidadPresupuestal){
    $sql="INSERT INTO UnidadPresupuestal (Clave,Nombre,Estado) VALUES (:Clave,:Nombre,:Estado);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Clave' => $UnidadPresupuestal->getClave(), ':Nombre' => $UnidadPresupuestal->getNombre(), ':Estado' => $UnidadPresupuestal->getEstado()));
      $UnidadPresupuestal->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $UnidadPresupuestal;
  }

  public function update(UnidadPresupuestal $UnidadPresupuestal){
    $sql="UPDATE UnidadPresupuestal SET Clave=:Clave, Nombre=:Nombre, Estado=:Estado WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $UnidadPresupuestal->getId(), ':Clave' => $UnidadPresupuestal->getClave(), ':Nombre' => $UnidadPresupuestal->getNombre(), ':Estado' => $UnidadPresupuestal->getEstado()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $UnidadPresupuestal;
  }

  public function addOrUpdate(UnidadPresupuestal $UnidadPresupuestal){
    if($UnidadPresupuestal->getId()>0){
      $UnidadPresupuestal=$this->update($UnidadPresupuestal);
    }else{
      $UnidadPresupuestal=$this->add($UnidadPresupuestal);
    }
    return $UnidadPresupuestal;
  }

  public function delete($Id){
    $sql="DELETE FROM UnidadPresupuestal  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM UnidadPresupuestal WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $UnidadPresupuestal=new UnidadPresupuestal();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $UnidadPresupuestal=$this->createObject($result[0]);
    }
    return $UnidadPresupuestal;
  }

  public function showAll(){
    $sql="SELECT * FROM UnidadPresupuestal";
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
    $UnidadPresupuestal=new UnidadPresupuestal();
    $UnidadPresupuestal->setId($row['Id']);
    $UnidadPresupuestal->setClave($row['Clave']);
    $UnidadPresupuestal->setNombre($row['Nombre']);
    $UnidadPresupuestal->setEstado($row['Estado']);
    if(isset($row['Monto'])){
      $UnidadPresupuestal->setMonto($row['Monto']);
    }
    return $UnidadPresupuestal;
  }
  
  public function getByEstado($Estado){
    $sql="SELECT * FROM UnidadPresupuestal WHERE Estado=$Estado";
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
  
  public function buscarByEstado($Buscar,$Estado){
    $sql="SELECT *,CONCAT(LPAD(Clave,3,0),Nombre) AS Buscar FROM UnidadPresupuestal WHERE Estado=$Estado HAVING Buscar LIKE '%$Buscar%'";
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
  
  public function getPresupuestoByVersion($Version,$UP=NULL){
    $whereUP="";
    if($UP!==NULL){
      $whereUP=" AND UnidadResponsable.UnidadPresupuestal=$UP";
    }
    $sql="SELECT UnidadPresupuestal.*, SUM(Monto) AS Monto  FROM ObjetoDeGasto JOIN UnidadResponsable ON UnidadResponsable.Id=ObjetoDeGasto.UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal WHERE VersionPresupuesto=$Version $whereUP GROUP BY UnidadResponsable.UnidadPresupuestal ORDER BY UnidadPresupuestal.Clave";
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