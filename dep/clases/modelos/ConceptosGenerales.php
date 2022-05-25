<?php
class ConceptosGenerales {

  public $Id;
  public $Clave;
  public $Nombre;
  public $CapituloGasto;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Clave = NULL;
    $this->Nombre = NULL;
    $this->CapituloGasto = NULL;
    $this->Monto = 0;
  }

  public function getId(){
    return $this->Id;
  }
  public function getClave(){
    return $this->Clave;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getCapituloGasto(){
    return $this->CapituloGasto;
  }
  public function getMonto(){
    return $this->Monto;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setClave($Clave){
    return $this->Clave=$Clave;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setCapituloGasto($CapituloGasto){
    return $this->CapituloGasto=$CapituloGasto;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}