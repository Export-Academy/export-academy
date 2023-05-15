<?php

namespace common\models\assessment;

use common\models\base\BaseModel;

class QuestionAsset extends BaseModel
{
  public $file_id;
  public $question_id;


  public static function tableName()
  {
    return "question_asset";
  }

  public static function getPrimaryKey()
  {
    return "file_id,question_id";
  }
}
