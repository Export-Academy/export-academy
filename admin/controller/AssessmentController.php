<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\assessment\Answer;
use common\models\assessment\MultipleChoice;
use common\models\assessment\Question;
use common\models\assessment\QuestionType;
use common\models\File;
use components\HtmlComponent;
use Exception;
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

    switch ($this->request->method()) {
      case "POST":
        $data = $this->request->data("question");
        $link = $this->request->data("link", []);
        $media = $this->request->data("media", []);
        $type = $this->request->data("type");
        $questionType = QuestionType::findOne(["id" => $type]);
        $handler = $questionType->handler;
        $context = $this->request->data($handler);

        $qid = Helper::getValue("id", $data);


        try {
          $q = $handler::configure([
            "data" => $data,
            "type" => $questionType,
            "context" => $context,
            "media" => $media,
            "link" => $link
          ]);
        } catch (Exception $ex) {
          Logger::log($ex->getMessage(), "error");
        }

        if (!empty($link)) {
          $id = Helper::getValue("id", $link);
          Router::redirect(Helper::getURL("/admin/assessment/question?id=$id"));
          return;
        }

        if (isset($qid)) {
          Router::redirect("/academy/admin/assessment/question?id=$qid");
          return;
        }
        Router::redirect("/academy/admin/assessment");
        break;


      default:
        Router::redirect("/academy/admin/assessment");
    }
  }

  public function actionUnlink()
  {
    $id = $this->request->data("id");
    $type = $this->request->data("type", Question::class);


    $instance = $type::findOne(["id" => $id]);

    if (!isset($instance)) {
      Router::redirect(Helper::getURL("/admin/assessment"));
      return;
    }

    $instance->link = null;
    $instance->update();

    if ($instance instanceof Question) {
      Router::redirect(Helper::getURL("/admin/assessment/question?id=$instance->id"));
      return;
    }


    $question = $instance->question;
    Router::redirect(Helper::getURL("/admin/assessment/question?id=$question->id"));
  }

  public function actionSubmitAnswer()
  {
    $id = $this->request->data("id");

    $question = Question::findOne(["id" => $id]);

    if (!isset($question)) {
      Router::redirect(Helper::getURL("/admin/assessment"));
    }

    $context = $this->request->data($question->questionType->handler);

    try {
      $answer = Answer::configure($question, $context);
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
    }

    Router::redirect(Helper::getURL("/admin/assessment/question?id=$question->id"));
  }

  public function actionUpdate()
  {
    $questionId = $this->request->data("id");

    $question = Question::findOne(["id" => $questionId]);

    if (!isset($question)) {
      Router::redirect(Helper::getURL("/admin/assessment"));
    }

    $data = $this->request->data(get_class($question), []);

    $enabled = Helper::getValue("enabled", $data);
    $link = Helper::getValue("link", $data);

    $question->enabled = $enabled ? 1 : 0;
    $question->link = empty($link) ? null : $link;


    $question->update();
    Router::redirect(Helper::getURL("/admin/assessment/question?id=$questionId"));
  }

  public function actionDelete()
  {
    $id = $this->request->data("id");
    $type = $this->request->data("type", Question::class);


    $instance = $type::findOne(["id" => $id]);

    if (!isset($instance)) {
      Router::redirect(Helper::getURL("/admin/assessment"));
      return;
    }

    $question = null;
    if ($instance instanceof Answer) {
      $question = $instance->question;
    }

    $instance->delete();

    if ($instance instanceof Question) {
      Router::redirect(Helper::getURL("/admin/assessment"));
      return;
    }

    if ($question) {
      Router::redirect(Helper::getURL("/admin/assessment/question?id=$question->id"));
      return;
    }

    Router::redirect(Helper::getURL("/admin/assessment"));
  }

  public function actionUpdateAnswer()
  {
    $id = $this->request->data("id");

    $answer = Answer::findOne(["id" => $id]);

    if (!isset($answer)) {
      Router::redirect(Helper::getURL("/admin/assessment"));
    }

    $data = $this->request->data(get_class($answer), []);
    $link = Helper::getValue("link", $data);

    $answer->link = empty($link) ? null : $link;


    $answer->update();
    $question = $answer->question;
    Router::redirect(Helper::getURL("/admin/assessment/question?id=$question->id"));
  }

  public function actionQuestion()
  {
    $id = $this->request->params("id");
    $question = Question::findOne(["id" => $id]);
    if ($question) {
      $this->render("question", ["question" => $question, "title" => "Manage Question ($question->id)"]);
      return;
    }
    Router::redirect(Helper::getURL("/admin/assessment"));
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
