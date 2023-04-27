<?php

use components\Components;
use lib\app\view\View;
use lib\util\html\Html;


$this->registerSCSSFile("login.scss");

/** @var View $this */


$component = new Components();


?>

<div class="row login-container">

  <div class="col-md-12 col-lg-7 px-5">
    <div class="vstack px-5 justify-items-center my-5">
      <div class="px-5">
        <div class="vstack px-md-5 px-sm-2 my-5">
          <div class="text-center">
            <h3>Sign Up for Export Academy</h3>
            <div class="fw-semibold">Already Registered? <a href="/academy/login">Sign In</a> </div>
          </div>

          <div class="px-5">
            <hr class="my-5">
          </div>

          <?= Html::form_begin("/academy/sign_up") ?>


          <div class="hstack gap-2 justify-content-between">
            <?= $component->render("form-components/input-field", ["type" => "text", "label" => "First Name", "id" => "first-name-input", "name" => "User[firstName]", "required" => true]) ?>
            <?= $component->render("form-components/input-field", ["type" => "text", "label" => "Last Name", "id" => "last-name-input", "name" => "User[lastName]", "required" => true]) ?>
          </div>




          <?= $component->render("form-components/input-field", ["type" => "email", "label" => "Email", "id" => "email-input", "name" => "User[email]", "required" => true]) ?>
          <?= $component->render("form-components/input-field", ["type" => "password", "label" => "Password", "id" => "password-input", "name" => "User[password]", "required" => true]) ?>


          <div class="container mt-5">
            <button type="submit" class="w-100 btn btn-light btn-lg">Sign Up</button>
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