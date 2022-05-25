<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Comentarios.php';

class DaoComentarios extends np_base{

  public function add(Comentarios $Comentarios){
    $sql="INSERT INTO Comentarios (Usuario,Dateborn,Observacion,EnRespuesta,Comentario) VALUES (:Usuario,:Dateborn,:Observacion,:EnRespuesta,:Comentario);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Usuario' => $Comentarios->getUsuario(), ':Dateborn' => $Comentarios->getDateborn(), ':Observacion' => $Comentarios->getObservacion(), ':EnRespuesta' => $Comentarios->getEnRespuesta(), ':Comentario' => $Comentarios->getComentario()));
      $Comentarios->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Comentarios;
  }

  public function update(Comentarios $Comentarios){
    $sql="UPDATE Comentarios SET Usuario=:Usuario, Dateborn=:Dateborn, Observacion=:Observacion, EnRespuesta=:EnRespuesta, Comentario=:Comentario WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Comentarios->getId(), ':Usuario' => $Comentarios->getUsuario(), ':Dateborn' => $Comentarios->getDateborn(), ':Observacion' => $Comentarios->getObservacion(), ':EnRespuesta' => $Comentarios->getEnRespuesta(), ':Comentario' => $Comentarios->getComentario()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Comentarios;
  }

  public function addOrUpdate(Comentarios $Comentarios){
    if($Comentarios->getId()>0){
      $Comentarios=$this->update($Comentarios);
    }else{
      $Comentarios=$this->add($Comentarios);
    }
    return $Comentarios;
  }

  public function delete($Id){
    $sql="DELETE FROM Comentarios  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM Comentarios WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Comentarios=new Comentarios();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Comentarios=$this->createObject($result[0]);
    }
    return $Comentarios;
  }

  public function showAll(){
    $sql="SELECT * FROM Comentarios";
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
    $Comentarios=new Comentarios();
    $Comentarios->setId($row['Id']);
    $Comentarios->setUsuario($row['Usuario']);
    $Comentarios->setDateborn($row['Dateborn']);
    $Comentarios->setObservacion($row['Observacion']);
    $Comentarios->setEnRespuesta($row['EnRespuesta']);
    $Comentarios->setComentario($row['Comentario']);
    return $Comentarios;
  }
  
  public function getByObservacion($Observacion){
    $sql="SELECT * FROM Comentarios WHERE Observacion=$Observacion";
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