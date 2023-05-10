<?php

namespace lib\app\database\expression;

use lib\util\Helper;

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
