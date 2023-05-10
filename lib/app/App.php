<?php


namespace lib\app;

use common\controller\Controller;
use Exception;
use lib\app\auth\AuthHandler;
use lib\app\auth\interface\IAuthHandler;
use lib\app\auth\Secure;
use lib\app\database\Database;
use lib\app\Request;
use lib\app\log\Logger;
use lib\app\route\Router;
use lib\app\storage\Storage;
use lib\app\view\View;
use lib\config\Configuration;
use lib\util\BaseObject;
use lib\util\Helper;


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

  /** @var Storage */
  public $storage;

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
      'database' => Database::instance(),
      'storage' => Storage::instance()
    ]);
  }


  public function run()
  {

    try {
      $session = $this->request->startSession();
      if (!$session) return;
      View::reset();


      $router = $this->router;
      $action = $this->router->getAction();
      $user = $this->authHandler->authenticate($this->request);


      $controller = Helper::getValue("controller", $action, false);

      if (!$controller) {
        $router->route($action);
        return;
      }

      if (!class_exists($controller)) {
        Router::redirect('/academy/_404');
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
          if ($secure->requirePermission($user))
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
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "ERROR");
    }
  }
}
