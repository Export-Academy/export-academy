<?php


namespace common\app;

use common\app\database\Database;
use common\app\http\Request;
use common\router\Router;
use common\util\BaseObject;

require_once 'C:\xampp\htdocs\academy\common\util\Helper.php';
require_once 'C:\xampp\htdocs\academy\common\app\http\Request.php';
require_once 'C:\xampp\htdocs\academy\common\app\router\Router.php';
require_once 'C:\xampp\htdocs\academy\common\app\database\Database.php';
require_once  'C:\xampp\htdocs\academy\common\util\BaseObject.php';


class App extends BaseObject
{
  /** @var Request */
  public $request;

  public $response;
  public $security;
  public $schema;

  /** @var Router */
  public $router;
  public $logger;
  public $database;

  /**
   * Undocumented function
   *
   * @return App
   */
  static function instance($modules = [])
  {
    return new App([
      'request' => Request::instance(),
      'response' => null,
      'security' => null,
      'schema' => null,
      'router' => Router::instance($modules),
      'database' => Database::instance()
    ]);
  }


  public function route()
  {
    $this->router->route();
  }
}
