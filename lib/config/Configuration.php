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
      ],
      'auth' => [
        "challengePath" => "/academy/login",
        "forbiddenPath" => "/academy/_401",
        "redirectPath" => "/academy/admin/dashboard/"
      ],
      'logger' => [
        "filename" => "app.log"
      ],
    ];
    return isset($params[$key]) ? $params[$key] : $default;
  }
}
