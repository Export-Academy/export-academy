<?php

use common\models\assessment\components\QuestionComponents;
use common\models\assessment\MultipleChoice;
use lib\app\log\Logger;

$builder = new QuestionComponents();
Logger::log($builder);

?>






<div>

  <!-- Functional requirements of this page is to allow administrators to add new questions -->





  <?= $builder->render("index", []) ?>

</div>