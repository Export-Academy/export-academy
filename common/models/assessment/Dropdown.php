<?php

namespace common\models\assessment;

use lib\app\view\View;

class Dropdown extends Question
{

  public static function renderBuild(View $view)
  {
    $component = self::generate($view);
    return $component->render("dropdown-build");
  }
}