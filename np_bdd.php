<?php
class np_bdd {
  private $_dsn='mysql:host=localhost;dbname=nuestropresupuesto';
  private $_username='root';
  private $_password='######';
  private $_options=array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
  protected $_dbh;

  public function __construct(){
    try{
      $this->_dbh = new PDO($this->_dsn, $this->_username, $this->_password, $this->_options);
    }catch (Exception $e){
      error_log(json_encode($e));
    };
    date_default_timezone_set('America/Mexico_City');
  }
}