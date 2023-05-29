<?php


namespace common\models\resource\format\handlers;

use common\models\resource\AssetModel;
use lib\app\log\Logger;

class ImageHandler extends FormatHandler
{

  public function renderView(AssetModel $asset)
  {
    $content =  $this->render("image-view", ["asset" => $asset]);

    Logger::log($content);

    return $content;
  }
}
