<?php

namespace lib\app\view;

use lib\app\view\interface\IViewable;
use lib\util\BaseObject;
use lib\util\Helper;
use lib\util\html\Html;
use ScssPhp\ScssPhp\Compiler;

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



  /** @var View[] */
  public $registeredViews = [];


  public static function instance($context)
  {
    return new View(['context' => $context]);
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function render(string $filename, array $params = [])
  {
    $content = $this->generateContent($filename, $params);
    $this->renderContent($content);
  }

  public function renderContent($content)
  {
    echo $content;
  }

  public function generateContent($filename, $__params__ = [], $base_path = true)
  {
    $__file__ = $base_path ? $this->context->getViewsDirectory() . $filename : $filename;
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

  public function registerFile($filename = null, $type = 'js', $content = "")
  {

    $base_directory = Helper::getAlias('@common/views/assets', "/");

    if (isset($this->context))
      $base_directory = $this->context->getAssetDirectory();


    if ($filename) {
      $__file__ =  $base_directory . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $filename;

      if (strpos($filename, '@') === 0)
        $__file__ =  Helper::getAlias($filename);


      if (!strpos($__file__, ".$type"))
        $__file__ .= ".$type";



      if (!file_exists($__file__)) {
        echo "File doesn't exist: $__file__ <br>";
        return null;
      }
    }

    $content = isset($__file__) ?  file_get_contents($__file__) : $content;
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

      if (isset($__file__)) {
        $success = copy($__file__, $path);
      } else {
        $file = fopen($path, "w");
        fwrite($file, $content);
        fclose($file);

        $success = true;
      }


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
      $renders[] = $pos === self::POS_LOAD ? $path : Html::tag('script', '', ['src' => $path, 'type' => 'text/javascript']);
    }

    $scripts = Helper::getValue($pos, $this->scripts, []);
    foreach ($scripts as $script) {
      $path = $this->registerFile(null, 'js', $script);
      $renders[] = $pos === self::POS_LOAD ? $path : Html::tag('script', '', ['src' => $path, 'type' => 'text/javascript']);
    }
    return $renders;
  }

  public function registerView($views)
  {
    if (is_array($views)) {
      $this->registeredViews = array_merge($this->registeredViews, $views);
    } else {
      $this->registeredViews[] = $views;
    }
  }

  public function renderAssets($pos)
  {
    $views = [$this];
    $assets = [];

    while (count($views) > 0) {
      $view = array_shift($views);

      $views = array_merge($views, $view->registeredViews);
      $renders = $view->renderPosition($pos);
      $assets = array_merge($assets, $renders);
    }

    if ($pos === self::POS_LOAD) {
      return $this->renderOnload($assets);
    }

    return implode("\n", $assets);
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


  private function renderOnload($scripts)
  {
    $files = implode(", ", array_map(function ($s) {
      return "`$s`";
    }, array_filter($scripts, function ($script) {
      return strpos($script, "/") === 0;
    })));


    $content = <<<JS
      $(document).ready(function(e) {
        const scripts = [$files];
        scripts.forEach(function (script) {
          let loaded = document.createElement("script");
          loaded.setAttribute("src", script);
          document.body.appendChild(loaded);
        });
      })
    JS;


    $path = $this->registerFile(null, 'js', $content);
    return Html::tag('script', '', ['src' => $path, 'type' => 'text/javascript']);
  }
}