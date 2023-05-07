<?php

use lib\app\view\View;
use components\HtmlComponent;

/**
 * @var View $this
 */

$input = HtmlComponent::input($this, "Option[" . $option . "]", "", ["placeholder" => "Option " . $option . "", "variant" => "flushed"]);
$component = HtmlComponent::instance($this);

$button = $component->render("media-components/insert-image-button", [
  "container" => "12"
])

?>

<?= $this->renderAssets($this::POS_HEAD) ?>

<div class="hstack gap-2 option-container my-2">
  <i data-feather="circle"></i>
  <?= $input ?>
  <?= $button ?>
  <button type="button" class="btn btn-sm remove-option">
    <i data-feather="x"></i>
  </button>
</div>

<?= $this->renderAssets($this::POS_END) ?>
<?= $this->renderAssets($this::POS_LOAD) ?>