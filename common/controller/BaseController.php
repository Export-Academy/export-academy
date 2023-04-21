<?php

namespace common\controller;

use common\controller\Controller;


class BaseController extends Controller
{
  public function actionIndex()
  {
    $this->render('index');
  }

  public function actionLogin()
  {
    switch ($this->request->method()) {
      case 'POST':
        $this->jsonResponse($this->request->data());
        break;

      case 'GET':
      default:
        $this->render('login');
        break;
    }
  }

  public function actionSignUp()
  {
    switch ($this->request->method()) {
      case 'POST':
        $this->jsonResponse($this->request->data());
        break;

      case 'GET':
      default:
        $this->render('sign_up');
        break;
    }
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