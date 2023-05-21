<?php


namespace admin\controller;

use common\controller\Controller;

class MediaController extends Controller
{




  public function actionUpload()
  {
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
