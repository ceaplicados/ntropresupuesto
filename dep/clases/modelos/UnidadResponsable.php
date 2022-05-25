<?php
class UnidadResponsable {

  public $Id;
  public $Clave;
  public $Nombre;
  public $UnidadPresupuestal;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Clave = NULL;
    $this->Nombre = NULL;
    $this->UnidadPresupuestal = NULL;
    $this->Monto = NULL;
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
  public function getUnidadPresupuestal(){
    return $this->UnidadPresupuestal;
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
  public function setUnidadPresupuestal($UnidadPresupuestal){
    return $this->UnidadPresupuestal=$UnidadPresupuestal;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}