<?php

use lib\app\http\Request;
use lib\app\view\View;

/** @var View $this */

$this->registerSCSSFile("sidebar-item.scss");

$path = Request::path();
$active = is_int(strpos($path, $link)) ? true : false;

?>

<?php if (isset($items)) : ?>

<div class="accordion accordion-flush">
  <div class="accordion-item">
    <div class="accordion-header">
      <div class="academy-nav-item rounded-1 <?= $active ? "active" : "" ?>">
        <div class="item collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#container">
          <div class="hstack justify-content-between">
            <div class="p-2"><?= $title ?? "Title" ?></div>
            <div class="p-2">
              <i data-feather="arrow-right-circle"></i>
            </div>
          </div>
        </div>
      </div>

    </div>


    <div class="accordion-collapse collapse" id="container">

      <div class="accordion-body">
        <?php foreach ($items as $title => $link) : ?>

        <?php
            $active = $path === $link;
            ?>
        <div class="academy-nav-item sub-item rounded-1 <?= $active ? "active" : "" ?>">
          <a class="item" href="<?= $link ?? "#" ?>">
            <div class="hstack justify-content-between">
              <div class="p-2"><?= $title ?? "Title" ?></div>
              <div class="p-2">
                <i data-feather="plus"></i>
              </div>
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
    <div class="hstack justify-content-between">
      <div class="p-2"><?= $title ?? "Title" ?></div>
      <div class="p-2">
        <i data-feather="arrow-right-circle"></i>
      </div>
    </div>
  </a>
</div>


<?php endif; ?>