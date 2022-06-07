<?php
class Estados {

  public $Id;
  public $Nombre;
  public $Codigo;

  function __construct() {
    $this->Id = NULL;
    $this->Nombre = NULL;
    $this->Codigo = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getCodigo(){
    return $this->Codigo;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setCodigo($Codigo){
    return $this->Codigo=$Codigo;
  }

}