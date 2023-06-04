<?php

use lib\app\Request;
use lib\app\view\View;

/** @var View $this */



$path = Request::path();
$active = is_int(strpos($path, $link)) ? true : false;

$unique = str_replace(" ", "-", strtolower($title));

?>

<?php if (isset($items)) : ?>

  <div class="accordion accordion-flush">
    <div class="accordion-item">
      <div class="accordion-header">
        <div class="academy-nav-item rounded-1 <?= $active ? "active" : "" ?>">
          <div class="item collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#container-<?= $unique ?>">
            <div class="hstack gap-2 justify-content-between">
              <i data-feather="chevrons-right"></i>
              <div><?= $title ?? "Title" ?></div>
            </div>
          </div>
        </div>

      </div>


      <div class="accordion-collapse collapse" id="container-<?= $unique ?>">

        <div class="accordion-body">
          <?php foreach ($items as $title => $link) : ?>

            <?php
            $active = $path === $link;
            ?>
            <div class="academy-nav-item sub-item rounded-1 <?= $active ? "active" : "" ?>">
              <a class="item" href="<?= $link ?? "#" ?>">
                <div class="hstack justify-content-between">
                  <i data-feather="circle" width="8" height="8"></i>
                  <div><?= $title ?? "Title" ?></div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>


<?php else : ?>

  <div class="academy-nav-item rounded-1 <?= $active ? "active" : "" ?>">
    <a class="item" href="<?= $link ?? "#" ?>">
      <div class="hstack gap-2 justify-content-between">
        <i data-feather="chevrons-right"></i>
        <div><?= $title ?? "Title" ?></div>
      </div>
    </a>
  </div>


<?php endif; ?>