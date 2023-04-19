<?php

use lib\util\html\HtmlHelper;
?>

<div class="my-10">
  <div class="card w-50">
    <form action="/academy/login" method="post">
      <div class="card-header">
        <h4>Login</h4>
      </div>
      <div class="card-body">
        <div class="form-group my-2">
          <div class="form-label">Email</div>
          <?= HtmlHelper::input('', 'user[email]', ['class' => 'form-control', 'placeholder' => 'Your Email']) ?>
        </div>


        <div class="form-group my-2">
          <div class="form-label">Password</div>
          <?= HtmlHelper::input('', 'user[password]', ['class' => 'form-control', 'placeholder' => 'Your Password', 'type' => 'password']) ?>
        </div>
      </div>
      <div class="card-footer">
        <?= HtmlHelper::tag('button', 'Login', ['class' => 'btn btn-secondary', 'type' => 'submit']) ?>
      </div>
    </form>
  </div>
</div>