<?php


namespace lib\util;

use stdClass;

class Helper
{

  public static function aliases($alias, $separator = DIRECTORY_SEPARATOR)
  {
    $path  = false;
    $basePath = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['HTTP_BASE_PATH'];
    switch ($alias) {
      case '@admin':
        $path =  $basePath . $separator . 'admin';
        break;

      case '@site':
        $path =  $basePath . $separator . 'site';
        break;

      case '@common':
        $path =  $basePath . $separator . 'common';
        break;

      case '@lib':
        $path =  $basePath . $separator . 'lib';
        break;

      case '@vendor':
        $path =  $basePath . $separator . 'vendor';
        break;

      case '@web':
        $path =  $basePath . $separator . 'web';
        break;

      case '@components':
        $path =  $basePath . $separator . 'components';
        break;

      default:
        $path =  $basePath;
        break;
    }

    return $path;
  }



  static function getAlias($alias, $separator = DIRECTORY_SEPARATOR)
  {
    if (strpos($alias, '@') !== 0) {
      // not an alias
      return $alias;
    }

    $pos = strpos($alias, $separator);


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


  public static function getURL($path, $separator = DIRECTORY_SEPARATOR)
  {
    return $_SERVER['HTTP_BASE_PATH'] . $separator . $path;
  }
}
