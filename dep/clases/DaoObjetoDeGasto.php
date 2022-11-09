<?php
require_once 'modelos/np_base.php';
require_once 'modelos/ObjetoDeGasto.php';

class DaoObjetoDeGasto extends np_base{

  public function add(ObjetoDeGasto $ObjetoDeGasto){
    $sql="INSERT INTO ObjetoDeGasto (Clave,Nombre,PartidaGenerica,UnidadResponsable,VersionPresupuesto,Monto) VALUES (:Clave,:Nombre,:PartidaGenerica,:UnidadResponsable,:VersionPresupuesto,:Monto);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Clave' => $ObjetoDeGasto->getClave(), ':Nombre' => $ObjetoDeGasto->getNombre(), ':PartidaGenerica' => $ObjetoDeGasto->getPartidaGenerica(), ':UnidadResponsable' => $ObjetoDeGasto->getUnidadResponsable(), ':VersionPresupuesto' => $ObjetoDeGasto->getVersionPresupuesto(), ':Monto' => $ObjetoDeGasto->getMonto()));
      $ObjetoDeGasto->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ObjetoDeGasto;
  }

  public function update(ObjetoDeGasto $ObjetoDeGasto){
    $sql="UPDATE ObjetoDeGasto SET Clave=:Clave, Nombre=:Nombre, PartidaGenerica=:PartidaGenerica, UnidadResponsable=:UnidadResponsable, VersionPresupuesto=:VersionPresupuesto, Monto=:Monto WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $ObjetoDeGasto->getId(), ':Clave' => $ObjetoDeGasto->getClave(), ':Nombre' => $ObjetoDeGasto->getNombre(), ':PartidaGenerica' => $ObjetoDeGasto->getPartidaGenerica(), ':UnidadResponsable' => $ObjetoDeGasto->getUnidadResponsable(), ':VersionPresupuesto' => $ObjetoDeGasto->getVersionPresupuesto(), ':Monto' => $ObjetoDeGasto->getMonto()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ObjetoDeGasto;
  }

  public function addOrUpdate(ObjetoDeGasto $ObjetoDeGasto){
    if($ObjetoDeGasto->getId()>0){
      $ObjetoDeGasto=$this->update($ObjetoDeGasto);
    }else{
      $ObjetoDeGasto=$this->add($ObjetoDeGasto);
    }
    return $ObjetoDeGasto;
  }

  public function delete($Id){
    $sql="DELETE FROM ObjetoDeGasto  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM ObjetoDeGasto WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ObjetoDeGasto=new ObjetoDeGasto();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $ObjetoDeGasto=$this->createObject($result[0]);
    }
    return $ObjetoDeGasto;
  }

  public function showAll(){
    $sql="SELECT * FROM ObjetoDeGasto";
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
    $ObjetoDeGasto=new ObjetoDeGasto();
    $ObjetoDeGasto->setId($row['Id']);
    $ObjetoDeGasto->setClave($row['Clave']);
    $ObjetoDeGasto->setNombre($row['Nombre']);
    $ObjetoDeGasto->setPartidaGenerica($row['PartidaGenerica']);
    $ObjetoDeGasto->setUnidadResponsable($row['UnidadResponsable']);
    $ObjetoDeGasto->setVersionPresupuesto($row['VersionPresupuesto']);
    $ObjetoDeGasto->setMonto($row['Monto']);
    return $ObjetoDeGasto;
  }

  public function getByClaveEstado($Clave,$Estado){
    $sql="SELECT DISTINCT ObjetoDeGasto.Clave, ObjetoDeGasto.Nombre, ObjetoDeGasto.PartidaGenerica FROM ObjetoDeGasto JOIN VersionesPresupuesto ON VersionesPresupuesto.Id=ObjetoDeGasto.VersionPresupuesto WHERE Clave=$Clave AND Estado=$Estado";
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
  
  public function getPresupuestoByPartidaGenericaVersion($PartidaGenerica,$Version){
    $sql="SELECT * FROM ObjetoDeGasto WHERE ObjetoDeGasto.VersionPresupuesto=:Version AND PartidaGenerica=:PartidaGenerica ORDER BY Clave";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':PartidaGenerica' => $PartidaGenerica, ':Version' => $Version));
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
  
  public function getPresupuestoByClaveOGVersion($ClaveOG,$Version){
    $sql="SELECT ObjetoDeGasto.Clave, MAX(ObjetoDeGasto.Nombre) AS Nombre, SUM(Monto) AS Monto FROM ObjetoDeGasto WHERE VersionPresupuesto=$Version AND ObjetoDeGasto.Clave=$ClaveOG GROUP BY Clave";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ObjetoDeGasto=new ObjetoDeGasto();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $ObjetoDeGasto=$this->createObject($row[0]);
    }
    return $ObjetoDeGasto;
  }
  public function getPresupuestoByClaveOGURVersion($ClaveOG,$UR,$Version){
    $sql="SELECT Clave, MAX(Nombre) AS Nombre, SUM(Monto) AS Monto FROM ObjetoDeGasto WHERE UnidadResponsable=$UR AND VersionPresupuesto=$Version AND Clave=$ClaveOG GROUP BY Clave";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ObjetoDeGasto=new ObjetoDeGasto();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $ObjetoDeGasto=$this->createObject($row[0]);
    }
    return $ObjetoDeGasto;
  }
  public function getPresupuestoByClaveOGUPVersion($ClaveOG,$UP,$Version){
    $sql="SELECT ObjetoDeGasto.Clave, MAX(ObjetoDeGasto.Nombre) AS Nombre, SUM(Monto) AS Monto FROM ObjetoDeGasto JOIN UnidadResponsable ON UnidadResponsable.Id=ObjetoDeGasto.UnidadResponsable WHERE UnidadPresupuestal=$UP AND VersionPresupuesto=$Version AND ObjetoDeGasto.Clave=$ClaveOG GROUP BY Clave";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ObjetoDeGasto=new ObjetoDeGasto();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $ObjetoDeGasto=$this->createObject($row[0]);
    }
    return $ObjetoDeGasto;
  }
}