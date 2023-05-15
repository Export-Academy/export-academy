<?php


namespace components;

use common\models\base\BaseModel;
use lib\app\view\interface\IViewable;
use lib\app\view\View;

abstract class ModelComponent extends BaseModel implements IViewable
{
  /**
   * @var View
   */
  public $view;

  protected function init()
  {
    parent::init();
    $this->view = View::instance($this);
  }

  public static function generate(View &$view)
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

  public function getView()
  {
    return $this->view;
  }
}
