<?php



namespace components;

use lib\app\view\View;
use lib\util\Helper;



class HtmlComponent extends BaseComponent
{
  const TextInput = "text";
  const PasswordInput = "password";
  const TextArea = "textarea";

  public function getAssetDirectory()
  {
    return Helper::getAlias("@components/assets", "/");
  }

  public function getViewsDirectory()
  {
    return Helper::getAlias("@components/views/", "/");
  }

  public static function input(View &$view, $name, $value, $options = [])
  {
    $component = self::instance($view);
    $content = $component->render("form-components/input-component", array_merge([
      "name" => $name,
      "value" => $value,
      "component" => self::TextInput
    ], $options));
    return $content;
  }


  public static function textarea(View &$view, $name, $value, $options = [])
  {
    $component = self::instance($view);
    $content = $component->render("form-components/input-component", array_merge([
      "name" => $name,
      "value" => $value,
      "component" => self::TextArea
    ], $options));

    return $content;
  }


  public static function passwordInput(View &$view, $name, $value, $options = [])
  {
    $component = self::instance($view);
    $content = $component->render("form-components/input-component", array_merge([
      "name" => $name,
      "value" => $value,
      "component" => self::PasswordInput
    ], $options));

    return $content;
  }


  public static function dropdown(View &$view, $name, $value, $items = [], $options = [])
  {
    $component = self::instance($view);
    $content = $component->render("form-components/dropdown-component", array_merge([
      "name" => $name,
      "value" => $value,
      "items" => $items,
    ], $options));

    return $content;
  }
}