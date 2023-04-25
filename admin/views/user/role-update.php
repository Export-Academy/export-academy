<?php

use common\models\access\Permission;
use components\Components;
use lib\app\view\View;
use lib\util\html\Html;

/** @var View $this */

$components = new Components();
$granted_permissions = $granted_permissions ?? [];
$permissions = Permission::find()->all();


$this->registerJsFile("role-update.js", View::POS_LOAD);


?>
<?= Html::tag("script", "", ["src" => "https://cdn.jsdelivr.net/npm/sweetalert2@11"]) ?>

<div class="px-md-4 px-sm-3">

  <div class="row">
    <div class="col-lg-3 col-md-12">
      <div class="gap-2 d-block my-4">

        <button id="delete-role" data-role="<?= $role->id ?>" class="btn btn-danger-outline">Delete</button>
      </div>
      <div class="d-lg-block d-md-none d-sm-none">
        <?= $components->render("role/card", ["role" => $role, "hideUpdateButton" => true]) ?>
      </div>
    </div>
    <div class="col-lg-9 col-md-12">

      <div class="display-6 fw-bold fs-5 text-dark my-4">Role Permissions</div>
      <?= Html::form_begin("/academy/admin/user/update_role", "post", ["id" => "permissions-form"]) ?>

      <div class="hstack justify-content-between align-items-center p-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="permission-all">
          <label class="form-check-label display-6 fw-bold fs-6" for="permission-all">
            Select All Permissions
          </label>
        </div>
        <button type="submit" class="btn btn-primary text-light fw-semibold">Save Changes</button>
      </div>


      <div class="row my-4">
        <?php foreach ($permissions as $permission) : ?>

          <?php

          $granted = array_filter($granted_permissions, function ($_permission) use ($permission) {
            return $_permission->id === $permission->id;
          });

          $isSelected = !empty($granted);

          ?>

          <div class="col-md-4 col-sm-6 p-2">
            <div class="bg-light h-100 hstack justify-content-between p-3">
              <div class="form-check p-3">
                <div class="form-check-label display-6 fw-semibold fs-6">
                  <?= $permission->name ?>
                  <div class="text-secondary fw-light"><?= $permission->description ?? "No Description" ?></div>
                </div>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input check-permission" data-role="<?= $role->id ?>" <?= $isSelected ? "checked" : "" ?> type="checkbox" role="switch" id="permission-<?= $permission->id ?>">
                <label class="form-check-label" for="permission-<?= $permission->id ?>"><?= $isSelected ? "Enabled" : "Disabled" ?></label>
              </div>
            </div>
          </div>


        <?php endforeach; ?>
      </div>

      <?= Html::hiddenInput($role->id, "role") ?>
      <?= Html::form_end() ?>
    </div>
  </div>

</div>


<?php

$scripts = <<< JS

$()
JS;

$this->registerJs($scripts);
