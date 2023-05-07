<?php

namespace common\controller;

use common\controller\Controller;
use common\models\user\User;
use lib\app\log\Logger;
use lib\app\router\Router;
use lib\util\Helper;

class BaseController extends Controller
{

  public function secure()
  {
    return [
      "requiresAuth" => ["actionSignOut"],
      "strictNoAuth" => ["actionLogin", "actionSignUp"]
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
          $redirectPath = $this->request->params("r", null);
          Logger::log($redirectPath);
          Router::redirect($redirectPath ?? "/academy/admin/dashboard");
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
        $data = $this->request->data("User", []);

        $password = Helper::getValue("password", $data, null);

        if ($password) {
          $data["password"] = User::encryptPassword($password);
        }
        $user = new User($data);
        $user->save();
        Router::redirect("/academy/login");
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
    http_response_code(404);
    $this->render('_404');
  }

  public function action401()
  {
    http_response_code(401);
    $this->render('_401');
  }

  public function action500()
  {
    http_response_code(500);
    $this->render('_500');
  }
}