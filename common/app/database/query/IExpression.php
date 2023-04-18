<?php

namespace common\app\database\query;



interface IExpression
{
  /**
   * @return string
   */
  public function createCommand();
}
