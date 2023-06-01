<?php


namespace common\models\resource\format\handlers;

use common\models\resource\AssetModel;
use components\media\MediaComponent;

abstract class FormatHandler extends MediaComponent
{
  public function renderThumbnail(AssetModel $asset)
  {
    return $this->render("thumbnail", ["asset" => $asset]);
  }

  public function renderView(AssetModel $asset = null)
  {
    return "View";
  }
}
