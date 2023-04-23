<?php

use lib\app\http\Request;
use lib\util\html\HtmlHelper;

$state = $state ?? "end" ?>
<?php if (isset($state) || $state == "begin") : ?>
  <form action="<?= $action ?>" method="<?= $method ?? "post" ?>">
    <?= HtmlHelper::hiddenInput(Request::sessionToken(), Request::CSRF) ?>
  <?php else : ?>
  </form>
<?php endif; ?>