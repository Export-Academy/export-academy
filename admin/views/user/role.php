<div class="row">
  <?php

  use common\models\access\Role;
  use components\HtmlComponent;
  use lib\util\html\Html;

  /** @var Role[] $roles */

  $components = HtmlComponent::instance($this);


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


$nameInput = HtmlComponent::input($this, "Role[name]", "", ["label" => "Role Name", "type" => "text"]);
$descriptionInput = HtmlComponent::textarea($this, "Role[description]", "", ["label" => "Role Description"]);
$hiddenInput = Html::hiddenInput($role->id, "Role[id]");
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
  <div class="hstack justify-content-end">
    <button type="submit" class="btn btn-lg">Save</button>
  </div>
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