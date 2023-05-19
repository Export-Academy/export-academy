<?php

use lib\app\App;

$base = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['HTTP_BASE_PATH'];
require_once str_replace('\\', DIRECTORY_SEPARATOR, $base . DIRECTORY_SEPARATOR . 'vendor/autoload.php');

spl_autoload_register(
  function ($className) use ($base) {
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $base . DIRECTORY_SEPARATOR . $className) . '.php';
    if (file_exists($filename)) {
      require_once $filename;
      return true;
    }
    return false;
  }
);

$app = App::instance(['site', 'admin', 'web']);
$app->run();