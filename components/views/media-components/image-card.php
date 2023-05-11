<?php

use lib\app\view\View;
use lib\util\html\Html;

/** @var View $this */



$this->registerSCSSFile("media-component.scss");

?>

<div class="col p-2">
  <div class="media-component">
    <?= Html::hiddenInput($id ?? "", "media[<>]") ?>
    <img src="<?= $src ?? "" ?>" alt="<?= $alt ?? "No Image" ?>" class="w-100 rounded" />
    <div class="media-toolbar">
      <div class="hstack justify-content-between">
        <div class="btn-group dropup">
          <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i data-feather="more-vertical" height="20" width="20" color="white"></i></button>
          <ul class="dropdown-menu rounded-0">
            <li><button type="button" class="btn dropdown-item" href="#">Delete</button></li>
          </ul>
        </div>

        <button type="button" class="btn"><i data-feather="maximize" height="20" width="20" color="white"></i></button>
      </div>
    </div>
  </div>
</div>