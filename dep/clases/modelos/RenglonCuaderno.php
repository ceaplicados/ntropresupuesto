<?php
class RenglonCuaderno {

  public $Id;
  public $Cuaderno;
  public $Tipo;
  public $Estado;
  public $IdReferencia;
  public $TipoFiltro;
  public $IdFiltro;
  public $Graph;
  public $Mostrar;
  public $Data;
  public $Clave;
  public $Nombre;
  public $Referencia;
  public $Montos;

  function __construct() {
    $this->Id = NULL;
    $this->Cuaderno = NULL;
    $this->Tipo = NULL;
    $this->Estado = NULL;
    $this->IdReferencia = NULL;
    $this->TipoFiltro = NULL;
    $this->IdFiltro = NULL;
    $this->Graph = NULL;
    $this->Mostrar = NULL;
    $this->Data = array();
    $this->Clave= '';
    $this->Nombre= '';
    $this->Referencia= '';
    $this->Montos= array();
  }

  public function getId(){
    return $this->Id;
  }
  public function getCuaderno(){
    return $this->Cuaderno;
  }
  public function getTipo(){
    return $this->Tipo;
  }
  public function getEstado(){
    return $this->Estado;
  }
  public function getIdReferencia(){
    return $this->IdReferencia;
  }
  public function getTipoFiltro(){
    return $this->TipoFiltro;
  }
  public function getIdFiltro(){
    return $this->IdFiltro;
  }
  public function getGraph(){
    return $this->Graph;
  }
  public function getMostrar(){
    return $this->Mostrar;
  }
  public function getData(){
    return $this->Data;
  }
  public function getClave(){
    return $this->Clave;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getReferencia(){
    return $this->Referencia;
  }
  public function getMontos(){
    return $this->Montos;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setCuaderno($Cuaderno){
    return $this->Cuaderno=$Cuaderno;
  }
  public function setTipo($Tipo){
    return $this->Tipo=$Tipo;
  }
  public function setEstado($Estado){
    return $this->Estado=$Estado;
  }
  public function setIdReferencia($IdReferencia){
    return $this->IdReferencia=$IdReferencia;
  }
  public function setTipoFiltro($TipoFiltro){
    return $this->TipoFiltro=$TipoFiltro;
  }
  public function setIdFiltro($IdFiltro){
    return $this->IdFiltro=$IdFiltro;
  }
  public function setGraph($Graph){
    return $this->Graph=$Graph;
  }
  public function setMostrar($Mostrar){
    return $this->Mostrar=$Mostrar;
  }
  public function setData($Data){
    return $this->Data=$Data;
  }
  public function setClave($Clave){
    return $this->Clave=$Clave;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setReferencia($Referencia){
    return $this->Referencia=$Referencia;
  }
  public function setMontos($Montos){
    return $this->Montos=$Montos;
  }

}