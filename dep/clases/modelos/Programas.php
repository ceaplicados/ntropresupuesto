<?php
class Programas {

  public $Id;
  public $Clave;
  public $Nombre;
  public $UnidadResponsable;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Clave = NULL;
    $this->Nombre = NULL;
    $this->UnidadResponsable = NULL;
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
  public function getUnidadResponsable(){
    return $this->UnidadResponsable;
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
  public function setUnidadResponsable($UnidadResponsable){
    return $this->UnidadResponsable=$UnidadResponsable;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}