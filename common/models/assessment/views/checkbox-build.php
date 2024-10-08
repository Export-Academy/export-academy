<?php

use common\models\assessment\Checkboxes;
use lib\app\view\View;


/**
 * @var View $this
 * @var Question $question
 */

$this->registerJsFile("checkbox-build", $this::POS_END);

$prefix = $prefix ?? "";

$context = $this->context;
$option = "";


if ($context instanceof Checkboxes) {
  $option = $context->render("checkbox-option", ["option" => 1, "prefix" => $prefix]);
}

?>


<div id="<?= $prefix ?>checkbox-container">
  <?php if (isset($question->options) && $question instanceof Checkboxes) : ?>
    <?php foreach ($question->options as $key => $value) : ?>
      <?= $question->render("checkbox-option", ["key" => $key, "value" => $value, "prefix" => $prefix]) ?>
    <?php endforeach; ?>
  <?php else : ?>
    <?= $option ?>
  <?php endif; ?>
</div>
<hr>
<button type="button" id="<?= $prefix ?>add-checkbox-option" class="btn gap-2">
  <div>Add Option</div>
</button>


<?php

$script = <<< JS
Checkbox.initialize("$prefix");
JS;

$this->registerJs($script, View::POS_END);
