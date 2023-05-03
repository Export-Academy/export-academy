<?php


namespace common\models\assessment\components;

use common\models\assessment\interface\IQuestionView;
use lib\app\view\View;
use lib\util\BaseObject;
use lib\util\Helper;


require_once Helper::getAlias("@common\models\assessment\interface\IQuestionView.php");


class QuestionComponents extends BaseObject implements IQuestionView
{

  public $view;

  public function init()
  {
    $this->view = View::instance($this);
  }

  public function getViewsDirectory()
  {
    return Helper::getAlias("@common\models\assessment\components\\views\\");
  }


  public function getAssetDirectory()
  {
    return Helper::getAlias("@common\models\assessment\components\assets");
  }

  public function render($filename, $params)
  {
    return $this->view->generateContent($filename, $params);
  }
}