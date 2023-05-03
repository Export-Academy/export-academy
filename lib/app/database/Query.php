<?php


namespace lib\app\database;

use lib\app\database\query\Condition;
use lib\app\database\query\Expression;
use lib\app\database\query\IExpression;
use lib\app\database\query\OnCondition;
use lib\app\database\query\Select;
use common\models\base\BaseModel;
use DateTime;
use lib\util\BaseObject;
use lib\util\Helper;
use Exception;
use PDO;




class Query extends BaseObject implements IExpression
{

  const DESC = 'DESC';
  const ASC = 'ASC';

  private $selectOptions = [];
  private $conditions = [
    Condition::AND => [],
    Condition::OR => [],
  ];
  private $onConditions = [
    OnCondition::DEFAULT => [],
    OnCondition::LEFT => [],
    OnCondition::INNER => [],
    OnCondition::RIGHT => []
  ];
  private $_limit = null;
  private $orderColumns = [];
  private $order = self::DESC;
  private $tableName, $alias = null;

  /** @var Database */
  private $database;


  private $query;


  public $model;


  public function init()
  {
    $this->database = Database::instance();
  }


  public static function create($className, $alias = null)
  {
    try {
      $instance = new $className;
      if ($instance instanceof BaseModel) {
        return (new Query(['model' => $className]))->from($instance->tableName(), $alias);
      }
    } catch (Exception $ex) {
      throw new Exception('Not Instance of ' . BaseModel::class);
    }
  }

  public function count()
  {
    $arr = $this->all();
    return count($arr);
  }

  public function select($select)
  {
    $this->selectOptions = is_array($select) ? $select : explode(',', $select);
    return $this;
  }


  public function from($tableName, $alias = null)
  {
    $this->tableName = $tableName;
    if ($alias) $this->alias = $alias;
    return $this;
  }


  public function where($condition)
  {
    $this->conditions[Condition::AND][] = $condition;
    return $this;
  }


  public function andWhere($condition)
  {
    $this->conditions[Condition::AND][] = $condition;
    return $this;
  }


  public function orWhere($condition)
  {
    $this->conditions[Condition::OR][] = $condition;
    return $this;
  }


  public function limit(int $limit)
  {
    $this->_limit = $limit;
    return $this;
  }


  public function join($tableName, $condition)
  {
    $this->onConditions[OnCondition::DEFAULT][] = [
      'tableName' => $tableName,
      'conditions' => $condition
    ];

    return $this;
  }


  public function leftJoin($tableName, $condition)
  {
    $this->onConditions[OnCondition::LEFT][] = [
      'tableName' => $tableName,
      'conditions' => $condition
    ];

    return $this;
  }

  public function innerJoin($tableName, $condition)
  {
    $this->onConditions[OnCondition::INNER][] = [
      'tableName' => $tableName,
      'conditions' => $condition
    ];

    return $this;
  }


  public function rightJoin($tableName, $condition)
  {
    $this->onConditions[OnCondition::RIGHT][] = [
      'tableName' => $tableName,
      'conditions' => $condition
    ];

    return $this;
  }


  public function orderBy($columns, $order = self::DESC)
  {
    $columns = is_array($columns) ? $columns : explode(',', $columns);
    $this->orderColumns = array_merge($columns, $this->orderColumns);
    $this->order = $order;

    return $this;
  }


  public function query($sql)
  {
    $this->query = $sql;
    return $this;
  }


  public function update($conditions, $data = [], $update = true)
  {
    $setters = [];

    foreach ($data as $key => $value) {

      if (in_array($key, ["updated_at"])  && $update) {
        $now = new DateTime("now", Database::timezone());
        $setters[] = "`$key` = '" . $now->format("Y-m-d H:i:s") . "'";
        continue;
      }

      $setters[] = "`$key` = " . (isset($value) ? (is_int($value) ? $value : "'$value'") : "NULL");
    }
    $condition_statement = $this->where($conditions)->generateWhereCondition();
    $sql = "UPDATE `$this->tableName` SET " . implode(", ", $setters) . " $condition_statement";
    return $this->query($sql);
  }

  public function delete($condition)
  {
    $sql = "DELETE FROM `" . $this->tableName . "` " . $this->where($condition)->generateWhereCondition();
    return $this->query($sql);
  }


