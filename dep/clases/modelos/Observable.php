<?php
class Observable {

  public $Id;
  public $Estado;
  public $Anio;
  public $Tematica;
  public $Nombre;
  public $Nonce;
  public $URs;
  public $PPs;

  function __construct() {
    $this->Id = NULL;
    $this->Estado = NULL;
    $this->Anio = NULL;
    $this->Tematica = NULL;
    $this->Nombre = NULL;
    $this->Nonce = NULL;
    $this->URs = array();
    $this->PPs = array();
  }

  public function getId(){
    return $this->Id;
  }
  public function getEstado(){
    return $this->Estado;
  }
  public function getAnio(){
    return $this->Anio;
  }
  public function getTematica(){
    return $this->Tematica;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getNonce(){
    return $this->Nonce;
  }
  public function getURs(){
    return $this->URs;
  }
  public function getPPs(){
    return $this->PPs;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setEstado($Estado){
    return $this->Estado=$Estado;
  }
  public function setAnio($Anio){
    return $this->Anio=$Anio;
  }
  public function setTematica($Tematica){
    return $this->Tematica=$Tematica;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setNonce($Nonce){
    return $this->Nonce=$Nonce;
  }
  public function setURs($URs){
    return $this->URs=$URs;
  }
  public function setPPs($PPs){
    return $this->PPs=$PPs;
  }

}