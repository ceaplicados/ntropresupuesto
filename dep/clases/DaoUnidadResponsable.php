<?php
require_once 'modelos/np_base.php';
require_once 'modelos/UnidadResponsable.php';

class DaoUnidadResponsable extends np_base{

  public function add(UnidadResponsable $UnidadResponsable){
    $sql="INSERT INTO UnidadResponsable (Clave,Nombre,UnidadPresupuestal,OtrosNombres) VALUES (:Clave,:Nombre,:UnidadPresupuestal,:OtrosNombres);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Clave' => $UnidadResponsable->getClave(), ':Nombre' => $UnidadResponsable->getNombre(), ':UnidadPresupuestal' => $UnidadResponsable->getUnidadPresupuestal(), ':OtrosNombres' => $UnidadResponsable->getOtrosNombres()));
      $UnidadResponsable->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $UnidadResponsable;
  }

  public function update(UnidadResponsable $UnidadResponsable){
    $sql="UPDATE UnidadResponsable SET Clave=:Clave, Nombre=:Nombre, UnidadPresupuestal=:UnidadPresupuestal, OtrosNombres=:OtrosNombres WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $UnidadResponsable->getId(), ':Clave' => $UnidadResponsable->getClave(), ':Nombre' => $UnidadResponsable->getNombre(), ':UnidadPresupuestal' => $UnidadResponsable->getUnidadPresupuestal(), ':OtrosNombres' => $UnidadResponsable->getOtrosNombres()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $UnidadResponsable;
  }

  public function addOrUpdate(UnidadResponsable $UnidadResponsable){
    if($UnidadResponsable->getId()>0){
      $UnidadResponsable=$this->update($UnidadResponsable);
    }else{
      $UnidadResponsable=$this->add($UnidadResponsable);
    }
    return $UnidadResponsable;
  }

