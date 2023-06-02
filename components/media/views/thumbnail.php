<?php

use common\models\resource\AssetModel;
use common\models\resource\format\handlers\ImageHandler;
use common\models\resource\format\handlers\TextHandler;
use common\models\resource\format\handlers\VideoHandler;
use components\form\FormComponent;
use lib\app\view\View;
use lib\util\Helper;

/**
 * @var View $this
 * @var AssetModel $asset
 */



$context = $this->context;
$format = $asset->assetFormat;

?>




<div>
  <div class="thumbnail-container" id="thumbnail-<?= $asset->getId() ?>" type="button" data-bs-toggle="dropdown">
    <div class="thumbnail-display">
      <?php if ($context instanceof VideoHandler) : ?>
      <i data-feather="film" width="28" height="28"></i>
      <?php endif; ?>

      <?php if ($context instanceof TextHandler) : ?>
      <i data-feather="file" width="28" height="28"></i>
      <?php endif; ?>


      <?php if ($context instanceof ImageHandler) : ?>
      <i data-feather="image" width="28" height="28"></i>
      <?php endif; ?>
    </div>
    <div class="detail-container">
      <div class="main">
        <?= $asset->getName() ?><small><?= $asset->getPath() ?></small>
      </div>
      <small class="text-muted"><?= $asset->mime ?></small>

    </div>
  </div>
  <ul class="dropdown-menu">
    <li><button data-type="<?= basename($format->handler) ?>" data-key="<?= $asset->getId() ?>" type="button"
        data-key="<?= $asset->getId() ?>" class="button dropdown-item view-asset-button">View</button></li>
    <li><button class="button dropdown-item">Edit</button></li>
    <li><button id="media-delete-<?= $asset->getId() ?>" data-id="<?= $asset->getId() ?>"
        class="button dropdown-item">Delete</button></li>
  </ul>
</div>

<?= FormComponent::instance($this)->deleteForm(Helper::getURL("admin/media/delete"), "button#media-delete-{$asset->getId()}") ?>