<?php
class UnidadPresupuestal {

  public $Id;
  public $Clave;
  public $Nombre;
  public $Estado;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Clave = NULL;
    $this->Nombre = NULL;
    $this->Estado = NULL;
    $this->Estado = 0;
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
  public function getEstado(){
    return $this->Estado;
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
  public function setEstado($Estado){
    return $this->Estado=$Estado;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}