<?php


namespace common\models\resource\format\handlers;

use common\models\resource\AssetModel;

class ApplicationHandler extends FormatHandler
{
  public function renderView(?AssetModel $asset = null)
  {
    return $this->render("application-view", ["asset" => $asset]);
  }
}
