<?php

use lib\app\Request;
use lib\util\html\Html;

$state = $state ?? "end" ?>
<?php if ($state == "begin") : ?>
  <form action="<?= $action ?>" method="<?= $method ?? "post" ?>" <?= Html::renderAttributes($options ?? []) ?>>
    <?= Html::hiddenInput(Request::sessionToken(), Request::CSRF) ?>
  <?php else : ?>
  </form>
<?php endif; ?>