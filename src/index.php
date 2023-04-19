<?php

use lib\app\App;
use lib\util\Helper;
use lib\util\html\HtmlHelper;

require_once 'C:\xampp\htdocs\academy\lib\util\Helper.php';

require_once Helper::getAlias('@lib\util\BaseObject.php');
require_once Helper::getAlias('@lib\app\App.php');
require_once Helper::getAlias('@site\controller\AssessmentController.php');
require_once Helper::getAlias('@site\controller\ControlController.php');
require_once Helper::getAlias('@common\controller\BaseController.php');
require_once Helper::getAlias('@common\controller\Controller.php');
require_once Helper::getAlias('@common\models\base\BaseModel.php');
require_once Helper::getAlias('@common\models\index.php');
require_once Helper::getAlias('@lib\util\html\HtmlHelper.php');



$app = App::instance(['site', 'admin']);
$app->route();
