<?php


namespace lib\util\html;

use lib\util\BaseObject;

class HtmlHelper extends BaseObject
{

  private static function renderAttributes($attributes = [])
  {
    $html = "";
    foreach ($attributes as $key => $attr) {
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
}