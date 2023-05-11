<?php


namespace common\models\base;

use common\models\base\interface\IActiveModel;
use lib\app\database\Database;
use lib\app\database\Query;
use lib\app\database\RelationalQuery;
use lib\app\database\Transaction;
use lib\util\BaseObject;
use lib\util\Helper;




/**
 * Base models are used to reference tables in the database
 * 
 * @author Joel Henry <joel.henry.023@gmail.com>
 */
abstract class BaseModel extends BaseObject implements IActiveModel
{
  /**
   * Returns instance of the model using properties provided in `config`
   *
   * @param array $config
   * @return static
   */
  public static function instance($config = [])
  {
    $className = get_called_class();
    $model = new $className($config);
    return $model;
  }

  public function __get($name)
  {
    $result = parent::__get($name);
    if ($result instanceof RelationalQuery)
      return $result->getLimit() === 1 ? $result->one() : $result->all();
    return $result;
  }

  /**
   * Returns the name of the referenced table
   *
   * @return string
   */
  public static function tableName()
  {
    return strtolower(basename(get_called_class()));
  }

  /**
   * Returns the primary key/keys the referenced table
   *
   * @return string
   */
  public static function getPrimaryKey()
  {
    return "id";
  }

  /**
   * Returns the primary conditions as an associative array of the referenced table
   *
   * @return array
   */
  public function getPrimaryCondition()
  {
    $keys = explode(",", $this->getPrimaryKey());
    $condition = [];
    foreach ($keys as $key) {
      $condition[$key] = is_int($this->{$key}) ? $this->{$key} : $this->{$key};
    }
    return $condition;
  }

  /**
   * Returns an array of all the abject properties that are not columns in the referenced table
   *
   * @return string[]
   */
  protected static function excludeProperty()
  {
    return array_merge(["created_at"], explode(",", self::getPrimaryKey()));
  }

  /**
   * Return a Query instance based on provided condition if condition is provided returns 
   * a query to get all instance in the referenced table
   *
   * @param boolean|array $condition
   * @return Query
   */
  public static function find($condition = false)
  {
    $query = Query::create(get_called_class());
    return $condition ? $query->where($condition) : $query;
  }

  /**
   * Returns a single instance of the referenced table matching the provided condition
   *
   * @param boolean $condition
   * @return static
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

  /**
   * Gets the properties that are columns in the referenced table
   *
   * @param boolean $isUpdate 
   * @return void
   */
  protected function getDatabaseProperties($isUpdate = true)
  {
    $properties = $this->toArray();


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

  /**
   * Executes query to update the referenced row in the table
   *
   * @param boolean $update Will update  `updated_at` column if `true`, will ignore otherwise
   * @param Transaction|null $transaction
   * @return void
   */
  public function update($update = true, Transaction &$transaction = null)
  {
    $query = Query::create(get_called_class())->update($this->getPrimaryCondition(), $this->getDatabaseProperties(), $update);

    if ($transaction) {
      $transaction->execute($query);
      return;
    }
    $query->run();
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

    $query = Query::create(get_called_class())->query($sql);
    $query->run($transaction);
  }

  public function delete(Transaction &$transaction = null)
  {
    $condition = $this->getPrimaryCondition();
    $query = Query::create(get_called_class())->delete($condition);
    $query->run($transaction);
  }

  public static function deleteByCondition($condition, Transaction &$transaction = null)
  {
    $query = Query::create(get_called_class())->delete($condition);
    $query->run($transaction);
  }

  /**
   * Delete all entries provided
   *
   * @param BaseModel[] $models
   * @return \PDOStatement|false  
   */
  public static function deleteAll($models, Transaction &$transaction = null)
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
