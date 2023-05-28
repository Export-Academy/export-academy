<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\resource\Asset;
use common\models\resource\File;
use Exception;
use lib\app\log\Logger;

class MediaController extends Controller
{




  public function actionUpload()
  {
    $method = $this->request->method();

    switch ($method) {
      case "POST":
        break;

      default:
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

          $this->jsonResponse('Success');
        } catch (Exception $ex) {
          $this->jsonResponse($ex->getMessage());
        }

        break;

      default:
        $this->jsonResponse("Invalid method");
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
      $this->jsonResponse("Deleted");
    } catch (Exception $ex) {
      $this->jsonResponse($ex->getMessage());
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
    $assetInstance = Asset::create($file);

    Logger::log($assetInstance);
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
    Logger::log(mime_content_type($file->getResource()));
    $assetInstance = Asset::create($file);

    return $assetInstance;
  }




  public function actionMedia()
  {
    $method = $this->request->method();

    switch ($method) {
      case "GET":
        break;


      case "POST":
        break;


      default:
        break;
    }
  }



  private function getContent()
  {
  }
}
