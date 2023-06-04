<?php

namespace components\modal;

use components\BaseComponent;
use lib\app\log\Logger;
use lib\util\Helper;

class Modal extends BaseComponent
{

  const MODAL_SM = "sm";
  const MODAL_MD = "md";
  const MODAL_LG = "lg";
  const MODAL_XL = "xl";
  const MODAL_FULL = "fullscreen";


  public function getAssetDirectory()
  {
    return Helper::getAlias("@components\modal\\assets\\");
  }


  public function getViewsDirectory()
  {
    return Helper::getAlias("@components\modal\\views\\");
  }


  public function show($id, $content, $header = null, $footer = null, $options = [], $modalOptions = [], $headerOptions = [], $footerOptions = [])
  {
    $params = array_merge([
      "content" => $content,
      "header" => $header ?? "",
      "footer" => $footer ?? "",
      "showHeader" => true,
      "showFooter" => true,
      "showCloseButton" => true,
      "size" => self::MODAL_MD
    ], $options);

    $params["options"] = array_merge($modalOptions, ["id" => $id]);
    $params["headerOptions"] = $headerOptions;
    $params["footerOptions"] = $footerOptions;

    return $this->render("index", $params);
  }
}
