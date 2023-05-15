<?php

use common\models\assessment\Question;
use common\models\assessment\QuestionType;
use components\HtmlComponent;
use lib\app\view\View;
use lib\util\html\Html;

/**
 * @var View $this
 * @var Question $question
 */


$question = $this->context ?? Question::generate($this);
$isNewRecord = !isset($question->id);

$htmlComponent = HtmlComponent::instance($this);
$types = QuestionType::find()->all();


$ImageModal = $htmlComponent->render("media-components/insert-image-modal");
$InsertImageButton = $htmlComponent->render("media-components/insert-image-button", ["container" => "#main-image-container"]);


$this->registerJsFile("index.js", $this::POS_HEAD);

?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


<?= Html::form_begin("/academy/admin/assessment/build") ?>


<?= $isNewRecord ? null : Html::hiddenInput($question->id, "question[id]")  ?>

<div class="question-builder container">
  <div class="toolbar card rounded-0 p-2 my-2 hstack justify-content-between gap-2">

    <div class="hstack gap-2">
      <?= $InsertImageButton ?>
    </div>

    <button type="submit" class="btn btn-dark rounded-0"><?= $isNewRecord ? "Save" : "Update" ?></button>

  </div>


  <div class="card rounded-0 p-4 position-relative question-prompt-container border-0">

    <div class="fw-bold fs-5 my-2"><?= $isNewRecord ? "Create Question" : "Update Question" ?></div>

    <div class="row">
      <div class="col-md-7 col-sm-12">
        <?= HtmlComponent::textarea($this, "question[prompt]", $question->prompt ?? null, ["placeholder" => "Enter Question Prompt here", "variant" => "flushed"]); ?>
      </div>

      <div class="col-md-5 col-sm-12">
        <div class="row" id="main-image-container">


          <?php if (!$isNewRecord) : ?>
            <?php foreach ($question->files as $asset) : ?>
              <?= $htmlComponent->render("media-components/image-card", [
                "src" => $asset->getURL(),
                "id" => $asset->id
              ]) ?>
            <?php endforeach; ?>
          <?php endif; ?>


        </div>

        <?= Html::hiddenInput($question->type ?? null, "type", ["id" => "question-type-input", "required" => true]) ?>


        <div class="btn-group w-100 border-0">

          <button type="button" data-bs-toggle="dropdown" class="btn rounded-0 border" id="current-question-type">
            <?= $isNewRecord ? "Select Question Type" : $question->questionType->name  ?>
          </button>


          <ul class="dropdown-menu rounded-0 w-100">
            <?php foreach ($types as $type) : ?>
              <li>
                <button type="button" class="dropdown-item question-type" data-type="<?= $type->id ?>"><?= $type->name ?></button>
              </li>
            <?php endforeach; ?>
          </ul>

        </div>
      </div>
    </div>

    <div class="card my-2 p-3 rounded-0" id="question-builder-container">
      <?php if ($isNewRecord) : ?>
        <div class="text-center p-5">Please Select Question Type</div>
      <?php else : ?>
        <?= $question->renderBuild() ?>
      <?php endif; ?>
    </div>

  </div>
</div>

<?= Html::form_end() ?>

<?= $ImageModal ?>

<?php

$script = <<< JS
  let MultipleChoice = Checkbox = Dropdown = null;
  Question.start();
JS;


$this->registerJs($script);
