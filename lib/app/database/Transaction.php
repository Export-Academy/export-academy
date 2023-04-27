<?php


namespace lib\app\database;

use Exception;
use lib\app\log\Logger;
use lib\util\BaseObject;
use PDO;

class Transaction extends BaseObject
{
  /** @var PDO */
  private $db;

  public function __construct(PDO &$database)
  {
    $this->db = $database;
    $this->db->beginTransaction();
    Logger::log("BEGIN TRANSACTION", "info");
  }

  public function execute(Query $query)
  {

    try {
      $command = $this->db->prepare($query->createCommand());
      $command->execute();
      Logger::log("Executed: $command->queryString", "info");
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
      $this->rollback();
      Logger::log("ROLLBACK", "info");
    }

    return $command;
  }


  private function rollback()
  {
    $this->db->rollBack();
  }
}
