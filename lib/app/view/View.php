<?php

namespace lib\app\view;

use lib\app\log\Logger;
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

  /**
   * Set the page title
   *
   * @param string $title
   * @return void
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }

  /**
   * Generate and render the given file
   *
   * @param string $filename
   * @param array $params
   * @return void
   */
  public function render(string $filename, array $params = [])
  {
    $content = $this->generateContent($filename, $params);
    $this->renderContent($content);
  }

  /**
   * Echo provided content
   *
   * @param string $content
   * @return void
   */
  public function renderContent($content)
  {
    echo $content;
  }

  /**
   * Generates and returns the content of a view
   *
   * @param string $filename
   * @param array $__params__
   * @param boolean $base_path
   * @return string
   */
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

  /**
   * Register a JS file to be rendered in final view
   *
   * @param string $__file__
   * @param string $pos
   * @return void
   */
  public function registerJsFile($__file__, $pos = self::POS_LOAD)
  {
    $this->js_scripts[$pos][] = $__file__;
  }

  /**
   * Register CSS file to be rendered in final view
   *
   * @param string $__file__
   * @return void
   */
  public function registerCssFile($__file__)
  {
    $this->css_styles[] = $__file__;
  }

  /**
   * Register a SCSS file to be rendered in final view
   *
   * @param string $__file__
   * @return void
   */
  public function registerSCSSFile($__file__)
  {
    $this->scss_styles[] = $__file__;
  }

  /**
   * Register JS code
   *
   * @param string $script
   * @param string $pos
   * @return void
   */
  public function registerJs($script, $pos = self::POS_LOAD)
  {
    $this->scripts[$pos][] = $script;
  }

  /**
   * Gets content from a file and returns the URL to access the file
   *
   * @param string $filename
   * @param string $type
   * @param string $content
   * @return void
   */
  public function registerFile($filename = null, $type = 'js', $content = "")
  {
    // Gets the base directory from IViewable Context
    $base_directory = $this->context->getAssetDirectory();


    if ($filename) {
      // Creates the current path of the file
      $__file__ =  $base_directory . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $filename;

      if (strpos($filename, '@') === 0)
        $__file__ =  Helper::getAlias($filename);

      // Add file extension if it was not provided
      if (!strpos($__file__, ".$type"))
        $__file__ .= ".$type";


      // Check if the file exists
      if (!file_exists($__file__)) {
        echo "File doesn't exist: $__file__ <br>";
        return null;
      }
    }
    // Gets the content from file or provided content
    $content = isset($__file__) ?  file_get_contents($__file__) : $content;

    // Hash the content from the file
    $hash = hash('md5', $content);


    if ($type == 'scss') {
      // Generate path to distributed files
      $generated_path = "web/source/css/$hash.css";
      $path = Helper::getAlias("@$generated_path", "/");

      // If file already exist return null
      if (file_exists($path)) {
        return null;
      }

      // Creating compiler to convert SCSS to CSS
      $compiler = new Compiler();
      $compliedCss = $compiler->compileString($content)->getCss();
      $file = fopen($path, "w");

      // Write CSS content to the file
      fwrite($file, $compliedCss);
      fclose($file);

      // Return the URL to access distribution file
      return Helper::getURL($generated_path);
    }

    // Generate path to distributed files
    $generated_path = "web/source/" . $type . "/$hash.$type";
    $path = Helper::getAlias("@$generated_path", "/");

    // If file already exist return null
    if (file_exists($path)) {
      return null;
    }


    if (isset($__file__)) {
      // Copy file to the generated path
      $success = copy($__file__, $path);

      if (!$success) return null;
    } else {

      // Create new to add the content
      $file = fopen($path, "w");
      fwrite($file, $content);
      fclose($file);

      $success = true;
    }

    return Helper::getURL($generated_path);
  }

  /**
   * Render the html asset components for the specified position
   *
   * @param string $pos
   * @return mixed
   */
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
      if (!isset($path)) continue;
      $renders[] = $pos === self::POS_LOAD ? $path : Html::tag('script', '', ['src' => $path, 'type' => 'text/javascript']);
    }
    return $renders;
  }

  public function registerView($views)
  {
    if (is_array($views)) {
      foreach ($views as $view) {
        $id = spl_object_id($view);
        if ($id === spl_object_id($this))
          continue;
        $this->registeredViews[$id] = $view;
      }
    } else {
      $id = spl_object_id($views);
      if ($id === spl_object_id($this))
        return;
      $this->registeredViews[$id] = $views;
    }
  }

  /**
   * Render Assets for the specified position
   *
   * @param string $pos
   * @return string
   */
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

    $content = implode("\n", $assets);
    return $content;
  }

  /**
   * Reset the View Asset directories
   *
   * @return void
   */
  public static function reset()
  {
    $js_dir = Helper::getAlias("@web\source\js");
    $css_dir = Helper::getAlias("@web\source\css");

    self::delete($js_dir, "js");
    self::delete($css_dir, "css");
  }


  private static function delete($dir, $ext = null)
  {
    $dir_handle = opendir($dir);
    while ($file = readdir($dir_handle)) {
      $file = $dir . '\\' . $file;
      if ($ext) {
        if (pathinfo($file, PATHINFO_EXTENSION) != $ext) continue;
      }
      if (is_dir($file)) continue;
      if (file_exists($file))
        unlink($file);
    }
    closedir($dir_handle);
  }

  /**
   * Render scripts in ready event wrapper
   *
   * @param [type] $scripts
   * @return void
   */
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
