<?php

use common\models\assessment\Boolean;
use components\HtmlComponent;
use lib\app\view\View;

/**
 * @var View $this
 */


if (isset($question) && $question instanceof Boolean) {
  $input1Component = HtmlComponent::input($this, Boolean::class . "[0]", $question->falseLabel, ["variant" => "flushed", "label" => "False Label"]);
  $input2Component = HtmlComponent::input($this, Boolean::class . "[1]", $question->trueLabel, ["variant" => "flushed", "label" => "True Label"]);
} else {
  $input1Component = HtmlComponent::input($this, Boolean::class . "[0]", "No", ["variant" => "flushed", "label" => "False Label"]);
  $input2Component = HtmlComponent::input($this, Boolean::class . "[1]", "Yes", ["variant" => "flushed", "label" => "True Label"]);
}

?>
<div class="hstack w-100 justify-content-between gap-3">
  <div class="p-4 w-100">
    <?= $input2Component ?>
  </div>
  <div class="p-4 w-100">
    <?= $input1Component ?>
  </div>
</div>