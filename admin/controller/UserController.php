<?php


namespace admin\controller;

use common\controller\Controller;

class UserController extends Controller
{


  public function secure()
  {
    return [
      "requiresAuth" => ["*"]
    ];
  }

  public function actionIndex()
  {
    $this->render('index', ["title" => "User Management"], 'dashboard');
  }


  public function actionRole()
  {
    $this->render('role', ["title" => "User Role Management"], 'dashboard');
  }


  public function actionPermission()

  {
    $this->render('permission', ['title' => "Permission Management"], 'dashboard');
  }
}
