<?php
class Comentarios {

  public $Id;
  public $Usuario;
  public $Dateborn;
  public $Observacion;
  public $EnRespuesta;
  public $Comentario;

  function __construct() {
    $this->Id = NULL;
    $this->Usuario = NULL;
    $this->Dateborn = NULL;
    $this->Observacion = NULL;
    $this->EnRespuesta = NULL;
    $this->Comentario = NULL;
  }

  public function getId(){
    return $this->Id;
  }
  public function getUsuario(){
    return $this->Usuario;
  }
  public function getDateborn(){
    return $this->Dateborn;
  }
  public function getObservacion(){
    return $this->Observacion;
  }
  public function getEnRespuesta(){
    return $this->EnRespuesta;
  }
  public function getComentario(){
    return $this->Comentario;
  }

  public function setId($Id){
    return $this->Id=$Id;
  }
  public function setUsuario($Usuario){
    return $this->Usuario=$Usuario;
  }
  public function setDateborn($Dateborn){
    return $this->Dateborn=$Dateborn;
  }
  public function setObservacion($Observacion){
    return $this->Observacion=$Observacion;
  }
  public function setEnRespuesta($EnRespuesta){
    return $this->EnRespuesta=$EnRespuesta;
  }
  public function setComentario($Comentario){
    return $this->Comentario=$Comentario;
  }

}