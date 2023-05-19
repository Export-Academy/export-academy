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


$prefix = $prefix ?? "";


$question = $this->context ?? Question::generate($this);
$isNewRecord = !isset($question->id);

$htmlComponent = HtmlComponent::instance($this);
$types = QuestionType::find()->all();


$ImageModal = $htmlComponent->render("media-components/insert-image-modal");
$InsertImageButton = $htmlComponent->render("media-components/insert-image-button", ["container" => "#main-image-container"]);


$this->registerJsFile("index.js", $this::POS_HEAD);

?>

<?= Html::form_begin("/academy/admin/assessment/build") ?>

<?= $isNewRecord ? null : Html::hiddenInput($question->id, "question[id]")  ?>


<?= isset($link) ? Html::hiddenInput($link, "link[id]") : "" ?>
<?= isset($link) ? Html::hiddenInput($linkType, "link[type]") : "" ?>

<div class="<?= $prefix ?>question-builder">
  <div class="toolbar p-2 my-2 hstack justify-content-between gap-2 border">

    <div class="hstack gap-2">
      <!-- <?= $InsertImageButton ?> -->
    </div>

    <button type="submit" class="btn btn-dark"><?= ($isNewRecord ? ($link ? "Link to " . ($linkType === Question::QUESTION_LINK ? "Question" : "Answer") : "Save") : "Update") ?></button>

  </div>


  <div class="card p-2 position-relative <?= $prefix ?>question-prompt-container border-0">

    <div class="fw-bold fs-5 px-4"><?= $isNewRecord ? "Create Question" : "Update Question" ?></div>
    <hr>
    <div class="row">
      <div class="col-md-7 col-sm-12">
        <?= HtmlComponent::textarea($this, "question[prompt]", $question->prompt ?? null, ["placeholder" => "Enter Question Prompt here", "variant" => "flushed"]); ?>
      </div>

      <div class="col-md-5 col-sm-12">
        <!-- <div class="row" id="main-image-container">


          <?php if (!$isNewRecord) : ?>
            <?php foreach ($question->files as $asset) : ?>
              <?= $htmlComponent->render("media-components/image-card", [
                "src" => $asset->getURL(),
                "id" => $asset->id
              ]) ?>
            <?php endforeach; ?>
          <?php endif; ?>


        </div> -->

        <?= Html::hiddenInput($question->type ?? null, "type", ["id" => $prefix . "question-type-input", "required"
        => true]) ?>


        <div class="btn-group w-100 border-0">

          <button type="button" data-bs-toggle="dropdown" class="btn rounded-3 border" id="<?= $prefix ?>current-question-type">
            <?= $isNewRecord ? "Select Question Type" : $question->questionType->name  ?>
          </button>


          <ul class="dropdown-menu rounded-3 w-100">
            <?php foreach ($types as $type) : ?>
              <li>
                <button type="button" class="dropdown-item <?= $prefix ?>question-type" data-type="<?= $type->id ?>"><?= $type->name ?></button>
              </li>
            <?php endforeach; ?>
          </ul>

        </div>
      </div>
    </div>

    <div class="card my-2 p-3 rounded-0" id="<?= $prefix ?>question-builder-container">
      <?php if ($isNewRecord) : ?>
        <div class="text-center p-5">Please Select Question Type</div>
      <?php else : /** @var Question $question **/ ?>
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
JS;


$this->registerJs($script, View::POS_HEAD);
$this->registerJs("Question.start('$prefix');");
