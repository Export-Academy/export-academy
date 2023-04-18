<?php


namespace lib\app\http;

use lib\util\BaseObject;

require_once 'C:\xampp\htdocs\academy\lib\util\BaseObject.php';

class Request extends BaseObject
{


  static function instance()
  {
    return new Request();
  }

  public function params($name, $default = null)
  {
    if (isset($_GET[$name])) return $_GET[$name];
    return $default;
  }


  public function data($name, $default = null)
  {
    if (isset($_POST[$name])) return $_POST[$name];
    return $default;
  }

  public function url()
  {
    return $_SERVER['REQUEST_URI'];
  }

  public function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }
}
