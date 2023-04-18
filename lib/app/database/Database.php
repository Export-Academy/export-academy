<?php


namespace lib\app\database;

use lib\util\Helper;
use lib\config\Configuration;
use lib\util\BaseObject;
use PDO;
use PDOException;

require_once Helper::getAlias('@lib\config\Configuration.php');
require_once  Helper::getAlias('@lib\util\BaseObject.php');

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


  /**
   * Instance of PDO
   *
   * @return PDO
   */
  public function handler()
  {
    if (isset($this->_handler)) return  $this->_handler;
    return $this->_handler;
  }
}
