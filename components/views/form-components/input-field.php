<?php

use components\Components;
use lib\app\view\View;
use lib\util\html\Html;

/** @var View $this */

/** @var string[] $options */


$options = array_merge([
  "value" => $value ?? "",
  "placeholder" => $placeholder ?? "",
  "name" => $name ?? "",
], $options ?? []);

$required = $required ?? false;
$id = $id ?? false;
?>

<div class="mb-3 w-100">
  <?php if (isset($label)) : ?>
  <label class="fw-semibold" <?php if (isset($id)) : ?> for="<?= $id ?>" <?php endif ?>><?= $label ?></label>
  <?php endif; ?>




  <?php if ($component === Components::TextInput) : ?>

  <input <?= $required ? "required" : "" ?> <?= Html::renderAttributes($options) ?>
    class="form-control form-control-lg w-100" <?= $id ? "id='$id'" : "" ?>>
  <?php endif; ?>


  <?php if ($component === Components::TextArea) : ?>


  <textarea <?= $required ? "required" : "" ?> <?= Html::renderAttributes($options) ?>
    class="form-control form-control-lg  w-100" <?= $id ? "id='$id'" : "" ?>
    <?= isset($name) ? "name='$name'" : "" ?>><?= $value ?? "" ?></textarea>


  <?php endif; ?>


  <?php if ($component === Components::PasswordInput) : ?>

  <?php $this->registerJsFile('input-field', View::POS_LOAD);  ?>
  <div class="input-group">
    <input <?= Html::renderAttributes($options) ?> class="form-control form-control-lg"
      <?= $required ? "required" : "" ?> <?= $id ? "id='$id'" : "" ?> <?= isset($name) ? "name='$name'" : "" ?>>
    <button type="button" class="btn password-visibility-btn">Show</button>
  </div>


  <?php endif; ?>
</div>