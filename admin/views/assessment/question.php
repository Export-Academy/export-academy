<?php

use common\models\assessment\Answer;
use common\models\assessment\Question;
use components\form\FormComponent;
use components\HtmlComponent;
use components\modal\Modal;
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

  <!-- Question Builder -->
  <div class="col-lg-7 col-sm-12">
    <div class="card mb-4">
      <div class="card-body">
        <?= $question->renderBuilder() ?>
      </div>
    </div>
  </div>


  <div class="col-lg-5 col-sm-12">

    <div class="card">
      <div class="card-body">

        <?= FormComponent::instance($this)->begin(Helper::getURL("admin/assessment/question")) ?>
        <?= Html::hiddenInput($question->id, "id") ?>


        <div class="hstack justify-content-between">
          <div class="fw-bold fs-5">Question Settings</div>
          <div class="hstack gap-1">
            <button class="btn">Save Changes</button>
            <button type="button" class="btn delete-button" data-type="<?= Question::class ?>"
              data-id="<?= $question->id ?>">Delete Question</button>
          </div>
        </div>


        <hr>


        <div class="vstack gap-3">

          <div class="form-check form-switch mt-4">
            <input name="<?= get_class($question) ?>[enabled]" class="form-check-input" type="checkbox" role="switch"
              id="question-enable-toggle" <?= $question->enabled ? "checked" : "" ?>>
            <label class="form-check-label fs-6" for="question-enable-toggle"><?= "Enable/Disable" ?></label>
          </div>

          <div class="vstack">

            <?php if ($linkedQuestion) : ?>

            <small class="fw-semibold text-muted">Linked Question</small>
            <div class="my-2 hstack gap-3 justify-content-between p-2">
              <div class="vstack">
                <small class="fw-semibold">QID-<?= $linkedQuestion->id ?> <small
                    class="text-muted">(<?= $linkedQuestion->questionType->name ?>)</small></small>
                <small class="text-muted"><?= $linkedQuestion->prompt ?></small>
              </div>
              <div class="hstack gap-2">
                <a href="<?= Helper::getURL("admin/assessment/question", ["id" => $linkedQuestion->id]) ?>"
                  class="btn">Edit</a>
                <button type="button" data-type="<?= Question::class ?>" data-id="<?= $question->id ?>"
                  class="btn unlink-button" data-toggle="tooltip" title="Unlink Question"><i data-feather="link"
                    width="16" height="16"></i></button>
              </div>
            </div>

            <?php else : ?>

            <?= HtmlComponent::dropdown($this, get_class($question) . "[link]", null, $questionOptions, ["label" => "Select Linked Question"]) ?>
            <button type="button" class="btn w-100 my-3" data-bs-toggle="modal"
              data-bs-target="#question-builder">Create Link
              Question</button>


            <?php endif; ?>
          </div>

          <div class="vstack">
            <small class="fw-semibold text-muted">Linked From</small>
            <?php if (empty($linkedFromQuestions)) : ?>
            <small class="text-center p-3">No Links</small>
            <?php endif; ?>
            <?php foreach ($linkedFromQuestions as $linked) : ?>
            <div class="my-2 hstack gap-3 justify-content-between p-2">
              <div class="vstack">
                <small class="fw-semibold">QID-<?= $linked->id ?> <small
                    class="text-muted">(<?= $linked->questionType->name ?>)</small></small>
                <small class="text-muted"><?= $linked->prompt ?></small>
              </div>
              <a href="<?= Helper::getURL("admin/assessment/question", ["id" => $linked->id]) ?>" class="btn">Edit</a>
            </div>
            <?php endforeach; ?>
          </div>

        </div>
        <?= FormComponent::instance($this)->end() ?>
      </div>
    </div>


    <div class="card mt-4">
      <div class="card-body">
        <div class="hstack justify-content-between">
          <div class="fw-bold fs-5">Answers</div>
          <button class="btn" data-bs-toggle="modal" data-bs-target="#question-answer"><i data-feather="plus" width="16"
              length="16"></i></button>
        </div>

        <hr>


        <?php if (empty($questionAnswers)) : ?>

        <div class="text-center p-4">No answer provided</div>

        <?php endif; ?>

        <?php foreach ($questionAnswers as $instance) :
          $answer = $instance->answer;
          $linked = $answer->linked;
        ?>
        <div class="p-3 my-2">
          <?= FormComponent::instance($this)->begin(Helper::getURL("admin/assessment/answer")) ?>
          <?= Html::hiddenInput($answer->id, "id") ?>
          <div class="vstack gap-2 justify-content-between">
            <div class="fw-semibold pb-1">Response</div>
            <div class="px-2">
              <?= $question->getAnswer($answer) ?>
            </div>

            <?php if ($linked) : ?>
            <div class="my-2 hstack gap-3 justify-content-between p-2">
              <div class="vstack">
                <small class="fw-semibold">QID-<?= $linked->id ?> <small
                    class="text-muted">(<?= $linked->questionType->name ?>)</small></small>
                <small class="text-muted"><?= $linked->prompt ?></small>
              </div>
              <div class="hstack gap-2">
                <a href="<?= Helper::getURL("admin/assessment/question", ["id" => $linked->id]) ?>" class="btn">Edit</a>
                <button type="button" data-type="<?= Answer::class ?>" data-id="<?= $answer->id ?>"
                  class="btn unlink-button" data-toggle="tooltip" title="Unlink Question"><i data-feather="link"
                    width="16" height="16"></i></button>
              </div>
            </div>
            <?php else : ?>
            <?= HtmlComponent::dropdown($this, get_class($answer) . "[link]", null, $questionOptions, ["label" => "Select Linked Question"]) ?>
            <?php endif; ?>
          </div>
          <div class="hstack justify-content-end gap-2">
            <button class="btn">Save</button>
            <button type="button" class="btn delete-button" data-toggle="tooltip" title="Delete Answer"
              data-type="<?= Answer::class ?>" data-id="<?= $answer->id ?>"><i data-feather="trash" width="16"
                height="16"></i></button>
          </div>
          <?= FormComponent::instance($this)->end() ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>



  </div>


</div>

<?php


$view = $question->renderView();
$beginForm = FormComponent::instance($this)->begin(Helper::getURL("admin/assessment/build_answer"));
$hiddenInput = Html::hiddenInput($question->id, "id");
$endForm = FormComponent::instance($this)->end();
$content = <<< HTML
    $beginForm
    $hiddenInput
    <div class="vstack gap-2">
        <div class="py-1 px-4">
          <p class="fw-semibold fs-6">$question->prompt</p>
        </div>
          $view 
    </div>
    <div class="hstack mt-5 justify-content-end">
        <button type="submit" class="btn" >Add Answer</button>
    </div>
    $endForm
HTML;

$header = <<< HTML
  <div class="fs-4 fw-semi-bold">Add Answer</div>
HTML;

?>





<?= FormComponent::instance($this)->deleteForm(Helper::getURL("admin/assessment/unlink"), ".unlink-button") ?>
<?= FormComponent::instance($this)->deleteForm(Helper::getURL("admin/assessment/delete"), "button.delete-button") ?>


<?= Modal::instance($this)->show("question-answer", $content, $header, null, ["showFooter" => false, "size" => "lg"]) ?>
<?= Modal::instance($this)->show("question-builder", Question::generate($this)->renderBuilder($question->id), null, null, ["size" => Modal::MODAL_LG, "showHeader" => false, "showFooter" => false]) ?>