<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\assessment\Answer;
use common\models\assessment\Question;
use common\models\assessment\QuestionType;
use common\models\base\BaseModel;
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

  public function actionAnswer()
  {
    $method = $this->request->method();

    switch ($method) {
      case "POST":
        $this->updateAnswer();
        break;
    }
  }

  public function actionQuestion()
  {
    $method = $this->request->method();

    switch ($method) {
      case "GET":
        $this->getQuestion();
        break;

      case "POST":
        $this->updateQuestion();
        break;

      default:
        $this->getQuestion();
    }
  }

  public function actionBuildQuestion()
  {
    $this->createQuestion();
  }

  public function actionBuildAnswer()
  {
    $this->createAnswer();
  }

  public function actionUnlink()
  {
    $id = $this->request->data("id");
    $type = $this->request->data("type", Question::class);


    $instance = $type::findOne(["id" => $id]);

    if (!isset($instance)) {
      Router::redirect(Helper::getURL("admin/assessment"));
      return;
    }

    $instance->link = null;
    $instance->update();

    if ($instance instanceof Question) {
      Router::redirect(Helper::getURL("admin/assessment/question?id=$instance->id"));
      return;
    }


    $question = $instance->question;
    Router::redirect(Helper::getURL("admin/assessment/question?id=$question->id"));
  }


  public function actionDelete()
  {
    $id = $this->request->data("id");
    $type = $this->request->data("type");

    $instance = null;
    if (class_exists($type))
      $instance = $type::findOne(["id" => $id]);



    if (!isset($instance) || !($instance instanceof BaseModel)) {
      Router::redirect(Helper::getURL("admin/assessment"));
      return;
    }

    $question = null;
    if ($instance instanceof Answer) {
      $question = $instance->question;
    }

    $instance->delete();

    if ($instance instanceof Question) {
      Router::redirect(Helper::getURL("admin/assessment"));
      return;
    }

    if ($question) {
      Router::redirect(Helper::getURL("admin/assessment/question?id=$question->id"));
      return;
    }

    Router::redirect(Helper::getURL("admin/assessment"));
  }


  /**
   * Handles request to save a new question
   *
   * @return never
   */
  private function createQuestion()
  {
    $data = $this->request->data("question");
    $link = $this->request->data("link", []);
    $type = $this->request->data("type");
    $questionType = QuestionType::findOne(["id" => $type]);
    $handler = $questionType->handler;
    $context = $this->request->data($handler);

    $qid = Helper::getValue("id", $data);

    try {
      $handler::configure([
        "data" => $data,
        "type" => $questionType,
        "context" => $context,
        "link" => $link
      ]);
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
    }

    if (!empty($link)) {
      $id = Helper::getValue("id", $link);
      Router::redirect(Helper::getURL("admin/assessment/question?id=$id"));
      return;
    }

    if (isset($qid)) {
      Router::redirect(Helper::getURL("admin/assessment/question?id=$qid"));
      return;
    }
    Router::redirect(Helper::getURL("admin/assessment"));
  }

  /**
   * Updates exsiting answer
   *
   * @return never
   */
  private function updateQuestion()
  {
    $questionId = $this->request->data("id");

    $question = Question::findOne(["id" => $questionId]);

    if (!isset($question)) {
      Router::redirect(Helper::getURL("admin/assessment"));
    }

    $data = $this->request->data(get_class($question), []);

    $enabled = Helper::getValue("enabled", $data);
    $link = Helper::getValue("link", $data);

    $question->enabled = $enabled ? 1 : 0;
    $question->link = empty($link) ? null : $link;


    $question->update();
    Router::redirect(Helper::getURL("admin/assessment/question?id=$questionId"));
  }

  /**
   * Updates an existing answer
   *
   * @return never
   */
  private function updateAnswer()
  {
    $id = $this->request->data("id");
    $answer = Answer::findOne(["id" => $id]);
    if (!isset($answer)) {
      Router::redirect(Helper::getURL("admin/assessment"));
    }
    $data = $this->request->data(get_class($answer), []);
    $link = Helper::getValue("link", $data);
    $answer->link = empty($link) ? null : $link;
    $answer->update();
    $question = $answer->question;
    Router::redirect(Helper::getURL("admin/assessment/question?id=$question->id"));
  }

  /**
   * Handles request to save a new answer
   *
   * @return never
   */
  private function createAnswer()
  {
    $id = $this->request->data("id");
    $question = Question::findOne(["id" => $id]);
    if (!isset($question)) {
      Router::redirect(Helper::getURL("admin/assessment"));
    }
    $context = $this->request->data($question->questionType->handler);
    try {
      $answer = Answer::configure($question, $context);
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
    }
    Router::redirect(Helper::getURL("admin/assessment/question?id=$question->id"));
  }

  /**
   * Handle GET request to navigate to question page
   *
   * @return never
   */
  private function getQuestion()
  {
    $id = $this->request->params("id");
    $question = Question::findOne(["id" => $id]);
    if ($question) {
      $this->render("question", ["question" => $question, "title" => "Question ($question->id)"]);
    }
    Router::redirect(Helper::getURL("admin/assessment"));
  }
}