  public function delete($Id){
    $sql="DELETE FROM UnidadResponsable  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM UnidadResponsable WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $UnidadResponsable=new UnidadResponsable();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $UnidadResponsable=$this->createObject($result[0]);
    }
    return $UnidadResponsable;
  }

  public function showAll(){
    $sql="SELECT * FROM UnidadResponsable";
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
    $UnidadResponsable=new UnidadResponsable();
    $UnidadResponsable->setId($row['Id']);
    $UnidadResponsable->setClave($row['Clave']);
    $UnidadResponsable->setNombre($row['Nombre']);
    $UnidadResponsable->setUnidadPresupuestal($row['UnidadPresupuestal']);
    $UnidadResponsable->setOtrosNombres($row['OtrosNombres']);
    if(isset($row['Monto'])){
      $UnidadResponsable->setMonto($row['Monto']);
    }
    return $UnidadResponsable;
  }
  
  public function getByClaveUnidadPresupuestal($Clave,$UnidadPresupuestal){
    $sql="SELECT * FROM UnidadResponsable WHERE Clave='$Clave' AND UnidadPresupuestal=$UnidadPresupuestal;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $UnidadResponsable=new UnidadResponsable();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $UnidadResponsable=$this->createObject($result[0]);
    }
    return $UnidadResponsable;
  }
  
  public function getByUnidadPresupuestal($UnidadPresupuestal){
    $sql="SELECT * FROM UnidadResponsable WHERE UnidadPresupuestal=$UnidadPresupuestal";
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
  
  public function getByObservable($Observable){
    $sql="SELECT UnidadResponsable.* FROM UnidadResponsable JOIN ObservableUR ON ObservableUR.UnidadResponsable=UnidadResponsable.Id";
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
  
  public function searchByEstado($Buscar,$Estado){
    $sql="SELECT UnidadResponsable.* FROM UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal WHERE UnidadResponsable.Clave LIKE '%$Buscar%' AND Estado=$Estado";
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
    if(count($resp)==0){
      $sql="SELECT UnidadResponsable.* FROM UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal WHERE UnidadResponsable.Nombre LIKE '%$Buscar%' AND Estado=$Estado";
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
    }
    if(count($resp)==0){
      $sql="SELECT UnidadResponsable.* FROM UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal WHERE UnidadResponsable.OtrosNombres LIKE '%$Buscar%' AND Estado=$Estado";
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
    }
    return $resp;
  }
  
  public function getByEstado($Estado){
    $sql="SELECT UnidadResponsable.* FROM UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal WHERE Estado=$Estado";
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
  
  public function pendientesPresupuestoDDHHbyEstado($Estado){
    $sql="SELECT UnidadResponsable.* FROM UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal JOIN (SELECT DISTINCT UnidadResponsable FROM Programas JOIN IndicadoresProgramas ON IndicadoresProgramas.Programa=Programas.Id LEFT JOIN PropuestaProgramaODS ON PropuestaProgramaODS.Programa=Programas.Id WHERE PropuestaProgramaODS.Id IS NULL) AS ProgramasConIndicadores ON ProgramasConIndicadores.UnidadResponsable=UnidadResponsable.Id WHERE Estado=$Estado";
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
    $sql="SELECT UnidadResponsable.*,CONCAT(LPAD(UnidadPresupuestal.Clave,3,0),'-',LPAD(UnidadResponsable.Clave,3,0),UnidadResponsable.Nombre) AS Buscar FROM UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal WHERE Estado=$Estado HAVING Buscar LIKE '%$Buscar%'";
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
  
  public function getPresupuestoByVersion($Version,$UR=NULL){
    $whereUR="";
    if($UR!==NULL){
      $whereUR=" AND UnidadResponsable.Id=$UR";
    }
    $sql="SELECT UnidadResponsable.*, SUM(Monto) AS Monto  FROM ObjetoDeGasto JOIN UnidadResponsable ON UnidadResponsable.Id=ObjetoDeGasto.UnidadResponsable WHERE VersionPresupuesto=$Version $whereUR GROUP BY UnidadResponsable.Id ORDER BY UnidadPresupuestal,Clave";
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
  
  
  public function getPresupuestoByVersionCapGasto($Version,$CapituloGasto,$UR=NULL){
    $whereUR="";
    if($UR!==NULL){
      $whereUR=" AND UnidadResponsable.Id=$UR";
    }
    $sql="SELECT UnidadResponsable.Id, CONCAT(UnidadPresupuestal.Clave,'-',UnidadResponsable.Clave) AS Clave, UnidadResponsable.Nombre, UnidadPresupuestal, OtrosNombres, SUM(Monto) AS Monto FROM ObjetoDeGasto JOIN UnidadResponsable ON UnidadResponsable.Id=ObjetoDeGasto.UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal JOIN PartidasGenericas ON PartidasGenericas.Id=ObjetoDeGasto.PartidaGenerica JOIN ConceptosGenerales ON ConceptosGenerales.Id=PartidasGenericas.ConceptoGeneral  WHERE VersionPresupuesto=$Version AND CapituloGasto=$CapituloGasto $whereUR GROUP BY UnidadResponsable.Id ORDER BY Clave";
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
  
  public function getPresupuestoByVersionConceptoGeneral($Version,$ConceptoGeneral,$UR=NULL){
    $whereUR="";
    if($UR!==NULL){
      $whereUR=" AND UnidadResponsable.Id=$UR";
    }
    $sql="SELECT UnidadResponsable.Id, CONCAT(UnidadPresupuestal.Clave,'-',UnidadResponsable.Clave) AS Clave, UnidadResponsable.Nombre, UnidadPresupuestal, OtrosNombres, SUM(Monto) AS Monto FROM ObjetoDeGasto JOIN UnidadResponsable ON UnidadResponsable.Id=ObjetoDeGasto.UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal JOIN PartidasGenericas ON PartidasGenericas.Id=ObjetoDeGasto.PartidaGenerica   WHERE VersionPresupuesto=$Version AND ConceptoGeneral=$ConceptoGeneral $whereUR GROUP BY UnidadResponsable.Id ORDER BY Clave";
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
  
  public function getPresupuestoByVersionFromPP($Version,$UR=NULL){
    $whereUR="";
    if($UR!==NULL){
      $whereUR=" AND UnidadResponsable.Id=$UR";
    }
    $sql="SELECT UnidadResponsable.*, SUM(Monto) AS Monto  FROM Programas JOIN UnidadResponsable ON UnidadResponsable.Id=Programas.UnidadResponsable JOIN ProgramasMonto ON ProgramasMonto.Programa=Programas.Id WHERE Version=$Version $whereUR GROUP BY UnidadResponsable.Id ORDER BY UnidadPresupuestal,Clave";
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