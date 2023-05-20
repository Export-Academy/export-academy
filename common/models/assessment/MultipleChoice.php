<?php


namespace common\models\assessment;

use lib\util\BaseObject;
use lib\util\Helper;

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
    return $this->render("multiple-choice-view");
  }

  public function getAnswer(Answer $answer)
  {
    if ($this->type !== $answer->type) return "Invalid Answer";
    $context = $answer->parseContext();

    $value = Helper::getValue("value", $context);
    return Helper::getValue($value, $this->options, "Invalid Answer");
  }
}
