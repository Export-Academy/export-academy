<?php



namespace components;

use lib\app\view\interface\IViewable;
use lib\app\view\View;
use lib\util\BaseObject;
use lib\util\Helper;

class Components extends BaseObject implements IViewable
{
  /**
   * View
   *
   * @var View
   */
  public $view;

  public function init()
  {
    $this->view = View::instance($this);
  }


  public function render($name, $params = [])
  {
    return $this->view->generateContent($this->getViewsDirectory() . "/$name", $params);
  }

  public function getAssetDirectory()
  {
    return Helper::getAlias("@components/assets", "/");
  }

  public function getViewsDirectory()
  {
    return Helper::getAlias("@components/views", "/");
  }
}
