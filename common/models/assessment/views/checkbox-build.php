<?php

use common\models\assessment\Checkboxes;
use lib\app\view\View;


/**
 * @var View $this
 */
$this->registerJsFile("checkbox-build", $this::POS_END);

$context = $this->context;
$option = "";
if ($context instanceof Checkboxes) {
  $option = $context->render("checkbox-option", ["option" => 1]);
}

?>

<?= $this->renderAssets($this::POS_HEAD) ?>


<div id="checkbox-container">
  <?= $option ?>
</div>
<hr>
<button type="button" id="add-checkbox-option" class="btn gap-2">
  <div>Add Option</div>
</button>



<?= $this->renderAssets($this::POS_END) ?>

<script>
Checkbox.initialize();
</script>