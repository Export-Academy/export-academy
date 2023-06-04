<?php


namespace components\media;

use common\models\resource\AssetModel;
use common\models\resource\interfaces\IAssetStorage;
use components\BaseComponent;
use lib\util\Helper;

class MediaComponent extends BaseComponent
{
  public function getViewsDirectory()
  {
    return Helper::getAlias("@components\media\\views\\");
  }


  public function getAssetDirectory()
  {
    return Helper::getAlias("@components\media\assets\\");
  }


  public function view($path = null)
  {
    return $this->render("index", ["path" => $path]);
  }


  public function content(AssetModel $asset = null)
  {
    return $this->render("media-content", ["asset" => $asset]);
  }


  public function uploader($reload = false, $path = null)
  {
    return $this->render("uploader", ["reload" => $reload, "path" => $path]);
  }


  public function editor(IAssetStorage $asset)
  {
    return $this->render("editor", ["asset" => $asset]);
  }
}
