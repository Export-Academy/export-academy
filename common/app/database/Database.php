<?php


namespace common\app\database;

use common\config\Configuration;
use common\util\BaseObject;
use PDO;
use PDOException;

require_once 'C:\xampp\htdocs\academy\common\config\Configuration.php';
require_once  'C:\xampp\htdocs\academy\common\util\BaseObject.php';

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
