<?php
  
class UserModel extends Model implements IModel{

  private $id;
  private $username;
  private $password;
  private $role;
  private $budget;
  private $photo;
  private $name;

  public function __construct(){
    parent::__construct();
    $this->id = '';
    $this->username = '';
    $this->password = '';
    $this->role = 0.0;
    $this->budget = 0.0;
    $this->photo = '';
    $this->name = '';
  }

  function save(){
    try{
      $query = $this->prepare('INSERT INTO users(username, password, role, budget, photo, name) VALUES (:username, :password, :role, :budget, :photo, :name)');
      $query -> execute([
        'username'        => $this->username,
        'password'        => $this->password,
        'role'            => $this->role,
        'budget'          => $this->budget,
        'photo'           => $this->photo,
        'name'            => $this->name
      ]);
      return true;
    }
    catch(PDOException $e){
      error_log("USERMODEL::save -> PDOException ". $e);
      return false;
    }
  }
  function getAll(){
    $items = [];
    try{
      $query = $this->query('SELECT * FROM USERS');
      while($p = $query->fetch(PDO::FETCH_ASSOC)){
        $item = new UserModel();
        $item->setId($p['id']);
        $item->setRole($p['role']);
        $item->setPhoto($p['photo']);
        $item->setBudget($p['budget']);
        $item->setPassword($p['password']);
        $item->setUsername($p['username']);
        $item->setName($p['name']);
        array_push($items, $item);
      }
      return $items;
    }catch(PDOException $e){
      error_log("USERMODEL::getAll -> PDOException ". $e);
      return false;
    }
  }
  function get($id){
    $items = [];
    try{
      $query = $this->prepare('SELECT * FROM users WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);
      $user = $query->fetch(PDO::FETCH_ASSOC);
      $this->setId($user['id']);
      $this->setRole($user['role']);
      $this->setName($user['name']);
      $this->setPhoto($user['photo']);
      $this->setBudget($user['budget']);
      $this->setPassword($user['password']);
      $this->setUsername($user['username']);
      return $this;

    }catch(PDOException $e){
      error_log("USERMODEL::getId -> PDOException ". $e);
      return false;
    }
  }

  function delete($id){
    try{
      $query = $this->query('DELETE FROM users WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);
      return true;
    }
    catch(PDOException $e){
      error_log("USERMODEL::getDelete -> PDOException ". $e);
      return false;
    }
  }
  function update(){
    $items = [];
    try{
      $query = $this->prepare('UPDATE users SET username = :username, password = :password, budget = :budget, photo = :photo, name = :name WHERE id = :id');
      $query->execute([
        'id' => $this->id,
        'username'        => $this->username,
        'password'        => $this->password,
        'role'            => $this->role,
        'budget'          => $this->budget,
        'photo'           => $this->photo,
        'name'            => $this->name
      ]);
      return true;
    }catch(PDOException $e){
      error_log("USERMODEL::Update -> PDOException ". $e);
      return false;
    }
  }
  function from($array){
    $this->id = $array['id'];
    $this->username = $array['username'];
    $this->name = $array['name'];
    $this->role = $array['role'];
    $this->password = $array['password'];
    $this->budget = $array['budget'];
    $this->photo = $array['photo'];
  }

  public function setId($id){               $this->id = $id;}
  public function setRole($role){           $this->role = $role;}
  public function setName($name){           $this->name = $name;}
  public function setPhoto($photo){         $this->photo = $photo;}
  public function setBudget($budget){       $this->budget = $budget;}
  public function setUsername($username){   $this->username = $username;}
  public function setPassword($password){
    $this->password = $this->getHashedPassword($password);
  }

  public function getId(){        return $this->id; }
  public function getName(){      return $this->name;}
  public function getRole(){      return $this->role; }
  public function getPhoto(){     return $this->photo; }
  public function getBudget(){    return $this->budget; }
  public function getUsername(){  return $this->username; }
  public function getPassword(){  return $this->password; }

  private function getHashedPassword($password){
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
  }

  public function exists($username){
    try {
      $query = $this->prepare('SELECT username FROM users WHERE username = :username');
      $query->execute(['username' => $username]);
      if($query->rowCount() > 0){
        return true;
      }else return false;
    } catch (PDOException $e) {
      error_log("USERMODEL::exists -> PDOException ". $e);
      return false;
    }
  }
  
  public function comparePasswords($password, $id){
    try {
      $user = $this->get($id);
      return password_verify($password, $user->getPassword());
    } catch (PDOException $e) {
      error_log("USERMODEL::comparePasswords -> PDOException ". $e);
      return false;
    }
  }

}

?>
