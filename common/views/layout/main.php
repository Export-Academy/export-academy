<?php

use lib\util\html\HtmlHelper;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Export Academy' ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <?= HtmlHelper::tag('script', '', ['type' => 'text/javascript', 'src' => '/academy/web/source/js/index.js']) ?>
  <link href="/academy/web/source/css/style.css" rel="stylesheet" />
</head>

<body>

  <div class="d-flex justify-content-between px-5 py-2">
    <a href="/academy" class="btn">Export Academy</a>
    <div>
      <a href="/academy/login" class="btn btn-primary">Login</a>
      <a href="/academy/sign_up" class="btn btn-dark">Sign Up</a>
    </div>
  </div>

  <div class="container h-75 my-5">
    <?= $content ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
  </script>
</body>

</html>