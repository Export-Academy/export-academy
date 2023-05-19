<?php

use common\models\assessment\Answer;
use common\models\assessment\Question;
use components\HtmlComponent;
use lib\app\log\Logger;
use lib\app\view\View;
use lib\util\Helper;
use lib\util\html\Html;

/**
 * @var Question $question
 * @var View $this
 */


$this->registerView($question->getView());


$questionAnswers = $question->questionAnswers;
$linkedQuestion = $question->linkQuestion;

$linkedFromQuestions = $question->linked;

$questionOptions = Question::dropdownOptions($question->id);

?>



<div class="row">
  <div class="col-lg-7 col-sm-12">
    <div class="card">
      <div class="card-header fw-semibold">
        QID-<?= $question->id ?>
      </div>
      <div>
        <?= $question->renderBuilder() ?>
      </div>
    </div>
  </div>


  <div class="col-lg-5 col-sm-12">
    <div class="card">
      <div class="card-body">
        <div class="border bg-light p-3 my-2">
          <?= Html::form_begin(Helper::getURL("/admin/assessment/update")) ?>
          <?= Html::hiddenInput($question->id, "id") ?>
          <div class="hstack justify-content-between">

            <div class="fw-bold fs-5">Question Settings</div>
            <div class="hstack gap-1">
              <button class="btn btn-sm btn-light">Save Changes</button>
              <button type="button" class="btn btn-sm btn-light">Delete Question</button>
            </div>
          </div>
          <hr>
          <div class="vstack gap-3">
            <div class="form-check form-switch mt-4">
              <input name="<?= get_class($question) ?>[enabled]" class="form-check-input" type="checkbox" role="switch" id="question-enable-toggle" <?= $question->enabled ? "checked" : "" ?>>
              <label class="form-check-label fs-6" for="question-enable-toggle"><?= "Enable/Disable" ?></label>
            </div>

            <div class="vstack">
              <?php if ($linkedQuestion) : ?>
                <small class="fw-semibold text-muted">Linked Question</small>
                <div class="bg-white border my-2 hstack gap-3 justify-content-between p-2">
                  <div class="vstack">
                    <small class="fw-semibold">QID-<?= $linkedQuestion->id ?> <small class="text-muted">(<?= $linkedQuestion->questionType->name ?>)</small></small>
                    <small class="text-muted"><?= $linkedQuestion->prompt ?></small>
                  </div>
                  <div class="hstack gap-2">
                    <a href="<?= Helper::getURL("/admin/assessment/question?id=" . $linkedQuestion->id) ?>" class="btn btn-sm btn-light border">Edit</a>
                    <button type="button" id="unlink-button" data-type="<?= Question::class ?>" data-id="<?= $question->id ?>" class="btn btn-sm" data-toggle="tooltip" title="Unlink Question"><i data-feather="link" width="16" height="16"></i></button>
                  </div>

                </div>
              <?php else : ?>
                <?= HtmlComponent::dropdown($this, get_class($question) . "[link]", null, $questionOptions, ["label" => "Select Linked Question"]) ?>
                <button type="button" class="btn btn-light btn-sm w-100 my-3 border" data-bs-toggle="offcanvas" data-bs-target="#question-builder">Create Link
                  Question</button>
              <?php endif; ?>
            </div>

            <div class="vstack">
              <small class="fw-semibold text-muted">Linked From</small>
              <?php if (empty($linkedFromQuestions)) : ?>
                <small class="text-center p-3">No Links</small>
              <?php endif; ?>
              <?php foreach ($linkedFromQuestions as $linked) : ?>
                <div class="bg-white border my-2 hstack gap-3 justify-content-between p-2">
                  <div class="vstack">
                    <small class="fw-semibold">QID-<?= $linked->id ?> <small class="text-muted">(<?= $linked->questionType->name ?>)</small></small>
                    <small class="text-muted"><?= $linked->prompt ?></small>
                  </div>
                  <a href="<?= Helper::getURL("/admin/assessment/question?id=" . $linked->id) ?>" class="btn btn-sm btn-light border">Edit</a>
                </div>
              <?php endforeach; ?>
            </div>

          </div>
          <?= Html::form_end() ?>
        </div>


        <?= Html::form_begin(Helper::getURL("/admin/assessment/unlink"), "POST", ["id" => "unlink-form"]) ?>
        <?= Html::hiddenInput(null, "id", ["id" => "unlink-id-input"]) ?>
        <?= Html::hiddenInput(null, "type", ["id" => "unlink-type-input"]) ?>
        <?= Html::form_end() ?>

        <div class="border bg-light p-3 my-2">
          <div class="hstack justify-content-between">
            <div class="fw-bold fs-5">Answers</div>
            <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#question-answer"><i data-feather="plus" width="16" length="16"></i></button>
          </div>

          <hr>


          <?php if (empty($questionAnswers)) : ?>

            <div class="text-center p-4">No answer provided</div>

          <?php endif; ?>

          <?php foreach ($questionAnswers as $instance) :
            $answer = $instance->answer;
            $linked = $answer->linked;
          ?>
            <div class="p-3 bg-white border my-2">
              <?= Html::form_begin(Helper::getURL("/admin/assessment/update_answer"), "POST") ?>
              <?= Html::hiddenInput($answer->id, "id") ?>
              <div class="vstack gap-2 justify-content-between">
                <div class="fw-semibold pb-1">Response</div>
                <div class="px-2">
                  <?= $question->getAnswer($answer) ?>
                </div>

                <?php if ($linked) : ?>
                  <div class="bg-white border my-2 hstack gap-3 justify-content-between p-2">
                    <div class="vstack">
                      <small class="fw-semibold">QID-<?= $linked->id ?> <small class="text-muted">(<?= $linked->questionType->name ?>)</small></small>
                      <small class="text-muted"><?= $linked->prompt ?></small>
                    </div>
                    <div class="hstack gap-2">
                      <a href="<?= Helper::getURL("/admin/assessment/question?id=" . $linked->id) ?>" class="btn btn-sm btn-light border">Edit</a>
                      <button type="button" id="unlink-button" data-type="<?= Answer::class ?>" data-id="<?= $answer->id ?>" class="btn btn-sm" data-toggle="tooltip" title="Unlink Question"><i data-feather="link" width="16" height="16"></i></button>
                    </div>
                  </div>
                <?php else : ?>
                  <?= HtmlComponent::dropdown($this, get_class($answer) . "[link]", null, $questionOptions, ["label" => "Select Linked Question"]) ?>
                <?php endif; ?>
              </div>
              <div class="hstack justify-content-end border-top pt-1 mt-1">
                <button class="btn btn-light">Save</button>
                <button type="button" class="btn btn-sm" data-toggle="tooltip" title="Delete Question"><i data-feather="trash" width="16" height="16"></i></button>
              </div>
              <?= Html::form_end() ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="offcanvas-end offcanvas w-75" tabindex="-1" id="question-builder">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasBottomLabel">Create New Question</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <?= Question::generate($this)->renderBuilder($question->id) ?>
  </div>
