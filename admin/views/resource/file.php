<?php

use components\media\MediaComponent;
use lib\app\view\View;

/** @var View $this */
?>



<div class="container-fluid">
  <?= MediaComponent::instance($this)->view() ?>
</div>