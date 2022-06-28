<?php
class IndicadoresProgramas {

  public $Id;
  public $Programa;
  public $IdMIR;
  public $Nombre;
  public $Nivel;
  public $Resumen;
  public $Medios;
  public $Supuesto;
  public $Data;

  function __construct() {
    $this->Id = NULL;
    $this->Programa = NULL;
    $this->IdMIR = NULL;
    $this->Nombre = NULL;
    $this->Nivel = NULL;
    $this->Resumen = NULL;
    $this->Medios = NULL;
    $this->Supuesto = NULL;
    $this->Data = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getPrograma(){
    return $this->Programa;
  }
  public function getIdMIR(){
    return $this->IdMIR;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getNivel(){
    return $this->Nivel;
  }
  public function getResumen(){
    return $this->Resumen;
  }
  public function getMedios(){
    return $this->Medios;
  }
  public function getSupuesto(){
    return $this->Supuesto;
  }
  public function getData(){
    return $this->Data;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setPrograma($Programa){
    return $this->Programa=$Programa;
  }
  public function setIdMIR($IdMIR){
    return $this->IdMIR=$IdMIR;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setNivel($Nivel){
    return $this->Nivel=$Nivel;
  }
  public function setResumen($Resumen){
    return $this->Resumen=$Resumen;
  }
  public function setMedios($Medios){
    return $this->Medios=$Medios;
  }
  public function setSupuesto($Supuesto){
    return $this->Supuesto=$Supuesto;
  }
  public function setData($Data){
    return $this->Data=$Data;
  }

}