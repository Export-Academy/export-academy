<?php


namespace lib\util;

use stdClass;

/**
 * Helper functions that are used throughout the code
 * 
 * @author Joel Henry <joel.henry.023@gmail.com>
 */
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


  /**
   * Use the provided alias to return the respective pathname in the system
   *
   * @param string $alias The alias pathname of the file
   * @param string $separator The separator being used in the provided alias pathname
   * @return string Returns the path for the alias provided
   */
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

  /**
   * Creates a new object with provided associative array if a class name is provided will return an instance of the provided class
   *
   * @param array $values Associative array of the attributes of the object
   * @param string $class The class name of the object that should be created
   * @return object
   */
  public static function createObject(array $values, $class = null)
  {
    $object = isset($class) ? new $class : new stdClass();

    foreach ($values as $key => $value) {
      $object->{$key} = $value;
    }
    return $object;
  }

  /**
   * Configures the provided object with the provided properties
   *
   * @param object $object The object that should be configured
   * @param array $properties An associative array of all the values to be added to the object
   * @return object
   */
  public static function configure(object $object, $properties)
  {
    foreach ($properties as $name => $value) {
      $object->$name = $value;
    }

    return $object;
  }

  /**
   * Return the element in the array specified by key, it first checks if the element exist
   *
   * @param mixed $key The key to search for in the array
   * @param array $array The array to search
   * @param mixed $default The default value returned if the element was'nt found
   * @return mixed
   */
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
