<?php

namespace common\models\resource\format;

use common\models\base\BaseModel;
use lib\app\view\View;

class Format extends BaseModel
{
  public $id;
  public $name;
  public $handler;



  public function handlerInstance(View $view)
  {
    $className = $this->handler;
    if (class_exists($className))
      return $className::instance($view);
    return null;
  }
}
