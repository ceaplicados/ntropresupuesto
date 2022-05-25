<?php
require_once 'modelos/np_base.php';
require_once 'modelos/INPC.php';

class DaoINPC extends np_base{

  public function add(INPC $INPC){
    $sql="INSERT INTO INPC (Valor) VALUES (:Valor);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Valor' => $INPC->getValor()));
      $INPC->setAnio($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $INPC;
  }

  public function update(INPC $INPC){
    $sql="UPDATE INPC SET Valor=:Valor WHERE  Anio=:Anio;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Anio' => $INPC->getAnio(), ':Valor' => $INPC->getValor()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $INPC;
  }

  public function addOrUpdate(INPC $INPC){
    if($INPC->getAnio()>0){
      $INPC=$this->update($INPC);
    }else{
      $INPC=$this->add($INPC);
    }
    return $INPC;
  }

  public function delete($Id){
    $sql="DELETE FROM INPC  WHERE  Anio=$Id;";
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
    $sql="SELECT * FROM INPC WHERE Anio=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $INPC=new INPC();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $INPC=$this->createObject($result[0]);
    }
    return $INPC;
  }

  public function showAll(){
    $sql="SELECT * FROM INPC ORDER BY Anio DESC";
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
    $INPC=new INPC();
    $INPC->setAnio($row['Anio']);
    $INPC->setValor($row['Valor']);
    return $INPC;
  }
  
  public function getLast(){
    $sql="SELECT * FROM INPC ORDER BY Anio DESC LIMIT 1";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $INPC=new INPC();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $INPC=$this->createObject($result[0]);
    }
    return $INPC;
  }

}