<?php

namespace common\models\resource\format;

use common\models\base\BaseModel;
use Exception;
use lib\app\log\Logger;
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
    Logger::log("$className does's not exist", "error");
    throw new Exception("$className does's not exist");
  }

  public function getSupportedFiles()
  {
    return implode(", ", array_map(function ($type) {
      return ".$type";
    }, explode(",", $this->name)));
  }


  public static function getTypeOptions()
  {
    $formats = self::find()->all();
    $options = ["" => "All Files"];

    foreach ($formats as $format) {
      $basename = preg_split('/(?=[A-Z])/', basename($format->handler))[1];
      $options[$format->supportedFiles] = "$basename Files";
    }

    return $options;
  }
}
