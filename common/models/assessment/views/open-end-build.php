<?php

use common\models\assessment\OpenEnd;

$checkLong = $checkShort = "";


if (isset($question) && $question instanceof OpenEnd) {
  if ($question->length === OpenEnd::LONG_ANSWER) $checkLong = "checked";
  if ($question->length === OpenEnd::SHORT_ANSWER) $checkShort = "checked";
}

?>

<div class="p-3">
  <div class="form-check">
    <input <?= $checkLong ?> class="form-check-input fw-semibold" value="<?= OpenEnd::LONG_ANSWER ?>" type="radio" name="<?= OpenEnd::class ?>" id="<?= OpenEnd::LONG_ANSWER ?>">
    <label class="form-check-label fw-semibold" for="#<?= OpenEnd::LONG_ANSWER ?>">
      Long Answer
    </label>
  </div>
</div>

<div class="p-3">
  <div class="form-check">
    <input <?= $checkShort ?> class="form-check-input fw-semibold" value="<?= OpenEnd::SHORT_ANSWER ?>" type="radio" name="<?= OpenEnd::class ?>" id="<?= OpenEnd::SHORT_ANSWER ?>">
    <label class="form-check-label fw-semibold" for="#<?= OpenEnd::SHORT_ANSWER ?>">
      Short Answer
    </label>
  </div>
</div>