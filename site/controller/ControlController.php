<?php

namespace site\controller;

use common\controller\Controller;

require_once 'C:\xampp\htdocs\academy\common\controller\Controller.php';
require_once 'C:\xampp\htdocs\academy\lib\app\http\Request.php';


class ControlController extends Controller
{
  public function actionIndex()
  {
    $this->jsonResponse($_REQUEST);
  }
}
