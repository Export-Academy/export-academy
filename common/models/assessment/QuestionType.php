<?php


namespace common\models\assessment;

use common\models\base\BaseModel;


/**
 * @property int $id
 * @property string $name
 * @property string $handler
 */
class QuestionType extends BaseModel
{

  public $id;
  public $name;
  public $handler;

  public static function tableName()
  {
    return "question_type";
  }
}
