<?php

use common\models\assessment\Question;
use lib\app\view\View;


/**
 * @var View $this
 */

$component = Question::generate($this);
$content = $component->render("builder");
?>

<?= $content ?>