<?php

namespace site\controller;

use common\controller\Controller;
use lib\util\Helper;

require_once Helper::getAlias('@common\controller\Controller.php');
require_once Helper::getAlias('@lib\app\http\Request.php');


class ControlController extends Controller
{
  public function actionIndex()
  {
    $this->jsonResponse($_REQUEST);
  }
}
