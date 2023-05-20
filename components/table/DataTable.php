<?php

namespace components\table;

use components\BaseComponent;

class DataTable extends BaseComponent
{

  public function getViewsDirectory()
  {
    return __DIR__ . "\\views\\";
  }


  public function getAssetDirectory()
  {
    return __DIR__ . "\assets\\";
  }


  public function table($items = [],)
  {

    $content = $this->render("index", [
      "footerOptions"
    ]);
    return $content;
  }
}
