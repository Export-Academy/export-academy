<?php

namespace components;

use lib\app\view\interface\IViewable;
use lib\app\view\View;
use lib\util\BaseObject;

abstract class BaseComponent extends BaseObject implements IViewable
{
  /**
   * @var View
   */
  public $view;

  protected function init()
  {
    $this->view = View::instance($this);
  }

  /**
   * Generates instance of Viewable Component class
   *
   * @param View $view
   * @return static
   */
  public static function instance(View &$view)
  {
    $className = get_called_class();
    $component = new $className;
    $view->registerView($component->view);

    return $component;
  }

  public function render($name, $params = [])
  {
    return $this->view->generateContent($name, $params);
  }
}
