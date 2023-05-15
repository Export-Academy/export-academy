<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\assessment\Question;
use common\models\assessment\QuestionType;
use components\BaseComponent;
use components\HtmlComponent;
use components\ModelComponent;
use lib\app\view\View;

class ComponentController extends Controller
{
  public function actionRender()
  {
    $view = $this->getView();
    $handler = $this->request->data("handler", null);
    $name = $this->request->data("name");
    $params = $this->request->data("params", []);

    $component = HtmlComponent::instance($view);

    if (isset($handler) && class_exists($handler)) {
      $instance = new $handler;

      if ($instance instanceof ModelComponent) {
        $component = $instance::generate($view);
      }

      if ($instance instanceof BaseComponent) {
        $component = $instance::instance($view);
      }
    }


    $content = $component->render($name, $params);
    if ($content) {
      $content = $view->renderAssets(View::POS_HEAD) . "\n" . $content . "\n" . $view->renderAssets(View::POS_END) . "\n" . $view->renderAssets(View::POS_LOAD);;
      $this->renderView($content);
      return;
    }

    $this->jsonResponse("Component Not Found");
    return;
  }



  public function actionQuestionBuild()
  {
    $typeId = $this->request->data("type");

    $type = QuestionType::findOne(["id" => $typeId]);

    if (!isset($type)) {
      $this->jsonResponse("Not Found");
      return;
    }


    $handler = new $type->handler;

    if ($handler instanceof Question) {

      $view = $this->getView();

      $instance = $handler::generate($view);
      $content = $instance->renderBuild();


      if ($content) {
        $content = $view->renderAssets(View::POS_HEAD) . "\n" . $content . "\n" . $view->renderAssets(View::POS_END) . "\n" . $view->renderAssets(View::POS_LOAD);
        $this->renderView($content);
        return;
      }
    }


    isset($content) ? $this->renderView($content) : $this->jsonResponse("Not Found");
  }
}
