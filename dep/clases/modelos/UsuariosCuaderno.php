<?php
class UsuariosCuaderno {

  public $Id;
  public $Usuario;
  public $Cuaderno;

  function __construct() {
    $this->Id = NULL;
    $this->Usuario = NULL;
    $this->Cuaderno = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getUsuario(){
    return $this->Usuario;
  }
  public function getCuaderno(){
    return $this->Cuaderno;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setUsuario($Usuario){
    return $this->Usuario=$Usuario;
  }
  public function setCuaderno($Cuaderno){
    return $this->Cuaderno=$Cuaderno;
  }

}