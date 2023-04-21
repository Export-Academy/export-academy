<?php


namespace web\controller;

use common\controller\Controller;
use lib\util\Helper;

class SourceController extends Controller
{

  public function actionJs($__filename__ = null)
  {
    $this->returnScript(Helper::getAlias("@web/source/js/$__filename__"));
  }


  public function actionCss($__filename__ = null)
  {
    $this->returnStylesheet(Helper::getAlias("@web/source/css/$__filename__"));
  }
}