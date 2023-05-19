<?php


namespace common\models\assessment;

use lib\util\BaseObject;

class Option extends BaseObject
{
  public $label;
  public $value;
}


/**
 * @property Option[] $options
 * @property bool $multiple
 * @property int $max
 */
class MultipleChoice extends Question
{
  public $options;


  public function renderBuild()
  {
    return $this->render("multiple-choice-build", ["question" => $this, "prefix" => spl_object_id($this) . "-"]);
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
    return $this->view->render("multiple-choice-view");
  }
}
