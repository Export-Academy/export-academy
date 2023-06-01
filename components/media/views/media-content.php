<?php

use common\models\resource\AssetModel;
use common\models\resource\format\handlers\ImageHandler;
use common\models\resource\format\handlers\TextHandler;
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

<div class="media-details-container">
</div>