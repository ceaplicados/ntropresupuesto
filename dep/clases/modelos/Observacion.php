<?php
class Observacion {

  public $Id;
  public $Observable;
  public $Usuario;
  public $Nonce;
  public $DateBorn;

  function __construct() {
    $this->Id = NULL;
    $this->Observable = NULL;
    $this->Usuario = NULL;
    $this->Nonce = NULL;
    $this->DateBorn = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getObservable(){
    return $this->Observable;
  }
  public function getUsuario(){
    return $this->Usuario;
  }
  public function getNonce(){
    return $this->Nonce;
  }
  public function getDateBorn(){
    return $this->DateBorn;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setObservable($Observable){
    return $this->Observable=$Observable;
  }
  public function setUsuario($Usuario){
    return $this->Usuario=$Usuario;
  }
  public function setNonce($Nonce){
    return $this->Nonce=$Nonce;
  }
  public function setDateBorn($DateBorn){
    return $this->DateBorn=$DateBorn;
  }

}