<?php

namespace lib\app\auth;

use lib\app\auth\interface\IAuthIdentity;
use lib\util\BaseObject;
use lib\util\Helper;

class Secure extends BaseObject
{
  public $base = [];
  public $methods = [];
  public $controller;
  public $action;

  public static function instance($config = [])
  {
    return new Secure([
      "base" => Helper::getValue("base", $config, []),
      "methods" => Helper::getValue("methods", $config, []),
      "controller" => Helper::getValue("controller", $config, null),
      "action" => Helper::getValue("action", $config, null)
    ]);
  }



  public function requiresAuth()
  {
    $baseAuthRequired = Helper::getValue("auth", $this->base, false);
    if ($baseAuthRequired) return true;
    $methodSettings = Helper::getValue($this->action, $this->methods, []);
    $methodAuthRequired = Helper::getValue("auth", $methodSettings, false);
    return $methodAuthRequired;
  }

  public function requiresNoAuth()
  {
    $baseNoAuthRequired = Helper::getValue("no_auth", $this->base, false);
    if ($baseNoAuthRequired) return true;
    $methodSettings = Helper::getValue($this->action, $this->methods, []);
    $methodAuthRequired = Helper::getValue("no_auth", $methodSettings, false);
    return $methodAuthRequired;
  }


  public function requirePermission(IAuthIdentity $identity)
  {
    return true;
  }
}
