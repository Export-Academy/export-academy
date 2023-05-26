<?php


namespace lib\app;

use lib\app\auth\interfaces\IAuthHandler;
use lib\app\auth\interfaces\IAuthIdentity;
use lib\util\BaseObject;
use lib\util\Helper;

/**
 * A request object
 * 
 * @property IAuthHandler $auth
 */
class Request extends BaseObject
{
  /** @var IAuthHandler */
  public $auth;


  public static $session = false;


  const CSRF = "csrf_token";

  static function instance($auth)
  {
    $request = new Request(['auth' => $auth]);
    return $request;
  }



  public static function params($name = null, $default = null)
  {
    if (!isset($name)) return $_GET;
    if (isset($_GET[$name])) return $_GET[$name];
    return $default;
  }

  /**
   * Returns the current user
   *
   * @return IAuthIdentity|false Returns user, false if no user exist
   */
  public static function getIdentity()
  {
    return Helper::getValue("AUTH_IDENTITY", $_SESSION);
  }


  public static function setIdentity(IAuthIdentity $identity)
  {
    $_SESSION["AUTH_IDENTITY"] = $identity;
  }


  public static function data($name = null, $default = null)
  {
    if (isset($name)) {
      if (isset($_POST[$name])) return $_POST[$name];
      return $default;
    }
    return $_POST;
  }

  public static function file($name = null, $default = null)
  {
    if (isset($name)) {
      if (isset($_FILES[$name])) return $_FILES[$name];
      return $default;
    }
    return $_FILES;
  }

  public static function path()
  {
    return $_SERVER['REDIRECT_URL'];
  }

  public static function url()
  {
    return $_SERVER['REQUEST_URI'];
  }

  public static function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public static function startSession()
  {
    self::$session = session_start();
    if (self::$session) {
      $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    }
    return self::$session;
  }


  public static function add($key, $value)
  {
    if (self::$session) {
      $_SESSION[$key] = $value;
      return true;
    }
    return false;
  }


  public static function get($key, $default = null)
  {
    if (self::$session) {
      return Helper::getValue($key, $_SESSION, $default);
    }
    return $default;
  }


  public static function sessionToken()
  {
    return Helper::getValue("token", $_SESSION, "");
  }
}