<?php
class Tematicas {

  public $Id;
  public $Tema;

  function __construct() {
    $this->Id = NULL;
    $this->Tema = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getTema(){
    return $this->Tema;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setTema($Tema){
    return $this->Tema=$Tema;
  }

}