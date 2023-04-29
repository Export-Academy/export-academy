<?php

namespace lib\app\view;

use lib\app\view\interface\IViewable;
use lib\util\BaseObject;
use lib\util\Helper;
use lib\util\html\Html;
use ScssPhp\ScssPhp\Compiler;

require_once Helper::getAlias('@common\controller\Controller.php');

class View extends BaseObject
{

  const POS_HEAD = 'HEAD';
  const POS_LOAD = 'LOAD';
  const POS_END = 'END';

  /** @var IViewable */
  public $context;

  /** @var string */
  public $title;


  /** @var array */
  public $css_styles = [];

  /** @var array */
  public $scss_styles = [];

  /** @var array */
  public $scripts = [];


  /** @var array */
  public $js_scripts = [];


  public static function instance($context = null)
  {
    return new View(['context' => $context]);
  }



  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function renderContent($content)
  {
    echo $content;
  }

  public function render(string $__file__, array $params = [])
  {
    if (!strpos($__file__, '.php'))
      $__file__ .= '.php';

    $content = $this->generateContent($__file__, $params);
    $this->renderContent($content);
  }

  public function generateContent($__file__, $__params__ = [])
  {
    if (!strpos($__file__, '.php'))
      $__file__ .= '.php';
    ob_start();
    ob_clean();
    extract($__params__, EXTR_OVERWRITE);

    if (!is_file($__file__)) {
      echo ("View file was not found: $__file__");
      return false;
    }

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


  public function registerJsFile($__file__, $pos = self::POS_LOAD)
  {
    $this->js_scripts[$pos][] = $__file__;
  }


  public function registerCssFile($__file__)
  {
    $this->css_styles[] = $__file__;
  }

  public function registerSCSSFile($__file__)
  {
    $this->scss_styles[] = $__file__;
  }


  public function registerJs($script, $pos = self::POS_LOAD)
  {
    $this->scripts[$pos][] = $script;
  }

  public function registerFile($filename, $type = 'js')
  {

    $base_directory = Helper::getAlias('@common/views/assets', "/");

    if (isset($this->context))
      $base_directory = $this->context->getAssetDirectory();



    $__file__ =  $base_directory . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $filename;

    if (strpos($filename, '@') === 0)
      $__file__ =  Helper::getAlias($filename);


    if (!strpos($__file__, ".$type"))
      $__file__ .= ".$type";



    if (!file_exists($__file__)) {
      echo "File doesn't exist: $__file__ <br>";
      return null;
    }

    $content = file_get_contents($__file__);
    $hash = hash('md5', $content);


    if ($type == 'scss') {

      $generated_path = "web/source/css/$hash.css";
      $path = Helper::getAlias("@$generated_path", "/");


      if (file_exists($path)) {
        return null;
      }

      $compiler = new Compiler();
      $compliedCss = $compiler->compileString($content)->getCss();
      $file = fopen($path, "w");

      fwrite($file, $compliedCss);
      fclose($file);

      return Helper::getURL($generated_path, "/");
    } else {
      $generated_path = "web/source/" . $type . "/$hash.$type";
      $path = Helper::getAlias("@$generated_path", "/");

      if (file_exists($path)) {
        return null;
      }

      $success = copy($__file__, $path);

      if (!$success) {
        echo "Failed to copy file $__file__ to $path";
        return null;
      }


      return Helper::getURL($generated_path, "/");
    }
  }

  public function renderPosition($pos)
  {
    $renders = [];
    if ($pos == self::POS_HEAD) {
      foreach ($this->css_styles as $styles) {
        $path = $this->registerFile($styles, 'css');
        if (!isset($path)) continue;
        $renders[] = Html::linkTag($path);
      }
      foreach ($this->scss_styles as $styles) {
        $path = $this->registerFile($styles, 'scss');
        if (!isset($path)) continue;
        $renders[] = Html::linkTag($path);
      }
    }
    $js_files = Helper::getValue($pos, $this->js_scripts, []);

    foreach ($js_files as $file) {
      $path = $this->registerFile($file, 'js');
      if (!isset($path)) continue;
      $renders[] = $pos == self::POS_LOAD ? $path :  Html::tag('script', '', ['src' => $path, 'type' => 'text/javascript']);
    }

    $scripts = Helper::getValue($pos, $this->scripts, []);
    foreach ($scripts as $script) {
      $renders[] = $pos == self::POS_LOAD ? ["content" => $script] : Html::tag('script', $script);
    }


    if ($pos === View::POS_LOAD) {
      return $renders;
    } else {
      echo implode("\n", $renders);
    }
  }


  public static function reset()
  {
    $js_dir = Helper::getAlias("@web\source\css");
    $css_dir = Helper::getAlias("@web\source\js");

    self::delete($js_dir);
    self::delete($css_dir);
  }


  private static function delete($dir)
  {
    $dir_handle = opendir($dir);
    while ($file = readdir($dir_handle)) {
      $file = $dir . '\\' . $file;
      if (is_dir($file)) continue;
      if (file_exists($file))
        unlink($file);
    }
    closedir($dir_handle);
  }
}
