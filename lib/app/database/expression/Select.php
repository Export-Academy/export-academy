<?php

namespace lib\app\database\expression;

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
        } else if (strpos($selection, "@") === 0) {
          return substr($selection, 1);
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
