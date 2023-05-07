<?php

use components\HtmlComponent;
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


$variant = $variant ?? "";
$class = isset($class) ? "$class" : "";


$this->registerSCSSFile("input-component.scss");


?>



<?php if (isset($label)) : ?>
<div class="m-2">
  <label class="fw-semibold" <?php if (isset($id)) : ?> for="<?= $id ?>" <?php endif ?>><?= $label ?></label>
</div>
<?php endif; ?>



<?php if ($component === HtmlComponent::TextInput) : ?>
<input <?= $required ? "required" : "" ?> <?= Html::renderAttributes($options) ?>
  class="form-control form-control-lg <?= $class ?> <?= $variant ?>" <?= $id ? "id='$id'" : "" ?>>
<?php endif; ?>



<?php if ($component === HtmlComponent::TextArea) : ?>
<textarea <?= $required ? "required" : "" ?> <?= Html::renderAttributes($options) ?>
  class="form-control form-control-lg <?= $class ?> <?= $variant ?>" <?= $id ? "id='$id'" : "" ?>
  <?= isset($name) ? "name='$name'" : "" ?>><?= $value ?? "" ?></textarea>
<?php endif; ?>



<?php if ($component === HtmlComponent::PasswordInput) : ?>
<?php $this->registerJsFile('input-component', View::POS_LOAD);  ?>
<div class="input-group <?= $class ?>">
  <input <?= Html::renderAttributes($options) ?> class="form-control form-control-lg <?= $variant ?>"
    <?= $required ? "required" : "" ?> <?= $id ? "id='$id'" : "" ?> <?= isset($name) ? "name='$name'" : "" ?>>
  <button type="button" class="btn password-visibility-btn"><i data-feather="eye"></i></button>
</div>
<?php endif; ?>