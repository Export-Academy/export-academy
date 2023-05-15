<?php

use common\models\assessment\Question;
use lib\app\log\Logger;
use lib\app\view\View;

/**
 * @var Question $question
 * @var View $this
 */


$this->registerView($question->getView());


?>



<div class="row">
  <div class="col-lg-7 col-sm-12">
    <div class="card">
      <div class="card-header fw-semibold">
        QID-<?= $question->id ?>
      </div>
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="flex-sm-fill text-sm-center nav-link active" data-bs-toggle="list" href="#render-view" role="tab">View Question</a>
          </li>
          <li class="nav-item">
            <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="list" href="#build-view" role="tab">Edit
              Question</a>
          </li>
        </ul>
      </div>

      <div class="tab-content rounded-0 p-3 mt-3">
        <div class="tab-pane fade show active position-relative" id="render-view" role="tabpanel">
          <div class="vstack gap-2">
            <div class="border-bottom py-1 px-4">
              <p class="fw-semibold fs-6"><?= $question->prompt ?></p>
            </div>
            <?= $question->renderView() ?>
          </div>
        </div>
        <div class="tab-pane fade" id="build-view" role="tabpanel">
          <?= $question->renderBuilder() ?>
        </div>
      </div>
    </div>
  </div>


  <div class="col-lg-5 col-sm-12">
    <div class="card">
      <div class="card-body">
        <div class="text-center">Information about question</div>
      </div>
    </div>
  </div>
</div>

<?php


Logger::log(spl_object_id($this), "master");
