<?php


namespace admin\controller;

use common\controller\Controller;

class DashboardController extends Controller
{


  public function secure()
  {
    return [
      "requiresAuth" => ["*"]
    ];
  }

  public function actionIndex()
  {
    $this->render('index', ["title" => "Dashboard"], 'dashboard');
  }
}
