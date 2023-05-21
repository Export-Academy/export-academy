<?php

namespace common\models\resource\format;

use components\ModelComponent;
use lib\util\Helper;

class Format extends ModelComponent
{

  public function getAssetDirectory()
  {
    return Helper::getAlias("@common\models\\resource\\format\assets\\");
  }

  public function getViewsDirectory()
  {
    return Helper::getAlias("@common\models\\resource\\format\\views\\");
  }
}
