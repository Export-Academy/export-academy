<?php

use common\models\access\Permission;
use components\Components;
use lib\util\html\Html;

/**
 * @var Permission[] $permissions
 */



$components = new Components();




?>



<div class="container">
  <div class="card h-100 rounded-4">
    <div class="card-body">

      <?= $components->render('data-table-component/data-table', [
        "data" => $permissions,
        "columns" => [
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
                $content .= "<span class='badge bg-secondary mx-1'>$role->name</span>";
              }
              return $content;
            }
          ],
          "action" => [
            "label" => "Action",
            "content" => function (Permission $permission) {
              return Html::tag("button", "Edit Permission", ["class" => "btn btn-light"]);
            }
          ]
        ]
      ]) ?>

    </div>
  </div>
</div>