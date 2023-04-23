<?php

namespace lib\app\auth;

use lib\app\auth\interface\IAuthIdentity;
use lib\util\BaseObject;
use lib\util\Helper;

class Secure extends BaseObject
{
  public $methods = [];
  public $controller;
  public $action;


  public $requiresAuth = [];
  public $strictNoAuth = [];


  public static function instance($config = [])
  {
    return new Secure([
      "controller" => Helper::getValue("controller", $config, null),
      "action" => Helper::getValue("action", $config, null),

      "requiresAuth" => Helper::getValue("requiresAuth", $config, []),
      "strictNoAuth" => Helper::getValue("strictNoAuth", $config, []),
      "methods" => Helper::getValue("methods", $config, []),
    ]);
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


  public function requirePermission(IAuthIdentity $identity)
  {
    return true;
  }
}
