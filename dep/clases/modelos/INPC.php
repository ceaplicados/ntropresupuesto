<?php
class INPC {

  public $Anio;
  public $Valor;

  function __construct() {
    $this->Anio = NULL;
    $this->Valor = NULL;
  }

  public function getAnio(){
    return $this->Anio;
  }
  public function getValor(){
    return $this->Valor;
  }

  public function setAnio($Anio){
    return $this->Anio=$Anio;
  }
  public function setValor($Valor){
    return $this->Valor=$Valor;
  }

}