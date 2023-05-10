<?php


use lib\app\view\View;

use lib\util\html\Html;

/**
 * 
 * @var View $this
 * 
 * @var string $id
 * 
 * @var string[] $options
 * @var string[] $headerOptions
 * @var string[] $footerOptions
 * @var string[] $bodyOptions
 * 
 * @var string $header
 * @var string $footer
 * 
 * @var bool $showHeader
 * @var bool $showFooter
 * @var bool $showCloseButton
 * 
 * 
 * @var string $size
 * 
 * */



$showHeader = $showHeader ?? true;
$showFooter = $showFooter ?? true;

$showCloseButton = $showCloseButton ?? true;

$defaultOptions = ["id" => $id, "tabindex" => "-1"];

$options = array_merge($defaultOptions, $options ?? []) ?? $defaultOptions;
$headerOptions = $headerOptions ?? [];
$footerOptions = $footerOptions ?? [];

?>



<div class="modal fade" <?= Html::renderAttributes($options ?? []) ?>>
  <div class="modal-dialog modal-<?= $size ?? "md" ?>">
    <div class="modal-content">
      <?php if ($showHeader) : ?>
        <div class="modal-header" <?= Html::renderAttributes($headerOptions ?? []) ?>>
          <?= $header ?? "" ?>
          <?php if ($showCloseButton) : ?>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close" type="button"></button>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="modal-body px-4">
        <?= $content ?? "" ?>
      </div>
      <?php if ($showFooter) : ?>
        <div class="modal-footer" <?= Html::renderAttributes($footerOptions ?? []) ?>>
          <?= $footer ?? "" ?>
        </div>
      <?php endif; ?>
    </div>
  </div>


  <?php


  $script = <<< JS

    class Modal {

      static content;
      static target;
    
      static initialize() {
        $(".modal").on("show.bs.modal", Modal.handleModalShow);
        $(".modal").on("hide.bs.modal", Modal.handleModalHide);
      }

      static handleModalShow(e) {
        Modal.content = $(e.currentTarget).html();
        Modal.target = $(e.currentTarget);
      }

      static handleModalHide(e) {
        setTimeout(() => {
          Modal.target.html(Modal.content);
        }, 1000);
      }
  }


  Modal.initialize();

JS;

  $this->registerJs($script);
