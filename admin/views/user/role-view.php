<?php

use common\models\access\Role;
use common\models\user\User;

/** @var Role $role */



use components\Components;

$components = new Components();
?>


<div class="row">
  <div class="col-md-3 col-sm-12 position-static top-0">
    <?= $components->render("role-components/card", ["role" => $role, "hideViewButton" => true]) ?>
  </div>
  <div class="col-md-9 col-sm-12">
    <div class="card h-100 rounded-4">
      <div class="card-body">
        <?= $components->render("data-table-component/data-table", [
          "data" => $role->assignedUsers,
          "columns" => [
            "id" => ["label" => "ID", "content" => function (User $user) {
              return "<b>$user->id</b>";
            }],
            "name" => [
              "label" => "Name",
              "content" => function (User $user) {
                return $user->displayName;
              }
            ],
            "email" => [
              "label" => "Email"
            ],
            "roles" => [
              "label" => "Roles",
              "content" => function (User $user) {
                $content = "";
                $roles = $user->roles;
                foreach ($roles as $role) {
                  $content .= "<span class='badge bg-secondary mx-1'>$role->name</span>";
                }
                return $content;
              }
            ]
          ]
        ]) ?>
      </div>
    </div>

  </div>
</div>

<div class="badge">psdl</div>