<?php

use common\models\assessment\Checkboxes;
use lib\app\view\View;
use components\HtmlComponent;

/**
 * @var View $this
 */

$prefix = $prefix ?? "";


if (!isset($option)) {
  $input = HtmlComponent::textarea($this, Checkboxes::class . "[" . $key . "]", $value, ["placeholder" => "Option " . $key . "", "variant" => "flushed"]);
} else {
  $input = HtmlComponent::textarea($this, Checkboxes::class . "[" . $option . "]", null, ["placeholder" => "Option " . $option . "", "variant" => "flushed"]);
}

?>

<div class="hstack gap-2 <?= $prefix ?>option-container my-2">
  <i data-feather="square" width="16" height="16"></i>
  <?= $input ?>
  <button type="button" class="btn <?= $prefix ?>remove-option">
    <i data-feather="x" width="16" height="16"></i>
  </button>
</div>