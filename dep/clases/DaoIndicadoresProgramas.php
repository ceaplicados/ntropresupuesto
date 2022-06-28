<?php
require_once 'modelos/np_base.php';
require_once 'modelos/IndicadoresProgramas.php';

class DaoIndicadoresProgramas extends np_base{

  public function add(IndicadoresProgramas $IndicadoresProgramas){
    $sql="INSERT INTO IndicadoresProgramas (Programa,IdMIR,Nombre,Nivel,Resumen,Medios,Supuesto,Data) VALUES (:Programa,:IdMIR,:Nombre,:Nivel,:Resumen,:Medios,:Supuesto,:Data);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Programa' => $IndicadoresProgramas->getPrograma(), ':IdMIR' => $IndicadoresProgramas->getIdMIR(), ':Nombre' => $IndicadoresProgramas->getNombre(), ':Nivel' => $IndicadoresProgramas->getNivel(), ':Resumen' => $IndicadoresProgramas->getResumen(), ':Medios' => $IndicadoresProgramas->getMedios(), ':Supuesto' => $IndicadoresProgramas->getSupuesto(), ':Data' => $IndicadoresProgramas->getData()));
      $IndicadoresProgramas->setId($this->_dbh->lastInsertId());
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (IndicadoresProgramas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $IndicadoresProgramas;
  }

  public function update(IndicadoresProgramas $IndicadoresProgramas){
    $sql="UPDATE IndicadoresProgramas SET Programa=:Programa, IdMIR=:IdMIR, Nombre=:Nombre, Nivel=:Nivel, Resumen=:Resumen, Medios=:Medios, Supuesto=:Supuesto, Data=:Data WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $IndicadoresProgramas->getId(), ':Programa' => $IndicadoresProgramas->getPrograma(), ':IdMIR' => $IndicadoresProgramas->getIdMIR(), ':Nombre' => $IndicadoresProgramas->getNombre(), ':Nivel' => $IndicadoresProgramas->getNivel(), ':Resumen' => $IndicadoresProgramas->getResumen(), ':Medios' => $IndicadoresProgramas->getMedios(), ':Supuesto' => $IndicadoresProgramas->getSupuesto(), ':Data' => $IndicadoresProgramas->getData()));
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (IndicadoresProgramas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $IndicadoresProgramas;
  }

  public function addOrUpdate(IndicadoresProgramas $IndicadoresProgramas){
    if($IndicadoresProgramas->getId()>0){
      $IndicadoresProgramas=$this->update($IndicadoresProgramas);
    }else{
      $IndicadoresProgramas=$this->add($IndicadoresProgramas);
    }
    return $IndicadoresProgramas;
  }

  public function delete($Id){
    $sql="DELETE FROM IndicadoresProgramas  WHERE  Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      $error=$sth->errorInfo();
      if($error[0]>0){
            error_log('PDO Error (IndicadoresProgramas): '.json_encode($error));
      }
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

  public function show($Id){
    $sql="SELECT * FROM IndicadoresProgramas WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $IndicadoresProgramas=new IndicadoresProgramas();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $IndicadoresProgramas=$this->createObject($result[0]);
    }
    return $IndicadoresProgramas;
  }

  public function showAll(){
    $sql="SELECT * FROM IndicadoresProgramas";
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
    $IndicadoresProgramas=new IndicadoresProgramas();
    $IndicadoresProgramas->setId($row['Id']);
    $IndicadoresProgramas->setPrograma($row['Programa']);
    $IndicadoresProgramas->setIdMIR($row['IdMIR']);
    $IndicadoresProgramas->setNombre($row['Nombre']);
    $IndicadoresProgramas->setNivel($row['Nivel']);
    $IndicadoresProgramas->setResumen($row['Resumen']);
    $IndicadoresProgramas->setMedios($row['Medios']);
    $IndicadoresProgramas->setSupuesto($row['Supuesto']);
    $IndicadoresProgramas->setData($row['Data']);
    return $IndicadoresProgramas;
  }
  
  public function getByPrograma($Programa){
    $sql="SELECT * FROM IndicadoresProgramas WHERE Programa=$Programa";
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