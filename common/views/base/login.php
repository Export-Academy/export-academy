<?php

use components\Components;
use lib\app\http\Request;
use lib\app\view\View;
use lib\util\html\Html;

/** @var View $this */


$this->registerSCSSFile("login.scss");

$component = new Components();


$actionLink = "/academy/login";
$redirect_link = Request::params("r");


if (isset($redirect_link))
  $actionLink .= "?r=" . $redirect_link;


?>




<div class="row login-container">

  <div class="col-md-12 col-lg-7 px-5">
    <div class="vstack px-5 justify-items-center my-5">
      <div class="px-5">
        <div class="vstack px-md-5 px-sm-2 my-5">
          <div class="text-center">
            <h3>Sign In to Export Academy</h3>
            <div class="fw-semibold">New here? <a href="<?= $actionLink ?>">Create an Account</a> </div>
          </div>

          <div class="px-5">
            <hr class="my-5">
          </div>

          <?= Html::form_begin("/academy/login") ?>

          <?php $component->render("form-components/input-field", ["type" => "email", "label" => "Email", "id" => "email-input", "name" => "email"]) ?>
          <?php $component->render("form-components/input-field", ["type" => "password", "label" => "Password", "id" => "password-input", "name" => "password"]) ?>



          <div class="container mt-5">
            <button type="submit" class="w-100 btn btn-light btn-lg">Continue</button>
          </div>


          <?= Html::form_end() ?>
          <?php $component->view->renderPosition(View::POS_END) ?>
        </div>

      </div>
    </div>
  </div>
  <div class="col-lg-5 p-1 bg-secondary-subtle">

  </div>
</div>