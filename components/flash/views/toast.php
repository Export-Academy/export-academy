<?php

use components\flash\Flash;
use lib\app\view\View;

/** 
 * @var View $this
 */


$this->registerSCSSFile("toast");
?>



<div class="toast show text-<?= $category === Flash::WARNING || $category === Flash::INFO ? "black" : "white" ?> bg-<?= $category ?>" role="alert" aria-live="assertive" aria-atomic="true">

  <?php if (isset($title)) : ?>
    <div class="toast-header">
      <strong class="me-auto"><?= $title ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>

    <div class="toast-body">
      <?= $message ?>
    </div>
  <?php else : ?>

    <div class="d-flex justify-content-between align-items-center px-2">
      <div class="toast-body">
        <?= $message ?>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  <?php endif; ?>


</div>