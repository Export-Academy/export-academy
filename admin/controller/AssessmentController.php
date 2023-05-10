<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\assessment\MultipleChoice;
use lib\app\log\Logger;
use lib\app\storage\Storage;
use lib\util\Helper;

class AssessmentController extends Controller
{


  public function secure()
  {
    return [
      "requiresAuth" => ["*"]
    ];
  }

  public function actionIndex()
  {
    $this->render('index', ["title" => "Manage Assessments"]);
  }

  public function actionBuild()
  {
    $this->jsonResponse($this->request->data());
  }


  public function actionImageUpload()
  {
    $image = $this->request->file("image");
    Logger::log($image);


    $client = Storage::instance();
    $object = $client->upload(Helper::getValue("tmp_name", $image, ""), basename(self::class) . "/" . Helper::getValue("name", $image, ""));

    Logger::log($object);

    $client->$client->$this->jsonResponse();
  }


  public function actionTest()
  {
    $this->jsonResponse(MultipleChoice::excludeProperty());
  }
}