<?php

use common\models\assessment\Checkboxes;
use lib\app\view\interface\IViewable;

use lib\app\view\View;

/**
 * @var View $this
 * @var IViewable $context
 */



$context = $this->context;

?>



<?php if ($context instanceof Checkboxes) : ?>

  <div class="border p-3">
    <?php foreach ($context->options as $key => $value) : ?>

      <div class="p-2">
        <div class="form-check">
          <input class="form-check-input fw-semibold" value="<?= $value ?>" type="checkbox" name="<?= $context::class ?>" id="<?= $key ?>">
          <label class="form-check-label fw-semibold" for="#<?= $key ?>">
            <?= $value ?>
          </label>
        </div>
      </div>

    <?php endforeach; ?>
  </div>

<?php endif; ?>