<?php


namespace web\controller;

use common\controller\Controller;
use common\models\resource\Asset;
use common\models\resource\interfaces\IAssetStorage;
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

  public function actionMedia($id = null)
  {
    $id = $this->request->params("source");
    $asset = Asset::findOne(["id" => $id]);
    $path = null;

    if ($asset instanceof IAssetStorage)
      $path = $asset->referencePath();
    $this->returnAsset($path);
  }
}
