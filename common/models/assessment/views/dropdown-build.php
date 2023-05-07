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

<?= $this->renderAssets($this::POS_HEAD) ?>


<div id="dropdown-container">
  <?= $option ?>
</div>
<hr>
<button type="button" id="add-dropdown-option" class="btn gap-2">
  <div>Add Option</div>
</button>



<?= $this->renderAssets($this::POS_END) ?>

<script>
Dropdown.initialize();
</script>