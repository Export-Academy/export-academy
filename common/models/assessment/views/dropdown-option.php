<?php

use lib\app\view\View;
use components\HtmlComponent;

/**
 * @var View $this
 */



$input = HtmlComponent::input($this, "option[" . $option . "]", "", ["placeholder" => "Dropdown Option " . $option . "", "variant" => "flushed"]);
$component = HtmlComponent::instance($this);
?>

<?= $this->renderAssets($this::POS_HEAD) ?>

<div class="hstack gap-2 option-container my-2">
  <div><?= $option ?>.</div>
  <?= $input ?>
  <button type="button" class="btn remove-option">
    <i data-feather="x"></i>
  </button>
</div>


<?= $this->renderAssets($this::POS_END) ?>
<?= $this->renderAssets($this::POS_LOAD) ?>