<?php
class Dashboard extends SessionController{

  private $user;
  function __construct(){
    parent::__construct();
    $this->user = $this->getUserSessionData();
    error_log("DASHBOARD::construct -> inicio de dashboard");
  }
  function render(){
    error_log("DASHBOARD::render -> carga el index de dashboard");
    $expensesModel = new ExpensesModel();
    $expesnses = $this->getExpenses(5);
    $totalThisMonth = $expensesModel->getTotalAmountThisMonth($this->user->getId());
    $maxExpensesThisMonth = $expensesModel->getMaxExpensesThisMonth($this->user->getId());
    $categories = $this->getCategories();
    $this->view->render('dashboard/index',[
      'user' => $this->user,
      'expesnses' => $expesnses,
      'totalAmountThisMonth' => $totalThisMonth,
      'maxExpensesThisMonth' => $maxExpensesThisMonth,
      'categories' => $categories
    ]);
  }

  public function getExpenses($n = 0){
    if ($n < 0) {
      return NULL;
    } 
    $expesnses = new ExpensesModel();
    return $expesnses->getByUserIdAndLimit($this->user->getId(),$n);
  }

  public function getCategories(){
    $res = [];
    $categoriesModel = new CategoriesModel();
    $expensesModel = new ExpensesModel();
    $categories = $categoriesModel->getAll();

   foreach ($categories as $category) {
      $categoryArray = [];
      $total = $expensesModel->getTotalByCategoryThisMonth($category->getId(),$this->user->getId());
      $numberOfExpenses = $expensesModel->getNumberOfExpensesByCategoryThisMonth($category->getId(),$this->user->getId());
      if ($numberOfExpenses > 0) {
        $categoryArray['total'] = $total;
        $categoryArray['count'] = $numberOfExpenses;
        $categoryArray['category'] = $category;
        array_push($res, $categoryArray);
      }
    } 
    return $res;
  }


}
?>
