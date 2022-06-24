<?php
class ODS {

  public $Id;
  public $Nombre;
  public $Descripcion;
  public $Color;

  function __construct() {
    $this->Id = NULL;
    $this->Nombre = NULL;
    $this->Descripcion = NULL;
    $this->Color = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getDescripcion(){
    return $this->Descripcion;
  }
  public function getColor(){
    return $this->Color;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setDescripcion($Descripcion){
    return $this->Descripcion=$Descripcion;
  }
  public function setColor($Color){
    return $this->Color=$Color;
  }

}