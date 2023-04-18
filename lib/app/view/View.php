<?php

namespace lib\app\view;

use common\controller\Controller;
use lib\util\Helper;

require_once Helper::getAlias('@common\controller\Controller.php');

class View
{

  /** @var Controller */
  public $context;


  public static function instance($context = null)
  {
    return Helper::createObject(['context' => $context], self::class);
  }


  public function render(string $__file__, array $params = [])
  {
    if (!strpos($__file__, '.php'))
      $__file__ .= '.php';

    $content = $this->generateContent($__file__, $params);
    return $this->renderContent($content);
  }


  public function renderContent($content)
  {
    echo $content;
  }


  public function generateContent($__file__, $__params__ = [])
  {
    if (!strpos($__file__, '.php'))
      $__file__ .= '.php';
    ob_start();
    ob_clean();
    extract($__params__, EXTR_OVERWRITE);

    if (!is_file($__file__)) return false;

    try {
      require $__file__;
      $content = ob_get_contents();

      ob_end_clean();
      return $content;
    } catch (\Exception $e) {
      ob_end_clean();
      return false;
    }
  }
}
