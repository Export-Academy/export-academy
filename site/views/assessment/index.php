<?php

use lib\app\view\View;
use lib\util\Helper;

/**
 * @var string $method
 * @var View $this
 */



$this->context->pageTitleName('Access Control');

?>



<div class="container">
  <h1>Access Controller Index</h1>
  <input class="form-control form-control-sm" />

  <?= $method ?>
</div>

<?= include Helper::getAlias('@site\views\assessment\assets\site.js') ?>