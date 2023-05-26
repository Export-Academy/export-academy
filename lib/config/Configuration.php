<?php



namespace lib\config;

use lib\util\BaseObject;
use lib\util\Helper;

class Configuration extends BaseObject
{
  public static function get(string $key, $default = null)
  {
    $base = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['HTTP_BASE_PATH'];
    $json = file_get_contents($base . DIRECTORY_SEPARATOR . ("configuration.json"));
    $config = json_decode($json, true);
    return Helper::getValue($key, $config ?? [], $default);
  }
}