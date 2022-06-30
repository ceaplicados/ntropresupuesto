<?php
class PropuestaMetas {

  public $Id;
  public $Propuesta;
  public $Meta;

  function __construct() {
    $this->Id = NULL;
    $this->Propuesta = NULL;
    $this->Meta = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getPropuesta(){
    return $this->Propuesta;
  }
  public function getMeta(){
    return $this->Meta;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setPropuesta($Propuesta){
    return $this->Propuesta=$Propuesta;
  }
  public function setMeta($Meta){
    return $this->Meta=$Meta;
  }

}