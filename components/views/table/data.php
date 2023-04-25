<?php

use lib\app\database\Query;
use lib\util\Helper;

if ($data instanceof Query)
  $data = $data->all();
$columns = $columns ?? [];


?>


<div class="border-1">
  <table class="table">
    <thead>
      <tr>
        <?php foreach ($columns as $key => $column) :
          $label = Helper::getValue("label", $column);
        ?>
          <th class="text-muted">
            <?= $label ?? $key ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($data as $model) : ?>
        <?php $model = is_array($model) ? new stdClass($model) : $model; ?>
        <tr>
          <?php foreach ($columns as $property => $column) : ?>

            <?php $content = Helper::getValue("content", $column); ?>

            <?php if (isset($content)) : ?>
              <td class="py-3"><?= is_callable($content) ? call_user_func_array($content, [$model]) : $model->{$content}  ?></td>
            <?php else : ?>
              <td><?= $model->{$property} ?></td>
            <?php endif; ?>
          <?php endforeach ?>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>