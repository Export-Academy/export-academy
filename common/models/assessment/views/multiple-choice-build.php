<?php

use common\models\assessment\MultipleChoice;
use lib\app\view\View;


/**
 * @var View $this
 */
$this->registerJsFile("multiple-choice-build", $this::POS_END);


$context = $this->context;
$option = "";
if ($context instanceof MultipleChoice) {
  $option = $context->render("multiple-choice-option", ["option" => 1]);
}
?>




<div id="multiple-choice-container">
  <?php if (isset($question->type) && $question instanceof MultipleChoice) : ?>
    <?php foreach ($question->options as $key => $value) : ?>
      <?= $question->render("multiple-choice-option", ["key" => $key, "value" => $value]) ?>
    <?php endforeach; ?>
  <?php else : ?>
    <?= $option ?>
  <?php endif; ?>
</div>
<hr>
<button type="button" id="add-multiple-choice-option" class="btn gap-2">
  <div>Add Option</div>
</button>