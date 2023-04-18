<?php


namespace lib\app;

use lib\app\database\Database;
use lib\app\http\Request;
use lib\app\router\Router;
use lib\util\BaseObject;
use lib\util\Helper;



require_once Helper::getAlias('@lib\app\http\Request.php');
require_once Helper::getAlias('@lib\app\router\Router.php');
require_once Helper::getAlias('@lib\app\database\Database.php');
require_once Helper::getAlias('@lib\util\BaseObject.php');


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
