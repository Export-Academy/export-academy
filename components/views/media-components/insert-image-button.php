<?php

use lib\app\view\View;

/**
 * @var View $this
 */
$this->registerJs("InsertImageModal.initialize();", $this::POS_LOAD);
$this->registerSCSSFile("media-component.scss");
?>


<button type="button" data-container="<?= $container ?>" class="btn btn-sm insert-image">
  <i data-feather="image"></i>
</button>