<?php

namespace lib\app\database\query;

use lib\util\Helper;

class Select implements IExpression
{
  /** @var (string|array)[] */
  public $select;

  /** @var string|null */
  public $alias;

  public function createCommand()
  {
    if (is_array($this->select)) {
      return empty($this->select) ? '*' : implode(', ', array_map(function ($selection) {
        if (is_array($selection)) {


          return implode('.', array_map(
            function ($name) {
              return "`$name`";
            },
            $selection
          ));
        } else if (isset($this->alias)) {

          return "`$this->alias`.`$selection`";
        } else {

          return "`$selection`";
        }
      }, $this->select));
    } else if (is_string($this->select)) {
      return isset($this->alias) ? "`$this->alias`.`$this->select`" : "`$this->select`";
    } else {
      return '*';
    }
  }
}


class Expression implements IExpression
{

  const TRUE = 'TRUE';
  const FALSE = 'FALSE';

  public $condition = [];
  public $alias = '';

  /**
   * Return clause instance
   *
   * @param mixed $condition
   * @return Expression
   */
  public static function instance($condition, $alias)
  {
    return Helper::createObject([
      'condition' => $condition,
      'alias' => $alias
    ], self::class);
  }



  public static function format($name, $alias)
  {
    $sections = explode('.', $name);
    if (count($sections) == 1)
      return "`$alias`.`$sections[0]`";

    return implode('.', array_map(
      function ($name) {
        return "`$name`";
      },
      $sections
    ));
  }

  public function createCommand()
  {
    if (is_array($this->condition)) {

      if (in_array(Helper::getValue(0, $this->condition, false), [Condition::BETWEEN, Condition::AND, Condition::OR])) {
        $type = array_shift($this->condition);
        return Condition::instance($this->condition, $this->alias, $type)->createCommand();
      }


      $conditions = [];
      foreach ($this->condition as $key => $clause) {

        if (is_int($key)) {
          $conditions[] = self::instance($clause, $this->alias);
          continue;
        }


        if (is_array($clause)) {
          $conditions[] = Expression::instance(
            $this->format($key, $this->alias) . " IN ( " . implode(
              ', ',
              array_map(
                function ($clause) {
                  return Expression::instance($clause, $this->alias)->createCommand();
                },
                $clause
              )
            ) . " )",
            $this->alias
          );
        } else if ($clause instanceof IExpression) {
          $conditions[] = Expression::instance(self::format($key, $this->alias) . " IN ( " . $clause->createCommand() . " )", $this->alias);
        } else {
          $conditions[] = Expression::instance(self::format($key, $this->alias) . " = " . Expression::instance($clause, $this->alias)->createCommand(), $this->alias);
        }
      }
      return Condition::instance($conditions, $this->alias)->createCommand();
    } else if (is_bool($this->condition)) {
      return $this->condition ? self::TRUE : self::FALSE;
    } else if ($this->condition instanceof IExpression) {
      return $this->condition->createCommand();
    } else if (is_string($this->condition)) {
      if (substr($this->condition, 0, 1) == '@') {
        return self::format(substr($this->condition, 1), $this->alias);
      }
      return (substr($this->condition, 0, 1) == '*' ? "\"" . substr($this->condition, 1) . "\"" : (empty($this->condition) ? 0 : $this->condition));
    } else {
      return empty($this->condition) ? 0 : $this->condition;
    }
  }
}


class Condition implements IExpression
{
  const BETWEEN = 'BETWEEN';
  const AND = 'AND';
  const OR = 'OR';

  public $type = self::AND;
  public $conditions = [];
  public $alias = '';


  public static function instance($conditions, $alias, $type = null)
  {
    return Helper::createObject(['conditions' => $conditions, 'type' => $type ?? self::AND, 'alias' => $alias], self::class);
  }

  private function parseConditions($conditions, $separator = self::AND)
  {
    return implode(" $separator ", array_map(
      function ($condition) {
        return Expression::instance($condition, $this->alias)->createCommand();
      },
      $conditions
    ));
  }

  public function createCommand()
  {

    switch ($this->type) {
      case self::BETWEEN:
        return Expression::instance(Helper::getValue(0, $this->conditions, 0), $this->alias)->createCommand() .
          " BETWEEN " .
          Expression::instance(Helper::getValue(1, $this->conditions, 0), $this->alias)->createCommand() .
          " AND " .
          Expression::instance(Helper::getValue(2, $this->conditions, 0), $this->alias)->createCommand();

      case self::OR:
        return $this->parseConditions($this->conditions, self::OR);

      case self::AND:
      default:
        return $this->parseConditions($this->conditions);
    }
  }
}


class OnCondition implements IExpression
{
  const LEFT = 'LEFT JOIN';
  const RIGHT = 'RIGHT JOIN';
  const INNER = 'INNER JOIN';
  const DEFAULT = 'JOIN';

  public $type;
  public $conditions = [];
  public $alias = null;
  public $tableName;


  public static function instance($conditions, $tableName, $alias, $type = self::LEFT)
  {
    return Helper::createObject([
      'conditions' => $conditions,
      'tableName' => $tableName,
      'alias' => $alias,
      'type' => $type
    ], self::class);
  }

  public function createCommand()
  {
    $referenceTable = is_array($this->tableName) ? implode(' ', array_map(function ($name) {
      return "`$name`";
    }, $this->tableName)) : "`$this->tableName`";

    $conditions = Expression::instance($this->conditions, $this->alias)->createCommand();
    return "$this->type $referenceTable ON $conditions";
  }
}
