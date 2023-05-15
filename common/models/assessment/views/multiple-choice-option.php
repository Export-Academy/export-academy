<?php

use common\models\assessment\MultipleChoice;
use lib\app\view\View;
use components\HtmlComponent;

/**
 * @var View $this
 */
if (!isset($option)) {
  $input = HtmlComponent::textarea($this, MultipleChoice::class . "[" . $key . "]", $value, ["placeholder" => "Option " . $key . "", "variant" => "flushed"]);
} else {
  $input = HtmlComponent::textarea($this, MultipleChoice::class . "[" . $option . "]", null, ["placeholder" => "Option " . $option . "", "variant" => "flushed"]);
}
?>

<div class="hstack gap-2 option-container my-2">
  <i data-feather="circle"></i>
  <?= $input ?>
  <button type="button" class="btn btn-sm remove-option">
    <i data-feather="x"></i>
  </button>
</div>