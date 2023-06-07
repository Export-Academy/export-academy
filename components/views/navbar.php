<?php

use lib\app\view\View;
use components\HtmlComponent;
use lib\app\Request;
use lib\util\Helper;

/**
 * @var View $this
 */


$this->registerSCSSFile("dashboard.scss");


$components = HtmlComponent::instance($this);

?>



<div class="dashboard-nav">
  <div class="p-2">
    <button class="menu" data-bs-toggle="offcanvas" data-bs-target="#sidebar-canvas">
      <i data-feather="menu" width="36" height="36"></i>
    </button>
  </div>
  <div class="hstack">
    <div>
      <button type="button" data-bs-toggle="dropdown" class="user-detail">
        <div class="text-start">
          <div class="fw-bold"><?= Request::getIdentity()->getDisplayName() ?></div>
        </div>
      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item fw-semibold hstack justify-content-between" href="<?= Helper::getURL("admin/account") ?>"> <i data-feather="user" width="18" height="18"></i>
            <div>Account</div>
          </a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item fw-semibold text-danger hstack justify-content-between" href="<?= Helper::getURL("sign_out") ?>"><i data-feather="log-out" width="18" height="18"></i>
            <div>Sign Out</div>
          </a></li>
      </ul>
    </div>
  </div>
</div>


<div id="sidebar-canvas" class="offcanvas offcanvas-start" tabindex="-1">
  <div class="offcanvas-header">
    <div class="fw-semibold fs-3">Export Academy</div>
    <div class="fw-bold">Admin Portal</div>
  </div>
  <div class="offcanvas-body mt-5">


    <?= $components->render("sidebar-item", ["title" => "Dashboard", "link" => Helper::getURL("admin/dashboard")]) ?>
    <?= $components->render("sidebar-item", ["title" => "Assessment Manager", "link" => Helper::getURL("admin/assessment"), "items" => [
      "Manage Assessment" => Helper::getURL("admin/assessment")
    ]]) ?>
    <?= $components->render("sidebar-item", ["title" => "Resource Manager", "link" => "/academy/admin/resource", "items" => [
      "Resource Manager" => Helper::getURL("admin/resource"),
      "File Manager" => Helper::getURL("admin/resource/files")
    ]]) ?>
    <?= $components->render("sidebar-item", ["title" => "User Manager", "link" => "/academy/admin/user", "items" => [
      "Manage Users" => Helper::getURL("admin/user"),
      "User Roles" => Helper::getURL("admin/user/role"),
      "Permissions" => Helper::getURL("admin/user/permission")
    ]]) ?>


  </div>
</div>