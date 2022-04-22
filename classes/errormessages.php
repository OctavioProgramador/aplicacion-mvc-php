<?php
class ErrorMessages 
{
  // ERROR CONTROLLER METHOD ACTION
  const ERROR_ADMIN_NEWCATEGORY_EXISTS= '48994e11bcdac899929496c500047e35'; 
  const ERROR_SIGNUP_NEWUSER = 		'58994e11bcdac8999b9496c500047e3a';
  const ERROR_SIGNUP_NEWUSER_EMPTY =	'68994e11bcdac8999b9496c500047e3b';
  const ERROR_SIGNUP_NEWUSER_EXISTS =	'78994e11bcdac8999b9496c500047e3c';
  const ERROR_LOGIN_AUTHENTICATE_EMPTY ='88994e11bcdac8999b9496c500047e3d';
  const ERROR_LOGIN_AUTHENTICATE_DATA =	'98994e11bcdac8999b9496c500047e3e';
  const ERROR_LOGIN_AUTHENTICATE =	'08994e11bcdac8999b9496c500047e3f';

  private $errorsList = [];

  public function __construct(){
    $this->errorsList = [
      ErrorMessages::ERROR_ADMIN_NEWCATEGORY_EXISTS => 	"El nombre de la categoría ya existe",
      ErrorMessages::ERROR_SIGNUP_NEWUSER =>		"Hubo un error al intentar procesar la solicitud",
      ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY => 	"LLena los campos de usuario y password",
      ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS => 	"Ya existe ese nombre de usuario",
      ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY => 	"Llena los campos de usuario y password",
      ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA => 	"Nombre de usuario y/o password incorrecto",
      ErrorMessages::ERROR_LOGIN_AUTHENTICATE => 	"No se puede procesar la solicitud. Ingresa usuario y password"
    ];
  }
  public function get($hash){
    return $this->errorsList[$hash];
  }

  public function existsKey($key){
    if(array_key_exists($key, $this->errorsList)){
      return true;
    }else return false;
  }
}

?>
