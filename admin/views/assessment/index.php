<?php

use common\models\assessment\Question;
use lib\app\view\View;

/**
 * @var View $this
 * @var Question $component
 */

$component = Question::generate($this);


?>

<?= $component->renderBuilder() ?>