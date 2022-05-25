<?php
class VersionesPresupuesto {

  public $Id;
  public $Estado;
  public $Anio;
  public $Nombre;
  public $Descripcion;
  public $Fecha;
  public $Actual;
  public $ObjetoGasto;
  public $ProgramaPresupuestal;
  public $Monto;

  function __construct() {
    $this->Id = NULL;
    $this->Estado = NULL;
    $this->Anio = NULL;
    $this->Nombre = NULL;
    $this->Descripcion = NULL;
    $this->Fecha = NULL;
    $this->Actual = NULL;
    $this->ObjetoGasto = NULL;
    $this->ProgramaPresupuestal = NULL;
    $this->Monto = NULL;
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
  public function getNombre(){
    return $this->Nombre;
  }
  public function getDescripcion(){
    return $this->Descripcion;
  }
  public function getFecha(){
    return $this->Fecha;
  }
  public function getActual(){
    return $this->Actual;
  }
  public function getObjetoGasto(){
    return $this->ObjetoGasto;
  }
  public function getProgramaPresupuestal(){
    return $this->ProgramaPresupuestal;
  }
  public function getMonto(){
    return $this->Monto;
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
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setDescripcion($Descripcion){
    return $this->Descripcion=$Descripcion;
  }
  public function setFecha($Fecha){
    return $this->Fecha=$Fecha;
  }
  public function setActual($Actual){
    return $this->Actual=$Actual;
  }
  public function setObjetoGasto($ObjetoGasto){
    return $this->ObjetoGasto=$ObjetoGasto;
  }
  public function setProgramaPresupuestal($ProgramaPresupuestal){
    return $this->ProgramaPresupuestal=$ProgramaPresupuestal;
  }
  public function setMonto($Monto){
    return $this->Monto=$Monto;
  }

}