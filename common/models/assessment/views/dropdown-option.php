<?php

use common\models\assessment\Dropdown;
use lib\app\view\View;
use components\HtmlComponent;

/**
 * @var View $this
 */


if (!isset($option)) {
  $input = HtmlComponent::textarea($this, Dropdown::class . "[" . $key . "]", $value, ["placeholder" => "Option " . $key . "", "variant" => "flushed"]);
} else {
  $input = HtmlComponent::textarea($this, Dropdown::class . "[" . $option . "]", null, ["placeholder" => "Option " . $option . "", "variant" => "flushed"]);
}
?>

<div class="hstack gap-2 option-container my-2">
  <div><?= is_array($option) ? $option["key"] : $option ?>.</div>
  <?= $input ?>
  <button type="button" class="btn remove-option">
    <i data-feather="x"></i>
  </button>
</div>