<?php

namespace lib\app\auth;

use lib\app\auth\interfaces\IAuthIdentity;
use lib\util\BaseObject;
use lib\util\Helper;

class Secure extends BaseObject
{
  public $methods = [];
  public $controller;
  public $action;


  public $requiresAuth = [];
  public $strictNoAuth = [];
  public $permission = [];


  public static function instance($config = [])
  {
    return new Secure($config);
  }



  public function requiresAuth()
  {
    $baseAuthRequired = in_array("*", $this->requiresAuth);
    if ($baseAuthRequired) return true;
    $methodAuthRequired = in_array($this->action, $this->requiresAuth);
    return $methodAuthRequired;
  }

  public function requiresNoAuth()
  {
    $baseNoAuthRequired = in_array("*", $this->strictNoAuth);
    if ($baseNoAuthRequired) return true;
    $methodNoAuthRequired = in_array($this->action, $this->strictNoAuth);
    return $methodNoAuthRequired;
  }


  public function hasPermission(IAuthIdentity $identity)
  {
    $controllerPermissions = Helper::getValue("*", $this->permission, []);
    $methodPermissions = Helper::getValue($this->action, $this->permission, []);


    $requiredPermissions = array_merge(is_array($controllerPermissions) ? $controllerPermissions : [$controllerPermissions], is_array($methodPermissions) ? $methodPermissions : [$methodPermissions]);

    $allowed = true;
    foreach ($requiredPermissions as $permission) {
      $isPermitted = $identity->hasPermission($permission);
      if ($isPermitted) {
        continue;
      }

      $allowed = $isPermitted;
      break;
    }
    return $allowed;
  }
}
