<?php
require_once 'models/usermodel.php';
require_once 'classes/session.php';
class SessionController extends Controller{
  private $userSession;
  private $userName;
  private $userId;
  private $session;
  private $sites;
  private $user;

  function __construct(){
    parent::__construct();
    $this->init();
  }
  function init(){
    $this->session = new Session();
    $json = $this->getJSONFileConfig();
    $this->sites = $json['sites'];
    $this->defaultSites = $json['default-sites'];
    $this->validateSession();
  }
  private function getJSONFileConfig(){
    $string = file_get_contents('config/access.json');
    $json = json_decode($string, true);
    return $json;
  } 

  public function validateSession(){
    error_log('SessionController::validateSession');
    if($this->existsSession()){
      $role = $this->getUserSessionData()->getRole();
      // si la pagina a entrar es publica
      if ($this->isPublic())
      {
        $this->redirectDefaultSiteByRole($role);
      }
      else {
        if ($this->isAuthorized($role)) {
          // lo dejo pasar
        }
        else {
          $this->redirectDefaultSiteByRole($role);
        }
      }
    }
    else {
      // no existe la session
      if ($this->isPublic()) {
        // no pasa nada lo deja entrar
      }
      else {
        header("location: ". constant('URL'));
      }
    }
  }

  public function existsSession(){
    if (!$this->session->exists()) {
      return false;
    }
    if ($this->session->getCurrentUser() == NULL ) {
      return false;
    }
    $userId = $this->session->getCurrentUser();
    if($userId) return true;
    return false;
  }

  public function getUserSessionData(){
    $id = $this->session->getCurrentUser();
    $this->user = new UserModel();
    $this->user->get($id);
    error_log("SessionController::getUserSessionData -> " . $this->user->getUsername());
    return $this->user;
  }
  public function isPublic()
  {
    $currentURL = $this->getCurrentPage();
    $currentURL = preg_replace("/\?.*/","",$currentURL);
    for ($i=0; $i < sizeof($this->sites); $i++) { 
      if ($currentURL == $this->sites[$i]['site'] && $this->sites[$i]['access'] == 'public') {
        return true; 
      }
    }
    return false;
  }

  public function getCurrentPage()
  {
    $actualLink = trim("$_SERVER[REQUEST_URI]");
    $url = explode('/', $actualLink);
    error_log("SessionController::getCurrentPage -> ".$url[2]);
    return $url[2];
  }

  private function redirectDefaultSiteByRole($role){
    $url = '';
    for ($i=0; $i <sizeof($this->sites) ; $i++) { 
      if ($this->sites[$i]['role'] == $role) {
        $url = '/expenses/' . $this->sites[$i]['site'];
        break;
      }
    }
    header("location:" . constant('URL'). $url);
  }

  function initialize($user)
  {
    $this->session->setCurrentUser($user->getId());
    $this->authorizeAccess($user->getRole());
  }

  private function isAuthorized($role){
    $currentURL = $this->getCurrentPage();
    $currentURL = preg_replace("/\?.*/","",$currentURL);
    for ($i=0; $i < sizeof($this->sites); $i++) { 
      if ($currentURL == $this->sites[$i]['site'] && $this->sites[$i]['role'] == $role) {
        return true; 
      }
    }
    return false;
  }

  function authorizeAccess($role){
    switch ($role) {
      case 'user':
        $this->redirect($this->defaultSites['user'],[]); 
      break;
      case 'admin':
        $this->redirect($this->defaultSites['admin'],[]); 
      break;
    }
  }

  function logout(){
    $this->session->closeSession();
  }
}
?>
