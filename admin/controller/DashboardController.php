<?php


namespace admin\controller;

use common\controller\Controller;

class DashboardController extends Controller
{


  public function secure()
  {
    return [
      "base" => [
        "auth" => true,
        "policy" => []
      ],
    ];
  }

  public function actionIndex()
  {
    $this->render('index', [], 'dashboard');
  }
}
