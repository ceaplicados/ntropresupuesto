<?php
class PropuestaProgramaODS {

  public $Id;
  public $Programa;
  public $Usuario;
  public $DatePropuesta;
  public $TipoPropuesta; // Inicial,Ratifica,Final
  public $ODS;
  public $Argumentacion;
  public $Metas;

  function __construct() {
    $this->Id = NULL;
    $this->Programa = NULL;
    $this->Usuario = NULL;
    $this->DatePropuesta = NULL;
    $this->TipoPropuesta = NULL;
    $this->Argumentacion = NULL;
    $this->ODS = array();
    $this->Metas = array();
  }

  public function getId(){
    return $this->Id;
  }
  public function getPrograma(){
    return $this->Programa;
  }
  public function getUsuario(){
    return $this->Usuario;
  }
  public function getDatePropuesta(){
    return $this->DatePropuesta;
  }
  public function getTipoPropuesta(){
    return $this->TipoPropuesta;
  }
  public function getArgumentacion(){
    return $this->Argumentacion;
  }
  public function getODS(){
    return $this->ODS;
  }
  public function getMetas(){
    return $this->Metas;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setPrograma($Programa){
    return $this->Programa=$Programa;
  }
  public function setUsuario($Usuario){
    return $this->Usuario=$Usuario;
  }
  public function setDatePropuesta($DatePropuesta){
    return $this->DatePropuesta=$DatePropuesta;
  }
  public function setTipoPropuesta($TipoPropuesta){
    return $this->TipoPropuesta=$TipoPropuesta;
  }
  public function setArgumentacion($Argumentacion){
    return $this->Argumentacion=$Argumentacion;
  }
  public function setODS($ODS){
    return $this->ODS=$ODS;
  }
  public function setMetas($Metas){
    return $this->Metas=$Metas;
  }

}