</div>


<?php


$view = $question->renderView();
$beginForm = Html::form_begin(Helper::getURL("/admin/assessment/submit_answer"));
$hiddenInput = Html::hiddenInput($question->id, "id");
$endForm = Html::form_end();
$content = <<< HTML
$beginForm
$hiddenInput
<div class="vstack gap-2">
      <div class="border-bottom py-1 px-4">
        <p class="fw-semibold fs-6">$question->prompt</p>
      </div>
      $view
    </div>
    <div class="hstack mt-5 justify-content-end">
      <button class="btn btn-light" >Add Answer</button>
    </div>
    $endForm
HTML;

$header = <<< HTML

<div class="fs-4 fw-semi-bold">Add Answer</div>

HTML;


?>



<?= HtmlComponent::instance($this)->render("modal-component/modal", [
  "header" => $header,
  "content" => $content,
  "size" => "lg",
  "id" => "question-answer",
  "showFooter" => false
]) ?>



<?php



$script = <<< JS
$("#unlink-button").on("click", async function (e) {

  const target = $(e.currentTarget);
  const typeInput = $("#unlink-type-input");
  const idInput = $("#unlink-id-input");


  typeInput.val(target.data("type"));
  idInput.val(target.data("id"));


  $("form#unlink-form").submit();
})
JS;


$this->registerJs($script);


Logger::log($_SESSION);
