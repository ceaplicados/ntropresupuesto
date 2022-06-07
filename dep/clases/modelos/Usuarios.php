<?php
class Usuarios {

  public $Id;
  public $Sobrenombre;
  public $Nombre;
  public $Telefono;
  public $Celular;
  public $Email;
  public $Estado;
  public $Password;
  public $DateBorn;
  public $Activo;
  public $ResetKey;
  public $Image;
  public $UUID;
  public $Admin;

  function __construct() {
    $this->Id = NULL;
    $this->Sobrenombre = NULL;
    $this->Nombre = NULL;
    $this->Telefono = NULL;
    $this->Celular = NULL;
    $this->Email = NULL;
    $this->Estado = NULL;
    $this->Password = NULL;
    $this->DateBorn = NULL;
    $this->Activo = NULL;
    $this->ResetKey = NULL;
    $this->Image = NULL;
    $this->UUID = NULL;
    $this->Admin = 0;
  }

  public function getId(){
    return $this->Id;
  }
  public function getSobrenombre(){
    return $this->Sobrenombre;
  }
  public function getNombre(){
    return $this->Nombre;
  }
  public function getTelefono(){
    return $this->Telefono;
  }
  public function getCelular(){
    return $this->Celular;
  }
  public function getEmail(){
    return $this->Email;
  }
  public function getEstado(){
    return $this->Estado;
  }
  public function getPassword(){
    return $this->Password;
  }
  public function getDateBorn(){
    return $this->DateBorn;
  }
  public function getActivo(){
    return $this->Activo;
  }
  public function getResetKey(){
    return $this->ResetKey;
  }
  public function getImage(){
    return $this->Image;
  }
  public function getUUID(){
    return $this->UUID;
  }
  public function getAdmin(){
    return $this->Admin;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setSobrenombre($Sobrenombre){
    return $this->Sobrenombre=$Sobrenombre;
  }
  public function setNombre($Nombre){
    return $this->Nombre=$Nombre;
  }
  public function setTelefono($Telefono){
    return $this->Telefono=$Telefono;
  }
  public function setCelular($Celular){
    return $this->Celular=$Celular;
  }
  public function setEmail($Email){
    return $this->Email=$Email;
  }
  public function setEstado($Estado){
    return $this->Estado=$Estado;
  }
  public function setPassword($Password){
    return $this->Password=$Password;
  }
  public function setDateBorn($DateBorn){
    return $this->DateBorn=$DateBorn;
  }
  public function setActivo($Activo){
    return $this->Activo=$Activo;
  }
  public function setResetKey($ResetKey){
    return $this->ResetKey=$ResetKey;
  }
  public function setImage($Image){
    return $this->Image=$Image;
  }
  public function setUUID($UUID){
    return $this->UUID=$UUID;
  }
  public function setAdmin($Admin){
    return $this->Admin=$Admin;
  }

}