<?php

use lib\app\database\Query;
use lib\util\Helper;
use lib\util\html\Html;
?>
<?php


$default_class = "table w-100";

$options = $options ?? [];
$class = $class ?? Helper::getValue("class", $options, null) ?? $default_class;

if (isset($options["class"]))
  unset($options["class"]);


if ($data instanceof Query)
  $data = $data->all();
$columns = $columns ?? [];

?>





<link href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.4/datatables.min.css" rel="stylesheet" />


<table <?= Html::renderAttributes($options) ?> class="<?= $class ?>">

  <caption><?= count($data) == 0 ? "No Results Found" : count($data) . " result(s) found" ?></caption>

  <?php if (count($data) > 0) : ?>

    <thead>
      <tr>
        <?php foreach ($columns as $key => $column) :
          $label = Helper::getValue("label", $column);
        ?>
          <th class="text-muted text-nowrap p-3">
            <?= $label ?? $key ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>

  <?php endif; ?>



  <tbody>
    <?php foreach ($data as $model) : ?>
      <?php $model = is_array($model) ? new stdClass($model) : $model; ?>
      <tr>
        <?php foreach ($columns as $property => $column) : ?>

          <?php $content = Helper::getValue("content", $column); ?>

          <?php if (isset($content)) : ?>
            <td class="p-3"><?= is_callable($content) ? call_user_func_array($content, [$model]) : $model->{$content}  ?>
            </td>
          <?php else : ?>
            <td><?= $model->{$property} ?></td>
          <?php endif; ?>
        <?php endforeach ?>
      </tr>
    <?php endforeach ?>
  </tbody>



</table>

<script src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.4/datatables.min.js"></script>

<?php


$script = <<< JS
$(document).ready(function() {
    $('#example').DataTable();
  });
JS;


$this->registerJs($script);
