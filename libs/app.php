<?php
require_once 'controllers/errores.php';
class App{
  function __construct(){
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    error_log("APP url-> ".$url);
    $url = rtrim($url, '/');
    $url = explode('/', $url);

    if (empty($url[0])){
      error_log("APP::construct-> No hay controlador especificado");
      $archivoController = 'controllers/login.php';
      require_once $archivoController;
      $controller = new Login();
      $controller->loadModel('login');
      $controller->render();
      return false;
    }
    error_log("APP -> se carga el archivo controllers/".$url[0].".php");
    $archivoController = 'controllers/'. $url[0] . '.php';

    if (file_exists($archivoController)){
      require_once $archivoController;
      $controller = new $url[0];
      $controller->loadModel($url[0]);
      if (isset($url[1])){
        if(method_exists($controller, $url[1])){
          if( isset($url[2])){
            //no de parametros
            $nparam = sizeof($url) - 2;
            // arreglo de parametros
            $params = [];
            for ($i = 0; $i < $nparam; $i++){
              array_push($params, $url[$i+2]);
            }
            $controller->{$url[1]}($params);
          }else{
            // no tiene parametros se manda a llamar
            // el metodo tal cual
            $controller->{$url[1]}();
          }
        }
        else{
          // error no existe el metodo
		error_log("APP -> Error, no existe el método");
          $controller = new Errores();
        }
      }else{
        //no hay metodo por default
        $controller->render();
      }
    }else{
      // no existe archivo manda error
	error_log("APP -> Error, no existe el archivo");
      $controller = new Errores();
    }
  }
}
?>
