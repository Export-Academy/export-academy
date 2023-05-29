<?php

use common\models\resource\AssetModel;
use lib\app\log\Logger;
use lib\app\view\View;

/** 
 * 
 * @var View $this
 * @var AssetModel $asset
 * 
 *  */

Logger::log($asset->getUrl());

?>


<div class="media-image-container">
  <img src="<?= $asset->getUrl() ?>" alt="">
</div>