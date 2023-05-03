<?php


namespace common\models\assessment\interface;

use lib\app\view\interface\IViewable;

interface IQuestionView extends IViewable
{
  public function render($filename, $params);
}