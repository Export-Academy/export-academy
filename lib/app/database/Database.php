<?php


namespace lib\app\database;

use DateTimeZone;
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

  public static function timezone()
  {
    return new DateTimeZone("UTC");
  }

  public function execute(Query $query, $lastId = false)
  {
    $db = $this->_handler;

    try {
      $command = $db->prepare($query->createCommand());
      $command->execute();

      Logger::log("Executed: $command->queryString", "info");
    } catch (Exception $ex) {
      Logger::log("Error running:  " . $query->createCommand() . "\n" . $ex->getMessage(), "error");
    }

    return $lastId ? $db->lastInsertId() : $command;
  }

  public function getLastInsertId()
  {
    return $this->_handler->lastInsertId();
  }


  public function transaction($transaction)
  {
    $tr = new Transaction($this->_handler);
    $results = null;

    try {
      /** @var mixed $results */
      $results = call_user_func_array($transaction, [$tr]);
      $this->_handler->commit();
      Logger::log("COMMIT TRANSACTION", "info");
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
      $tr->rollback();
      Logger::log("ROLLBACK", "info");
    }

    return $results;
  }
}
