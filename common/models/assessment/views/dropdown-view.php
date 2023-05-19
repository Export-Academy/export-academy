<?php

use common\models\assessment\Dropdown;
use components\HtmlComponent;
use lib\app\view\interface\IViewable;

use lib\app\view\View;

/**
 * @var View $this
 * @var IViewable $context
 */



$context = $this->context;

?>



<?php if ($context instanceof Dropdown) : ?>

<?= HtmlComponent::dropdown($this, Dropdown::class, null, $context->options) ?>

<?php endif; ?>