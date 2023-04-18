<?php

use lib\app\App;

require_once('../lib/app/App.php');


require_once 'C:\xampp\htdocs\academy\site\controller\AssessmentController.php';
require_once 'C:\xampp\htdocs\academy\site\controller\ControlController.php';
require_once 'C:\xampp\htdocs\academy\common\controller\BaseController.php';
require_once 'C:\xampp\htdocs\academy\common\controller\Controller.php';

$app = App::instance(['site', 'admin']);
$app->route();
