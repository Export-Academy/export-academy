<?php

use common\models\resource\format\Format;
use components\HtmlComponent;
use components\modal\Modal;
use lib\app\view\View;

/**
 * @var View $this
 */


$this->registerJsFile("uploader", $this::POS_HEAD);

$id = spl_object_id($this);
$urlInput = HtmlComponent::input($this, 'url', null, ["variant" => "flushed", "placeholder" => "Paste URL here", "id" => "media-url-input-$id"]);

$path = $path ?? '';
$reload = $reload ? 'true' : 'false';


$header = <<< HTML
<div class="fw-semibold fs-4">Upload File</div>
HTML;

$selectedTypeId = "uploader-type-$id";
$dropdown = HtmlComponent::dropdown($this, "type", null, Format::getTypeOptions(), ["id" => $selectedTypeId]);


$content = <<< HTML

<div role="tabpanel">
  <ul class="nav nav-underline" role="tablist">
    <li class="nav-item">
      <a class="flex-md-fill text-md-center nav-link active" data-bs-toggle="list" href="#file-upload" role="tab">Upload
        File</a>
    </li>
    <li class="nav-item">
      <a class="flex-md-fill text-md-center nav-link" data-bs-toggle="list" href="#file-url-upload" role="tab">By
        URL</a>
    </li>
  </ul>
</div>
<div class="tab-content rounded-0 p-3 mt-3">
  <div class="tab-pane fade show active position-relative" id="file-upload" role="tabpanel">
  <div class="form-group" >
        <label class="fw-semibold">Select Accepted File</label>
        {$dropdown}
      </div>
  <div class="vstack justify-content-center align-items-center p-5 border" id="drag-drop-area-$id">
        <i data-feather="upload-cloud" width="120" height="120" stroke="lightgrey"></i>
        <div class="fw-light fs-5 text-center">drag and drop a file here</div>
        <small class="text-center text-muted" >Accepting <span id='$selectedTypeId-desc' >all</span> file types</small>
        <input type="file" class="form-control mt-5" id="file-input-$id" />
      </div>
  </div>
<div class="tab-pane fade" id="file-url-upload" role="tabpanel">
  <div class="vstack justify-content-center align-items-center p-5">
    $urlInput
  </div>
  <div class="hstack justify-content-end gap-2">
    <button data-bs-dismiss="modal" aria-label="Close" type="button" class="btn">Cancel</button>
    <button type="button" class="btn" id="media-url-button-$id" >Upload</button>
  </div>
</div>
</div>
<div id="uploader-loading-$id" class="position-absolute d-none bg-white top-0 bottom-0 start-0 end-0 vstack justify-content-center align-items-center p-5">
  <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>


HTML;

$this->registerJS("Uploader.init({ id: `$id` , path: `$path`, reload: $reload, selectedType: `#$selectedTypeId` })", $this::POS_LOAD);


?>

<?= Modal::instance($this)->show("uploader-modal-$id", $content, $header, null, ["size" => Modal::MODAL_LG, "showFooter" => false]) ?>

<div class="d-flex justify-content-end">
  <button type="button" class="btn" id="uploader-trigger-button-<?= $id ?>">
    <div class="d-flex justify-content-between align-items-center gap-2">
      <div class="fw-semibold">New File</div>
      <i data-feather="plus"></i>
    </div>
  </button>
</div>