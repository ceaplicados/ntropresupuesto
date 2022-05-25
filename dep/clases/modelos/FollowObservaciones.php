<?php
class FollowObservaciones {

  public $Usuario;
  public $Observable;

  function __construct() {
    $this->Usuario = NULL;
    $this->Observable = NULL;
  }

  public function getUsuario(){
    return $this->Usuario;
  }
  public function getObservable(){
    return $this->Observable;
  }

  public function setUsuario($Usuario){
    return $this->Usuario=$Usuario;
  }
  public function setObservable($Observable){
    return $this->Observable=$Observable;
  }

}