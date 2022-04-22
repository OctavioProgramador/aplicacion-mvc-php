<?php
class Login extends SessionController{
  function __construct(){
    parent::__construct();
    error_log("Login::construct -> inicio de login");
  }
  function render(){
    error_log("Login::render -> carga el index de login");
    $this->view->render('login/index');
  }
  function authenticate(){
	  if($this->existPOST(['username','password'])){
		  $username = $this->getPOST('username');
		  $password = $this->getPOST('password');
		  if($username == '' || empty($username) || $password == '' || empty($username) ){
			  $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY]);
		  }
		  $user = $this->model->login($username, $password);
		  if($user != NULL){
			  $this->initialize($user);
		  }else{
			  
			  $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA]);
		  }
	}else{
		$this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE]);
	}
  }
}
?>
