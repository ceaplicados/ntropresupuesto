<?php
require_once 'modelos/np_base.php';
require_once 'modelos/CapitulosGasto.php';

class DaoCapitulosGasto extends np_base{

  public function add(CapitulosGasto $CapitulosGasto){
    $sql="INSERT INTO CapitulosGasto (Clave,Nombre) VALUES (:Clave,:Nombre);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Clave' => $CapitulosGasto->getClave(), ':Nombre' => $CapitulosGasto->getNombre()));
      $CapitulosGasto->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $CapitulosGasto;
  }

  public function update(CapitulosGasto $CapitulosGasto){
    $sql="UPDATE CapitulosGasto SET Clave=:Clave, Nombre=:Nombre WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $CapitulosGasto->getId(), ':Clave' => $CapitulosGasto->getClave(), ':Nombre' => $CapitulosGasto->getNombre()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $CapitulosGasto;
  }

  public function addOrUpdate(CapitulosGasto $CapitulosGasto){
    if($CapitulosGasto->getId()>0){
      $CapitulosGasto=$this->update($CapitulosGasto);
    }else{
      $CapitulosGasto=$this->add($CapitulosGasto);
    }
    return $CapitulosGasto;
  }

  public function delete($Id){
    $sql="DELETE FROM CapitulosGasto  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM CapitulosGasto WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $CapitulosGasto=new CapitulosGasto();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $CapitulosGasto=$this->createObject($result[0]);
    }
    return $CapitulosGasto;
  }

  public function showAll(){
    $sql="SELECT * FROM CapitulosGasto";
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
    $CapitulosGasto=new CapitulosGasto();
    $CapitulosGasto->setId($row['Id']);
    $CapitulosGasto->setClave($row['Clave']);
    $CapitulosGasto->setNombre($row['Nombre']);
    if(isset($row['Monto'])){
      $CapitulosGasto->setMonto($row['Monto']);
    }
    return $CapitulosGasto;
  }
  public function getPresupuestoByVersion($Version){
    $sql="SELECT CapitulosGasto.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM CapitulosGasto JOIN ConceptosGenerales ON ConceptosGenerales.CapituloGasto=CapitulosGasto.Id JOIN PartidasGenericas ON PartidasGenericas.ConceptoGeneral=ConceptosGenerales.Id JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id WHERE ObjetoDeGasto.VersionPresupuesto=$Version GROUP BY CapitulosGasto.Id";
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
  public function getPresupuestoByURVersion($UR,$Version){
    $sql="SELECT CapitulosGasto.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM CapitulosGasto JOIN ConceptosGenerales ON ConceptosGenerales.CapituloGasto=CapitulosGasto.Id JOIN PartidasGenericas ON PartidasGenericas.ConceptoGeneral=ConceptosGenerales.Id JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id WHERE ObjetoDeGasto.UnidadResponsable=$UR AND ObjetoDeGasto.VersionPresupuesto=$Version GROUP BY CapitulosGasto.Id";
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
  public function getPresupuestoByCGVersion($CG,$Version){
    $sql="SELECT CapitulosGasto.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM CapitulosGasto JOIN ConceptosGenerales ON ConceptosGenerales.CapituloGasto=CapitulosGasto.Id JOIN PartidasGenericas ON PartidasGenericas.ConceptoGeneral=ConceptosGenerales.Id JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id  AND ObjetoDeGasto.VersionPresupuesto=$Version AND CapitulosGasto.Id=$CG GROUP BY CapitulosGasto.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $CapitulosGasto=new CapitulosGasto();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $CapitulosGasto=$this->createObject($row[0]);
    }
    return $CapitulosGasto;
  }
  public function getPresupuestoByCGURVersion($CG,$UR,$Version){
    $sql="SELECT CapitulosGasto.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM CapitulosGasto JOIN ConceptosGenerales ON ConceptosGenerales.CapituloGasto=CapitulosGasto.Id JOIN PartidasGenericas ON PartidasGenericas.ConceptoGeneral=ConceptosGenerales.Id JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id WHERE ObjetoDeGasto.UnidadResponsable=$UR AND ObjetoDeGasto.VersionPresupuesto=$Version AND CapitulosGasto.Id=$CG GROUP BY CapitulosGasto.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $CapitulosGasto=new CapitulosGasto();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $CapitulosGasto=$this->createObject($row[0]);
    }
    return $CapitulosGasto;
  }
  public function getPresupuestoByCGUPVersion($CG,$UP,$Version){
    $sql="SELECT CapitulosGasto.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM CapitulosGasto JOIN ConceptosGenerales ON ConceptosGenerales.CapituloGasto=CapitulosGasto.Id JOIN PartidasGenericas ON PartidasGenericas.ConceptoGeneral=ConceptosGenerales.Id JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id JOIN UnidadResponsable ON UnidadResponsable.Id=ObjetoDeGasto.UnidadResponsable WHERE UnidadResponsable.UnidadPresupuestal=$UP AND ObjetoDeGasto.VersionPresupuesto=$Version AND CapitulosGasto.Id=$CG GROUP BY CapitulosGasto.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $CapitulosGasto=new CapitulosGasto();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $CapitulosGasto=$this->createObject($row[0]);
    }
    return $CapitulosGasto;
  }
}