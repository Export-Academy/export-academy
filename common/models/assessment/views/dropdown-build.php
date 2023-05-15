<?php

use common\models\assessment\Dropdown;
use lib\app\view\View;


/**
 * @var View $this
 */
$this->registerJsFile("dropdown-build", $this::POS_END);


$context = $this->context;
$option = "";
if ($context instanceof Dropdown) {
  $option = $context->render("dropdown-option", ["option" => 1]);
}

?>


<div id="dropdown-container">
  <?php if (isset($question->type) && $question instanceof Dropdown) : ?>
    <?php foreach ($question->options as $key => $value) : ?>
      <?= $question->render("dropdown-option", ["key" => $key, "value" => $value]) ?>
    <?php endforeach; ?>
  <?php else : ?>
    <?= $option ?>
  <?php endif; ?>
</div>
<hr>
<button type="button" id="add-dropdown-option" class="btn gap-2">
  <div>Add Option</div>
</button>