<?php
class PropuestaODSs {

  public $Id;
  public $Propuesta;
  public $ODS;
  public $Principal;

  function __construct() {
    $this->Id = NULL;
    $this->Propuesta = NULL;
    $this->ODS = NULL;
    $this->Principal = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getPropuesta(){
    return $this->Propuesta;
  }
  public function getODS(){
    return $this->ODS;
  }
  public function getPrincipal(){
    return $this->Principal;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setPropuesta($Propuesta){
    return $this->Propuesta=$Propuesta;
  }
  public function setODS($ODS){
    return $this->ODS=$ODS;
  }
  public function setPrincipal($Principal){
    return $this->Principal=$Principal;
  }

}