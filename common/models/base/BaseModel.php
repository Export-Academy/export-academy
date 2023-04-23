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


  public static function getPrimaryKey()
  {
    return "id";
  }

  public function getPrimaryCondition()
  {
    $keys = explode(",", $this->getPrimaryKey());
    $condition = [];
    foreach ($keys as $key) {
      $condition[$key] = is_int($this->{$key}) ? $this->{$key} : "*$this->{$key}";
    }
    return $condition;
  }


  protected static function excludeProperty()
  {
    return array_merge(["created_at"], explode(",", self::getPrimaryKey()));
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


  protected function getDatabaseProperties($isUpdate = true)
  {
    $properties = json_decode(json_encode($this), true);


    $excludedProperties = $this->excludeProperty();


    if (!$isUpdate) {
      $excludedProperties = array_diff($excludedProperties, array_merge(explode(",", $this->getPrimaryKey())));
      $excludedProperties[] = 'updated_at';
    }

    $database_properties = [];


    foreach ($properties as $key => $value) {
      if (in_array($key, $excludedProperties)) continue;
      $database_properties[$key] = $value;
    }

    return $database_properties;
  }


  public function update()
  {
    $query = Query::create(get_called_class())->update($this->getPrimaryCondition(), $this->getDatabaseProperties());
    return $query->execute();
  }


  public function save()
  {
    $condition = $this->getPrimaryCondition();
    $sql = "IF " . Query::create(get_called_class())->exists($condition)->createCommand() . " THEN " .
      Query::create(get_called_class())
      ->update($condition, $this->getDatabaseProperties())
      ->createCommand() . "; " .
      "ELSE " .
      Query::create(get_called_class())
      ->insert($this->getDatabaseProperties(false))
      ->createCommand() . "; END IF;";
    return Query::create(get_called_class())->query($sql)->execute();
  }
}
