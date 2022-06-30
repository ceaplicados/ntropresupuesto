<?php
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once("../dep/clases/DaooAuths.php");
require_once("../dep/clases/DaoUsuarios.php");
require_once("../dep/clases/DaoSessions.php");
$DaooAuths=new DaooAuths();
$DaoUsuarios=new DaoUsuarios();
$DaoSessions= new DaoSessions();



if(strlen($_GET['code'])>0){
	$url="https://www.googleapis.com/oauth2/v3/token";

	$data=array();
	array_push($data, "code=".$_GET['code']);
	array_push($data, "client_id=".$DaoUsuarios->getParam("Google_ClientId"));
	array_push($data, "client_secret=".$DaoUsuarios->getParam("Google_ClientSecret"));
	array_push($data, "redirect_uri=https://".$DaoUsuarios->getParam("dominio")."/oauth_Google");
	array_push($data, "grant_type=authorization_code");
	$return=$DaoUsuarios->gweb_curl("POST", false, $url, $data);
	
	$resultado=json_decode($return);
	$access_token=$resultado->access_token;
	$refresh_token="";
	if(isset($resultado->refresh_token)){
		$refresh_token=$resultado->refresh_token;
	}
	$token_expires=mktime(date("H"), date("i") , date("s")+intval($resultado->expires_in), date("n"), date("j") , date("Y"));

	$url="https://www.googleapis.com/plus/v1/people/me?personFields=emailAddresses";
	$url="https://people.googleapis.com/v1/people/me?personFields=emailAddresses,names,photos&access_token=".$access_token;
	$header=array();
	array_push($header, "Authorization: Bearer ".$access_token);
	$return=$DaoUsuarios->gweb_curl("GET", $header, $url);
	$user_info=json_decode($return);
	if(isset($user_info->error)){
		echo($user_info->error->message);
		exit();
	}
	$userId=str_replace("people/", "", $user_info->resourceName);
	$oAuths=$DaooAuths->advancedQuery("SELECT * FROM oAuths WHERE Servicio='Google' AND UID='".$userId."'");
	if(count($oAuths)>0){
		$Usuario=$DaoUsuarios->show($oAuths[0]->getUsuario());

		// Actualizar tokens
		$oAuths=$oAuths[0];
		$oAuths->setAccessKey($tokens->access_token);
		if(isset($tokens->refresh_token)){
			if(strlen($tokens->refresh_token)>0){
				$oAuths->setRefreshKey($tokens->refresh_token);
			}
		}
		$oAuths->setNeedsReauthorization(0);
		$DaooAuths->update($oAuths);
		
		// crear sesión sin cliente
		$Session = new Sessions();

		if(isset($_COOKIE["SessionUID"])){
			$Session=$DaoSessions->getSession($_COOKIE["SessionUID"]);
			if($Session->getId()>0){
				// renovar por 48 horas
				$Session->setDateDeath(date("Y-m-d H:i:s",strtotime("+48 hours")));
				$DaoSessions->update($Session);
			}else{
				header("Location: /logout");
				exit();
			}
		}
		if(!$Session->getId()>0){
			$Session->setUID($DaoSessions->nonce(),25);
			$Session->setUsuario($Usuario->getId());
			$Session->setDateBorn(date("Y-m-d H:i:s"));
			$Session->setDateDeath(date("Y-m-d H:i:s",strtotime("+48 hours")));
			$Session=$DaoSessions->add($Session);
		}
	}else{
		if(isset($_COOKIE["SessionUID"])){
			$Session=$DaoSessions->getSession($_COOKIE["SessionUID"]);
			// renovar por 48 horas
			if($Session->getId()>0){
				$Session->setDateDeath(date("Y-m-d H:i:s",strtotime("+48 hours")));
				$DaoSessions->update($Session);
				$Usuario=$DaoUsuarios->show($Session->getUsuario());
			}else{
				header("Location: /logout");
				exit();
			}
		}else{
			$Usuario = new Usuarios();
			// Buscar por email al usuario
			foreach($user_info->emailAddresses as $emailObj){
				$SearchUsuario=$DaoUsuarios->getByEmail($emailObj->value);
				if($SearchUsuario->getId()>0){
					$Usuario=$SearchUsuario;
				}
			}
			if(!$Usuario->getId()>0){
				$image="";
				if(count($user_info->photos)>0){
					$image=$user_info->photos[0]->url;
				}
				// Crear un usuario nuevo
				$Usuario->setSobrenombre($user_info->names[0]->givenName);
				$Usuario->setNombre($user_info->names[0]->displayName);
				$Usuario->setEmail($user_info->emailAddresses[0]->value);
				$Usuario->setDateBorn(date("Y-m-d H:i:s"));
				$Usuario->setActivo(1);
				$Usuario->setImage($image);
				$Usuario->setUUID($DaoUsuarios->nonce());
				$Usuario=$DaoUsuarios->add($Usuario);
			}
			// Crear sessión nueva
			$Session = new Sessions();
			$Session->setUID($DaoSessions->nonce("",25));
			$Session->setUsuario($Usuario->getId());
			$Session->setDateBorn(date("Y-m-d H:i:s"));
			$Session->setDateDeath(date("Y-m-d H:i:s",strtotime("+48 hours")));
			$Session=$DaoSessions->add($Session);
		}
		
		// poner tokens
		$oAuths=new oAuths();
		$oAuths->setUsuario($Usuario->getId());
		$oAuths->setServicio("Google");
		$oAuths->setUID($userId);
		$oAuths->setAccessKey($tokens->access_token);
		$oAuths->setRefreshKey($tokens->refresh_token);
		$oAuths->setDateBorn(date("Y-m-d H:i:s"));
		$oAuths->setNeedsReauthorization(0);
		$oAuths=$DaooAuths->add($oAuths);
	}
	
	// poner cookie de sesión
	setcookie("SessionUID", $Session->getUID(), time() + (86400 * 2), "/");
	header("Location: /");
	exit();	
}
