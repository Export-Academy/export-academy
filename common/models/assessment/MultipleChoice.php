<?php


namespace common\models\assessment;

use lib\app\view\View;
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


  public static function renderBuild(View $view)
  {
    $component = self::generate($view);
    return $component->render("multiple-choice-build");
  }
}
