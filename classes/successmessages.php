<?php

class SuccessMessages{
  const PRUEBA = 			'48994e11bcdac899929496c500047e38'; 
  const SUCCESS_SIGNUP_NEWUSER = 	'33394e11bcdac899929496c500047e37';
  private $successList = [];

  public function __construct(){
    $this->successList = [
      SuccessMessages::PRUEBA => 			"Este es un mensaje de éxito",
      SuccessMessages::SUCCESS_SIGNUP_NEWUSER => 	"Nuevo usuario registrado correctamente" 
    ];
  }
  public function get($hash){
    return $this->successList[$hash];
  }

  public function existsKey($key){
    if(array_key_exists($key, $this->successList)){
      return true;
    }else return false;
  }
}

?>
