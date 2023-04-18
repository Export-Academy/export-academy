<?php

namespace common\controller;

use common\controller\Controller;
use lib\util\Helper;

require_once Helper::getAlias('@common\controller\Controller.php');
require_once Helper::getAlias('@lib\app\http\Request.php');


class BaseController extends Controller
{
  public function actionIndex()
  {
    $this->render('index');
  }

  public function actionLogin()
  {
    $this->render('login');
  }

  public function actionSignUp()
  {
    $this->render('register');
  }

  public function action404()
  {
    $this->render('_404');
  }

  public function action401()
  {
    $this->render('_401');
  }

  public function action500()
  {
    $this->render('_500');
  }
}
