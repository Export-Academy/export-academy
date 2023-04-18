<?php



namespace lib\config;

use lib\util\BaseObject;
use lib\util\Helper;

require_once  Helper::getAlias('@lib\util\BaseObject.php');


class Configuration extends BaseObject
{
  public static function get(string $key, $default = null)
  {
    $params = [
      'database' => [
        'server' => 'localhost',
        'username' => 'system',
        'password' => 'password123',
        'db' => 'export-academy'
      ]
    ];
    return isset($params[$key]) ? $params[$key] : $default;
  }
}
