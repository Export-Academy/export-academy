<?php

use common\models\assessment\Question;
use lib\app\view\View;
use lib\util\Helper;

/**
 * @var View $this
 * @var Question $component
 */

$component = Question::generate($this);
$questions = Question::find()->all();


?>

<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#question-builder">Create
  Question</button>

<div class="offcanvas-end offcanvas w-75" tabindex="-1" id="question-builder">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasBottomLabel">Create New Question</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <?= $component->renderBuilder() ?>
  </div>
</div>

<div class="row my-5 gap-2">
  <?php foreach ($questions as $question) : ?>
    <div class="col-sm-3">
      <div class="bg-white border my-2 hstack gap-3 justify-content-between p-2">
        <div class="vstack">
          <small class="fw-semibold">QID-<?= $question->id ?> <small class="text-muted">(<?= $question->questionType->name ?>)</small></small>
          <small class="text-muted"><?= $question->prompt ?></small>
        </div>
        <a href="<?= Helper::getURL("/admin/assessment/question?id=" . $question->id) ?>" class="btn btn-sm btn-light border">Edit</a>
      </div>
    </div>

  <?php endforeach; ?>
</div>