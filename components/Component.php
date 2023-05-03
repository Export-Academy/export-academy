<?php



namespace components;

use lib\app\view\interface\IViewable;
use lib\app\view\View;
use lib\util\BaseObject;
use lib\util\Helper;




class ComponentRender extends BaseObject
{
  /** @var Components */
  public $component;

  /** @var string */
  public $content;


  public static function instance($component, $content)
  {
    return new ComponentRender(["component" => $component, "content" => $content]);
  }


  public function render()
  {
    echo $this->content;
  }

  public function getView()
  {
    return $this->component->view;
  }
}

class Components extends BaseObject implements IViewable
{
  const TextInput = "text";
  const PasswordInput = "password";
  const TextArea = "textarea";

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

  public static function instance()
  {
    return new Components();
  }


  public function render($name, $params = [])
  {
    return $this->view->generateContent($name, $params);
  }

  public function getAssetDirectory()
  {
    return Helper::getAlias("@components/assets", "/");
  }

  public function getViewsDirectory()
  {
    return Helper::getAlias("@components/views/", "/");
  }


  public static function input($name, $value, $options = [])
  {
    $component = self::instance();
    $content = $component->render("form-components/input-field", array_merge([
      "name" => $name,
      "value" => $value,
      "component" => self::TextInput
    ], $options));

    return ComponentRender::instance($component, $content);
  }


  public static function textarea($name, $value, $options = [])
  {
    $component = self::instance();
    $content = $component->render("form-components/input-field", array_merge([
      "name" => $name,
      "value" => $value,
      "component" => self::TextArea
    ], $options));

    return ComponentRender::instance($component, $content);
  }


  public static function passwordInput($name, $value, $options = [])
  {
    $component = self::instance();
    $content = $component->render("form-components/input-field", array_merge([
      "name" => $name,
      "value" => $value,
      "component" => self::PasswordInput
    ], $options));

    return ComponentRender::instance($component, $content);
  }
}