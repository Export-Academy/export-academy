<?php

use common\models\assessment\Boolean;
use lib\app\view\interface\IViewable;

use lib\app\view\View;

/**
 * @var View $this
 * @var IViewable $context
 */



$context = $this->context;

?>



<?php if ($context instanceof Boolean) : ?>

  <div class="p-3">
    <div class="row">
      <div class="col-6">
        <div class="p-2">
          <div class="form-check">
            <input class="form-check-input fw-semibold" value="1" type="radio" name="<?= $context::class ?>" id="true">
            <label class="form-check-label fw-semibold" for="#true">
              <?= $context->trueLabel ?? "True" ?>
            </label>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="p-2">
          <div class="form-check">
            <input class="form-check-input fw-semibold" value="0" type="radio" name="<?= $context::class ?>" id="false">
            <label class="form-check-label fw-semibold" for="#false">
              <?= $context->falseLabel ?? "False" ?>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>