<?php

namespace lib\app\view;

use common\controller\Controller;
use lib\util\Helper;
use lib\util\html\HtmlHelper;

require_once Helper::getAlias('@common\controller\Controller.php');

class View
{

  const POS_HEAD = 'HEAD';
  const POS_LOAD = 'LOAD';
  const POS_END = 'END';



  /** @var Controller */
  public $context;


  private $scripts = [];
  private $stylesheets = [];
  private $jsFiles = [];


  public $title;



  public function setTitle($title)
  {
    $this->title = $title;
  }


  public static function instance($context = null)
  {
    return Helper::createObject(['context' => $context], self::class);
  }


  public function render(string $__file__, array $params = [], $render = false)
  {
    if (!strpos($__file__, '.php'))
      $__file__ .= '.php';

    $content = $this->generateContent($__file__, $params);
    if ($render) {
      $this->renderContent($content);
    } else {
      return $this->renderContent($content);
    }
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


  public function registerJsFile($__file__, $pos = self::POS_END)
  {
    $this->jsFiles[$pos][] = $__file__;
  }


  public function registerCssFile($__file__)
  {
    $this->stylesheets[] = $__file__;
  }


  public function registerJs($__file__, $pos = self::POS_END)
  {
    $this->scripts[$pos][] = $__file__;
  }

  public function registerFile($filename, $type = 'js')
  {
    $base_directory = $this->context ? $this->context->getAssetDirectory() : Helper::getAlias('@common/views/assets', "/");
    $__file__ =  strpos($filename, "@") ? Helper::getAlias($filename) : $base_directory . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $filename;

    if (!strpos($__file__, ".$type"))
      $__file__ .= ".$type";

    if (!file_exists($__file__)) return null;


    $content = file_get_contents($__file__);
    $hash = hash('md5', $content);

    $generated_path = "web/source/$type/$hash.$type";
    $path = Helper::getAlias("@$generated_path", "/");

    $success = copy($__file__, $path);
    return $success ? Helper::getURL($generated_path, "/")  : null;
  }

  public function renderPosition($pos)
  {

    if ($pos == self::POS_HEAD) {
      foreach ($this->stylesheets as $styles) {
        $path = $this->registerFile($styles, 'css');
        if (!isset($path)) continue;
        echo HtmlHelper::linkTag($path);
      }
    }
    $js_files = Helper::getValue($pos, $this->jsFiles, []);

    foreach ($js_files as $file) {
      $path = $this->registerFile($file, 'js');
      if (!isset($path)) continue;
      echo HtmlHelper::tag('script', '', ['src' => $path, 'type' => 'text/javascript']);
    }

    $scripts = Helper::getValue($pos, $this->scripts, []);
    foreach ($scripts as $script) {
      echo HtmlHelper::tag('script', $script);
    }
  }
}