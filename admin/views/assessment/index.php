<?php

use common\models\assessment\Question;
use components\HtmlComponent;
use lib\app\view\View;
use lib\util\Helper;
use lib\util\html\Html;

/**
 * @var View $this
 * @var Question $component
 */

$component = Question::generate($this);
$questions = Question::find()->all();


?>




<div class="offcanvas-end offcanvas w-75" tabindex="-1" id="question-builder">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasBottomLabel">Create New Question</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <?= $component->renderBuilder() ?>
  </div>
</div>

<div class="my-5 container">
  <div class="hsack justify-content-right border p-2">
    <button class="btn btn-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#question-builder">
      <div class="hstack justify-content-between gap-3 text-nowrap">
        Create
        Question
        <i data-feather="plus" width="16" height="16"></i>
      </div>
    </button>
  </div>
  <div class="card mt-2">
    <div class="card-body">
      <?= HtmlComponent::instance($this)->render('data-table-component/data-table', [
        "data" => $questions,
        "columns" => [
          "id" => [
            "label" => "ID",
            "content" => function (Question $question) {
              return Html::tag("a", "QID-$question->id", ["href" => Helper::getURL("/admin/assessment/question?id=" . $question->id)]);
            }
          ],
          "prompt" => [
            "label" => "Prompt",
            "content" => function (Question $question) {
              return Html::tag("small", $question->prompt, ["class" => "fw-semibold text-muted text-wrap"]);
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
              return Html::tag("a", "Edit", ["href" => Helper::getURL("/admin/assessment/question?id=" . $question->id), "class" => "btn btn-sm btn-light"]);
            }
          ]
        ]
      ]) ?>
    </div>
  </div>

</div>