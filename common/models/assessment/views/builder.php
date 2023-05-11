<?php

use common\models\assessment\QuestionType;
use components\HtmlComponent;
use lib\app\view\View;
use lib\util\html\Html;

/**
 * @var View $this
 */


$this->registerJsFile("index.js", View::POS_HEAD);
$questionTypes = QuestionType::find()->all();
$component = HtmlComponent::instance($this);
$imageModal = $component->render("media-components/insert-image-modal");
$insertImageButton = $component->render("media-components/insert-image-button", [
  "container" => "#main-image-container"
]);

$mediaComponent = $component->render("media-components/image-card", [
  "src" => "https://images.unsplash.com/photo-1683526976156-1a3f1a315049?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=872&q=80"
])


?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<?= Html::form_begin("/academy/admin/assessment/build") ?>



<div class="question-builder container">
  <div class="toolbar card rounded-0 p-2 my-2 hstack justify-content-between gap-2">
    <div class="btn-group">
      <?= $insertImageButton ?>
      <button type="button" class="btn">
        <i data-feather="video"></i>
      </button>
    </div>

    <button class="btn btn-dark rounded-0">Save</button>
  </div>
  <div class="card rounded-0 p-4 position-relative question-prompt-container">

    <div class="fw-bold fs-5 my-2">Create Question</div>
    <div class="row">
      <div class="col-md-7 col-sm-12">
        <?= HtmlComponent::textarea($this, "question[prompt]", "", ["placeholder" => "Enter Question Prompt here", "variant" => "flushed"]); ?>
      </div>
      <div class="col-md-5 col-sm-12">
        <div class="row" id="main-image-container">
        </div>
        <?= Html::hiddenInput("", "type", ["id" => "question-type-input", "required" => true]) ?>
        <div class="btn-group w-100 border-0">
          <button type="button" data-bs-toggle="dropdown" class="btn rounded-0 border" id="current-question-type">
            Select Question Type
          </button>
          <ul class="dropdown-menu rounded-0 w-100">
            <?php foreach ($questionTypes as $type) : ?>
              <li>
                <button type="button" class="dropdown-item question-type" data-type="<?= $type->id ?>"><?= $type->name ?></button>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

    </div>

    <div class="card my-2 p-3 rounded-0" id="question-builder-container">
      <div class="text-center p-5">Please Select Question Type</div>
    </div>
  </div>
</div>

<?= Html::form_end() ?>

<?= $imageModal ?>

<?php

$script = <<< JS
  let MultipleChoice = Checkbox = Dropdown = null;
  Question.start();
JS;


$this->registerJs($script);
