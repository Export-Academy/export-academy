<?php

namespace common\controller;

use common\controller\Controller;
use lib\app\router\Router;


class BaseController extends Controller
{

  public function secure()
  {
    return [
      "methods" => [
        "actionLogin" => [
          "no_auth" => true
        ],
        "actionSignOut" => [
          "auth" => true,
        ],
        "actionSignUp" => [
          "no_auth" => true
        ]
      ]
    ];
  }

  public function actionIndex()
  {
    $this->render('index');
  }

  public function actionLogin()
  {
    switch ($this->request->method()) {
      case 'POST':
        $email = $this->request->data("email", "");
        $password = $this->request->data("password", "");


        $result = $this->request->auth->handleLogin($email, $password);

        if ($result) {
          Router::redirect("/academy/admin/dashboard/");
        }
        Router::redirect("/academy/login");
        return;

      case 'GET':
      default:
        $this->render('login', []);
        return;
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


  public function actionSignOut()
  {
    $this->request->auth->handleSignOut();
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
