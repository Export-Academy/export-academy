<?php

namespace lib\app\database\query;



interface IExpression
{
  /**
   * @return string
   */
  public function createCommand();
}
