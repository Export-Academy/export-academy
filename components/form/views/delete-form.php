<?php

use components\form\FormComponent;
use lib\app\log\Logger;
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


Logger::log($button);
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

JS;


$this->registerJs($script);
