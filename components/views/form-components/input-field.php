<?php


use lib\app\view\View;

/** @var View $this */

?>



<?php if ($type == "password") : ?>

<?php
  $this->registerJsFile('input-field', View::POS_END);
  ?>
<div class="mb-5">
  <label class="fw-semibold" <?php if (isset($id)) : ?> for="<?= $id ?>" <?php endif ?>><?= $label ?? "" ?></label>
  <div class="input-group">
    <input required type="<?= $type ?? "text" ?>" class="form-control form-control-lg" <?php if (isset($id)) : ?>
      id="<?= $id ?>" <?php endif ?> <?php if (isset($name)) : ?> name="<?= $name ?>" <?php endif ?>
      placeholder="<?= $placeholder ?? "" ?>">
    <button type="button" class="btn password-visibility-btn">Show</button>
  </div>
</div>
<?php else : ?>


<div class="mb-5">
  <label class="fw-semibold" <?php if (isset($id)) : ?> for="<?= $id ?>" <?php endif ?>><?= $label ?? "" ?></label>


  <input required type="<?= $type ?? "text" ?>" class="form-control form-control-lg" <?php if (isset($id)) : ?>
    id="<?= $id ?>" <?php endif ?> <?php if (isset($name)) : ?> name="<?= $name ?>" <?php endif ?>
    placeholder="<?= $placeholder ?? "" ?>">
</div>



<?php endif; ?>




<?php
$this->renderPosition(View::POS_END);
?>