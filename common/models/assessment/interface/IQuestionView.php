<?php


namespace common\models\assessment\interface;

interface IQuestionView
{
  public function render($filename, $params);
}