<?php

use common\models\resource\AssetModel;
use common\models\resource\format\handlers\ImageHandler;
use common\models\resource\format\handlers\VideoHandler;
use lib\app\view\View;

/** 
 * @var View $this 
 * @var AssetModel $asset
 *  
 * */
?>



<div class="media-container">
  <?= VideoHandler::instance($this)->renderView() ?>
  <?= ImageHandler::instance($this)->renderView() ?>
</div>

<div class="media-details-container my-3" id="media-details-container-handle" style="display: none">
  <div class="row gap-3">
    <div class="col-auto">
      <small class="text-muted">Filename</small>
      <div id="filename" class="fw-semibold">-</div>
    </div>

    <div class="col-auto">
      <small class="text-muted">Directory</small>
      <div id="directory" class="fw-semibold">-</div>
    </div>

    <div class="col-auto">
      <small class="text-muted">File Type</small>
      <div id="mime-type" class="fw-semibold">-</div>
    </div>

    <div class="col-auto">
      <small class="text-muted">Created</small>
      <div id="created-user" class="fw-semibold">-</div>
      <small id="created-date" class="fw-semibold">-</small>
    </div>

    <div class="col-auto">
      <small class="text-muted">Last Updated</small>
      <div id="updated-user" class="fw-semibold">-</div>
      <small id="updated-date" class="fw-semibold">-</small>
    </div>
  </div>
</div>