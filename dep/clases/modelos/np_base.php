<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../.config/np_bdd.php';

class np_base extends np_bdd{
  function __construct(){
    np_bdd::__construct();
  }

  public function hashPassword($password,$hash_pss_string=null,$rotate_hex_string=null){
    $salt = 'qsfvufdj7ajxmbxrpubkda5ro';
    if($hash_pss_string!=null){
      $salt=$hash_pss_string;
    }
    $len = strlen($password);
    $password = md5($password);
    $password = $this->rotateHEX($password,$len,$rotate_hex_string);
    return md5($salt.$password);
  }

  public function rotateHEX($string, $n,$rotate_hex_string=null){
    $chars = 'a2d57wz6bz3zod46dod76mc7t';
    if($rotate_hex_string!=null){
      $chars=$rotate_hex_string;
    }
    $str='';
    for ($i=0;$i<strlen($string);$i++){
      $pos = strpos($chars,$string[$i]);
      $pos += $n;
      if ($pos>=strlen($chars))
        $pos = $pos % strlen($chars);
      $str.=$chars[$pos];
    }
    return $str;
  }

  public function generarKey($largo=13){
    $var_count=0;
    $chars = 'abcdefghijkmnopqrstuvwxyz023456789';
    srand((double)microtime()*1000000);
    while($var_count<$largo){
      $num = rand() % 33;
      $str.=substr($chars, $num, 1);
      $var_count=$var_count+1;
    }
    return($str);
  }

  public function _query($sql){
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
      return $sth->fetchAll();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
  }

  public function mesLetra($mesActual){
    if($mesActual==1){
      return('Ene');
    }
    if($mesActual==2){
      return('Feb');
    }
    if($mesActual==3){
      return('Mar');
    }
    if($mesActual==4){
      return('Abr');
    }
    if($mesActual==5){
      return('May');
    }
    if($mesActual==6){
      return('Jun');
    }
    if($mesActual==7){
      return('Jul');
    }
    if($mesActual==8){
      return('Ago');
    }
    if($mesActual==9){
      return('Sep');
    }
    if($mesActual==10){
      return('Oct');
    }
    if($mesActual==11){
      return('Nov');
    }
    if($mesActual==12){
      return('Dic');
    }
  }
  
  public function mesLetraLargo($mesActual){
    if($mesActual==1){
      return('Enero');
    }
    if($mesActual==2){
      return('Febrero');
    }
    if($mesActual==3){
      return('Marzo');
    }
    if($mesActual==4){
      return('Abril');
    }
    if($mesActual==5){
      return('Mayo');
    }
    if($mesActual==6){
      return('Junio');
    }
    if($mesActual==7){
      return('Julio');
    }
    if($mesActual==8){
      return('Agosto');
    }
    if($mesActual==9){
      return('Septiembre');
    }
    if($mesActual==10){
      return('Octubre');
    }
    if($mesActual==11){
      return('Noviembre');
    }
    if($mesActual==12){
      return('Diciembre');
    }
  }
  
  public function formatFecha($fechaSQL,$corta=true){
    $anioSQL=substr($fechaSQL, 0, 4);
    $mesSQL=substr($fechaSQL, 5, 2);
    $mesSQL=$mesSQL*1;
    if($corta){
      $mesSQL=$this->mesLetra($mesSQL);
    }else{
      $mesSQL=$this->mesLetraLargo($mesSQL);
    }
    $diaSQL=substr($fechaSQL, 8, 2);
    $diaSQL=$diaSQL*1;
    if(!$corta==0){
      return ($diaSQL.' de '.$mesSQL.', '.$anioSQL);
    }else{
      return ($diaSQL.' '.$mesSQL.', '.substr($anioSQL,2,2));
    }
  }

  public function gweb_curl($method, $addData=array(), $url, $data=false, $show_headers=false){
    $headers=array();
    if(is_array($addData)){
      foreach ($addData as $value) {
        array_push($headers, $value);
      }
    }else{
      array_push($headers, 'Connection: Keep-Alive');
      array_push($headers, 'Content-type: application/x-www-form-urlencoded;charset=UTF-8');
    }
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($process, CURLOPT_HEADER, $show_headers);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    if($method=='POST'){
      curl_setopt($process, CURLOPT_POST, 1);
    }elseif($method!=='GET'){
      curl_setopt($process, CURLOPT_CUSTOMREQUEST, $method);
    }
    if(is_array($data)){
      curl_setopt($process, CURLOPT_POSTFIELDS, implode('&', $data));
    }elseif($data){
      curl_setopt($process, CURLOPT_POSTFIELDS, $data);
    }
    $return = curl_exec($process);
    curl_close($process);
    return $return;
  }

  public function nonce($IdStr='',$largo=13){
    if($IdStr!=='' && $largo==13){
      $largo=6;
    }
    $var_count=0;
    $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ0123456789';
    srand((double)microtime()*1000000);
    $string='';
    while($var_count<$largo){
      $num = rand() % 33;
      $string.=substr($chars, $num, 1);
      $var_count=$var_count+1;  
    }
    $string=$IdStr.$string.time();
    $chars = 'd3w50x6zyoq3bKJSLKJ230KDJjs4aqck24z8xrAE3KJV3209C';
    $str='';
    $n=strlen($string);
    for ($i=0;$i<strlen($string);$i++){
      $pos = strpos($chars,$string[$i]);
      $pos += $n;
      if ($pos>=strlen($chars))
        $pos = $pos % strlen($chars);
      $str.=$chars[$pos];
    }
    return $str;
  }

