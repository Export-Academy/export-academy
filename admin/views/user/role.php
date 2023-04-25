<div class="row">
  <?php

  use common\models\access\Role;
  use components\Components;

  /** @var Role[] $roles */

  $components = new Components();


  foreach ($roles as $role) : ?>
    <div class="col-lg-4 col-md-6 col-sm-12 px-sm-4 px-md-2 py-3 ">
      <?= $components->render("role/card", ["role" => $role]) ?>
    </div>
  <?php endforeach; ?>


  <div class="col-lg-4 col-md-6 col-sm-12 px-sm-4 px-md-2 py-3 ">
    <div class="card rounded-4 h-100 flex-shrink-0">
      <div class="card-body ">

        <div class="vstack justify-content-center h-100 w-100">
          <h3 class="display-6 fw-semibold fs-4">Add New Role</h3>
          <a href="#" class="stretched-link"></a>
        </div>

      </div>
    </div>
  </div>
</div>