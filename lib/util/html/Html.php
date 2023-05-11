<?php


namespace lib\util\html;

use components\HtmlComponent;
use lib\util\BaseObject;

class Html extends BaseObject
{

  static function renderAttributes($attributes = [])
  {
    $html = "";
    foreach ($attributes as $key => $attr) {
      if (isset($attr))
        $html .= " $key=\"$attr\"";
    }
    return $html;
  }

  public static function tag($container, $content, $options = [])
  {
    $html = "<$container" . self::renderAttributes($options) . " >";
    $html .= $content . "</$container>";
    return $html;
  }

  public static function linkTag($src, $options = [])
  {
    return "<link" . self::renderAttributes(array_merge($options, ['href' => $src, "rel" => 'stylesheet'])) . " />";
  }

  public static function input($value, $name, $options = [])
  {
    return "<input " . self::renderAttributes(array_merge($options, ['name' => $name, 'value' => $value])) . " />";
  }


  public static function hiddenInput($value, $name, $options = [])
  {
    return self::input($value, $name, array_merge($options, ['hidden' => 'true']));
  }


  public static function textarea($value, $name, $options = [])
  {
    return self::tag('textarea', $value, array_merge($options, ['name' => $name]));
  }


  public static function form_begin($action, $method = "post", $options = [])
  {
    return (new HtmlComponent())->render("form-components/index", ["state" => "begin", "action" => $action, "method" => $method, "options" => $options]);
  }


  public static function form_end()
  {
    return (new HtmlComponent())->render("form-components/index");
  }


  public static function list(
    $list,
    $render = null,
    $max = null,
    $component = null,
    $container_options = [],
    $container = "ul",
    $item_container_options = [],
    $item_container = "li"
  ) {
    $content = "";
    $count = -1;

    foreach ($list as $item) {
      $count += 1;

      if ($count == $max) {
        $content .= $component ?? "";
        break;
      }

      if (is_callable($render)) {
        $content .= call_user_func_array($render, [$item]);
        continue;
      }
      $content .= self::tag($item_container, $item, $item_container_options);
    }

    return self::tag($container, $content, $container_options);
  }
}
