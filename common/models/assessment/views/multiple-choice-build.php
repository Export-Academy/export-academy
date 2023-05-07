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


<?= $this->renderAssets($this::POS_HEAD) ?>


<div id="multiple-choice-container">
  <?= $option ?>
</div>
<hr>
<button type="button" id="add-multiple-choice-option" class="btn gap-2">
  <div>Add Option</div>
</button>


<?= $this->renderAssets($this::POS_END) ?>


<script>
MultipleChoice.initialize();
</script>