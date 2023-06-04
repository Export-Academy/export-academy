<?php

use common\models\access\Permission;
use components\table\DataTable;
use lib\util\Helper;
use lib\util\html\Html;

/**
 * @var Permission[] $permissions
 */
?>



<div class="px-md-5 py-5">
  <div class="card">
    <div class="card-body">

      <?= DataTable::instance($this)->table($permissions, [
        "id" => ["label" => "ID"],
        "name" => [
          "label" => "Name",
          "content" => 'name'
        ],
        "description" => [
          "label" => "Description",
          "content" => function (Permission $permission) {
            return $permission->description ?? "No Description";
          }
        ],
        "roles" => [
          "label" => "Permitted Roles",
          "content" => function (Permission $permission) {
            $content = "";
            $roles = $permission->assignedRoles;
            foreach ($roles as $role) {
              $content .= "<span class='badge bg-success mx-1'>$role->name</span>";
            }
            return $content;
          }
        ],
        "action" => [
          "label" => "Action",
          "content" => function (Permission $permission) {
            return Html::tag("a", "Edit Permission", ["class" => "btn", "href" => Helper::getURL("admin/permission", ["id" => $permission->id])]);
          }
        ]
      ], null) ?>

    </div>
  </div>
</div>