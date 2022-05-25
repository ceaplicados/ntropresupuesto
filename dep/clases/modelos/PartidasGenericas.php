<?php
class PartidasGenericas {

  public $Id;
  public $Clave;
  public $Nombre;
  public $ConceptoGeneral;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Clave = NULL;
    $this->Nombre = NULL;
    $this->ConceptoGeneral = NULL;
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
  public function getConceptoGeneral(){
    return $this->ConceptoGeneral;
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
  public function setConceptoGeneral($ConceptoGeneral){
    return $this->ConceptoGeneral=$ConceptoGeneral;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}