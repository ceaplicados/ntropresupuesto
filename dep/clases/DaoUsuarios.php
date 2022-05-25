<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Usuarios.php';

class DaoUsuarios extends np_base{

  public function add(Usuarios $Usuarios){
    $sql="INSERT INTO Usuarios (Sobrenombre,Nombre,Telefono,Celular,Email,Estado,Password,DateBorn,Activo,ResetKey,Image,UUID) VALUES (:Sobrenombre,:Nombre,:Telefono,:Celular,:Email,:Estado,:Password,:DateBorn,:Activo,:ResetKey,:Image,:UUID);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Sobrenombre' => $Usuarios->getSobrenombre(), ':Nombre' => $Usuarios->getNombre(), ':Telefono' => $Usuarios->getTelefono(), ':Celular' => $Usuarios->getCelular(), ':Email' => $Usuarios->getEmail(), ':Estado' => $Usuarios->getEstado(), ':Password' => $Usuarios->getPassword(), ':DateBorn' => $Usuarios->getDateBorn(), ':Activo' => $Usuarios->getActivo(), ':ResetKey' => $Usuarios->getResetKey(), ':Image' => $Usuarios->getImage(), ':UUID' => $Usuarios->getUUID()));
      $Usuarios->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Usuarios;
  }

  public function update(Usuarios $Usuarios){
    $sql="UPDATE Usuarios SET Sobrenombre=:Sobrenombre, Nombre=:Nombre, Telefono=:Telefono, Celular=:Celular, Email=:Email, Estado=:Estado, Password=:Password, DateBorn=:DateBorn, Activo=:Activo, ResetKey=:ResetKey, Image=:Image, UUID=:UUID WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Usuarios->getId(), ':Sobrenombre' => $Usuarios->getSobrenombre(), ':Nombre' => $Usuarios->getNombre(), ':Telefono' => $Usuarios->getTelefono(), ':Celular' => $Usuarios->getCelular(), ':Email' => $Usuarios->getEmail(), ':Estado' => $Usuarios->getEstado(), ':Password' => $Usuarios->getPassword(), ':DateBorn' => $Usuarios->getDateBorn(), ':Activo' => $Usuarios->getActivo(), ':ResetKey' => $Usuarios->getResetKey(), ':Image' => $Usuarios->getImage(), ':UUID' => $Usuarios->getUUID()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Usuarios;
  }

  public function addOrUpdate(Usuarios $Usuarios){
    if($Usuarios->getId()>0){
      $Usuarios=$this->update($Usuarios);
    }else{
      $Usuarios=$this->add($Usuarios);
    }
    return $Usuarios;
  }

  public function delete($Id){
    $sql="DELETE FROM Usuarios  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM Usuarios WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Usuarios=new Usuarios();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Usuarios=$this->createObject($result[0]);
    }
    return $Usuarios;
  }

  public function showAll(){
    $sql="SELECT * FROM Usuarios";
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
    $Usuarios=new Usuarios();
    $Usuarios->setId($row['Id']);
    $Usuarios->setSobrenombre($row['Sobrenombre']);
    $Usuarios->setNombre($row['Nombre']);
    $Usuarios->setTelefono($row['Telefono']);
    $Usuarios->setCelular($row['Celular']);
    $Usuarios->setEmail($row['Email']);
    $Usuarios->setEstado($row['Estado']);
    $Usuarios->setPassword($row['Password']);
    $Usuarios->setDateBorn($row['DateBorn']);
    $Usuarios->setActivo($row['Activo']);
    $Usuarios->setResetKey($row['ResetKey']);
    $Usuarios->setImage($row['Image']);
    $Usuarios->setUUID($row['UUID']);
    return $Usuarios;
  }
  public function getByEmail($Email){
    $sql="SELECT * FROM Usuarios WHERE Email='$Email';";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Usuarios=new Usuarios();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Usuarios=$this->createObject($result[0]);
    }
    return $Usuarios;
  }

}