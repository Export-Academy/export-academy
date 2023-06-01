<?php

use common\models\resource\AssetModel;
use lib\app\view\View;

/** 
 * 
 * @var View $this
 * @var AssetModel $asset
 * 
 **/

?>


<link href="https://vjs.zencdn.net/8.3.0/video-js.css" rel="stylesheet" />


<div class="media-video-container" id="media-video-handler" style="display: none;">
  <video class="video-js" controls preload="auto">
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a
      web browser that
      <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
  </video>
</div>


<script src="https://vjs.zencdn.net/8.3.0/video.min.js"></script>