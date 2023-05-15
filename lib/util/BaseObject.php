<?php

namespace lib\util;

use Exception;
use ReflectionClass;

/**
 * @author Joel Henry <joel.henry.023@gmail.com>
 */
class BaseObject
{



  /**
   * Configures model using the provided associative array
   *
   * @param array $config
   */
  public function __construct($config = [])
  {
    if (!empty($config)) {
      Helper::configure($this, $config);
    }
    $this->init();
  }

  public function __get($name)
  {
    $getter = 'get' . $name;
    if (method_exists($this, $getter)) {
      return $this->$getter();
    } elseif (method_exists($this, 'set' . $name)) {
      throw new Exception('Getting write-only property: ' . get_class($this) . '::' . $name);
    }

    throw new Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
  }

  /** 
   * This method is ran whenever a base object has been created 
   * 
   */
  protected function init()
  {
  }

  /**
   * Converts the object to an associative array
   *
   * @return array
   */
  public function toArray()
  {
    $ref = new ReflectionClass(get_called_class());
    $properties = $ref->getProperties();

    $model = [];

    foreach ($properties as $property) {
      $model[$property->name] = $this->{$property->name};
    }
    return $model;
  }
}
