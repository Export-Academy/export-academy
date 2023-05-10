<?php

use lib\app\view\View;

/**
 * @var View $this
 */
$this->registerJs("InsertImageModal.initialize();", $this::POS_LOAD);
?>


<button type="button" data-hidden="<?= $input ?? "hidden" ?>" data-container="<?= $container ?>" class="btn btn-sm insert-image">
  <i data-feather="image"></i>
</button>