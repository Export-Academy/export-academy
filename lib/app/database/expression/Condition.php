<?php

namespace lib\app\database\expression;

use lib\util\Helper;

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
