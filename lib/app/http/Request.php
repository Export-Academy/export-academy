<?php


namespace lib\app\http;

use lib\app\auth\interface\IAuthHandler;
use lib\app\auth\interface\IAuthIdentity;
use lib\util\BaseObject;
use lib\util\Helper;

class Request extends BaseObject
{
  /** @var IAuthIdentity */
  private $__identity;

  /** @var IAuthHandler */
  public $auth;


  const CSRF = "csrf_token";

  static function instance($auth)
  {
    $request = new Request(['auth' => $auth]);
    return $request;
  }



  public function params($name, $default = null)
  {
    if (isset($_GET[$name])) return $_GET[$name];
    return $default;
  }

  /**
   * Returns the current user
   *
   * @return IAuthIdentity|false Returns user, false if no user exist
   */
  public function getIdentity()
  {
    return isset($this->__identity) ? $this->__identity : false;
  }


  public function setIdentity(IAuthIdentity $identity)
  {
    $this->__identity = $identity;
  }


  public function data($name = null, $default = null)
  {
    if (isset($name)) {
      if (isset($_POST[$name])) return $_POST[$name];
      return $default;
    }
    return $_POST;
  }

  public static function path()
  {
    return $_SERVER['REDIRECT_URL'];
  }

  public static function url()
  {
    return $_SERVER['REQUEST_URI'];
  }

  public function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public static function startSession()
  {
    $session = session_start();
    if ($session) {
      $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    }
    return $session;
  }


  public static function sessionToken()
  {
    return Helper::getValue("token", $_SESSION, "");
  }
}
