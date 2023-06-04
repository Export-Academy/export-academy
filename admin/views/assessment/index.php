<?php

use common\models\assessment\Question;
use components\modal\Modal;
use components\table\DataTable;
use lib\app\view\View;
use lib\util\Helper;
use lib\util\html\Html;

/**
 * @var View $this
 * @var Question $component
 */
$questions = Question::find()->all();
?>


<div class="my-5">
  <div class="hsack justify-content-right p-2">
    <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#question-builder">
      <div class="hstack justify-content-between gap-3 text-nowrap">
        Create
        Question
        <i data-feather="plus" width="16" height="16"></i>
      </div>
    </button>
  </div>
  <div class="card mt-2">
    <div class="card-body">
      <?= DataTable::instance($this)->table($questions, [
        "id" => [
          "label" => "ID",
          "content" => function (Question $question) {
            return Html::tag("a", "QID-$question->id", ["class" => "text-nowrap", "href" => Helper::getURL("admin/assessment/question", ["id" => $question->id])]);
          }
        ],
        "prompt" => [
          "label" => "Prompt",
          "content" => function (Question $question) {
            return Html::tag("div", $question->prompt, ["class" => "fw-semibold text-muted text-wrap"]);
          }
        ],
        "type" => [
          "label" => "Question Type",
          "content" => function (Question $question) {
            return Html::tag("div", $question->questionType->name, ["class" => "fw-semibold text-muted"]);
          }
        ],
        "action" => [
          "label" => "Action",
          "content" => function (Question $question) {
            return Html::tag("a", "Edit", ["href" => Helper::getURL("admin/assessment/question", ["id" => $question->id]), "class" => "btn"]);
          }
        ]
      ]) ?>
    </div>
  </div>

</div>



<?php



?>



<?= Modal::instance($this)->show("question-builder", Question::generate($this)->renderBuilder(), null, null, ["showHeader" => false, "showFooter" => false, "size" => Modal::MODAL_LG]) ?>