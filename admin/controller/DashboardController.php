<?php


namespace admin\controller;

use common\controller\Controller;

class DashboardController extends Controller
{

  public function actionIndex()
  {
    $this->render('index', []);
  }
}