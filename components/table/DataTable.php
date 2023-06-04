<?php

namespace components\table;

use components\BaseComponent;
use lib\util\Helper;

class DataTable extends BaseComponent
{

  public function getViewsDirectory()
  {
    return Helper::getAlias("@components\\table\\views\\", "\\");
  }


  public function getAssetDirectory()
  {
    return Helper::getAlias("@components\\table\assets\\", "\\");
  }


  public function table($data, $columns, $id = null, $options = [])
  {
    $class = Helper::getValue("class", $options);
    if ($class) {
      unset($options["class"]);
    }

    return $this->render("table", ["data" => $data, "columns" => $columns ?? [], "id" => $id, "options" => $options]);
  }
}
