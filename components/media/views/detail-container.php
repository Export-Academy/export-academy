<?php

use common\models\resource\AssetModel;
use common\models\resource\format\handlers\FormatHandler;
use lib\app\view\View;

/** 
 * @var View $this 
 * @var AssetModel $asset
 *  
 * */

/** @var FormatHandler */
$handler = $asset->getHandler($this);
?>



<div>
  <div class="media-container">
    <?= $handler->renderView($asset) ?>
  </div>

  <div class="media-details-container">
  </div>
</div>