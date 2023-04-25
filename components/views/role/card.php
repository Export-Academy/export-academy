<?php

use common\models\access\Permission;
use common\models\access\Role;
use lib\util\html\Html;



/** @var Role $role */

$permissions = $role->permissions;
$maxVisiblePermissions = 4;

$hideUpdateButton = $hideUpdateButton ?? false;
$hideViewButton = $hideViewButton ?? false;

?>

<div class="card h-100 rounded-4 overflow-hidden border-0">
  <div class="card-body bg-body-secondary vstack justify-content-between">
    <div class="container-fluid">
      <h4><?= $role->name ?></h4>
      <small><?= $role->description ?? "No Description" ?></small>

      <hr class="my-2">


      <div class="text-muted fw-bold my-3">
        <?= $role->getAssignedUsers(false)->count() ?> Assigned User(s)
      </div>

      <?=

      Html::list(
        $permissions,
        function (Permission $permission) {
          return Html::tag(
            "li",
            "
                  <div class='fw-semibold'>$permission->name</div>
                  <p class='fw-light fs-6'>" . $permission->description ?? "No Description" . "</p>
                ",
            ["class" => "list-group-item rounded-0 vstack justify-content-center fw-bold"]
          );
        },
        4,
        Html::tag("li",  "and " . count($permissions) - $maxVisiblePermissions . " more...", ["class" => "list-group-item text-muted fw-bold"]),
        ["class" => "list-group list-group-flush"]
      )
      ?>
    </div>


    <div class="d-block gap-2 mt-5">
      <?php if (!$hideUpdateButton) : ?>
        <a href="/academy/admin/user/update_role?role=<?= $role->id ?>" class="btn btn-secondary">Update Role</a>
      <?php endif ?>
      <?php if (!$hideViewButton) : ?>
        <a href="/academy/admin/user/role?role=<?= $role->id ?>" class="btn btn-secondary">View Role</a>
      <?php endif ?>
    </div>
  </div>
</div>