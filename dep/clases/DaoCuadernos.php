<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Cuadernos.php';
require_once 'DaoUsuarios.php';
require_once 'DaoCuadernoAnios.php';
require_once 'DaoRenglonCuaderno.php';

class DaoCuadernos extends np_base{

  public function add(Cuadernos $Cuadernos){
    $sql="INSERT INTO Cuadernos (Owner,DateBorn,Nombre,Descripcion,Publico,AnioINPC) VALUES (:Owner,:DateBorn,:Nombre,:Descripcion,:Publico,:AnioINPC);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Owner' => $Cuadernos->getOwner(), ':DateBorn' => $Cuadernos->getDateBorn(), ':Nombre' => $Cuadernos->getNombre(), ':Descripcion' => $Cuadernos->getDescripcion(), ':Publico' => $Cuadernos->getPublico(), ':AnioINPC' => $Cuadernos->getAnioINPC()));
      $Cuadernos->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Cuadernos;
  }

  public function update(Cuadernos $Cuadernos){
    $sql="UPDATE Cuadernos SET Owner=:Owner, DateBorn=:DateBorn, Nombre=:Nombre, Descripcion=:Descripcion, Publico=:Publico, AnioINPC=:AnioINPC WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Cuadernos->getId(), ':Owner' => $Cuadernos->getOwner(), ':DateBorn' => $Cuadernos->getDateBorn(), ':Nombre' => $Cuadernos->getNombre(), ':Descripcion' => $Cuadernos->getDescripcion(), ':Publico' => $Cuadernos->getPublico(), ':AnioINPC' => $Cuadernos->getAnioINPC()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Cuadernos;
  }

  public function addOrUpdate(Cuadernos $Cuadernos){
    if($Cuadernos->getId()>0){
      $Cuadernos=$this->update($Cuadernos);
    }else{
      $Cuadernos=$this->add($Cuadernos);
    }
    return $Cuadernos;
  }

  public function delete($Id){
    $sql="DELETE FROM Cuadernos  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM Cuadernos WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Cuadernos=new Cuadernos();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Cuadernos=$this->createObject($result[0]);
    }
    return $Cuadernos;
  }

  public function showAll(){
    $sql="SELECT * FROM Cuadernos";
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
    $DaoUsuarios=new DaoUsuarios();
    $DaoCuadernoAnios=new DaoCuadernoAnios();
    $DaoRenglonCuaderno=new DaoRenglonCuaderno();
    
    $Cuadernos=new Cuadernos();
    $Cuadernos->setId($row['Id']);
    $Cuadernos->setOwner($row['Owner']);
    $Cuadernos->setDateBorn($row['DateBorn']);
    $Cuadernos->setNombre($row['Nombre']);
    $Cuadernos->setDescripcion($row['Descripcion']);
    $Cuadernos->setPublico($row['Publico']);
    $Cuadernos->setAnioINPC($row['AnioINPC']);
    if($row['Id']>0){
      $Cuadernos->setUsuarios($DaoUsuarios->advancedQuery("SELECT Usuarios.* FROM Usuarios JOIN UsuariosCuaderno ON UsuariosCuaderno.Usuario=Usuarios.Id WHERE Cuaderno=".$row['Id']));
      $Cuadernos->setAnios($DaoCuadernoAnios->getByCuaderno($row["Id"]));
      $Cuadernos->setRenglones($DaoRenglonCuaderno->getByCuaderno($row["Id"]));
    }
    
    return $Cuadernos;
  }
  
  public function getByUsuario($Usuario){
	  $sql="SELECT Cuadernos.* FROM Cuadernos WHERE Owner=$Usuario UNION SELECT Cuadernos.* FROM Cuadernos JOIN UsuariosCuaderno ON UsuariosCuaderno.Cuaderno=Cuadernos.Id WHERE Usuario=$Usuario";
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