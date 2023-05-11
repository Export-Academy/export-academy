<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\assessment\MultipleChoice;
use common\models\File;
use components\HtmlComponent;
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
    $urlImage = $this->request->data("image");
    $isComponent = $this->request->data("component", true);
    $data = null;


    if ($urlImage) {
      $imageURL = Helper::getValue("url", $urlImage, "");
      $name = Helper::getValue("name", $urlImage, "");
      $data = File::uploadByURL($imageURL, $name);
    } elseif ($image) {
      $data = File::uploadByFile($image);
    }


    if (isset($data)) {
      $url = $data->getURL();

      if (!$isComponent)
        $this->jsonResponse([
          "id" => $data->id,
          "url" => $url
        ]);
      $component = HtmlComponent::instance($this->view);

      $this->renderView($component->render("media-components/image-card", [
        "src" => $url,
        "alt" => "Image",
        "id" => $data->id
      ]));
      return;
    }


    $this->jsonResponse(null);
    return;
  }


  public function actionTest()
  {
    $this->jsonResponse(MultipleChoice::excludeProperty());
  }
}
