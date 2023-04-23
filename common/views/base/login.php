<?php

use components\Components;
use lib\app\view\View;
use lib\util\html\HtmlHelper;

/** @var View $this */


$this->registerSCSSFile("login.scss");

$component = new Components();




?>




<div class="row login-container">
  <div class="col-md-12 col-lg-5">
    <div class="vstack justify-items-center my-5">
      <div class="px-5">
        <div class="vstack px-md-5 px-sm-2 my-5">
          <div class="text-center">
            <h3>Sign In to Export Academy</h3>
            <div class="fw-semibold">New here? <a href="/academy/sign_up">Create an Account</a> </div>
          </div>

          <div class="px-5">
            <hr class="my-5">
          </div>

          <?= HtmlHelper::form_begin("/academy/login") ?>

          <?php $component->render("form-components/input-field", ["type" => "email", "label" => "Email", "id" => "email-input", "name" => "email"]) ?>
          <?php $component->render("form-components/input-field", ["type" => "password", "label" => "Password", "id" => "password-input", "name" => "password"]) ?>


          <div class="container mt-5">
            <button type="submit" class="w-100 btn btn-light btn-lg">Continue</button>
          </div>


          <?= HtmlHelper::form_end() ?>

        </div>

      </div>
    </div>
  </div>

  <div class="col-lg-7 p-1 bg-secondary-subtle">

  </div>
</div>