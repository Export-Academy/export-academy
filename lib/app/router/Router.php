<?php

namespace lib\app\router;

use lib\app\http\Request;
use common\controller\BaseController;
use lib\app\log\Logger;
use lib\util\BaseObject;
use lib\util\Helper;

class Router extends BaseObject
{

  const _404 = 'action404';
  const _500 = 'action500';
  const _401 = 'action401';

  public $modules = [];
  public $defaultModule;
  public $request;
  public $publicModule;

  static function instance(Request &$request, $modules = [], $defaultModule = 'common', $publicModule = 'web')
  {
    return new Router(array_merge(['modules' => $modules, 'defaultModule' => $defaultModule, 'request' => $request, 'publicModule' => $publicModule]));
  }



  public function getAction()
  {
    $request_url = $this->request->path();

    $request_url_sections = explode('/', $request_url);


    $modules = array_intersect($request_url_sections, $this->modules);
    $module = $this->defaultModule;

    if (!empty($modules))
      $module = array_values($modules)[0];

    $action = $controller = $param = false;


    if ($module == $this->defaultModule) {
      if (isset($request_url_sections[2])) {
        $controller = 'BaseController';
        if (!empty($request_url_sections[2]))
          $action = 'action' . str_replace('_', '', ucwords($request_url_sections[2], '_'));
      } else {
        $action = 'actionIndex';
      }
    } else {
      if (isset($request_url_sections[3])) {
        if (!empty($request_url_sections[3]))
          $controller = str_replace('_', '', ucwords($request_url_sections[3], '_') . 'Controller');
      }

      if (isset($request_url_sections[4])) {
        if (!empty($request_url_sections[4]))
          $action = 'action' . str_replace('_', '', ucwords($request_url_sections[4], '_'));
      } else {
        $action = 'actionIndex';
      }

      if ($module == $this->publicModule) {
        $param = isset($request_url_sections[5]) ? $request_url_sections[5] : false;
      }
    }

    if ($controller)
      $controller = $module . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $controller;



    return [
      'module' => $module,
      'controller' => $controller,
      'action' => $action ? $action : "actionIndex",
      'param' => $param
    ];
  }





  public function route($context = false)
  {

    if (!$context)
      $context = $this->getAction();


    $controller = Helper::getValue('controller', $context, null);
    $module = Helper::getValue('module', $context, null);
    $action = Helper::getValue('action', $context, null);
    $param = Helper::getValue('param', $context, null);

    try {
      if (class_exists($controller)) {
        $instance = Helper::createObject([
          'request' => $this->request,
          'module' => "@{$module}"
        ], $controller);


        $action = [$instance, $action ? $action : 'actionIndex'];


        if (is_callable($action)) {
          call_user_func_array($action, $module == $this->publicModule ? [$param ? $param : 'index.js'] : []);
        } else {
          $this->route([
            'module' => $this->defaultModule,
            'controller' => BaseController::class,
            'action' => self::_404
          ]);
        }
      } else {
        $this->route([
          'module' => $this->defaultModule,
          'controller' => BaseController::class,
          'action' => self::_404
        ]);
      }
    } catch (\Exception $ex) {
      $this->route([
        'module' => $this->defaultModule,
        'controller' => BaseController::class,
        'action' => self::_500
      ]);
      Logger::log($ex->getMessage(), "error");
    }
  }


  public static function redirect($path, $permanent = true)
  {
    header("Location: " . $path, true, $permanent ? 301 : 302);
    die();
  }
}
