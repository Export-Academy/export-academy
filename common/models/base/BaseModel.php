<?php


namespace common\models\base;

use common\models\base\interface\IActiveModel;
use lib\app\database\Database;
use lib\app\database\Query;
use lib\app\database\RelationalQuery;
use lib\app\database\Transaction;
use lib\util\BaseObject;
use lib\util\Helper;

require_once Helper::getAlias("@common\models\base\interface\IActiveModel.php", "\\");
require_once Helper::getAlias("@lib\app\database\Query.php");
require_once Helper::getAlias("@lib\app\database\RelationalQuery.php");


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
      $condition[$key] = is_int($this->{$key}) ? $this->{$key} : "*" . $this->{$key};
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
   * @return BaseModel
   */
  public static function findOne($condition = false)
  {
    return self::find($condition)->limit(1)->one();
  }


  public function hasMany($className, $condition)
  {
    return RelationalQuery::instance($className, $condition);
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

  public function update($update = true, Transaction &$transaction = null)
  {
    $query = Query::create(get_called_class())->update($this->getPrimaryCondition(), $this->getDatabaseProperties(), $update);

    if ($transaction) {
      $transaction->execute($query);
      return;
    }
    return $query->run();
  }

  public function save(Transaction &$transaction = null)
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

    $query =  Query::create(get_called_class())->query($sql);
    return $query->run($transaction);
  }

  public function delete(Transaction &$transaction = null)
  {
    $condition = $this->getPrimaryCondition();
    $query = Query::create(get_called_class())->delete($condition);
    return $query->run($transaction);
  }

  public static function deleteByCondition($condition, Transaction &$transaction = null)
  {
    $query = Query::create(get_called_class())->delete($condition);
    return $query->run($transaction);
  }

  /**
   * Delete all entries provided
   *
   * @param BaseModel[] $models
   * @return \PDOStatement|false  
   */
  public static function deleteAll($models = [], Transaction &$transaction = null)
  {
    if ($transaction) {
      foreach ($models as $model) {
        $model->delete($transaction);
      }

      return;
    }
    $db = Database::instance();
    $response = $db->transaction(function (Transaction $tr) use ($models) {
      foreach ($models as $model) {
        $model->delete($tr);
      }
    });
    return $response;
  }

  /**
   * Update all entries provided
   *
   * @param BaseModel[] $models
   * @return \PDOStatement|false  
   */
  public static function updateAll($models, Transaction &$transaction = null)
  {

    if ($transaction) {
      foreach ($models as $model) {
        $model->update(true, $transaction);
      }

      return;
    }
    $db = Database::instance();

    $response = $db->transaction(function (Transaction $tr) use ($models) {
      foreach ($models as $model) {
        $model->update(true, $tr);
      }
    });
    return $response;
  }

  /**
   * Inserts or Updates provided
   *
   * @param BaseModel[] $models
   * @return \PDOStatement|false 
   */
  public static function saveAll($models, Transaction &$transaction = null)
  {
    if ($transaction) {
      foreach ($models as $model) {
        $model->save($transaction);
      }
      return;
    }
    $db = Database::instance();

    $response = $db->transaction(function (Transaction $tr) use ($models) {
      foreach ($models as $model) {
        $model->save($tr);
      }
    });
    return $response;
  }
}
