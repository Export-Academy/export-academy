<?php

use lib\util\html\Html;
?>

<div class="my-10">
  <div class="card w-50">
    <form action="/academy/sign_up" method="post">
      <div class="card-header">
        <h4>Sign Up</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="form-group my-2">
              <div class="form-label">First Name</div>
              <?= Html::input('', 'user[first_name]', ['class' => 'form-control', 'placeholder' => 'Your First Name']) ?>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group my-2">
              <div class="form-label">Last Name</div>
              <?= Html::input('', 'user[last_name]', ['class' => 'form-control', 'placeholder' => 'Your Last Name']) ?>
            </div>
          </div>
        </div>
        <div class="form-group my-2">
          <div class="form-label">Email</div>
          <?= Html::input('', 'user[email]', ['class' => 'form-control', 'placeholder' => 'Your Email']) ?>
        </div>


        <div class="form-group my-2">
          <div class="form-label">Password</div>
          <?= Html::input('', 'user[password]', ['class' => 'form-control', 'placeholder' => 'Your Password', 'type' => 'password']) ?>
        </div>

        <div class="form-group my-2">
          <div class="form-label">Confirm Password</div>
          <?= Html::input('', 'user[confirm_password]', ['class' => 'form-control', 'placeholder' => 'Your Password', 'type' => 'password']) ?>
        </div>
      </div>
      <div class="card-footer">
        <?= Html::tag('button', 'Sign Up', ['class' => 'btn btn-secondary', 'type' => 'submit']) ?>
      </div>
    </form>
  </div>
</div>