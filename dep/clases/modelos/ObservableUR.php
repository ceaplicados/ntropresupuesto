<?php
class ObservableUR {

  public $Observable;
  public $UnidadResponsable;
  public $BornDate;
  public $BornBy;

  function __construct() {
    $this->Observable = NULL;
    $this->UnidadResponsable = NULL;
    $this->BornDate = NULL;
    $this->BornBy = NULL;
  }

  public function getObservable(){
    return $this->Observable;
  }
  public function getUnidadResponsable(){
    return $this->UnidadResponsable;
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
  public function setUnidadResponsable($UnidadResponsable){
    return $this->UnidadResponsable=$UnidadResponsable;
  }
  public function setBornDate($BornDate){
    return $this->BornDate=$BornDate;
  }
  public function setBornBy($BornBy){
    return $this->BornBy=$BornBy;
  }

}