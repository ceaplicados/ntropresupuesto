<?php
require_once 'modelos/np_base.php';
require_once 'modelos/ProgramasMonto.php';

class DaoProgramasMonto extends np_base{

  public function add(ProgramasMonto $ProgramasMonto){
    $sql="INSERT INTO ProgramasMonto (Programa,Version,Monto) VALUES (:Programa,:Version,:Monto);";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Programa' => $ProgramasMonto->getPrograma(), ':Version' => $ProgramasMonto->getVersion(), ':Monto' => $ProgramasMonto->getMonto()));
      $ProgramasMonto->setId($this->_dbh->lastInsertId());
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ProgramasMonto;
  }

  public function update(ProgramasMonto $ProgramasMonto){
    $sql="UPDATE ProgramasMonto SET Programa=:Programa, Version=:Version, Monto=:Monto WHERE  Id=:Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute(array(':Id' => $ProgramasMonto->getId(), ':Programa' => $ProgramasMonto->getPrograma(), ':Version' => $ProgramasMonto->getVersion(), ':Monto' => $ProgramasMonto->getMonto()));
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    return $ProgramasMonto;
  }

  public function addOrUpdate(ProgramasMonto $ProgramasMonto){
    if($ProgramasMonto->getId()>0){
      $ProgramasMonto=$this->update($ProgramasMonto);
    }else{
      $ProgramasMonto=$this->add($ProgramasMonto);
    }
    return $ProgramasMonto;
  }

  public function delete($Id){
    $sql="DELETE FROM ProgramasMonto  WHERE  Id=$Id;";
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
    $sql="SELECT * FROM ProgramasMonto WHERE Id=$Id;";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $ProgramasMonto=new ProgramasMonto();
    $result=$sth->fetchAll();
    if(count($result)>0){
      $ProgramasMonto=$this->createObject($result[0]);
    }
    return $ProgramasMonto;
  }

  public function showAll(){
    $sql="SELECT * FROM ProgramasMonto";
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
    $ProgramasMonto=new ProgramasMonto();
    $ProgramasMonto->setId($row['Id']);
    $ProgramasMonto->setPrograma($row['Programa']);
    $ProgramasMonto->setVersion($row['Version']);
    $ProgramasMonto->setMonto($row['Monto']);
    return $ProgramasMonto;
  }


}