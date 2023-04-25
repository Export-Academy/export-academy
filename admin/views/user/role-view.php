<?php

use components\Components;

$components = new Components();
?>


<div class="row">
  <div class="col-md-3 col-sm-12">
    <?= $components->render("role/card", ["role" => $role, "hideViewButton" => true]) ?>
  </div>
  <div class="col-md-7 col-sm-12"></div>
</div>