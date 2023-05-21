<?php

use components\form\FormComponent;
use lib\app\Request;
use lib\app\view\View;
use lib\util\html\Html;

/**
 * @var View $this
 */

/** @var FormComponent */
$context = $this->context;

?>



<?php if ($state === $context::BEGIN) : ?>
  <form action="<?= $action ?>" method="<?= $method ?? "post" ?>" <?= Html::renderAttributes($options ?? []) ?>>
    <?= Html::hiddenInput(Request::sessionToken(), Request::CSRF) ?>
  <?php endif; ?>
  <?php if ($state === $context::END) : ?>
  </form>
<?php endif; ?>