<?php

use lib\app\view\View;
use components\HtmlComponent;
use lib\app\Request;

/**
 * @var View $this
 */



$components = HtmlComponent::instance($this);

?>



<div class="hstack container-fluid align-items-center justify-content-between w-100 py-2 bg-light ">
  <div class="p-2">
    <button class="btn icon-btn" data-bs-toggle="offcanvas" data-bs-target="#sidebar-canvas">
      <i data-feather="menu"></i>
    </button>
  </div>
  <div class="hstack">

    <div class="btn-group border-0">
      <button type="button" data-bs-toggle="dropdown" class="btn border-0">
        <div class="text-start">
          <div class="fw-bold"><?= Request::getIdentity()->getDisplayName() ?> <br><small class="fw-semibold">Administrator</small></div>
        </div>
      </button>
      <button type="button" class="btn border-0 dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="visually-hidden">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu rounded-0">
        <li><a class="dropdown-item" href="/academy/sign_out">Sign Out</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
      </ul>
    </div>
  </div>
</div>


<div id="sidebar-canvas" class="offcanvas offcanvas-start" tabindex="-1">
  <div class="offcanvas-header">
    <input placeholder="Search" type="text" class="form-control form-control-sm">
  </div>
  <div class="offcanvas-body">


    <?= $components->render("sidebar-item", ["title" => "Dashboard", "link" => "/academy/admin/dashboard"]) ?>
    <?= $components->render("sidebar-item", ["title" => "Assessment Manager", "link" => "/academy/admin/assessment", "items" => [
      "Manage Assessment" => "/academy/admin/assessment"
    ]]) ?>
    <?= $components->render("sidebar-item", ["title" => "Resource Manager", "link" => "/academy/admin/resource"]) ?>
    <?= $components->render("sidebar-item", ["title" => "User Manager", "link" => "/academy/admin/user", "items" => [
      "Manage Users" => "/academy/admin/user",
      "User Roles" => "/academy/admin/user/role",
      "Permissions" => "/academy/admin/user/permission"
    ]]) ?>


  </div>
</div>