<?php
class Estados {

  public $Id;
  public $Nombre;

  function __construct() {
    $this->Id = NULL;
    $this->Nombre = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getNombre(){
    return $this->Nombre;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }

}