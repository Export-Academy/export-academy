<?php

namespace common\controller;

use Exception;
use lib\app\auth\interface\IAuthController;
use lib\app\http\request\Request;
use lib\app\view\interface\IViewable;
use lib\app\view\View;
use lib\util\BaseObject;
use lib\util\Helper;

require_once Helper::getAlias("@lib\app\auth\interface\IAuthController.php");

/**
 * @property View $view
 */
class Controller extends BaseObject implements IAuthController, IViewable
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



  protected function render($path_to_view, $params = [], $layout_file_name = 'main')
  {
    $view_path = $this->getViewPath();
    $body = $this->getView()->generateContent($view_path . $path_to_view, $params);

    $layoutFilePath = $this->getLayoutFile($layout_file_name);

    $content = $this->getView()->generateContent($layoutFilePath, array_merge([
      'content' => $body
    ], $params));

    $content ? $this->getView()->renderContent($content) :  $this->getView()->renderContent($body);
  }

  protected function jsonResponse($data = null, $httpStatus = 200)
  {
    ob_clean();
    header_remove();
    header("Content-Type: application/json");
    http_response_code($httpStatus);
    echo json_encode($data);
    exit();
  }


  protected function returnScript($__filename__)
  {
    ob_clean();
    header_remove();
    header('Content-Type: text/javascript');
    if (file_exists($__filename__)) {
      http_response_code(200);
      echo file_get_contents($__filename__);
    } else {
      http_response_code(404);
    }
    exit();
  }


  protected function returnStylesheet($__filename__)
  {
    ob_clean();
    header_remove();
    header('Content-Type: text/css');
    if (file_exists($__filename__)) {
      http_response_code(200);
      echo file_get_contents($__filename__);
    } else {
      http_response_code(404);
    }
    exit();
  }


  protected function renderFile($file, $params = [])
  {
    $base_path = $this->getViewPath();
    return $this->getView()->render($base_path . $file, $params);
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


  public function getViewPath()
  {
    if (!isset($this->_viewPath)) {
      $this->_viewPath = Helper::getAlias($this->module) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . strtolower(preg_split('/(?=[A-Z])/', basename(get_called_class()))[1]) . DIRECTORY_SEPARATOR;
    }
    return $this->_viewPath;
  }


  public function getLayoutFile($name = 'main')
  {
    $path = Helper::getAlias($this->module) . '/views/layout/' . $name;
    return $path;
  }

  /**
   * The default asset directory for the controller
   *
   * @return void
   */
  public function getAssetDirectory()
  {
    /*
    strtolower(preg_split('/(?=[A-Z])/', basename(get_called_class()))[1]))
    */
    $base =  Helper::getAlias("$this->module" . DIRECTORY_SEPARATOR . "views"  . DIRECTORY_SEPARATOR . 'assets');
    return $base;
  }


  public function getViewsDirectory()
  {
    return $this->getViewPath();
  }
}
