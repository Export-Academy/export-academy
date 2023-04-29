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
  public $multiple;
  public $max;
}
