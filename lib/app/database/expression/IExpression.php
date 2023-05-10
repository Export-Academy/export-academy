<?php

namespace lib\app\database\expression;

interface IExpression
{
  /**
   * @return string
   */
  public function createCommand();
}