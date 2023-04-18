<?php


namespace common\models\base;

use lib\app\database\Query;
use lib\util\BaseObject;
use PDO;


require_once 'C:\xampp\htdocs\academy\lib\app\database\Database.php';
require_once  'C:\xampp\htdocs\academy\lib\util\Helper.php';
require_once  'C:\xampp\htdocs\academy\lib\util\BaseObject.php';

class BaseModel extends BaseObject
{


  function __instance($config = [])
  {
    $class = get_called_class();
    return new $class($config);
  }


  public static function tableName()
  {
    return strtolower(basename(get_called_class()));
  }


  public static function find($condition = false)
  {
    $query = Query::create(get_called_class());
    return $condition ? $query->where($condition) : $query;
  }

  public static function findOne($condition = false)
  {
    return self::find($condition)->limit(1)->one();
  }


  public static function hasMany($className, $condition)
  {
    return Query::create($className)->where($condition);
  }


  public static function hasOne($className, $condition)
  {
    return self::hasMany($className, $condition)->limit(1);
  }
}
