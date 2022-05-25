<?php
class CuadernoAnios {

  public $Id;
  public $Cuaderno;
  public $Anio;
  public $Data;

  function __construct() {
    $this->Id = NULL;
    $this->Cuaderno = NULL;
    $this->Anio = NULL;
    $this->Data = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getCuaderno(){
    return $this->Cuaderno;
  }
  public function getAnio(){
    return $this->Anio;
  }
  public function getData(){
    return $this->Data;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setCuaderno($Cuaderno){
    return $this->Cuaderno=$Cuaderno;
  }
  public function setAnio($Anio){
    return $this->Anio=$Anio;
  }
  public function setData($Data){
    return $this->Data=$Data;
  }

}