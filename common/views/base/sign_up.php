<?php

use lib\util\html\HtmlHelper;
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
              <?= HtmlHelper::input('', 'user[first_name]', ['class' => 'form-control', 'placeholder' => 'Your First Name']) ?>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group my-2">
              <div class="form-label">Last Name</div>
              <?= HtmlHelper::input('', 'user[last_name]', ['class' => 'form-control', 'placeholder' => 'Your Last Name']) ?>
            </div>
          </div>
        </div>
        <div class="form-group my-2">
          <div class="form-label">Email</div>
          <?= HtmlHelper::input('', 'user[email]', ['class' => 'form-control', 'placeholder' => 'Your Email']) ?>
        </div>


        <div class="form-group my-2">
          <div class="form-label">Password</div>
          <?= HtmlHelper::input('', 'user[password]', ['class' => 'form-control', 'placeholder' => 'Your Password', 'type' => 'password']) ?>
        </div>

        <div class="form-group my-2">
          <div class="form-label">Confirm Password</div>
          <?= HtmlHelper::input('', 'user[confirm_password]', ['class' => 'form-control', 'placeholder' => 'Your Password', 'type' => 'password']) ?>
        </div>
      </div>
      <div class="card-footer">
        <?= HtmlHelper::tag('button', 'Sign Up', ['class' => 'btn btn-secondary', 'type' => 'submit']) ?>
      </div>
    </form>
  </div>
</div>