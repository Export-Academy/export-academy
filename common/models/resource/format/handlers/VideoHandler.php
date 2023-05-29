<?php


namespace common\models\resource\format\handlers;

use common\models\resource\AssetModel;

class VideoHandler extends FormatHandler
{

  public function renderView(AssetModel $asset)
  {
    return $this->render("video-view", ["asset" => $asset]);
  }
}
