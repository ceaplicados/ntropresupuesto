<?php
class MetasODS {

  public $Id;
  public $ODS;
  public $Clave;
  public $Nombre;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->ODS = NULL;
    $this->Clave = NULL;
    $this->Nombre = NULL;
    $this->Monto = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getODS(){
    return $this->ODS;
  }
  public function getClave(){
    return $this->Clave;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getMonto(){
    return $this->Monto;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setODS($ODS){
    return $this->ODS=$ODS;
  }
  public function setClave($Clave){
    return $this->Clave=$Clave;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}