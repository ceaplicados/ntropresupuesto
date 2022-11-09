<?php
require_once 'modelos/np_base.php';
require_once 'modelos/PartidasGenericas.php';

class DaoPartidasGenericas extends np_base{

  public function add(PartidasGenericas $PartidasGenericas){
    $sql="INSERT INTO PartidasGenericas (Clave,Nombre,ConceptoGeneral) VALUES (:Clave,:Nombre,:ConceptoGeneral);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Clave' => $PartidasGenericas->getClave(), ':Nombre' => $PartidasGenericas->getNombre(), ':ConceptoGeneral' => $PartidasGenericas->getConceptoGeneral()));
      $PartidasGenericas->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $PartidasGenericas;
  }

  public function update(PartidasGenericas $PartidasGenericas){
    $sql="UPDATE PartidasGenericas SET Clave=:Clave, Nombre=:Nombre, ConceptoGeneral=:ConceptoGeneral WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $PartidasGenericas->getId(), ':Clave' => $PartidasGenericas->getClave(), ':Nombre' => $PartidasGenericas->getNombre(), ':ConceptoGeneral' => $PartidasGenericas->getConceptoGeneral()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $PartidasGenericas;
  }

  public function addOrUpdate(PartidasGenericas $PartidasGenericas){
    if($PartidasGenericas->getId()>0){
      $PartidasGenericas=$this->update($PartidasGenericas);
    }else{
      $PartidasGenericas=$this->add($PartidasGenericas);
    }
    return $PartidasGenericas;
  }

  public function delete($Id){
    $sql="DELETE FROM PartidasGenericas  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM PartidasGenericas WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $PartidasGenericas=new PartidasGenericas();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $PartidasGenericas=$this->createObject($result[0]);
    }
    return $PartidasGenericas;
  }

  public function showAll(){
    $sql="SELECT * FROM PartidasGenericas";
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
    $PartidasGenericas=new PartidasGenericas();
    $PartidasGenericas->setId($row['Id']);
    $PartidasGenericas->setClave($row['Clave']);
    $PartidasGenericas->setNombre($row['Nombre']);
    $PartidasGenericas->setConceptoGeneral($row['ConceptoGeneral']);
    if(isset($row['Monto'])){
      $PartidasGenericas->setMonto($row['Monto']);
    }
    return $PartidasGenericas;
  }
  
  public function getByClave($Clave){
    $sql="SELECT * FROM PartidasGenericas WHERE Clave=$Clave;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $PartidasGenericas=new PartidasGenericas();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $PartidasGenericas=$this->createObject($result[0]);
    }
    return $PartidasGenericas;
  }
  
  public function getPresupuestoByConceptoGeneralVersion($ConceptoGeneral,$Version){
    $sql="SELECT PartidasGenericas.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM  PartidasGenericas JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id  WHERE ObjetoDeGasto.VersionPresupuesto=:Version AND PartidasGenericas.ConceptoGeneral=:ConceptoGeneral GROUP BY PartidasGenericas.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':ConceptoGeneral' => $ConceptoGeneral, ':Version' => $Version));
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
  
  public function getPresupuestoByPGVersion($PG,$Version){
    $sql="SELECT PartidasGenericas.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM PartidasGenericas JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id WHERE ObjetoDeGasto.VersionPresupuesto=$Version AND PartidasGenericas.Id=$PG GROUP BY PartidasGenericas.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $PartidasGenericas=new PartidasGenericas();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $PartidasGenericas=$this->createObject($row[0]);
    }
    return $PartidasGenericas;
  }
  public function getPresupuestoByPGURVersion($PG,$UR,$Version){
    $sql="SELECT PartidasGenericas.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM PartidasGenericas JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id WHERE ObjetoDeGasto.UnidadResponsable=$UR AND ObjetoDeGasto.VersionPresupuesto=$Version AND PartidasGenericas.Id=$PG GROUP BY PartidasGenericas.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $PartidasGenericas=new PartidasGenericas();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $PartidasGenericas=$this->createObject($row[0]);
    }
    return $PartidasGenericas;
  }
  public function getPresupuestoByPGUPVersion($PG,$UP,$Version){
    $sql="SELECT PartidasGenericas.*, SUM(ObjetoDeGasto.Monto) AS Monto FROM PartidasGenericas JOIN ObjetoDeGasto ON ObjetoDeGasto.PartidaGenerica=PartidasGenericas.Id JOIN UnidadResponsable ON UnidadResponsable.Id=ObjetoDeGasto.UnidadResponsable WHERE UnidadResponsable.UnidadPresupuestal=$UP AND ObjetoDeGasto.VersionPresupuesto=$Version AND PartidasGenericas.Id=$PG GROUP BY PartidasGenericas.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $PartidasGenericas=new PartidasGenericas();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $PartidasGenericas=$this->createObject($row[0]);
    }
    return $PartidasGenericas;
  }

}