<?php

namespace common\controller;

use lib\app\auth\interface\IAuthController;
use lib\app\http\request\Request;
use lib\app\view\interface\IViewable;
use lib\app\view\View;
use lib\util\BaseObject;
use lib\util\Helper;

require_once Helper::getAlias("@lib\app\auth\interface\IAuthController.php");

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
    $view_path = $this->getViewPath();
    $body = $this->getView()->generateContent($view_path . $filename, $params);

    $layoutFilePath = $this->getLayoutFile($layout_file_name);

    $content = $this->getView()->generateContent($layoutFilePath, array_merge([
      'content' => $body
    ], $params));

    $content ? $this->getView()->renderContent($content) :  $this->getView()->renderContent($body);
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

  /**
   * Returns a JS script as response
   *
   * @param string $filename
   * @return never
   */
  protected function returnScript($filename)
  {
    ob_clean();
    header_remove();
    header('Content-Type: text/javascript');
    if (file_exists($filename)) {
      http_response_code(200);
      echo file_get_contents($filename);
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
  protected function returnStylesheet($filename)
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