  public function insert($data)
  {
    $keys = [];
    $values = [];

    foreach ($data as $key => $value) {
      if (!isset($value)) continue;

      $keys[] = "`$key`";
      $values[] = (is_int($value) ? $value : "'$value'");
    }

    $sql = "INSERT INTO `$this->tableName` (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $values) . ")";
    return $this->query($sql);
  }


  public function exists($conditions)
  {
    $clause_statement = $this->where($conditions)->createCommand();
    $sql = "EXISTS ( $clause_statement )";

    return $this->query($sql);
  }



  private function generateWhereCondition()
  {
    $condition = [];

    foreach ($this->conditions as $key => $condition) {
      if (!empty($condition))
        $conditions[] = ['type' => $key, 'instance' => Helper::createObject(['conditions' => $condition, 'alias' => $this->alias ?? $this->tableName], Condition::class)];
    }

    if (empty($conditions)) return null;

    $where_condition_statement = 'WHERE ';
    foreach ($conditions as $index => $cond) {
      if ($index > 0) {
        $where_condition_statement .= $cond['type'] . " ";
      }
      $where_condition_statement .= "( " . $cond['instance']->createCommand() . " ) ";
    }

    return $where_condition_statement;
  }


  private function generateSelectCondition()
  {
    /** @var Select $select */
    $select =  Helper::createObject([
      'select' => $this->selectOptions, 'alias' => $this->alias ?? $this->tableName
    ], Select::class);
    $select_statement = "SELECT " . $select->createCommand();
    $from_statement = isset($this->alias) ? "FROM `$this->tableName`  `$this->alias`" : " FROM `$this->tableName`";


    return "$select_statement $from_statement";
  }


  private function generateOnConditionStatement()
  {
    $joins = [];

    foreach ($this->onConditions as $key => $condition) {
      foreach ($condition as $cond) {
        if (is_array($cond)) {
          $joins[] = OnCondition::instance($cond['conditions'], $cond['tableName'], $cond['tableName'], $key);
        } else {
          $joins[] = OnCondition::instance([$cond['conditions']], $cond['tableName'], $cond['tableName'], $key);
        }
      }
    }

    if (empty($joins)) return null;
    $on_condition_statement = implode(" ", array_map(function ($join) {
      return $join->createCommand();
    }, $joins));

    return $on_condition_statement;
  }


  private function generateLimitStatement()
  {
    return isset($this->_limit) ? "LIMIT $this->_limit" : null;
  }



  private function generateOrderStatement()
  {
    return empty($this->orderColumns) ? null : "ORDER BY " . implode(', ', array_map(
      function ($column) {
        return Expression::format($column, $this->alias ?? $this->tableName);
      },
      $this->orderColumns
    )) . " $this->order";
  }

  private function getModelInstance($data = [])
  {
    if (isset($this->model))
      return $this->model::instance($data);
    return $data;
  }

  public function createCommand()
  {

    if (isset($this->query)) return $this->query;
    $statements = [$this->generateSelectCondition()];


    $on_condition_statement = $this->generateOnConditionStatement();
    if ($on_condition_statement)
      $statements[] = $on_condition_statement;


    $where_condition = $this->generateWhereCondition();
    if ($where_condition)
      $statements[] = $where_condition;


    $limit_statement = $this->generateLimitStatement();
    if ($limit_statement)
      $statements[] = $limit_statement;


    $order_statement = $this->generateOrderStatement();
    if ($order_statement)
      $statements[] = $order_statement;


    return implode(' ', $statements);
  }


  public function all(Transaction $tr = null)
  {
    $all = (isset($tr)) ? $tr->execute($this)->fetchAll(PDO::FETCH_ASSOC) : $this->database->execute($this)->fetchAll(PDO::FETCH_ASSOC);
    if ($all)
      return array_map(function ($data) {
        return $this->getModelInstance($data);
      }, $all);

    return [];
  }


  public function one(Transaction $tr = null)
  {
    $one = isset($tr) ? $tr->execute($this)->fetch(PDO::FETCH_ASSOC) : $this->database->execute($this)->fetch(PDO::FETCH_ASSOC);
    if ($one)
      return $this->getModelInstance($one);
    return null;
  }

  public function run(Transaction $tr = null)
  {
    $response = isset($tr) ? $tr->execute($this) : $this->database->execute($this);
    return $response;
  }
}
