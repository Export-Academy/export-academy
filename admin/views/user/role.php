<div class="row">
  <?php

  use common\models\access\Role;
  use components\Components;
  use lib\util\html\Html;

  /** @var Role[] $roles */

  $components = new Components();


  foreach ($roles as $role) : ?>
    <div class="col-lg-4 col-md-6 col-sm-12 px-sm-4 px-md-2 py-3 ">
      <?= $components->render("role-components/card", ["role" => $role]) ?>
    </div>
  <?php endforeach; ?>


  <div class="col-lg-4 col-md-6 col-sm-12 px-sm-4 px-md-2 py-3 ">
    <div class="card rounded-4 h-100" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#new-role-modal">
      <div class="card-body ">

        <div class="vstack justify-content-center h-100 w-100">
          <h3 class="display-6 fw-semibold fs-4">Add New Role</h3>
        </div>

      </div>
    </div>
  </div>
</div>


<?php


$header = "<h5 class='modal-title'>Add New Role</h5>";

$beginForm = Html::form_begin("/academy/admin/user/role", "post");
$nameInput = $components->render("form-components/input-field", [
  "type" => "text",
  "label" => "Role Name",
  "name" => "Role[name]"
]);
$descriptionInput = $components->render("form-components/input-field", [
  "type" => "textarea",
  "label" => "Role Description",
  "placeholder" => "Description here...",
  "name" => "Role[description]"
]);
$endForm = Html::form_end();



$content = <<< HTML
$beginForm
<div class="mb-5">
  <div class="py-2">
  $nameInput
  </div>
  <div class="py-2">
  $descriptionInput
  </div>
</div>

<div class="mt-3 d-block gap-2">
<button type="submit" class="btn btn-secondary">Save</button>
</div>
$endForm
HTML;


?>


<?= $components->render("modal-component/modal", [
  "header" => $header,
  "id" => "new-role-modal",
  "content" => $content,
  "size" => "lg",
  "showFooter" => false
]) ?>