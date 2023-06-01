<?php

namespace common\controller;

use lib\app\auth\interfaces\IAuthController;
use lib\app\log\Logger;
use lib\app\Request;
use lib\app\view\interface\IViewable;
use lib\app\view\View;
use lib\util\BaseObject;
use lib\util\Helper;

/**
 * @property View $view
 * @property Request $request
 */
abstract class Controller extends BaseObject implements IAuthController, IViewable
{

  /** @var View */
  private $_view;


  /** @var Request */
  public $request;


  /** @var string */
  private $_viewPath;


  /** @var string */
  public $module;



  public function secure()
  {
    return [];
  }


  /**
   * Renders the view
   *
   * @param string $filename The file name of the view relative to the views directory
   * @param array $params Parameters that should be passed to the view
   * @param string $layout_file_name layout file name, default is `main.php` relative to views directory
   * @return void
   */
  protected function render($filename, $params = [], $layout_file_name = 'main')
  {
    $body = $this->getView()->generateContent($filename, $params);
    $layoutFilePath = $this->getLayoutFile($layout_file_name);
    $content = $this->getView()->generateContent($layoutFilePath, array_merge([
      'content' => $body
    ], $params), false);
    $content ? $this->getView()->renderContent($content) :  $this->getView()->renderContent($body);
    exit();
  }

  protected function renderView($content)
  {
    $this->getView()->renderContent($content);
  }

  /**
   * Generates a JSON response
   *
   * @param mixed $data Data to be shown in JSON response
   * @param integer $httpStatus
   * @return never
   */
  protected function jsonResponse($data = null, $httpStatus = 200)
  {
    ob_clean();
    header_remove();
    header("Content-Type: application/json");
    http_response_code($httpStatus);
    echo json_encode($data);
    exit();
  }


  protected function returnAsset($path)
  {
    if (!file_exists($path)) {
      http_response_code(404);
      exit();
    }
    $handle = fopen($path, "r");
    $mime =  mime_content_type($handle);
    ob_clean();


    $buffer  = "";
    $count = 0;

    if ($handle === false) {
      http_response_code(404);
      exit();
    }

    while (!feof($handle)) {
      $buffer = fread($handle, 10 * 10);
      echo $buffer;

      ob_flush();
      flush();

      $count += strlen($buffer);
    }

    Logger::log($count);

    $status = fclose($handle);

    if ($status) {
      header_remove();
      header("Content-Type: $mime");
      http_response_code(200);
    } else {
      http_response_code(404);
    }
    exit();
  }

  /**
   * Returns a CSS stylesheet as response
   *
   * @param string $filename
   * @return never
   */
  protected function returnImage($filename)
  {
    ob_clean();
    header_remove();
    header('Content-Type: text/css');
    if (file_exists($filename)) {
      http_response_code(200);
      echo file_get_contents($filename);
    } else {
      http_response_code(404);
    }
    exit();
  }

  /**
   * Renders a PHP view file
   *
   * @param string $file
   * @param array $params
   * @return void
   */
  protected function renderFile($file, $params = [])
  {
    $base_path = $this->getViewPath();
    $this->getView()->render($base_path . $file, $params);
  }

  /**
   * Return a controller view instance
   *
   * @return View
   */
  public function getView()
  {
    if (!isset($this->_view)) {
      $this->_view = View::instance($this);
    }
    return $this->_view;
  }

  /**
   * Gets the view path directory for the controller
   *
   * @return void
   */
  public function getViewPath()
  {
    if (!isset($this->_viewPath)) {
      $this->_viewPath = Helper::getAlias($this->module) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . strtolower(preg_split('/(?=[A-Z])/', basename(get_called_class()))[1]) . DIRECTORY_SEPARATOR;
    }
    return $this->_viewPath;
  }

  /**
   * Gets layout file pathname
   *
   * @param string $name
   * @return string
   */
  public function getLayoutFile($name = 'main')
  {
    $path = Helper::getAlias($this->module) . '/views/layout/' . $name;
    return $path;
  }

  /**
   * Gets the asset path directory for the controller
   *
   * @return string
   */
  public function getAssetDirectory()
  {
    $base =  Helper::getAlias("$this->module" . DIRECTORY_SEPARATOR . "views"  . DIRECTORY_SEPARATOR . 'assets');
    return $base;
  }

  /**
   * Gets the view path directory for the controller
   *
   * @return void
   */
  public function getViewsDirectory()
  {
    return $this->getViewPath();
  }
}
