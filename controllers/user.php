<?php
require_once 'models/usermodel.php';
class User extends SessionController 
{
  private $user;
  function __construct()
  {
    parent::__construct(); 
    $this->user = $this->getUserSessionData();
  } 

  function render(){
    $this->view->render('user/index',[
      'user' => $this->user
    ]);
  }

  function updateBudget(){
    if (!$this->existPOST(['budget'])) {
      $this->redirect('user',['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET]); 
      return;
    }
    $budget = $this->getPOST('budget');
    if (empty($budget) || $budget == 0 || $budget < 0) {
      $this->redirect('user',['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET_EMPTY]); 
      return;
    }
    $this->user->setBudget($budget);
    if($this->user->update()){
      $this->redirect('user',['success' => SuccessMessages::SUCCESS_USER_UPDATEBUDGET]); 
    }
  }

  function updateName(){
    if (!$this->existPOST(['name'])) {
      $this->redirect('user',['error' => ErrorMessages::ERROR_USER_UPDATENAME]);
      return;
    }
    $name = $this->getPOST('name');
    if (empty($name) || $name == NULL ) {
      $this->redirect('user',['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET_EMPTY]); 
      return;
    }
    $this->user->setUsername($name);
    if($this->user->update()){
      $this->redirect('user',['success'=>SuccessMessages::SUCCESS_USER_UPDATENAME]); 
    }   
  }

  function updatePassword(){
    if (!$this->existPOST(['current_password','new_password'])) {
            $this->redirect('user',['error'=>ErrorMessages::ERROR_USER_UPDATEPASSWORD]); 
      return;
    }
    $current = $this->getPOST('current_password');
    $new = $this->getPOST('new_password');
    if (empty($current) || empty($new)) {
      $this->redirect('user',['error'=>ErrorMessages::ERROR_USER_UPDATENAME_EMPTY]); 
      return;
    }
    if ($current == $new) {
      $this->redirect('user',['error'=>ErrorMessages::ERROR_USER_UPDATEPASSWORD_ISNOTTHESAME]); 
      return;
    }

    $newHash = $this->model->comparePasswords($current,$this->user->getId());
    if ($newHash) {
      $this->user->setPassword($new);

      if ($this->user->update()) {
        $this->redirect('user',['success'=>SuccessMessages::SUCCESS_USER_UPDATEPASSWORD]); 
        return;
      }else{
        $this->redirect('user',['error'=>ErrorMessages::ERROR_USER_UPDATEPASSWORD]); 
        return;
      }
    }
    else{
      $this->redirect('user',['error'=>ErrorMessages::ERROR_USER_UPDATEPASSWORD]); 
      return;
    }
  }

  function updatePhoto(){
    if (!isset($_FILES['photo'])) {
      $this->redirect('user',['error'=>ErrorMessages::ERROR_USER_UPDATEPHOTO]);
      return;
    }
    $photo = $_FILES['photo'];
    $targetDir = 'public/img/photos/';
    $extension = explode('.', $photo['name']);
    $filename = $extension[sizeof($extension)-2];
    $ext = $extension[sizeof($extension)-1];
    $hash = md5(Date('Ymdgi').$filename).'.'. $ext;
    $targetFile = $targetDir . $hash;
    $uploadOk = true;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    $check = getimagesize($photo['tmp_name']);
    if ($check != false) {
      $uploadOk = true;
    }else{
      $uploadOk = false;
    }

    if (!$uploadOk) {
      $this->redirect('user',['error'=>ErrorMessages::ERROR_USER_UPDATEPHOTO_FORMAT]);
      return;
    }else{
      if (move_uploaded_file($photo['tmp_name'],$targetFile)) {
        error_log("USER::->updatePhoto() -> \$hash = ". $hash);
        $this->user->setPhoto($hash);
        $this->user->update();
        $this->redirect('user',['success'=>SuccessMessages::SUCCESS_USER_UPDATEPHOTO]);
        return;
      }else{
        $this->redirect('user',['error'=>ErrorMessages::ERROR_USER_UPDATEPHOTO]);
        return;
      }
    }
    
  }
}

?>
