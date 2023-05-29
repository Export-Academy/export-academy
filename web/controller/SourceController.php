<?php


namespace web\controller;

use common\controller\Controller;
use common\models\resource\Asset;
use common\models\resource\AssetModel;
use common\models\resource\interfaces\IAssetStorage;
use lib\app\log\Logger;
use lib\util\Helper;

class SourceController extends Controller
{

  public function actionJs($__filename__ = null)
  {
    $this->returnAsset(Helper::getAlias("@web/source/js/$__filename__"));
  }


  public function actionCss($__filename__ = null)
  {
    $this->returnAsset(Helper::getAlias("@web/source/css/$__filename__"));
  }

  public function actionMedia($path = null)
  {
    $id = $this->request->params("source");

    $asset = Asset::findOne(["id" => $id]);
    $path = null;

    if ($asset instanceof IAssetStorage)
      $path = $asset->referencePath();

    $this->returnAsset($path);
  }
}