  public function cantidad_letra($cant){
    $cant_letra='';
    $centavos=$cant-floor($cant);
    $centavos=$centavos*100;
    $centavos=round($centavos,0);
    if(strlen($centavos)<2){
      $centavos='0'.$centavos;
    }
    $cant=floor($cant);  
    if(floor($cant/1000000)-floor($cant/1000000000)*1000000>0){
      if($this->numeros_base(floor($cant/1000000))=='uno'){
        $cant_letra.='Un millón ';
      }else{
        $cant_letra.=$this->numeros_base(floor($cant/1000000)).' millones ';
      }
    }
    if(floor($cant/1000)-floor($cant/1000000)*1000>0){
      if($this->numeros_base(floor($cant/1000))=='uno'){
        $cant_letra.='Un mil ';
      }else{
        $cant_letra.=$this->numeros_base(floor($cant/1000)).' mil ';
      }
    }
    $cant_letra.=$this->numeros_base($cant);
    return ucfirst($cant_letra).' pesos '.$centavos.'/100';
  }

  public function numeros_base($num){
    $num_letra='';
    $num_comp=floor($num/100)-floor($num/1000)*10;
    switch($num_comp){
      case 1:
      if($num-floor($num/1000)==100){
        $num_letra.= 'cien';
        break;
      }else{
        $num_letra.= 'ciento ';
        break;
      }
      case 2:
      $num_letra.= 'doscientos ';
      break;
      case 3:
      $num_letra.= 'trescientos ';
      break;
      case 4:
      $num_letra.= 'cuatrocientos ';
      break;
      case 5:
      $num_letra.= 'quinientos ';
      break;
      case 6:
      $num_letra.= 'seiscientos ';
      break;
      case 7:
      $num_letra.= 'setecientos ';
      break;
      case 8:
      $num_letra.= 'ochocientos ';
      break;
      case 9:
      $num_letra.= 'novecientos ';
      break;
    }
    $num_comp=floor($num/10)-floor($num/100)*10;
    if($num_comp<>1){
      if($num-floor($num/10)*10==0){
        switch($num_comp){
          case 1:
          $num_letra.= 'diez';
          break;
          case 2:
          $num_letra.= 'veinte';
          break;
          case 3:
          $num_letra.= 'treinta';
          break;
          case 4:
          $num_letra.= 'cuarenta';
          break;
          case 5:
          $num_letra.= 'cincuenta';
          break;
          case 6:
          $num_letra.= 'sesenta';
          break;
          case 7:
          $num_letra.= 'setenta';
          break;
          case 8:
          $num_letra.= 'ochenta';
          break;
          case 9:
          $num_letra.= 'noventa';
          break;
        }
      }else{
        switch($num_comp){
          case 2:
          $num_letra.= 'venti';
          break;
          case 3:
          $num_letra.= 'treinta y ';
          break;
          case 4:
          $num_letra.= 'cuarenta y ';
          break;
          case 5:
          $num_letra.= 'cincuenta y ';
          break;
          case 6:
          $num_letra.= 'sesenta y ';
          break;
          case 7:
          $num_letra.= 'setenta y ';
          break;
          case 8:
          $num_letra.= 'ochenta y ';
          break;
          case 9:
          $num_letra.= 'noventa y ';
          break;
        }
        $num_comp=$num-floor($num/10)*10;
        switch($num_comp){
          case 1:
          $num_letra.= 'uno';
          break;
          case 2:
          $num_letra.= 'dos';
          break;
          case 3:
          $num_letra.= 'tres';
          break;
          case 4:
          $num_letra.= 'cuatro';
          break;
          case 5:
          $num_letra.= 'cinco';
          break;
          case 6:
          $num_letra.= 'seis';
          break;
          case 7:
          $num_letra.= 'siete';
          break;
          case 8:
          $num_letra.= 'ocho';
          break;
          case 9:
          $num_letra.= 'nueve';
          break;
          case 0:
          $num_letra.= 'diez';
          break;
        }
      }
    }else{
      $num_comp=$num-floor($num/100)*100;
      switch($num_comp){
        case 10:
        $num_letra.= 'diez';
        break;
        case 11:
        $num_letra.= 'once';
        break;
        case 12:
        $num_letra.= 'doce';
        break;
        case 13:
        $num_letra.= 'trece';
        break;
        case 14:
        $num_letra.= 'catorce';
        break;
        case 15:
        $num_letra.= 'quince';
        break;
        case 16:
        $num_letra.= 'dieciseis';
        break;
        case 17:
        $num_letra.= 'diecisiete';
        break;
        case 18:
        $num_letra.= 'dieciocho';
        break;
        case 19:
        $num_letra.= 'deicinueve';
        break;
      }
    }
    return $num_letra;
  }

  public function fillStr($Str,$len,$filler=0){
    while(strlen($Str)<$len){
      $Str=$filler.$Str;
    }
    return $Str;
  }

  public function fillStrRight($Str,$len,$filler=' '){
    if(strlen($Str)>$len){
      $Str=substr($Str, 0,$len);
    }
    while(strlen($Str)<$len){
      $Str=$Str.$filler;
    }
    return $Str;
  }

  public function fillDecimales($valor,$len){
    $valor=strval($valor);
    if(strpos($valor, '.')===false){
      $valor=$valor.'.';
    }
    while(strlen($valor)-strpos($valor, '.')<=$len){
      $valor=$valor.'0';
    }
    return $valor;
  }
  
  public function getParam($param){
    $sql="SELECT * FROM Parametros WHERE Nombre='$param';";
    try {
      $sth=$this->_dbh->prepare($sql);
      $sth->execute();
    } catch (Exception $e) {
      var_dump($e);
      echo($sql);
    }
    $valor=false;
    $result=$sth->fetchAll();
    if(count($result)>0){
      $valor=$result[0]["Valor"];
    }
    return $valor;
  }

}