<?php

use common\models\assessment\Dropdown;
use lib\app\view\View;


/**
 * @var View $this
 */
$this->registerJsFile("dropdown-build", $this::POS_END);

$prefix = $prefix ?? "";


$context = $this->context;
$option = "";
if ($context instanceof Dropdown) {
  $option = $context->render("dropdown-option", ["option" => 1, "prefix" => $prefix]);
}

?>


<div id="<?= $prefix ?>dropdown-container">
  <?php if (isset($question->type) && $question instanceof Dropdown) : ?>
  <?php foreach ($question->options as $key => $value) : ?>
  <?= $question->render("dropdown-option", ["key" => $key, "value" => $value, "prefix" => $prefix]) ?>
  <?php endforeach; ?>
  <?php else : ?>
  <?= $option ?>
  <?php endif; ?>
</div>
<hr>
<button type="button" id="<?= $prefix ?>add-dropdown-option" class="btn gap-2">
  <div>Add Option</div>
</button>

<?php
$script = <<< JS
Dropdown.initialize("$prefix");
JS;

$this->registerJs($script, View::POS_END);