<?php

namespace common\models\assessment;

use lib\util\Helper;

class Dropdown extends Question
{

  public $options;

  public function renderBuild()
  {
    return $this->render("dropdown-build", ["question" => $this, "prefix" => spl_object_id($this) . "-"]);
  }

  public function renderView()
  {
    return $this->render("dropdown-view");
  }

  public static function createContext($context)
  {
    $options = [];
    foreach ($context as $key => $value) {
      $options[$key] = $value;
    }

    return ["options" => $options];
  }

  public function getAnswer(Answer $answer)
  {
    if ($this->type !== $answer->type) return "Invalid Answer";
    $context = $answer->parseContext();

    $value = Helper::getValue("value", $context);
    return Helper::getValue($value, $this->options, "Invalid Answer");
  }
}
