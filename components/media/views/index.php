<?php

use common\models\resource\Asset;
use common\models\resource\format\handlers\FormatHandler;
use components\media\MediaComponent;
use lib\app\Request;
use lib\util\Helper;
use lib\util\html\Html;
use lib\app\view\View;

/** @var View $this */

$this->registerSCSSFile("style");

$this->registerJsFile("base-handler", $this::POS_HEAD);
$this->registerJsFile("image-handler", $this::POS_HEAD);
$this->registerJsFile("video-handler", $this::POS_HEAD);
$this->registerJsFile("application-handler", $this::POS_HEAD);
$this->registerJsFile("media", $this::POS_END);


$path = $path ?? Request::params("path");
$assets = Asset::path($path);
$current = "";

?>


<?= MediaComponent::instance($this)->uploader(true) ?>
<div class="directory-path-container">
  <div>
    <?= Html::tag('a', "Files", ["href" =>
    Helper::getURL("admin/resource")]) ?>
  </div>
  <div><i data-feather="chevron-right" width="16" height="16"></i></div>
  <?php foreach (explode("/", $path ?? "") as $dir) :
    if (empty($dir)) continue;
    $current .= $dir  ? $dir . "/" : "";
  ?>
    <?= Html::tag('a', $dir, ["href" =>
    Helper::getURL("admin/resource", ["path" => $current])]) ?>
    <div><i data-feather="chevron-right" width="16" height="16"></i></div>
  <?php endforeach ?>
</div>


<div class="row">
  <div class="col-lg-4 col-md-12" id="media-detail-container">
    <?= MediaComponent::instance($this)->content() ?>
  </div>


  <div class="col-lg-8 col-md-12">
    <div class="row">
      <?php if (empty($assets)) : ?>
        <div class="fw-semibold fs-6 p-5">No Files were Found</div>
      <?php else : ?>
        <?php foreach ($assets as $asset) :
          /** @var FormatHandler */
          $handler = $asset->getHandler($this);
        ?>
          <div class="col-lg-4 col-md-6 col-sm-12"><?= $handler->renderThumbnail($asset) ?></div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </div>
</div>