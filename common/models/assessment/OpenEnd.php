<?php

namespace common\models\assessment;

use lib\util\Helper;

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

  public function getAnswer(Answer $answer)
  {
    if ($this->type !== $answer->type) return "Invalid Answer";
    $context = $answer->parseContext();
    $value = Helper::getValue("value", $context);
    return isset($value) ? (empty((str_replace(" ", "", $value))) ? "Blank Answer" : $value) : "Invalid Answer";
  }
}
