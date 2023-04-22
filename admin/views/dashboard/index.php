<?php

use lib\app\view\View;

/** @var View $this */
$this->registerJsFile('index.js', View::POS_END);
$this->registerCssFile('style.css');

?>





<h1>Dashboard</h1>
<a href="/academy/sign_out" class="btn btn-primary">Sign Out</a>