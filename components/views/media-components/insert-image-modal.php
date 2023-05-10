<?php

use components\HtmlComponent;
use lib\app\view\View;

/**
 * @var View $this
 */


$this->registerJsFile("media-assets/insert-image", $this::POS_END);
$components = HtmlComponent::instance($this);
$urlInputButton = HtmlComponent::input($this, "", "", ["variant" => "flushed", "placeholder" => "Paste URL of image"]);

?>



<?php


$header = <<< HTML

<div class="fs-4 fw-semi-bold">Insert Image</div>

HTML;



$body = <<< HTML
<div role="tabpanel">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="flex-sm-fill text-sm-center nav-link active" data-bs-toggle="list" href="#upload-image" role="tab">Upload Image</a>
    </li>
    <li class="nav-item">
      <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="list" href="#image-url" role="tab">Image URL</a>
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
    <div class="vstack justify-content-center align-items-center p-5">
      $urlInputButton
    </div>
    <div class="hstack justify-content-end gap-2">
      <button data-bs-dismiss="modal" aria-label="Close" type="button" class="btn btn-lg">Cancel</button>
      <button class="btn btn-lg">Insert Image</button>
    </div>
  </div>
</div>
    <div id="insert-image-loading" class="position-absolute d-none bg-white top-0 bottom-0 start-0 end-0 vstack justify-content-center align-items-center p-5">
      <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
HTML;



?>




<?= $components->render("modal-component/modal", [
  "header" => $header,
  "id" => "insert-image-modal",
  "content" => $body,
  "size" => "lg",
  "showFooter" => false
]) ?>