<?php

namespace common\models\assessment;

use lib\util\Helper;

class Boolean extends Question
{
  public $trueLabel = "True";
  public $falseLabel = "False";

  public function renderBuild()
  {
    return $this->render("boolean-build", ["question" => $this]);
  }

  public function renderView()
  {
    return $this->render("boolean-view");
  }

  public static function createContext($context)
  {
    $data["trueLabel"] = Helper::getValue(1, $context, "Yes");
    $data["falseLabel"] = Helper::getValue(0, $context, "No");

    return $data;
  }
}
