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
      'storage' => [
        "apiKey" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5heGFwdW52Y3J4enBocWFndXJwIiwicm9sZSI6ImFub24iLCJpYXQiOjE2ODM2NTc4NDAsImV4cCI6MTk5OTIzMzg0MH0.bq774noOet18sSrInBYzvS6Elg_ckoUa01cENio8Cfo",
        "reference" => "naxapunvcrxzphqagurp"
      ]
    ];
    return isset($params[$key]) ? $params[$key] : $default;
  }
}