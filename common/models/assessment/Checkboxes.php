<?php

namespace common\models\assessment;

use lib\util\Helper;

class Checkboxes extends Question
{

  public $options;

  public function renderBuild()
  {
    return $this->render("checkbox-build", ["question" => $this, "prefix" => spl_object_id($this) . "-"]);
  }

  public static function createContext($context)
  {
    $options = [];
    foreach ($context as $key => $value) {
      $options[$key] = $value;
    }


    return ["options" => $options];
  }

  public function renderView()
  {
    return $this->render("checkbox-view");
  }


  public function getAnswer(Answer $answer)
  {
    if ($this->type !== $answer->type) return "Invalid Answer";
    $context = $answer->parseContext();

    $options = $this->options;
    $selected = array_map(function ($key) use ($options) {
      return Helper::getValue($key, $options, "Invalid Selection");
    }, is_array($context) ? $context : []);

    return is_array($selected) && count($selected) > 0 ? implode("<br/>", $selected) : "No Selections";
  }
}
