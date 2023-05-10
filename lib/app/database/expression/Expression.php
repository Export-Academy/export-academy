<?php


namespace lib\app\database\expression;

use lib\util\Helper;

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
          $conditions[] = Expression::instance(self::format($key, $this->alias) . " = " . Expression::instance((is_int($clause) ? $clause : (substr($clause, 0, 1) === "@" ? $clause : "'$clause'")), $this->alias)->createCommand(), $this->alias);
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
      // return (substr($this->condition, 0, 1) == '*' ? "\"" . substr($this->condition, 1) . "\"" : (empty($this->condition) ? 0 : $this->condition));
      return $this->condition;
    } else {
      return empty($this->condition) ? 0 : $this->condition;
    }
  }
}
