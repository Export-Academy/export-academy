<?php

use lib\app\view\View;
use lib\util\Helper;

/**
 * @var View $this
 */

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->title ?? 'Export Academy Admin Portal' ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

  <?= $this->renderPosition(View::POS_HEAD) ?>

</head>

<body>
  <div class="navbar px-5">
    <div class="nav-item">
      <a href="#" class="nav-link active">Link</a>
    </div>
  </div>
  <div>
    <?= $content ?>
  </div>


  <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"
    integrity="sha256-a2yjHM4jnF9f54xUQakjZGaqYs/V1CYvWpoqZzC2/Bw=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
  </script>
  <?= $this->renderPosition(View::POS_LOAD) ?>
  <?= $this->renderPosition(View::POS_END) ?>

</body>



</html>