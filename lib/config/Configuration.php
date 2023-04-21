<?php



namespace lib\config;

use lib\util\BaseObject;


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