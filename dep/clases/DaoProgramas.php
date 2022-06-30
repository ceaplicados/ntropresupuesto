<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Programas.php';

class DaoProgramas extends np_base{

  public function add(Programas $Programas){
    $sql="INSERT INTO Programas (Clave,Nombre,UnidadResponsable,ODS,MetaODS,Data) VALUES (:Clave,:Nombre,:UnidadResponsable,:ODS,:MetaODS);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Clave' => $Programas->getClave(), ':Nombre' => $Programas->getNombre(), ':UnidadResponsable' => $Programas->getUnidadResponsable(), ':ODS' => $Programas->getODS(), ':MetaODS' => $Programas->getMetaODS(), ':Data' => $Programas->getData()));
      $Programas->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (Programas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Programas;
  }

  public function update(Programas $Programas){
    $sql="UPDATE Programas SET Clave=:Clave, Nombre=:Nombre, UnidadResponsable=:UnidadResponsable, ODS=:ODS, MetaODS=:MetaODS, Data=:Data WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Programas->getId(), ':Clave' => $Programas->getClave(), ':Nombre' => $Programas->getNombre(), ':UnidadResponsable' => $Programas->getUnidadResponsable(), ':ODS' => $Programas->getODS(), ':MetaODS' => $Programas->getMetaODS(), ':Data' => $Programas->getData()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (Programas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Programas;
  }

  public function addOrUpdate(Programas $Programas){
    if($Programas->getId()>0){
      $Programas=$this->update($Programas);
    }else{
      $Programas=$this->add($Programas);
    }
    return $Programas;
  }

  public function delete($Id){
    $sql="DELETE FROM Programas  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (Programas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM Programas WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Programas=new Programas();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Programas=$this->createObject($result[0]);
    }
    return $Programas;
  }

  public function showAll(){
    $sql="SELECT * FROM Programas";
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
    $Programas=new Programas();
    $Programas->setId($row['Id']);
    $Programas->setClave($row['Clave']);
    $Programas->setNombre($row['Nombre']);
    $Programas->setUnidadResponsable($row['UnidadResponsable']);
    $Programas->setODS($row['ODS']);
    $Programas->setMetaODS($row['MetaODS']);
    $Programas->setData($row['Data']);
    if(isset($row['Monto'])){
      $Programas->setMonto($row['Monto']);
    }
    return $Programas;
  }
  
  public function getByClaveUnidadResponsable($Clave,$UnidadResponsable){
    $sql="SELECT * FROM Programas WHERE Clave='$Clave' AND UnidadResponsable=$UnidadResponsable;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Programas=new Programas();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Programas=$this->createObject($result[0]);
    }
    return $Programas;
  }
  
  public function getMontoByURVersion($UR,$Version,$Programa=NULL){
    $wherePrograma="";
    if($Programa!==NULL){
      $wherePrograma=" AND  Programas.Id=$Programa";
    }
    $sql="SELECT Programas.*, SUM(Monto) AS Monto FROM Programas JOIN ProgramasMonto ON ProgramasMonto.Programa=Programas.Id WHERE UnidadResponsable=$UR AND ProgramasMonto.Version=$Version $wherePrograma GROUP BY Programas.Id";
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
  
  public function getMontoByProgramaVersion($Programa,$Version){
    $sql="SELECT Programas.*, SUM(Monto) AS Monto FROM Programas JOIN ProgramasMonto ON ProgramasMonto.Programa=Programas.Id WHERE ProgramasMonto.Version=$Version  AND  Programas.Id=$Programa GROUP BY Programas.Id";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Programas=new Programas();
    $row=$sth->fetchAll();
    if(count($row)>0){
      $Programas=$this->createObject($row[0]);
    }
    return $Programas;
  }
  
  public function searchByEstado($Estado,$search=NULL,$URs=array()){
    $having="";
    if(!is_null($search)){
      $having=" HAVING Buscar LIKE '%$search%'";
    }
    $where="";
    if(count($URs)>0){
      $where="AND UnidadResponsable.Id IN (".implode(",", $URs).")";
    }
    $sql="SELECT CONCAT(Programas.Clave,Programas.Nombre) AS Buscar, Programas.* FROM Programas JOIN UnidadResponsable ON UnidadResponsable.Id=Programas.UnidadResponsable JOIN UnidadPresupuestal ON UnidadPresupuestal.Id=UnidadResponsable.UnidadPresupuestal WHERE Estado=$Estado $where $having";
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
  
  public function sinPropuestaODS($UR=null,$limit=null,$offset=null){
    $sql="SELECT DISTINCT Programas.* FROM Programas JOIN IndicadoresProgramas ON IndicadoresProgramas.Programa=Programas.Id LEFT JOIN PropuestaProgramaODS ON PropuestaProgramaODS.Programa=Programas.Id WHERE UnidadResponsable=$UR AND PropuestaProgramaODS.Id IS NULL";
    if($limit!==null){
      $sql.=" LIMIT $limit";
      if($offset!==null){
        $sql.=" OFFSET $offset";
      }  
    }
    $sql.=" ORDER BY Programas.Id";
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