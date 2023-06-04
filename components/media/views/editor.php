<?php

use components\HtmlComponent;
use lib\util\Helper;
use lib\util\html\Html;


$dir = explode("/", $asset->getPath());
$name = array_pop($dir);



?>
<?= Html::form_begin(Helper::getURL("admin/media/update")) ?>

<div class="container">
  <?= HtmlComponent::input($this, "name", $asset->getName(), ["variant" => "flushed", "label" => "Filename"]) ?>

  <?= Html::hiddenInput(implode("/", $dir), "path") ?>
  <?= Html::hiddenInput($asset->id, "id") ?>

  <div class="d-flex justify-content-end my-4">
    <button type="submit" class="btn">Save Changes</button>
  </div>
</div>

<?= Html::form_end() ?>