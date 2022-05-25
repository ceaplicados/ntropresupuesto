<?php
class Sessions {

  public $Id;
  public $UID;
  public $Usuario;
  public $Cliente;
  public $DateBorn;
  public $DateDeath;
  public $IP;
  public $Agent;

  function __construct() {
    $this->Id = NULL;
    $this->UID = NULL;
    $this->Usuario = NULL;
    $this->Cliente = NULL;
    $this->DateBorn = NULL;
    $this->DateDeath = NULL;
    $this->IP = NULL;
    $this->Agent = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getUID(){
    return $this->UID;
  }
  public function getUsuario(){
    return $this->Usuario;
  }
  public function getCliente(){
    return $this->Cliente;
  }
  public function getDateBorn(){
    return $this->DateBorn;
  }
  public function getDateDeath(){
    return $this->DateDeath;
  }
  public function getIP(){
    return $this->IP;
  }
  public function getAgent(){
    return $this->Agent;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setUID($UID){
    return $this->UID=$UID;
  }
  public function setUsuario($Usuario){
    return $this->Usuario=$Usuario;
  }
  public function setCliente($Cliente){
    return $this->Cliente=$Cliente;
  }
  public function setDateBorn($DateBorn){
    return $this->DateBorn=$DateBorn;
  }
  public function setDateDeath($DateDeath){
    return $this->DateDeath=$DateDeath;
  }
  public function setIP($IP){
    return $this->IP=$IP;
  }
  public function setAgent($Agent){
    return $this->Agent=$Agent;
  }

}