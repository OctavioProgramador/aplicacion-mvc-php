<?php
require_once 'models/expensesmodel.php';
require_once 'models/cateogiesmode.php';
class Expenses extends SessionController 
{
  private $user;
  public function __construct() {
    parent::__construct();
    $this->user = $this->getUserSessionData();
  } 

  function render()
  {
    $this->view->render('expenses/index',[
      'user' => $this->user
    ]);
  }

  function newExpense(){
    if(!$this->existPOST(['title', 'amount', 'category', 'date']))
    {
      $this->redirect('dashboard', []); //TODO: error
      return;
    }
    if($this->user == NULL)
    {
      $this->redirect('dashboard',[]); //TODO:error
      return;
    }
    $expense = new ExpensesModel();
    $expense->setTitle($this->getPOST('title'));
    $expense->setAmount((float)$this->getPOST('amount'));
    $expense->setCategoryId($this->getPOST('category'));
    $expense->setDate($this->getPOST('date'));
    $expense->setUserId($this->user->getId());

    $expense->save();
    $this->redirect('dashboard',[]); // TODO:success
  }

  function create(){
    $categories = new CategoriesModel();
    $this->view->render('expenses/create',[
      'categories'=>$categories->getAll(),
      'user'=>$this->user
    ]);
  }

  function getCategoriesId(){
    $joinModel = new JoinExpensesCategoriesModel();

    $categories = $joinModel->getAll($this->user->getId());
    $res = [];
    foreach ($categories as $cat) {
      array_push($res,$cat->getCategoryId());
    }
    $res = array_values(array_unique($res));
    return $res;
  }

  private function getDateList(){
    $months = [];
    $res = [];
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses = $joinModel->getAll($this->user->getId());
    foreach ($expenses as $expense) {
      array_push($months,substr($expense->getDate(),0,7));
    }
    $months = array_values(array_unique($months));
    if(count($months) >3 ){
      array_push($res,array_pop($months));
      array_push($res,array_pop($months));
      array_push($res,array_pop($months));
    }
    return $res;
  }

  function getCategoryList(){
    $res = [];
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses = $joinModel->getAll($this->user->getId());
    foreach ($expenses as $expense) {
      array_push($res,$expense->getNameCategory());
    }
    $res = array_values(array_unique($res));
    return $res;
  }

  function getCategoryColorList(){
    $res = [];
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses = $joinModel->getAll($this->user->getId());
    foreach ($expenses as $expense) {
      array_push($res,$expense->getColor());
    }
    $res = array_unique($res);
    $res = array_values(array_unique($res));
    return $res;
  }

  function getHistoryJSON(){
    header('Content-Type: application/json');
    $res = [];
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses = $joinModel->getAll($this->user->getId());
    foreach ($expenses as $expense) {
      array_push($res,$expense->toArray());
    }
    echo json_encode($res);
  }

  function getExpensesJSON(){

    header('Content-Type: application/json');
    $res = [];
    $categoryIds = $this->getCategoriesId();
    $categoryNames = $this->getCategoryList();
    $categoryColors = $this->getCategoryColorList();
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses = $joinModel->getAll($this->user->getId());
  }
}


?>
