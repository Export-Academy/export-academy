<?php

use components\HtmlComponent;
use lib\app\view\View;
use lib\util\html\Html;


$this->registerSCSSFile("login.scss");

/** @var View $this */


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


          <div class="hstack gap-2">
            <div class="w-100">
              <?= HtmlComponent::input($this, "User[firstName]", "", ["label" => "First Name", "id" => "first-name-input", "required" => true]) ?>
            </div>
            <div class="w-100">
              <?= HtmlComponent::input($this, "User[lastName]", "", ["label" => "Last Name", "id" => "last-name-input", "required" => true])  ?>
            </div>
          </div>




          <?= HtmlComponent::input($this, "User[email]", "", ["label" => "Email", "id" => "email-input", "required" => true, "type" => "email"]) ?>
          <?= HtmlComponent::passwordInput($this, "User[password]", "", ["label" => "Password", "id" => "password-input", "required" => true]) ?>


          <div class="container mt-5">
            <button type="submit" class="w-100 btn">Sign Up</button>
          </div>


          <?= Html::form_end() ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5 p-1">

  </div>
</div>