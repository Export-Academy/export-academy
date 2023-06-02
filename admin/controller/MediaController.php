<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\resource\Asset;
use common\models\resource\AssetModel;
use common\models\resource\File;
use components\media\MediaComponent;
use Exception;
use lib\app\log\Logger;
use lib\app\route\Router;
use lib\app\view\View;

class MediaController extends Controller
{

  public function secure()
  {
    return [
      "requiresAuth" => ["actionUpload", "actionUploadUrl"]
    ];
  }


  public function actionUpload()
  {
    $method = $this->request->method();

    switch ($method) {
      case "POST":
        try {
          $this->handleFileUpload();
        } catch (Exception $ex) {
          Logger::log($ex->getMessage(), "error");
          $this->jsonResponse($ex->getMessage(), 500);
        }
        break;

      default:
        $this->jsonResponse("Invalid method", 405);
        break;
    }
  }


  public function actionUploadUrl()
  {
    $method = $this->request->method();

    switch ($method) {
      case "POST":
        try {
          $this->handleUrlUpload();
        } catch (Exception $ex) {
          Logger::log($ex->getMessage(), "error");
          $this->jsonResponse($ex->getMessage(), 500);
        }
        break;

      default:
        $this->jsonResponse("Invalid method", 405);
        break;
    }
  }


  public function actionDelete()
  {
    $id = $this->request->data("id");

    $asset = Asset::findOne(["id" => $id]);

    if (!isset($asset)) $this->jsonResponse("Valid media ID is required");


    try {
      $asset->delete();
      Router::redirect($this->request->refer());
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
      Router::redirect($this->request->refer());
    }
  }


  private function handleFileUpload()
  {
    $path = $this->request->data("path");
    $name = $this->request->data("name");
    $media = $this->request->file("media");


    if (!isset($media)) throw new Exception("No file was uploaded");

    $config = array(
      "tmp_name" => $media["tmp_name"],
      "name" => $name ?? $media["name"],
      "path" => $path,
      "type" => $media["type"]
    );


    $file = new File($config);
    $asset = Asset::create($file);

    $this->jsonResponse(array(
      "url" => $asset->getUrl(),
      "mime" => mime_content_type($asset->readStream()),
      "id" => $asset->getId(),
      "name" => $asset->getName(),
      "path" => $asset->getPath(),
      "create_date" => $asset->getCreateDate(),
      "update_date" => $asset->getUpdateDate(),
      "created_by" => $asset->getCreateUser(),
      "updated_by" => $asset->getUpdateUser()
    ));
  }


  private function handleUrlUpload()
  {
    $path = $this->request->data("path");
    $name = $this->request->data("name");
    $url = $this->request->data("url");

    if (!isset($name) || !isset($url)) throw new Exception("Insufficient data");

    $config = array(
      "tmp_name" => $url,
      "isUrl" => true,
      "name" => $name,
      "path" => $path
    );

    $file = new File($config);
    $asset = Asset::create($file);

    $this->jsonResponse(array(
      "url" => $asset->getUrl(),
      "mime" => mime_content_type($asset->readStream()),
      "id" => $asset->getId(),
      "name" => $asset->getName(),
      "path" => $asset->getPath(),
      "create_date" => $asset->getCreateDate(),
      "update_date" => $asset->getUpdateDate(),
      "created_by" => $asset->getCreateUser(),
      "updated_by" => $asset->getUpdateUser()
    ));
  }




  public function actionMedia()
  {
    $method = $this->request->method();

    switch ($method) {
      case "POST":
        $this->handleMedia();
        break;


      default:
        $this->jsonResponse("Invalid Method", 405);
        break;
    }
  }


  public function actionMediaContent()
  {
    $request = $this->request;
    $assetId = $request->params("id");
    $asset = Asset::findOne(["id" => $assetId]);

    $view = $this->getView();


    if (isset($asset) && ($asset instanceof AssetModel)) {
      $content = MediaComponent::instance($view)->content($asset);

      if ($content) {
        $content = $view->renderAssets(View::POS_HEAD) . "\n" . $content . "\n" . $view->renderAssets(View::POS_END) . "\n" . $view->renderAssets(View::POS_LOAD);;
        $this->renderView($content);
        return;
      }
    }

    $this->jsonResponse("Component Not Found");
    return;
  }


  private function handleMedia()
  {
    $mediaId = $this->request->data("media");

    $asset = Asset::findOne(["id" => $mediaId]);
    if ($asset) {
      try {
        $data = array(
          "url" => $asset->getUrl(),
          "mime" => mime_content_type($asset->readStream()),
          "id" => $asset->getId(),
          "name" => $asset->getName(),
          "path" => $asset->getPath(),
          "create_date" => $asset->getCreateDate(),
          "update_date" => $asset->getUpdateDate(),
          "created_by" => $asset->getCreateUser(),
          "updated_by" => $asset->getUpdateUser()
        );

        Logger::log($data);
        $this->jsonResponse($data);
      } catch (Exception $ex) {
        Logger::log($ex->getMessage());
        $this->jsonResponse($ex->getMessage(), 500);
      }
      return;
    }

    $this->jsonResponse(null, 404);
  }
}
