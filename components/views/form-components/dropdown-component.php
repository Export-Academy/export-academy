<?php

use lib\app\view\View;
use lib\util\html\Html;

/**
 * @var View $this
 */


$prefix = spl_object_id($this);

?>



<div class="w-100">
  <?= Html::hiddenInput($value ?? null, $name ?? "", ["id" => "$prefix-selected-item-input"]) ?>
  <div type="button" data-bs-toggle="dropdown" class="hstack gap-5 justify-content-between w-100 p-3">
    <div class="fw-semibold text-muted text-wrap" id="<?= $prefix ?>-dropdown-item-label">
      <?= $label ?? "Select Option" ?>
    </div>
    <i data-feather="corner-right-down" width="18" height="18"></i>
  </div>


  <ul class="dropdown-menu rounded-0 w-100" style="overflow-y: auto; max-height: 40vh;">
    <?php foreach ($items as $key => $value) : ?>
      <li>
        <button type="button" class="dropdown-item text-wrap <?= $prefix ?>-item" data-key="<?= $key ?>">
          <?= $value ?>
        </button>
      </li>
    <?php endforeach; ?>
    <?php if (empty($items)) : ?>
      <div class="text-center">No Options</div>
    <?php endif; ?>
  </ul>

  <?php if (isset($id)) : ?>
    <div id="<?= $id ?>" data-value=""></div>
  <?php endif; ?>

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

  $("#$id").data("value", value);
  $("#$id").trigger("dropdown.change", [value]);
})
JS;



$this->registerJs($script, View::POS_LOAD);
?>