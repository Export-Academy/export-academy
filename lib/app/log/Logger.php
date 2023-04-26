<?php

namespace lib\app\log;

use DateTime;
use lib\config\Configuration;
use lib\util\BaseObject;
use lib\util\Helper;

class Logger extends BaseObject
{

  public $filename;

  public static function log($message, $state = "debug")
  {
    $config_options = Configuration::get("logger", []);
    $logger = new Logger($config_options);
    $logger->output($message, $state);
  }


  private function output($content, $state)
  {
    $path = Helper::getAlias("@common/runtime/logs/$this->filename", "/");
    $file = fopen($path, "a");

    if (!$file)
      return;


    if (is_array($content) || is_object($content)) {
      $content = print_r($content, true);
    }
    $message = "[" . (new DateTime())->format("D, d M Y H:i:s. u") . "] [" . strtoupper($state) . "] -- $content \n";
    fwrite($file, $message);
    fclose($file);
  }
}
