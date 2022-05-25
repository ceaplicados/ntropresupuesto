<?php
require_once 'modelos/np_base.php';
require_once 'modelos/UsuariosCuaderno.php';

class DaoUsuariosCuaderno extends np_base{

  public function add(UsuariosCuaderno $UsuariosCuaderno){
    $sql="INSERT INTO UsuariosCuaderno (Usuario,Cuaderno) VALUES (:Usuario,:Cuaderno);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Usuario' => $UsuariosCuaderno->getUsuario(), ':Cuaderno' => $UsuariosCuaderno->getCuaderno()));
      $UsuariosCuaderno->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $UsuariosCuaderno;
  }

  public function update(UsuariosCuaderno $UsuariosCuaderno){
    $sql="UPDATE UsuariosCuaderno SET Usuario=:Usuario, Cuaderno=:Cuaderno WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $UsuariosCuaderno->getId(), ':Usuario' => $UsuariosCuaderno->getUsuario(), ':Cuaderno' => $UsuariosCuaderno->getCuaderno()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $UsuariosCuaderno;
  }

  public function addOrUpdate(UsuariosCuaderno $UsuariosCuaderno){
    if($UsuariosCuaderno->getId()>0){
      $UsuariosCuaderno=$this->update($UsuariosCuaderno);
    }else{
      $UsuariosCuaderno=$this->add($UsuariosCuaderno);
    }
    return $UsuariosCuaderno;
  }

  public function delete($Id){
    $sql="DELETE FROM UsuariosCuaderno  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM UsuariosCuaderno WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $UsuariosCuaderno=new UsuariosCuaderno();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $UsuariosCuaderno=$this->createObject($result[0]);
    }
    return $UsuariosCuaderno;
  }

  public function showAll(){
    $sql="SELECT * FROM UsuariosCuaderno";
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
    $UsuariosCuaderno=new UsuariosCuaderno();
    $UsuariosCuaderno->setId($row['Id']);
    $UsuariosCuaderno->setUsuario($row['Usuario']);
    $UsuariosCuaderno->setCuaderno($row['Cuaderno']);
    return $UsuariosCuaderno;
  }
  
  public function deleteByUsuarioCuaderno($Usuario,$Cuaderno){
    $sql="DELETE FROM UsuariosCuaderno  WHERE  Usuario=$Usuario AND Cuaderno=$Cuaderno;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return true;
  }

}