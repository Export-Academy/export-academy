<?php

namespace common\models\assessment;

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
    return $this->view->render("checkbox-view");
  }
}
