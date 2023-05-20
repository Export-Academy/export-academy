<?php

use components\HtmlComponent;
use components\modal\Modal;
use lib\app\view\View;

/**
 * @var View $this
 */


$this->registerJsFile("media-assets/insert-image", $this::POS_END);
$components = HtmlComponent::instance($this);
$urlInputButton = HtmlComponent::input($this, null, null, ["variant" => "flushed", "placeholder" => "Paste URL of image", "id" => "image-url-input"]);

?>



<?php


$header = <<< HTML

<div class="fs-4 fw-semi-bold">Insert Image</div>

HTML;



$body = <<< HTML
<div role="tabpanel">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="flex-sm-fill text-sm-center nav-link active" data-bs-toggle="list" href="#upload-image" role="tab">Upload File</a>
    </li>
    <li class="nav-item">
      <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="list" href="#image-url" role="tab">Via URL</a>
    </li>
    <li class="nav-item">
      <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="list" href="#uploaded-images" role="tab">Uploaded Files</a>
    </li>
  </ul>
</div>
<div class="tab-content rounded-0 p-3 mt-3">
  <div class="tab-pane fade show active position-relative" id="upload-image" role="tabpanel">
    <div class="vstack justify-content-center align-items-center p-5" id="drag-drop-image">
        <i data-feather="upload-cloud" width="180" height="180" stroke="gray"></i>
        <div class="fw-light fs-5 text-center">drag and drop a file here</div>
        <input accept=".jpg, .jpeg, .png" type="file" class="form-control mt-5" id="image-file-input"/>
    </div>
  </div>
  <div class="tab-pane fade" id="image-url" role="tabpanel">
    <div class="vstack justify-content-center align-items-center p-1">
      $urlInputButton
    </div>
    <div class="hstack justify-content-end gap-2">
      <button data-bs-dismiss="modal" aria-label="Close" type="button" class="btn">Cancel</button>
      <button type="button" id="image-url-button" class="btn">Insert Image</button>
    </div>
  </div>
  <div class="tab-pane fade" id="uploaded-images" role="tabpanel">
    <div class="p-2 row">
      
    </div>
    <div class="hstack justify-content-end gap-2">
      <button data-bs-dismiss="modal" aria-label="Close" type="button" class="btn">Cancel</button>
      <button type="button" class="btn">Select</button>
    </div>
  </div>
</div>
    <div id="insert-image-loading" class="position-absolute d-none top-0 bottom-0 start-0 end-0 vstack justify-content-center align-items-center p-5">
      <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
HTML;



?>


<?= Modal::instance($this)->show("insert-image-modal", $body, $header, null, ["showFooter" => false, "size" => "lg"]) ?>