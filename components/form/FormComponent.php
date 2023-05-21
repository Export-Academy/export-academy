<?php

namespace components\form;

use components\BaseComponent;
use lib\app\log\Logger;
use lib\util\Helper;

class FormComponent extends BaseComponent
{

  const BEGIN = "begin";
  const END = "end";


  public function getViewsDirectory()
  {
    return Helper::getAlias("@components\\form\\views\\");
  }


  public function getAssetDirectory()
  {
    return Helper::getAlias("@components\\form\\assets\\");
  }


  public function begin($action, $method = "POST", $options = [])
  {
    return $this->render("form", [
      "state" => self::BEGIN,
      "action" => $action,
      "options" => $options,
      "method" => $method
    ]);
  }


  public function end()
  {
    return $this->render("form", [
      "state" => self::END
    ]);
  }


  public function deleteForm($action, $button)
  {
    $params = ["action" => $action, "button" => $button];
    return $this->render("delete-form", $params);
  }
}
