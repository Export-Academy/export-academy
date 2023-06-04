<?php


namespace common\models\user;

use common\models\base\BaseModel;

class UserMeta extends BaseModel
{
  public $timezone;
  public $id;


  public static function tableName()
  {
    return "user_meta";
  }
}
