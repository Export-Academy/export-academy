<?php


namespace lib\util\Helper;

use lib\util\Helper;
use ScssPhp\ScssPhp\Compiler;

require_once 'C:\xampp\htdocs\academy\lib\util\Helper.php';


class Asset
{


  /** @var View */
  public $view;

  /** @var array */
  public $css_styles = [];

  /** @var array */
  public $scss_styles = [];

  /** @var array */
  public $scripts = [];


  /** @var array */
  public $js_scripts = [];



  public function registerJsScript()
  {
  }


  public function registerJsFile()
  {
  }


  public function registerCss()
  {
  }


  public function registerScss()
  {
  }


  private function getContext()
  {
    return $this->view->context;
  }


  public function registerFile($filename, $type)
  {
    $context = $this->getContext();
    $base_dir = Helper::getAlias('@common/views/assets', "/");

    if ($context)
      $base_dir = $context->getAssetDirectory();


    $__file__ = $base_dir . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $filename;

    if (is_int(strpos($filename, "@")))
      $__file__ = Helper::getAlias($filename);

    if (!strpos($__file__, ".$type"))
      $__file__ .= ".$type";

    if (!file_exists($__file__)) return null;


    $content = file_get_contents($__file__);
    $hash = hash('md5', $content);


    if ($type == 'scss') {

      $generated_path = "web/source/css/$hash.css";
      $path = Helper::getAlias("@$generated_path", "/");

      if (file_exists($path)) return null;

      $compiler = new Compiler();
      $compliedCss = $compiler->compileString($content)->getCss();

      $file = fopen($path, "w");

      fwrite($file, $compliedCss);
      fclose($file);

      return Helper::getURL($generated_path, "/");
    } else {
      $generated_path = "web/source/" . $type . "/$hash.$type";
      $path = Helper::getAlias("@$generated_path", "/");

      if (file_exists($path)) return null;

      $success = copy($__file__, $path);
      return $success ? Helper::getURL($generated_path, "/")  : null;
    }
  }
}
