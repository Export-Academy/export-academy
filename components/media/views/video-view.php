<?php

use common\models\resource\AssetModel;
use lib\app\log\Logger;
use lib\app\view\View;

/** 
 * 
 * @var View $this
 * @var AssetModel $asset
 * 
 **/
?>

<div class="media-video-container" data-player-id="<?= spl_object_hash($asset) ?>">
  <script src="//cdn.flowplayer.com/players/ffdf2c44-aa29-4df8-a270-3a199a1b119e/native/flowplayer.async.js">
    {
      "src": "<?= $asset->getUrl() ?>"
    }
  </script>
</div>