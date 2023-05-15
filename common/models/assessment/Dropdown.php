<?php

namespace common\models\assessment;

class Dropdown extends Question
{

  public $options;

  public function renderBuild()
  {
    return $this->render("dropdown-build", ["question" => $this]);
  }

  public static function createContext($context)
  {
    $options = [];
    foreach ($context as $key => $value) {
      $options[$key] = $value;
    }

    return ["options" => $options];
  }
}
