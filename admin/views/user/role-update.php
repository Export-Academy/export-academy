<?php

use common\models\access\Permission;
use components\HtmlComponent;
use components\modal\Modal;
use lib\app\view\View;
use lib\util\html\Html;

/** @var View $this */

$components = HtmlComponent::instance($this);
$granted_permissions = $granted_permissions ?? [];
$permissions = Permission::find()->all();


$this->registerJsFile("role-update.js", View::POS_LOAD);


?>
<?= Html::tag("script", "", ["src" => "https://cdn.jsdelivr.net/npm/sweetalert2@11"]) ?>

<div class="px-md-4 px-sm-3">
  <div class="row">
    <div class="col-lg-3 col-md-12 px-2">
      <div class="gap-2 d-block my-4">
        <button data-bs-toggle="modal" data-bs-target="#update-role-modal" class="btn">Update Role
          Information</button>
        <button data-role="<?= $role->name ?> Role" data-id="<?= $role->id ?>" id="delete-role" class="btn ">Delete</button>
      </div>
      <div class="d-lg-block d-md-none d-sm-none">
        <?= $components->render("role-components/card", ["role" => $role, "hideUpdateButton" => true]) ?>
      </div>
    </div>
    <div class="col-lg-9 col-md-12 px-2">
      <div class="display-6 fw-bold">Role Permissions</div>
      <?= Html::form_begin("/academy/admin/user/update_role", "post", ["id" => "permissions-form", "data-role" => "$role->name Role"]) ?>

      <div class="hstack justify-content-between align-items-center my-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="permission-all">
          <label class="form-check-label display-6 fw-bold fs-6" for="permission-all">
            Select All Permissions
          </label>
        </div>
        <button type="submit" class="btn">Save Changes</button>
      </div>

      <div class="card">
        <div class="card-body">
          <?= HtmlComponent::instance($this)->render("data-table-component/data-table", [
            "data" => $permissions,
            "columns" => [
              "permission" => [
                "label" => "Permission",
                "content" => function ($permission) {
                  $description = $permission->description ?? "No Description";
                  return "<div class='display-6 fw-semibold fs-6'>
                $permission->name
                <div class='text-secondary fw-light'>$description</div>
              </div>";
                }
              ],
              "action" => [
                "label" => "",
                "content" => function ($permission) use ($granted_permissions) {
                  $granted = array_filter($granted_permissions, function ($_permission) use ($permission) {
                    return $_permission->id === $permission->id;
                  });
                  $selected = !empty($granted);
                  return "<div class='form-check form-switch'>
                <input name='Grants[$permission->id]' class='form-check-input check-permission' data-permission='$permission->id' " . ($selected ? "checked" : "") . " type='checkbox' role='switch' id='permission-$permission->id'>
                <label class='form-check-label' for='permission-$permission->id'>" . ($selected ? "Enabled" : "Disabled") . "</label>
              </div>";
                }
              ]
            ]
          ])  ?>

        </div>
      </div>

      <?= Html::hiddenInput($role->id, "role") ?>
      <?= Html::form_end() ?>
    </div>
  </div>
</div>




<?php

$header = "<h5 class='modal-title'>Update $role->name Role</h5>";

$beginForm = Html::form_begin("/academy/admin/user/role", "post");


$nameInput = HtmlComponent::input($this, "Role[name]", $role->name, ["label" => "Role Name", "type" => "text"]);
$descriptionInput = HtmlComponent::textarea($this, "Role[description]", $role->description, ["label" => "Role Description"]);
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
  $hiddenInput
 
</div>


<div class="mt-3 d-block gap-2">
  <div class="hstack justify-content-end">
    <button type="submit" class="btn">Update Role</button>
  </div>
</div>
$endForm
HTML;


?>

<?= Modal::instance($this)->show("update-role-modal", $content, $header, null, ["showFooter" => false, "size" => "lg"]) ?>

<?
