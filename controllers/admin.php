<?php
require_once 'models/expensesmodel.php';
require_once 'models/categoriesmodel.php';
class Admin extends SessionController 
{
  public function __construct() {
    parent::__construct();
  }

  public function render(){
    $stats = $this->getStatistics();
    $this->view->render('admin/index',[
      'stats' => $stats
    ]);
  }

  function createCategory(){
    $this->view->render('admin/create-category');
  }

  function newCategory(){
    if($this->existPOST(['name', 'color']))
    {
      $name =  $this->getPOST('name');
      $color =  $this->getPOST('color');
      $categoriesModel =  new CategoriesModel();

      if(!$categoriesModel->exists($name)){
        $categoriesModel->setName($name);
        $categoriesModel->setColor($color);
        $categoriesModel->save();
        error_log('ADMIN::newCategory -> Success');
        $this->redirect('admin',[]); //TODO success
      }else{
        error_log('ADMIN::newCategory -> Error');
        $this->redirect('admin',[]); //TODO error
      }
    }
  }

  private function getMaxAmount($expenses){
    $max = 0;
    foreach ($expenses as $expense ) {
      $max = max($max,$expense->getAmount());
    }
    return $max;
  }

  private function getMinAmount($expenses){
    $min = $this->getMaxAmount($expenses);
    foreach ($expenses as $expense ) {
      $min = min($min,$expense->getAmount());
    }
    return $min;
  }

  private function getAverageAmount($expenses){
    $sum = 0;
    foreach ($expenses as $expense) {
      $sum += $expense->getAmount();
    }
    return ($sum / count($expenses));
  }

  private function getCategoryMostUsed($expenses){
    $repeat = [];
    foreach ($expenses as $expense) {
      if(!array_key_exists($expense->getCategoryId(), $repeat)){
        $repeat[$expense->getCategoryId()] = 0;
      }
      $repeat[$expense->getCategoryId()]++;
    }
    //$categoryMostUsed = max($repeat);
    $categoryMostUsed = 0;
    $maxCategory = max($repeat);
    foreach ($repeat as $index => $category) {
      if($category == $maxCategory){
        $categoryMostUsed = $index;
      }
    }
    $categoryModel = new CategoriesModel();
    $categoryModel->get($categoryMostUsed);
    
    $category = $categoryModel->getName();
    error_log('Admin::getCategoryMostUsed ->'.$categoryMostUsed);
    return $category;
  }
  private function getCategoryLessUsed($expenses){
    $repeat = [];
    foreach ($expenses as $expense) {
      if(!array_key_exists($expense->getCategoryId(), $repeat)){
        $repeat[$expense->getCategoryId()] = 0;
      }
      $repeat[$expense->getCategoryId()]++;
    }
    $categoryLessUsed = min($repeat);
    $categoryModel = new CategoriesModel();
    $categoryModel->get($categoryLessUsed);
    
    $category = $categoryModel->getName();
    return $category;
  }

  function getStatistics(){
    $res = [];
    $userModel = new UserModel();
    $users = $userModel->getAll();
    $categoriesModel =  new CategoriesModel();
    $categories = $categoriesModel->getAll();
    $expensesModel = new ExpensesModel();
    $expenses = $expensesModel->getAll();
    $res['count-users'] = count($users);
    $res['count-expenses'] = count($expenses);
    $res['max-expenses'] = $this->getMaxAmount($expenses);
    $res['min-expenses'] = $this->getMinAmount($expenses);
    $res['avg-expenses'] = $this->getAverageAmount($expenses);
    $res['count-categories'] = count($categories);
    $res['mostused-categories'] = $this->getCategoryMostUsed($expenses);
    $res['lessused-categories'] = $this->getCategoryLessUsed($expenses);
   
    return $res;
  }

}

?>
