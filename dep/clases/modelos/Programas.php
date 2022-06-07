<?php
class Programas {

  public $Id;
  public $Clave;
  public $Nombre;
  public $UnidadResponsable;
  public $ODS;
  public $MetaODS;
  public $Data;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Clave = NULL;
    $this->Nombre = NULL;
    $this->UnidadResponsable = NULL;
    $this->ODS = NULL;
    $this->MetaODS = NULL;
    $this->Data = NULL;
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
  public function getODS(){
    return $this->ODS;
  }
  public function getMetaODS(){
    return $this->MetaODS;
  }
  public function getData(){
    return $this->Data;
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
  public function setODS($ODS){
    return $this->ODS=$ODS;
  }
  public function setMetaODS($MetaODS){
    return $this->MetaODS=$MetaODS;
  }
  public function setData($Data){
    return $this->Data=$Data;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}