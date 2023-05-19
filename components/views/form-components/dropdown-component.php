<?php

use lib\app\view\View;
use lib\util\html\Html;

/**
 * @var View $this
 */


$prefix = spl_object_id($this->context);

?>



<div class="btn-group border-0">
  <?= Html::hiddenInput($value ?? null, $name ?? "", ["id" => "$prefix-selected-item-input"]) ?>

  <hr>


  <div type="button" data-bs-toggle="dropdown" class="bg-light hstack gap-5 justify-content-between rounded-2 border w-100 p-3">
    <div class="fw-semibold text-muted text-wrap" id="<?= $prefix ?>-dropdown-item-label">
      <?= $label ?? "Select Option" ?>
    </div>
    <i data-feather="corner-right-down" width="18" height="18"></i>
  </div>


  <ul class="dropdown-menu rounded-0 w-100" style="overflow-y: scroll; height: 40vh;">
    <?php foreach ($items as $key => $value) : ?>
      <li>
        <button type="button" class="dropdown-item text-wrap border-top <?= $prefix ?>-item" data-key="<?= $key ?>">
          <?= $value ?>
        </button>
      </li>

    <?php endforeach; ?>
  </ul>

</div>



<?php

$dropdownItem = ".$prefix-item";
$dropdownLabel = "#$prefix-dropdown-item-label";
$hiddenInput = "#$prefix-selected-item-input";

$script = <<< JS

$('$dropdownItem').on("click", function (e) {
  const target = $(e.currentTarget);
  const name = target.html();

  $('$dropdownLabel').html(name);

  const value = $(target).data("key");

  $('$hiddenInput').val(value);

})
JS;



$this->registerJs($script, View::POS_LOAD);
?>