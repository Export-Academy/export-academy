<?php


use lib\app\view\View;
use lib\util\html\Html;

/** @var View $this */

/** @var string[] $options */


$options = array_merge([
  "value" => $value ?? "",
  "placeholder" => $placeholder ?? ""
], $options ?? []);

$required = $required ?? false;
$id = $id ?? false;
?>



<?php if ($type == "password") : ?>

  <?php
  $this->registerJsFile('input-field', View::POS_LOAD);
  ?>
  <div class="mb-3 w-100">
    <label class="fw-semibold" <?php if (isset($id)) : ?> for="<?= $id ?>" <?php endif ?>><?= $label ?? "" ?></label>
    <div class="input-group">
      <input <?= Html::renderAttributes($options) ?> class="form-control form-control-lg" <?= $required ? "required" : "" ?> <?= $id ? "id='$id'" : "" ?> <?= isset($name) ? "name='$name'" : "" ?>>
      <button type="button" class="btn password-visibility-btn">Show</button>
    </div>
  </div>
<?php elseif ($type == "textarea") : ?>

  <div class="mb-3 w-100">
    <label class="fw-semibold" <?php if (isset($id)) : ?> for="<?= $id ?>" <?php endif ?>><?= $label ?? "" ?></label>
    <textarea <?= $required ? "required" : "" ?> <?= Html::renderAttributes($options) ?> class="form-control form-control-lg  w-100" <?= $id ? "id='$id'" : "" ?> <?= isset($name) ? "name='$name'" : "" ?>><?= $value ?? "" ?></textarea>
  </div>

<?php else : ?>

  <div class="mb-3 w-100">
    <label class="fw-semibold" <?php if (isset($id)) : ?> for="<?= $id ?>" <?php endif ?>><?= $label ?? "" ?></label>
    <input <?= $required ? "required" : "" ?> <?= Html::renderAttributes($options) ?> class="form-control form-control-lg  w-100" <?= $id ? "id='$id'" : "" ?> <?= isset($name) ? "name='$name'" : "" ?>>
  </div>

<?php endif; ?>