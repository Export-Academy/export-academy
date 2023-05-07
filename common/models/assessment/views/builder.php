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
  "container" => "12"
])


?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<?= Html::form_begin("#") ?>



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
      <div class="col-md-8 col-sm-12">

        <div class="quill-container">
          <div id="question-prompt-quill">
          </div>
        </div>

      </div>
      <div class="col-md-4 col-sm-12">
        <div class="btn-group w-100 border-0">
          <button type="button" data-bs-toggle="dropdown" class="btn rounded-0 border" id="current-question-type">
            Select Question Type
          </button>
          <ul class="dropdown-menu rounded-0 w-100">
            <?php foreach ($questionTypes as $type) : ?>
            <li>
              <button type="button" class="dropdown-item question-type"
                data-type="<?= $type->id ?>"><?= $type->name ?></button>
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

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>


<?= $imageModal ?>

<?php

$script = <<< JS
  let quill = new Quill('#question-prompt-quill', {
    debug: 'info',
    theme: 'snow',
    placeholder: "Question Prompt...",
    bounds: '.quill-container'
  });
  let MultipleChoice = Checkbox = Dropdown = null;
  Question.start();
JS;


$this->registerJs($script);