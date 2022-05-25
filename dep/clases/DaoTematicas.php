<?php
require_once 'modelos/np_base.php';
require_once 'modelos/Tematicas.php';

class DaoTematicas extends np_base{

  public function add(Tematicas $Tematicas){
    $sql="INSERT INTO Tematicas (Tema) VALUES (:Tema);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Tema' => $Tematicas->getTema()));
      $Tematicas->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Tematicas;
  }

  public function update(Tematicas $Tematicas){
    $sql="UPDATE Tematicas SET Tema=:Tema WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $Tematicas->getId(), ':Tema' => $Tematicas->getTema()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $Tematicas;
  }

  public function addOrUpdate(Tematicas $Tematicas){
    if($Tematicas->getId()>0){
      $Tematicas=$this->update($Tematicas);
    }else{
      $Tematicas=$this->add($Tematicas);
    }
    return $Tematicas;
  }

  public function delete($Id){
    $sql="DELETE FROM Tematicas  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM Tematicas WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $Tematicas=new Tematicas();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $Tematicas=$this->createObject($result[0]);
    }
    return $Tematicas;
  }

  public function showAll(){
    $sql="SELECT * FROM Tematicas";
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
    $Tematicas=new Tematicas();
    $Tematicas->setId($row['Id']);
    $Tematicas->setTema($row['Tema']);
    return $Tematicas;
  }


}