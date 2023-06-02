<?php


namespace admin\controller;

use common\controller\Controller;

class ResourceController extends Controller
{
  public function secure()
  {
    return [
      "requiresAuth" => ["*"]
    ];
  }

  public function actionIndex()
  {
    $this->render('index', ["title" => "Manage Resources"]);
  }


  public function actionFiles()
  {
    $this->render('file', ["title" => "File Manager"]);
  }
}
