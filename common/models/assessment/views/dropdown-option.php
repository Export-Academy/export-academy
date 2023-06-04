<?php

use common\models\assessment\Dropdown;
use lib\app\view\View;
use components\HtmlComponent;

/**
 * @var View $this
 */


$prefix = $prefix ?? "";

if (!isset($option)) {
  $input = HtmlComponent::input($this, Dropdown::class . "[" . $key . "]", $value, ["placeholder" => "Option " . $key . "", "variant" => "flushed"]);
} else {
  $input = HtmlComponent::input($this, Dropdown::class . "[" . $option . "]", null, ["placeholder" => "Option " . $option . "", "variant" => "flushed"]);
}
?>

<div class="hstack gap-2 <?= $prefix ?>option-container my-2">
  <div><?= isset($option) ? $option : $key ?>.</div>
  <?= $input ?>
  <button type="button" class="btn btn-icon <?= $prefix ?>remove-option">
    <i data-feather="x" width="16" height="16"></i>
  </button>
</div>