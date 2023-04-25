<?php


namespace lib\app\database;

use lib\util\BaseObject;

class Transaction extends BaseObject
{
  /** @var Query[] */
  private $operations = [];

  public function runQuery(Query $query)
  {
    $this->operations[] = $query;
    return $this;
  }


  public function execute()
  {
    return $this->operations;
  }
}
