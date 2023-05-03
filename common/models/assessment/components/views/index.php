<?php

use components\Components;
use lib\app\view\View;
use lib\util\html\Html;

/**
 * @var View $this
 */


$this->registerJsFile("index.js");


?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<?= Html::form_begin("#") ?>



<div class="question-builder container">
  <div class="question-prompt-container">
    <div class="row">
      <div class="col-md-5 col-sm-12">
        <?php Components::textarea("Question[prompt]", "", ["placeholder" => "Enter Question Prompt here"])->render(); ?>
      </div>
      <div class="col-md-7 col-sm-12">
        <div class="btn-group w-100 border-0">
          <button type="button" data-bs-toggle="dropdown" class="btn border-0">
            Select Question Type
          </button>
          <ul class="dropdown-menu rounded-0 w-100">
            <li>
              <div class="dropdown-item">Multiple Choice</div>
            </li>
            <li>
              <div class="dropdown-item">Checkbox</div>
            </li>
            <li>
              <div class="dropdown-item">Ranking</div>
            </li>
            <li>
              <div class="dropdown-item">Dropdown</div>
            </li>
          </ul>
        </div>
        <div class="card" id="question-builder-container">
          <div class="card-body"></div>
        </div>
      </div>
    </div>
    <!-- <div id="prompt-editor"></div> -->

  </div>
</div>







<?= Html::form_end() ?>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<?php

$script = <<< JS
  let quill = new Quill("#prompt-editor", {
    theme: 'snow'
  })
JS;


// $this->registerJs($script);


// echo $this->renderPosition(View::POS_LOAD);