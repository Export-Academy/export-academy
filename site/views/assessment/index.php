<?php

use common\app\view\View;
use common\util\Helper;

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