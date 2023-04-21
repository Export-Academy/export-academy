<?php


namespace lib\app\database;

use lib\app\database\query\Condition;
use lib\app\database\query\Expression;
use lib\app\database\query\IExpression;
use lib\app\database\query\OnCondition;
use lib\app\database\query\Select;
use common\models\base\BaseModel;
use lib\util\BaseObject;
use lib\util\Helper;
use Exception;
use PDO;

require_once Helper::getAlias('@lib\app\database\query\IExpression.php');
require_once Helper::getAlias('@lib\app\database\query\Expressions.php');


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


  public $model;


  public function init()
  {
    $this->database = Database::instance();
  }


  public static function create($className, $alias = null)
  {
    try {
      $instance = Helper::createObject([], $className);
      if ($instance instanceof BaseModel) {
        return (new Query(['model' => $className]))->from($instance->tableName(), $alias);
      }
    } catch (Exception $ex) {
      throw new Exception('Not Instance of ' . BaseModel::class);
    }
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
          $joins[] = OnCondition::instance($cond['conditions'], $cond['tableName'], $this->alias ?? $this->tableName, $key);
        } else {
          $joins[] = OnCondition::instance([$cond['conditions']], $cond['tableName'], $this->alias ?? $this->tableName, $key);
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

  public function createCommand()
  {
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


  public function all()
  {
    $all = $this->execute()->fetchAll(PDO::FETCH_ASSOC);
    if ($all)
      return isset($this->model) ? array_map(function ($data) {
        return Helper::createObject($data, $this->model);
      }, $all) : $all;

    return null;
  }


  public function one()
  {
    $one = $this->execute()->fetch(PDO::FETCH_ASSOC);
    if ($one)
      return isset($this->model) ? Helper::createObject($one, $this->model) : $one;
    return null;
  }

  public function execute()
  {

    $sql = $this->createCommand();
    $params = [];

    $db = $this->database->handler();

    $db->beginTransaction();
    $query = $db->prepare($sql, $params);

    if (!$query) return null;

    $db->commit();
    $query->execute();

    return $query;
  }
}