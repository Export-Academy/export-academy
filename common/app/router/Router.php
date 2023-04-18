<?php

namespace common\router;

use common\app\http\Request;
use common\controller\BaseController;
use common\util\BaseObject;
use common\util\Helper;

require_once 'C:\xampp\htdocs\academy\common\util\Helper.php';
require_once 'C:\xampp\htdocs\academy\common\app\http\Request.php';
require_once 'C:\xampp\htdocs\academy\common\util\BaseObject.php';

class Router extends BaseObject
{

  const _404 = 'action404';
  const _500 = 'action500';
  const _401 = 'action401';

  public $modules = [];
  public $defaultModule;
  public $request;

  static function instance($modules = [], $defaultModule = 'common')
  {
    return new Router(array_merge(['modules' => $modules, 'defaultModule' => $defaultModule, 'request' => Request::instance()]));
  }



  public function getAction()
  {
    $request_url = $this->request->url();

    $request_url_sections = explode('/', $request_url);


    $modules = array_intersect($request_url_sections, $this->modules);
    $module = $this->defaultModule;

    if (!empty($modules))
      $module = array_values($modules)[0];

    $action = $controller = false;


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
    }

    if ($controller)
      $controller = $module . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $controller;



    return [
      'module' => $module,
      'controller' => $controller,
      'action' => $action
    ];
  }





  public function route($context = false)
  {

    if (!$context)
      $context = $this->getAction();


    $controller = $context['controller'];
    $module = $context['module'];
    $action = $context['action'];

    try {
      if (class_exists($controller)) {
        $instance = Helper::createObject([
          'request' => $this->request,
          'module' => "@{$module}"
        ], $controller);


        $action = [$instance, $action ? $action : 'actionIndex'];


        if (is_callable($action)) {
          call_user_func_array($action, []);
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

      echo $ex->getMessage();
    }
  }
}
