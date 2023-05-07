<?php

use lib\app\view\View;

/**
 * @var View $this
 */


$this->registerJsFile("media-assets/insert-image", $this::POS_END);


?>



<div class="modal fade" id="insert-image-modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div class="fs-4 fw-semi-bold">Insert Image</div>
        <button class="button btn-close" data-bs-dismiss="modal" aria-label="Close" type="button"></button>
      </div>


      <div class="modal-body">
        <nav class="nav nav-pills flex-column flex-sm-row">
          <a class="flex-sm-fill text-sm-center nav-link active" aria-current="page" href="#">Upload</a>
          <a class="flex-sm-fill text-sm-center nav-link" href="#">By URL</a>
        </nav>
      </div>
    </div>
  </div>
</div>