<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\assessment\MultipleChoice;
use common\models\assessment\Question;
use common\models\assessment\QuestionType;
use common\models\File;
use components\HtmlComponent;
use lib\app\log\Logger;
use lib\app\route\Router;
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
    Logger::log($this->request->data());

    switch ($this->request->method()) {
      case "POST":
        $data = $this->request->data("question");
        $media = $this->request->data("media", []);
        $type = $this->request->data("type");

        $questionType = QuestionType::findOne(["id" => $type]);

        $handler = $questionType->handler;
        $context = $this->request->data($handler);
        $q = $handler::configure($data, $questionType, $context, $media);
        Router::redirect("/academy/admin/assessment/question?id=$q->id");
    }
  }

  public function actionQuestion()
  {
    $id = $this->request->params("id");
    $question = Question::findOne(["id" => $id]);
    if ($question)
      $this->render("question", ["question" => $question]);
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

      $params = [
        "src" => $url,
        "alt" => "Image",
        "id" => $data->id
      ];

      $this->renderView($component->render("media-components/image-card", $params));
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
