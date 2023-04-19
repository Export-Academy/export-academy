<?php

namespace site\controller;

use common\controller\Controller;
use common\models\access\Restriction;
use lib\util\Helper;

require_once Helper::getAlias('@common\controller\Controller.php');
require_once Helper::getAlias('@lib\app\http\Request.php');
require_once Helper::getAlias('@lib\app\database\Query.php');
require_once Helper::getAlias('@lib\app\database\query\Expressions.php');
require_once Helper::getAlias('@common\models\index.php');



class AssessmentController extends Controller
{
  public function actionIndex()
  {
    $res = new Restriction(['role_id' => 2]);
    switch ($this->request->method()) {
      case ('GET'):
        return $this->render('index', ['method' => print_r($res->permission, true)]);

      case ('POST'):
        return $this->jsonResponse(['method' => $this->request->method()]);


      default:
        return $this->render('index', ['method' => $this->request->method()]);
    }
  }


  public function actionLogin()
  {
    $this->jsonResponse($this->request->data());
  }
}
