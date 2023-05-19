<?php

use common\models\assessment\MultipleChoice;
use lib\app\view\View;


/**
 * @var View $this
 */
$this->registerJsFile("multiple-choice-build", View::POS_END);

$prefix = $prefix ?? "";

$context = $this->context;
$option = "";
if ($context instanceof MultipleChoice) {
  $option = $context->render("multiple-choice-option", ["option" => 1, "prefix" => $prefix]);
}
?>




<div id="<?= $prefix ?>multiple-choice-container">
  <?php if (isset($question->type) && $question instanceof MultipleChoice) : ?>
    <?php foreach ($question->options as $key => $value) : ?>
      <?= $question->render("multiple-choice-option", ["key" => $key, "value" => $value, "prefix" => $prefix]) ?>
    <?php endforeach; ?>
  <?php else : ?>
    <?= $option ?>
  <?php endif; ?>
</div>
<hr>
<button type="button" id="<?= $prefix ?>add-multiple-choice-option" class="btn gap-2">
  <div>Add Option</div>
</button>


<?php

$script = <<< JS
MultipleChoice.initialize("$prefix");
JS;

$this->registerJs($script, View::POS_END);
