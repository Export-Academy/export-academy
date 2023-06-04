<?php

use common\models\resource\AssetModel;
use common\models\resource\format\handlers\ImageHandler;
use common\models\resource\format\handlers\TextHandler;
use common\models\resource\format\handlers\VideoHandler;
use components\form\FormComponent;
use components\media\MediaComponent;
use components\modal\Modal;
use lib\app\view\View;
use lib\util\Helper;

/**
 * @var View $this
 * @var AssetModel $asset
 */



$context = $this->context;
$format = $asset->assetFormat;

$modalId = "editor-modal-" . spl_object_id($this);

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
        <?= $asset->getName() ?>
      </div>
      <div class="text-muted text-truncate" style="min-width: 100%;">
        <small><?= $asset->getPath() ?></small>
      </div>
    </div>
  </div>
  <ul class="dropdown-menu">
    <li><button data-type="<?= basename($format->handler) ?>" data-key="<?= $asset->getId() ?>" type="button"
        data-key="<?= $asset->getId() ?>" class="button dropdown-item view-asset-button">View</button></li>
    <li><button type="button" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"
        class="button dropdown-item">Edit</button></li>
    <li><button id="media-delete-<?= $asset->getId() ?>" data-id="<?= $asset->getId() ?>"
        class="button dropdown-item">Delete</button></li>
  </ul>
</div>


<?= Modal::instance($this)->show($modalId, MediaComponent::instance($this)->editor($asset), null, null, ["size" => Modal::MODAL_LG, "showFooter" => false]); ?>
<?= FormComponent::instance($this)->deleteForm(Helper::getURL("admin/media/delete"), "button#media-delete-{$asset->getId()}") ?>