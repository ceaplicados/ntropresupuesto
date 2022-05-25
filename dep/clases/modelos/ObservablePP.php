<?php
class ObservablePP {

  public $Observable;
  public $Programa;
  public $BornDate;
  public $BornBy;

  function __construct() {
    $this->Observable = NULL;
    $this->Programa = NULL;
    $this->BornDate = NULL;
    $this->BornBy = NULL;
  }

  public function getObservable(){
    return $this->Observable;
  }
  public function getPrograma(){
    return $this->Programa;
  }
  public function getBornDate(){
    return $this->BornDate;
  }
  public function getBornBy(){
    return $this->BornBy;
  }

  public function setObservable($Observable){
    return $this->Observable=$Observable;
  }
  public function setPrograma($Programa){
    return $this->Programa=$Programa;
  }
  public function setBornDate($BornDate){
    return $this->BornDate=$BornDate;
  }
  public function setBornBy($BornBy){
    return $this->BornBy=$BornBy;
  }

}