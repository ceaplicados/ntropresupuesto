<?php
require_once 'modelos/np_base.php';
require_once 'modelos/VersionesPresupuesto.php';

class DaoVersionesPresupuesto extends np_base{

  public function add(VersionesPresupuesto $VersionesPresupuesto){
    $sql="INSERT INTO VersionesPresupuesto (Estado,Anio,Nombre,Descripcion,Fecha,Actual,ObjetoGasto,ProgramaPresupuestal) VALUES (:Estado,:Anio,:Nombre,:Descripcion,:Fecha,:Actual,:ObjetoGasto,:ProgramaPresupuestal);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Estado' => $VersionesPresupuesto->getEstado(), ':Anio' => $VersionesPresupuesto->getAnio(), ':Nombre' => $VersionesPresupuesto->getNombre(), ':Descripcion' => $VersionesPresupuesto->getDescripcion(), ':Fecha' => $VersionesPresupuesto->getFecha(), ':Actual' => $VersionesPresupuesto->getActual(), ':ObjetoGasto' => $VersionesPresupuesto->getObjetoGasto(), ':ProgramaPresupuestal' => $VersionesPresupuesto->getProgramaPresupuestal()));
      $VersionesPresupuesto->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $VersionesPresupuesto;
  }

  public function update(VersionesPresupuesto $VersionesPresupuesto){
    $sql="UPDATE VersionesPresupuesto SET Estado=:Estado, Anio=:Anio, Nombre=:Nombre, Descripcion=:Descripcion, Fecha=:Fecha, Actual=:Actual,ObjetoGasto=:ObjetoGasto,ProgramaPresupuestal=:ProgramaPresupuestal WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $VersionesPresupuesto->getId(), ':Estado' => $VersionesPresupuesto->getEstado(), ':Anio' => $VersionesPresupuesto->getAnio(), ':Nombre' => $VersionesPresupuesto->getNombre(), ':Descripcion' => $VersionesPresupuesto->getDescripcion(), ':Fecha' => $VersionesPresupuesto->getFecha(), ':Actual' => $VersionesPresupuesto->getActual(), ':ObjetoGasto' => $VersionesPresupuesto->getObjetoGasto(), ':ProgramaPresupuestal' => $VersionesPresupuesto->getProgramaPresupuestal()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $VersionesPresupuesto;
  }

  public function addOrUpdate(VersionesPresupuesto $VersionesPresupuesto){
    if($VersionesPresupuesto->getId()>0){
      $VersionesPresupuesto=$this->update($VersionesPresupuesto);
    }else{
      $VersionesPresupuesto=$this->add($VersionesPresupuesto);
    }
    return $VersionesPresupuesto;
  }

  public function delete($Id){
    $sql="DELETE FROM VersionesPresupuesto  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM VersionesPresupuesto WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $VersionesPresupuesto=new VersionesPresupuesto();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $VersionesPresupuesto=$this->createObject($result[0]);
    }
    return $VersionesPresupuesto;
  }

  public function showAll(){
    $sql="SELECT * FROM VersionesPresupuesto";
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
    $VersionesPresupuesto=new VersionesPresupuesto();
    $VersionesPresupuesto->setId($row['Id']);
    $VersionesPresupuesto->setEstado($row['Estado']);
    $VersionesPresupuesto->setAnio($row['Anio']);
    $VersionesPresupuesto->setNombre($row['Nombre']);
    $VersionesPresupuesto->setDescripcion($row['Descripcion']);
    $VersionesPresupuesto->setFecha($row['Fecha']);
    $VersionesPresupuesto->setActual($row['Actual']);
    $VersionesPresupuesto->setObjetoGasto($row['ObjetoGasto']);
    $VersionesPresupuesto->setProgramaPresupuestal($row['ProgramaPresupuestal']);
    if(isset($row['Monto'])){
      $VersionesPresupuesto->setMonto($row['Monto']);
    }
    return $VersionesPresupuesto;
  }
  public function getByEstado($Estado,$Actual=NULL){
    $WhereActual="";
    if($Actual!==NULL){
      $WhereActual=" AND Actual=1 ";
    }
    $sql="SELECT * FROM VersionesPresupuesto WHERE Estado=$Estado $WhereActual ORDER BY Anio DESC, Actual DESC";
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
  public function getByEstadoAnio($Estado,$Anio,$Actuales=true){
    $sql="SELECT * FROM VersionesPresupuesto WHERE Estado=$Estado AND Anio=$Anio";
    if($Actuales){
      $sql.=" AND Actual=1";
    }
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
  public function showMontosByEstado($Estado,$Actuales=true){
    $sql="SELECT VersionesPresupuesto.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM VersionesPresupuesto JOIN ObjetoDeGasto ON ObjetoDeGasto.VersionPresupuesto=VersionesPresupuesto.Id WHERE VersionesPresupuesto.Estado=$Estado GROUP BY ObjetoDeGasto.VersionPresupuesto ORDER BY Anio DESC, Actual DESC";
    if($Actuales){
      $sql="SELECT VersionesPresupuesto.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM VersionesPresupuesto JOIN ObjetoDeGasto ON ObjetoDeGasto.VersionPresupuesto=VersionesPresupuesto.Id WHERE VersionesPresupuesto.Estado=$Estado AND Actual=1 GROUP BY ObjetoDeGasto.VersionPresupuesto ORDER BY Anio DESC";
    }
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
  public function showMontoTotalByVersion($Version){
    $sql="SELECT VersionesPresupuesto.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM VersionesPresupuesto JOIN ObjetoDeGasto ON ObjetoDeGasto.VersionPresupuesto=VersionesPresupuesto.Id WHERE VersionesPresupuesto.Id=$Version";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $VersionesPresupuesto=new VersionesPresupuesto();
    foreach($sth->fetchAll() as $row){
      $VersionesPresupuesto=$this->createObject($row);
    }
    return $VersionesPresupuesto;
  }
  public function showAniosDisponibles(){
    $sql="SELECT DISTINCT Anio FROM VersionesPresupuesto ORDER BY Anio DESC";
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