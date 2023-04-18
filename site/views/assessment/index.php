<?php

use lib\app\view\View;
use lib\util\html\HtmlHelper;

/**
 * @var string $method
 * @var View $this
 */



$this->context->setTitle('Access Control');

?>

<div class="container">
  <h1>Access Controller Index</h1>
  <?= HtmlHelper::input('Joel Henry', 'User[name]', ['class' => 'form-control', 'placeholder' => 'Enter your name']) ?>
</div>


<script type="text/javascript" src="<?= "/academy/site/views/assets/site.js" ?>"></script>