<?php
class Dashboard extends SessionController{
  function __construct(){
    parent::__construct();
    error_log("DASHBOARD::construct -> inicio de login");
  }
  function render(){
    error_log("DASHBOARD::render -> carga el index de dashboard");
    $this->view->render('dashboard/index');
  }

  public function getExpenses(){

  }

  public function getCategories(){

  }


}
?>
