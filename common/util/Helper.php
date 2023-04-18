<?php


namespace common\util;

use stdClass;

class Helper
{

  public static function aliases($alias)
  {

    $path  = false;
    switch ($alias) {
      case '@admin':
        $path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REDIRECT_HTTP_BASE_PATH'] . DIRECTORY_SEPARATOR . 'admin';
        break;

      case '@site':
        $path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REDIRECT_HTTP_BASE_PATH'] . DIRECTORY_SEPARATOR . 'site';
        break;

      case '@common':
        $path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REDIRECT_HTTP_BASE_PATH'] . DIRECTORY_SEPARATOR . 'common';
        break;

      case '@vendor':
        $path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REDIRECT_HTTP_BASE_PATH'] . DIRECTORY_SEPARATOR . 'vendor';
        break;

      default:
        $path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REDIRECT_HTTP_BASE_PATH'];
        break;
    }

    return $path;
  }



  static function getAlias($alias)
  {
    if (strpos($alias, '@') !== 0) {
      // not an alias
      return $alias;
    }

    $pos = strpos($alias, DIRECTORY_SEPARATOR);


    $root = $pos === false ? $alias : substr($alias, 0, $pos);

    $path = static::aliases($root);

    if ($path) {
      return $pos === false ? $path : $path . substr($alias, $pos);
    }

    return false;
  }


  public static function createObject(array $values, $class = null)
  {
    $object = isset($class) ? new $class : new stdClass();

    foreach ($values as $key => $value) {
      $object->{$key} = $value;
    }
    return $object;
  }


  public static function configure(object $object, $properties)
  {
    foreach ($properties as $name => $value) {
      $object->$name = $value;
    }

    return $object;
  }


  public static function getValue($key, $array, $default = null)
  {
    if (isset($array[$key])) return $array[$key];
    return $default;
  }
}
