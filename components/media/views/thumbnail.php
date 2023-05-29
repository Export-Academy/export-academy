<?php

use common\models\resource\AssetModel;
use common\models\resource\format\handlers\ImageHandler;
use common\models\resource\format\handlers\TextHandler;
use common\models\resource\format\handlers\VideoHandler;
use lib\app\view\View;

/**
 * @var View $this
 * @var AssetModel $asset
 */

$this->registerSCSSFile("style");

$context = $this->context;
$format = $asset->assetFormat;

?>




<div>
  <div class="thumbnail-container" type="button" data-bs-toggle="dropdown">
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
      <?= $format->name ?>
    </div>
  </div>



  <ul class="dropdown-menu">
    <li><button class="button dropdown-item">View</button></li>
    <li><button class="button dropdown-item">Delete</button></li>
  </ul>
</div>