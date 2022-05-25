<?php
class Cuadernos {

  public $Id;
  public $Owner;
  public $DateBorn;
  public $Nombre;
  public $Descripcion;
  public $Publico;
  public $AnioINPC;
  public $Usuarios;
  public $ODSs;
  public $Anios;
  public $Renglones;

  function __construct() {
    $this->Id = NULL;
    $this->Owner = NULL;
    $this->DateBorn = NULL;
    $this->Nombre = NULL;
    $this->Descripcion = NULL;
    $this->Publico = NULL;
    $this->AnioINPC = NULL;
	  $this->Usuarios = array();
	  $this->ODSs = array();
    $this->Anios = array();
    $this->Renglones = array();
  }

  public function getId(){
    return $this->Id;
  }
  public function getOwner(){
    return $this->Owner;
  }
  public function getDateBorn(){
    return $this->DateBorn;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getDescripcion(){
    return $this->Descripcion;
  }
  public function getPublico(){
    return $this->Publico;
  }
  public function getAnioINPC(){
      return $this->AnioINPC;
    }
	public function getUsuarios(){
		return $this->Usuarios;
	}
	public function getODSs(){
		return $this->ODSs;
	}
  public function getAnios(){
    return $this->Anios;
  }
  public function getRenglones(){
      return $this->Renglones;
    }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setOwner($Owner){
    return $this->Owner=$Owner;
  }
  public function setDateBorn($DateBorn){
    return $this->DateBorn=$DateBorn;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setDescripcion($Descripcion){
    return $this->Descripcion=$Descripcion;
  }
  public function setPublico($Publico){
    return $this->Publico=$Publico;
  }
  public function setAnioINPC($AnioINPC){
    return $this->AnioINPC=$AnioINPC;
  }
	public function setUsuarios($Usuarios){
		return $this->Usuarios=$Usuarios;
	}
	public function setODSs($ODSs){
		return $this->ODSs=$ODSs;
	}
  public function setAnios($Anios){
    return $this->Anios=$Anios;
  }
  public function setRenglones($Renglones){
    return $this->Renglones=$Renglones;
  }

}