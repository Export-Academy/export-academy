<?php

use common\models\assessment\OpenEnd;
use components\HtmlComponent;
use lib\app\view\interface\IViewable;

use lib\app\view\View;

/**
 * @var View $this
 * @var IViewable $context
 */



$context = $this->context;

?>



<?php if ($context instanceof OpenEnd) : ?>



  <div>
    <?php if ($context->length === $context::LONG_ANSWER) : ?>
      <?= HtmlComponent::textarea($this, "answer", "", ["placeholder" => "Answer here...", "class" => "w-100", "required" => true]) ?>
    <?php endif; ?>
    <?php if ($context->length === $context::SHORT_ANSWER) : ?>
      <?= HtmlComponent::input($this, "answer", "", ["placeholder" => "Answer here...", "class" => "w-100", "required" => true]) ?>
    <?php endif; ?>
  </div>


<?php endif; ?>