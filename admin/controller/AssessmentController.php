<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\assessment\MultipleChoice;

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


  public function actionTest()
  {
    $this->jsonResponse(MultipleChoice::excludeProperty());
  }
}
