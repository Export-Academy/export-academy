<?php

namespace common\controller;

use common\controller\Controller;

require_once 'C:\xampp\htdocs\academy\common\controller\Controller.php';
require_once 'C:\xampp\htdocs\academy\common\app\http\Request.php';


class BaseController extends Controller
{
  public function actionIndex()
  {
    $this->jsonResponse('Index Page');
  }


  public function actionLogin()
  {
    $this->jsonResponse('Login Page');
  }


  public function actionSignUp()
  {
    $this->jsonResponse('Sign up Page');
  }

  public function action404()
  {
    $this->jsonResponse('Page Not Found');
  }

  public function action401()
  {
    $this->jsonResponse('Unauthorized');
  }

  public function action500()
  {
    $this->render('_500');
  }
}
