<?php
class ObjetoDeGasto {

  public $Id;
  public $Clave;
  public $Nombre;
  public $PartidaGenerica;
  public $UnidadResponsable;
  public $VersionPresupuesto;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Clave = NULL;
    $this->Nombre = NULL;
    $this->PartidaGenerica = NULL;
    $this->UnidadResponsable = NULL;
    $this->VersionPresupuesto = NULL;
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
  public function getPartidaGenerica(){
    return $this->PartidaGenerica;
  }
  public function getUnidadResponsable(){
    return $this->UnidadResponsable;
  }
  public function getVersionPresupuesto(){
    return $this->VersionPresupuesto;
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
  public function setPartidaGenerica($PartidaGenerica){
    return $this->PartidaGenerica=$PartidaGenerica;
  }
  public function setUnidadResponsable($UnidadResponsable){
    return $this->UnidadResponsable=$UnidadResponsable;
  }
  public function setVersionPresupuesto($VersionPresupuesto){
    return $this->VersionPresupuesto=$VersionPresupuesto;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }
}