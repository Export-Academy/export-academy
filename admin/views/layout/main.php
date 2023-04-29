<?php

use components\Components;
use lib\app\view\View;
use lib\util\Helper;

/**
 * @var View $this
 */


$components = new Components();



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->title ?? 'Export Academy Admin Portal' ?></title>
  <!-- Feather Icons Import -->
  <script src="https://unpkg.com/feather-icons"></script>
  <?php $this->registerJs("feather.replace();", View::POS_LOAD); ?>

  <!-- Bootstrap Stylesheet -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

  <!-- JQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

  <?= $this->renderPosition(View::POS_HEAD) ?>

</head>

<body>


  <?= $components->render("navbar") ?>


  <div class="px-md-5 py-5 ">
    <div class="hstack gap-3 align-content-center">
      <h3><?= $title ?? "Dashboard" ?></h3>
    </div>
    <hr>
    <?= $content ?>
  </div>


  <!-- Bootstrap JS files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
  </script>

  <?= $this->renderPosition(View::POS_END) ?>

  <script>
    $(document).ready(function(e) {
      const target = e.target;
      let loaded = null;
      <?php foreach ($this->renderPosition(View::POS_LOAD) as $script) : ?>
        loaded = document.createElement("script");
        <?php if (is_array($script)) : ?>
          loaded.innerHTML = `<?= Helper::getValue("content", $script, "") ?>`;
          document.body.appendChild(loaded);
        <?php else : ?>
          loaded.setAttribute("src", `<?= $script ?>`);
          document.body.appendChild(loaded);
        <?php endif; ?>
      <?php endforeach; ?>

    })
  </script>
</body>



</html>