<?php

use components\form\FormComponent;
use lib\app\view\View;
use lib\util\html\Html;

/**
 * @var View $this
 */

/** @var FormComponent */
$context = $this->context;
$input_id = spl_object_id($this) . "-delete-input";
$type_id = spl_object_id($this) . "-delete-type";
$id = spl_object_id($this) . "-delete-form";
?>



<?= $context->begin($action, "POST", ["id" => $id]) ?>
<?= Html::hiddenInput(null, "id", ["id" => $input_id]) ?>
<?= Html::hiddenInput(null, "type", ["id" => $type_id]) ?>
<?= $context->end() ?>

<?php



$script = <<< JS



$("$button").on("click", function (e) {
  const target = $(e.currentTarget);
  $("input#$input_id").val(target.data("id"));
  $("input#$type_id").val(target.data("type"));
  $("form#$id").submit();
});


$("form#$id").on("submit", function (e, allowSubmit) {
  if (allowSubmit) {
    return;
  }
  e.preventDefault();
  const target = e.currentTarget;

  Swal.fire({
    title: `Delete`,
    text: "Are you sure you want to delete?",
    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
    showCancelButton: true
  }).then(function (result) {
    if (result.isConfirmed) {
      $(target).trigger('submit', [true]);
    }
  })
})

JS;


$this->registerJs($script);
