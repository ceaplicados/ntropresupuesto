<?php
class CuadernosODSs {

  public $Id;
  public $Cuaderno;
  public $ODS;
  public $MetaODS;

  function __construct() {
    $this->Id = NULL;
    $this->Cuaderno = NULL;
    $this->ODS = NULL;
    $this->MetaODS = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getCuaderno(){
    return $this->Cuaderno;
  }
  public function getODS(){
    return $this->ODS;
  }
  public function getMetaODS(){
    return $this->MetaODS;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setCuaderno($Cuaderno){
    return $this->Cuaderno=$Cuaderno;
  }
  public function setODS($ODS){
    return $this->ODS=$ODS;
  }
  public function setMetaODS($MetaODS){
    return $this->MetaODS=$MetaODS;
  }

}