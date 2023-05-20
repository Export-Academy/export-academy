<?php

use common\models\access\Permission;
use components\HtmlComponent;
use lib\util\html\Html;

/**
 * @var Permission[] $permissions
 */



$components = HtmlComponent::instance($this);




?>



<div class="px-md-5 py-5">
  <div class="card">
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
                $content .= "<span class='badge bg-success mx-1'>$role->name</span>";
              }
              return $content;
            }
          ],
          "action" => [
            "label" => "Action",
            "content" => function (Permission $permission) {
              return Html::tag("button", "Edit Permission", ["class" => "btn"]);
            }
          ]
        ]
      ]) ?>

    </div>
  </div>
</div>