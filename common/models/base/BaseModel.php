<?php


namespace common\models\base;

use common\models\base\interface\IActiveModel;
use lib\app\database\Query;
use lib\util\BaseObject;
use lib\util\Helper;

require_once Helper::getAlias("@common\models\base\interface\IActiveModel.php", "\\");
require_once Helper::getAlias("@lib\app\database\Query.php");

class BaseModel extends BaseObject implements IActiveModel
{
  public static function tableName()
  {
    return strtolower(basename(get_called_class()));
  }


  public static function find($condition = false)
  {
    $query = Query::create(get_called_class());
    return $condition ? $query->where($condition) : $query;
  }

  /**
   * Returns a single instance of BaseModel matching the condition
   *
   * @param boolean $condition
   * @return void
   */
  public static function findOne($condition = false)
  {
    return self::find($condition)->limit(1)->one();
  }


  public function hasMany($className, $condition)
  {
    return Query::create($className)->where($condition);
  }


  public function hasOne($className, $condition)
  {
    return $this->hasMany($className, $condition)->limit(1);
  }
}
