<?php
class ProgramasMonto {

  public $Id;
  public $Programa;
  public $Version;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Programa = NULL;
    $this->Version = NULL;
    $this->Monto = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getPrograma(){
    return $this->Programa;
  }
  public function getVersion(){
    return $this->Version;
  }
  public function getMonto(){
    return $this->Monto;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setPrograma($Programa){
    return $this->Programa=$Programa;
  }
  public function setVersion($Version){
    return $this->Version=$Version;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}