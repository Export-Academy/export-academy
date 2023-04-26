<?php


namespace lib\app\database;

use Exception;
use lib\app\log\Logger;
use lib\config\Configuration;
use lib\util\BaseObject;
use PDO;
use PDOException;



class Database extends BaseObject
{

  /** @var PDO */
  private $_handler = null;


  public $server;
  public $username;
  public $password;
  public $db;


  /**
   * Creates a database Instance
   *
   * @return Database
   */
  public static function instance()
  {
    $instance = new Database(Configuration::get('database', []));
    return $instance;
  }


  function init()
  {
    try {
      $this->_handler = new PDO("mysql:host=$this->server;dbname=$this->db", "$this->username", "$this->password");
      $this->_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $ex) {
      die("<br/> Unable to connect to the database: <br/>" . $ex->getMessage());
    }
  }

  public function execute(Query $query)
  {
    $db = $this->_handler;

    try {
      $command = $db->prepare($query->createCommand());
      $command->execute();
      Logger::log("Executed: $command->queryString", "info");
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
      $db->rollBack();
    }

    return $command;
  }


  public function transaction($transaction)
  {
    /** @var Query[] $queries */
    $queries = call_user_func_array($transaction, [new Transaction()]);

    $db = $this->_handler;



    try {
      $db->beginTransaction();

      Logger::log("STARTING TRANSACTION", "info");

      foreach ($queries as $query) {
        $command = $db->prepare($query->createCommand());
        $command->execute();
        Logger::log("Executed: $command->queryString", "info");
      }


      $db->commit();
      Logger::log("COMMITTING TRANSACTION", "info");
    } catch (Exception $ex) {

      Logger::log("ROLLBACK TRANSACTION", "info");
      $db->rollBack();

      Logger::log($ex->getMessage(), "error");
    }

    return $command;
  }
}
