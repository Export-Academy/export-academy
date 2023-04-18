<?php

namespace lib\util;

use Exception;

class BaseObject
{

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

  public function init()
  {
  }
}
