<?php

namespace site\controller;

use common\app\database\Query;
use common\controller\Controller;
use common\models\access\Restriction;

require_once 'C:\xampp\htdocs\academy\common\controller\Controller.php';
require_once 'C:\xampp\htdocs\academy\common\app\http\Request.php';
require_once 'C:\xampp\htdocs\academy\common\app\database\Query.php';
require_once 'C:\xampp\htdocs\academy\common\app\database\query\Expressions.php';
require_once 'C:\xampp\htdocs\academy\common\models\index.php';
require_once 'C:\xampp\htdocs\academy\common\util\Helper.php';




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
}
