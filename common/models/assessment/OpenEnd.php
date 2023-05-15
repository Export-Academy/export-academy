<?php

namespace common\models\assessment;

class OpenEnd extends Question
{

  const LONG_ANSWER = "long.answer";
  const SHORT_ANSWER = "short.answer";

  public $length;

  public function renderBuild()
  {
    return $this->render("open-end-build", ["question" => $this]);
  }

  public static function createContext($context)
  {
    return ["length" => $context];
  }

  public function renderView()
  {
    return $this->render("open-end-view");
  }
}
