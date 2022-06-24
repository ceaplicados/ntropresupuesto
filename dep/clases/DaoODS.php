<?php
require_once 'modelos/np_base.php';
require_once 'modelos/ODS.php';

class DaoODS extends np_base{

  public function add(ODS $ODS){
    $sql="INSERT INTO ODS (Nombre,Descripcion,Color) VALUES (:Nombre,:Descripcion,:Color);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Nombre' => $ODS->getNombre(), ':Descripcion' => $ODS->getDescripcion(), ':Color' => $ODS->getColor()));
      $ODS->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (ODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ODS;
  }

  public function update(ODS $ODS){
    $sql="UPDATE ODS SET Nombre=:Nombre, Descripcion=:Descripcion, Color=:Color WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $ODS->getId(), ':Nombre' => $ODS->getNombre(), ':Descripcion' => $ODS->getDescripcion(), ':Color' => $ODS->getColor()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (ODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ODS;
  }

  public function addOrUpdate(ODS $ODS){
    if($ODS->getId()>0){
      $ODS=$this->update($ODS);
    }else{
      $ODS=$this->add($ODS);
    }
    return $ODS;
  }

  public function delete($Id){
    $sql="DELETE FROM ODS  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (ODS): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM ODS WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ODS=new ODS();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $ODS=$this->createObject($result[0]);
    }
    return $ODS;
  }

  public function showAll(){
    $sql="SELECT * FROM ODS";
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
    $ODS=new ODS();
    $ODS->setId($row['Id']);
    $ODS->setNombre($row['Nombre']);
    $ODS->setDescripcion($row['Descripcion']);
    $ODS->setColor($row['Color']);
    if(isset($row['Monto'])){
      $ODS->setMonto($row['Monto']);
    }
    return $ODS;
  }

  public function buscarNombre($Nombre){
    $sql="SELECT * FROM ODS WHERE Nombre='$Nombre'";
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
  
  public function showMontosByVersion($Version){
    $sql="SELECT ODS.*, SUM(Monto) AS Monto FROM ODS LEFT JOIN Programas ON Programas.ODS=ODS.Id JOIN ProgramasMonto ON ProgramasMonto.Programa=Programas.Id WHERE Version=$Version GROUP BY ODS.Id";
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