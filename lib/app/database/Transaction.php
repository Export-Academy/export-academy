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

  public function execute(Query $query, $lastId = false)
  {

    try {
      $command = $this->db->prepare($query->createCommand());
      Logger::log("Executing: $command->queryString", "info");
      $command->execute();
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
      $this->rollback();
      Logger::log("ROLLBACK", "info");
      throw $ex;
    }

    return $lastId ? $this->db->lastInsertId() : $command;
  }


  public function rollback()
  {
    if ($this->db->inTransaction())
      $this->db->rollBack();
  }
}
