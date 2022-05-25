<?php
require_once 'modelos/np_base.php';
require_once 'modelos/ConceptosGenerales.php';

class DaoConceptosGenerales extends np_base{

  public function add(ConceptosGenerales $ConceptosGenerales){
    $sql="INSERT INTO ConceptosGenerales (Clave,Nombre,CapituloGasto) VALUES (:Clave,:Nombre,:CapituloGasto);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Clave' => $ConceptosGenerales->getClave(), ':Nombre' => $ConceptosGenerales->getNombre(), ':CapituloGasto' => $ConceptosGenerales->getCapituloGasto()));
      $ConceptosGenerales->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ConceptosGenerales;
  }

  public function update(ConceptosGenerales $ConceptosGenerales){
    $sql="UPDATE ConceptosGenerales SET Clave=:Clave, Nombre=:Nombre, CapituloGasto=:CapituloGasto WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $ConceptosGenerales->getId(), ':Clave' => $ConceptosGenerales->getClave(), ':Nombre' => $ConceptosGenerales->getNombre(), ':CapituloGasto' => $ConceptosGenerales->getCapituloGasto()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ConceptosGenerales;
  }

  public function addOrUpdate(ConceptosGenerales $ConceptosGenerales){
    if($ConceptosGenerales->getId()>0){
      $ConceptosGenerales=$this->update($ConceptosGenerales);
    }else{
      $ConceptosGenerales=$this->add($ConceptosGenerales);
    }
    return $ConceptosGenerales;
  }

  public function delete($Id){
    $sql="DELETE FROM ConceptosGenerales  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM ConceptosGenerales WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ConceptosGenerales=new ConceptosGenerales();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $ConceptosGenerales=$this->createObject($result[0]);
    }
    return $ConceptosGenerales;
  }

  public function showAll(){
    $sql="SELECT * FROM ConceptosGenerales";
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
    $ConceptosGenerales=new ConceptosGenerales();
    $ConceptosGenerales->setId($row['Id']);
    $ConceptosGenerales->setClave($row['Clave']);
    $ConceptosGenerales->setNombre($row['Nombre']);
    $ConceptosGenerales->setCapituloGasto($row['CapituloGasto']);
    if(isset($row['Monto'])){
      $ConceptosGenerales->setMonto($row['Monto']);
    }
    return $ConceptosGenerales;
  }
  public function getPresupuestoByCGVersion($CG,$Version){
    $sql="SELECT ConceptosGenerales.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM ConceptosGenerales JOIN PartidasGenericas ON PartidasGenericas.ConceptoGeneral=ConceptosGenerales.Id JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id  WHERE ObjetoDeGasto.VersionPresupuesto=$Version AND ConceptosGenerales.Id=$CG GROUP BY ConceptosGenerales.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ConceptosGenerales=new ConceptosGenerales();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $ConceptosGenerales=$this->createObject($row[0]);
    }
    return $ConceptosGenerales;
  }
  public function getPresupuestoByCGURVersion($CG,$UR,$Version){
    $sql="SELECT ConceptosGenerales.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM ConceptosGenerales JOIN PartidasGenericas ON PartidasGenericas.ConceptoGeneral=ConceptosGenerales.Id JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id WHERE ObjetoDeGasto.UnidadResponsable=$UR AND ObjetoDeGasto.VersionPresupuesto=$Version AND ConceptosGenerales.Id=$CG GROUP BY ConceptosGenerales.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ConceptosGenerales=new ConceptosGenerales();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $ConceptosGenerales=$this->createObject($row[0]);
    }
    return $ConceptosGenerales;
  }
  public function getPresupuestoByCGUPVersion($CG,$UP,$Version){
    $sql="SELECT ConceptosGenerales.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM ConceptosGenerales JOIN PartidasGenericas ON PartidasGenericas.ConceptoGeneral=ConceptosGenerales.Id JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id JOIN UnidadResponsable ON UnidadResponsable.Id=ObjetoDeGasto.UnidadResponsable WHERE UnidadResponsable.UnidadPresupuestal=$UP AND ObjetoDeGasto.VersionPresupuesto=$Version AND ConceptosGenerales.Id=$CG GROUP BY ConceptosGenerales.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ConceptosGenerales=new ConceptosGenerales();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $ConceptosGenerales=$this->createObject($row[0]);
    }
    return $ConceptosGenerales;
  }


}