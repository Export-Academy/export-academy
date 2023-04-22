<?php


namespace lib\app;

use common\controller\Controller;
use lib\app\auth\AuthHandler;
use lib\app\auth\interface\IAuthHandler;
use lib\app\auth\Secure;
use lib\app\database\Database;
use lib\app\http\Request;
use lib\app\router\Router;
use lib\config\Configuration;
use lib\util\BaseObject;
use lib\util\Helper;



require_once Helper::getAlias('@lib\config\Configuration.php');
require_once Helper::getAlias('@lib\app\http\Request.php');
require_once Helper::getAlias('@lib\app\router\Router.php');
require_once Helper::getAlias('@lib\app\database\Database.php');
require_once Helper::getAlias('@lib\app\auth\interface\IAuthHandler.php');
require_once Helper::getAlias('@lib\app\auth\AuthHandler.php');
require_once Helper::getAlias('@lib\app\auth\Secure.php');


class App extends BaseObject
{
  /** @var Request */
  public $request;

  public $response;

  /** @var IAuthHandler */
  public $authHandler;


  public $schema;

  /** @var Router */
  public $router;
  public $logger;

  /** @var Database */
  public $database;

  /**
   * Undocumented function
   *
   * @return App
   */
  static function instance($modules = [])
  {
    $authHandler = new AuthHandler(Configuration::get('auth', []));
    $request = Request::instance($authHandler);
    $router = Router::instance($request, $modules);
    return new App([
      'request' => $request,
      'response' => null,
      'authHandler' => $authHandler,
      'schema' => null,
      'router' => $router,
      'database' => Database::instance()
    ]);
  }


  public function run()
  {
    $session = session_start();
    if (!$session) return;


    $router = $this->router;
    $action = $this->router->getAction();
    $user = $this->authHandler->authenticate($this->request);


    $controller = Helper::getValue("controller", $action, false);

    if (!$controller) {
      $router->route($action);
      return;
    }

    $instance = new $controller;

    if (!$instance instanceof Controller) {
      $router->route($action);
      return;
    }

    $secureConfig = array_merge($instance->secure(), $action);
    $secure = Secure::instance($secureConfig);


    if ($secure->requiresAuth()) {
      if ($user->isAuthenticated()) {
        if (!$secure->requirePermission($user))
          $this->authHandler->forbid();
        $this->router->route($action);
      } else {
        $this->authHandler->challenge($user);
      }
    } else if ($secure->requiresNoAuth() && $user->isAuthenticated()) {
      $this->authHandler->handleAuthenticatedRedirect($user);
    } else {
      $this->router->route($action);
    }
  }
}
